<?php

namespace App\Exports;

use App\Models\Payroll;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportAnnualSummarySheet implements FromArray, WithTitle, WithStyles, WithEvents, ShouldAutoSize
{
    private int $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function array(): array
    {
        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        $data = [];
        $totalEmployees = 0;
        $totalIncome = 0;
        $totalPph = 0;

        for ($m = 1; $m <= 12; $m++) {
            $payrolls = Payroll::where('month', $m)->where('year', $this->year)->get();
            $count = $payrolls->count();
            $income = $payrolls->sum('total_income');
            $pph = $payrolls->sum('pph21');

            $totalEmployees += $count;
            $totalIncome += $income;
            $totalPph += $pph;

            $data[] = [
                $months[$m],
                $count,
                $income,
                $pph,
            ];
        }

        // Total row
        $data[] = ['TOTAL', $totalEmployees, $totalIncome, $totalPph];

        return $data;
    }

    public function title(): string
    {
        return 'Ringkasan Bulanan';
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                $companyName = Setting::getValue('company_name', '-');
                $companyNpwp = Setting::getValue('company_npwp', '-');

                // Insert header rows
                $sheet->insertNewRowBefore(1, 6);

                $sheet->setCellValue('A1', "LAPORAN PPh 21 TAHUNAN - {$this->year}");
                $sheet->setCellValue('A2', $companyName);
                $sheet->setCellValue('A3', "NPWP: {$companyNpwp}");
                $sheet->setCellValue('A4', "Tahun Pajak: {$this->year}");

                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
                $sheet->mergeCells('A4:D4');

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Heading row
                $sheet->setCellValue('A6', 'Bulan');
                $sheet->setCellValue('B6', 'Jumlah Pegawai');
                $sheet->setCellValue('C6', 'Total Penghasilan');
                $sheet->setCellValue('D6', 'Total PPh 21');

                $sheet->getStyle('A6:D6')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Format and borders
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("C7:D{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("A6:D{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Total row bold
                $sheet->getStyle("A{$highestRow}:D{$highestRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$highestRow}:D{$highestRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E0E7FF');
            },
        ];
    }
}
