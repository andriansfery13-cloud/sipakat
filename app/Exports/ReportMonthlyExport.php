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

class ReportMonthlyExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithEvents, ShouldAutoSize
{
    private int $month;
    private int $year;
    private int $rowNumber = 0;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return Payroll::with('employee')
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'NPWP',
            'NIK',
            'Status PTKP',
            'Jabatan',
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
        $rate = $payroll->total_income > 0
            ? round(($payroll->pph21 / $payroll->total_income) * 100, 2)
            : 0;

        return [
            $this->rowNumber,
            $payroll->employee->name ?? '-',
            $payroll->employee->npwp ?? '-',
            $payroll->employee->nik ?? '-',
            $payroll->employee->ptkp_status ?? '-',
            $payroll->employee->position ?? '-',
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
        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        return ($months[$this->month] ?? '') . " {$this->year}";
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

                // Company header - insert rows at top
                $sheet->insertNewRowBefore(1, 5);

                $companyName = Setting::getValue('company_name', '-');
                $companyNpwp = Setting::getValue('company_npwp', '-');
                $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                $monthName = $months[$this->month] ?? '';

                $sheet->setCellValue('A1', 'LAPORAN PPh 21 BULANAN');
                $sheet->setCellValue('A2', $companyName);
                $sheet->setCellValue('A3', "NPWP: {$companyNpwp}");
                $sheet->setCellValue('A4', "Periode: {$monthName} {$this->year}");

                $sheet->mergeCells("A1:{$highestCol}1");
                $sheet->mergeCells("A2:{$highestCol}2");
                $sheet->mergeCells("A3:{$highestCol}3");
                $sheet->mergeCells("A4:{$highestCol}4");

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format currency columns (G to K → columns 7-11, offset by 5 header rows)
                $dataStart = 7; // row 6 is heading, data starts at 7
                $dataEnd = $highestRow + 5;
                $sheet->getStyle("G{$dataStart}:K{$dataEnd}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("L{$dataStart}:L{$dataEnd}")->getNumberFormat()->setFormatCode('0.00');

                // Borders for data
                $sheet->getStyle("A6:{$highestCol}{$dataEnd}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
