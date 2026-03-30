<?php

namespace App\Exports;

use App\Models\Payroll;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportAnnualDetailSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithEvents, ShouldAutoSize
{
    private int $year;
    private int $rowNumber = 0;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        return Payroll::with('employee')
            ->where('year', $this->year)
            ->orderBy('month')
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Bulan',
            'Nama Pegawai',
            'NPWP',
            'NIK',
            'Status PTKP',
            'Gaji Pokok',
            'Tunjangan',
            'Bonus',
            'Total Penghasilan',
            'PPh 21',
            'Tarif Efektif (%)',
        ];
    }

    public function map($payroll): array
    {
        $this->rowNumber++;
        $months = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Ags',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
        $rate = $payroll->total_income > 0
            ? round(($payroll->pph21 / $payroll->total_income) * 100, 2)
            : 0;

        return [
            $this->rowNumber,
            $months[$payroll->month] ?? $payroll->month,
            $payroll->employee->name ?? '-',
            $payroll->employee->npwp ?? '-',
            $payroll->employee->nik ?? '-',
            $payroll->employee->ptkp_status ?? '-',
            $payroll->gross_salary,
            $payroll->allowance,
            $payroll->bonus,
            $payroll->total_income,
            $payroll->pph21,
            $rate,
        ];
    }

    public function title(): string
    {
        return 'Detail Per Pegawai';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();

                // Insert header rows
                $sheet->insertNewRowBefore(1, 4);

                $companyName = Setting::getValue('company_name', '-');
                $sheet->setCellValue('A1', "DETAIL PPh 21 TAHUN {$this->year}");
                $sheet->setCellValue('A2', $companyName);

                $sheet->mergeCells("A1:{$highestCol}1");
                $sheet->mergeCells("A2:{$highestCol}2");

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $dataEnd = $highestRow + 4;
                $sheet->getStyle("G6:K{$dataEnd}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("L6:L{$dataEnd}")->getNumberFormat()->setFormatCode('0.00');
                $sheet->getStyle("A5:{$highestCol}{$dataEnd}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
