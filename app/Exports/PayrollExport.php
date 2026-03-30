<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayrollExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected int $month;
    protected int $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        return Payroll::query()
            ->with('employee')
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'NPWP',
            'Status PTKP',
            'Jabatan',
            'Gaji Pokok',
            'Tunjangan',
            'Bonus',
            'Total Penghasilan',
            'PPh 21',
        ];
    }

    public function map($payroll): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $payroll->employee->name,
            $payroll->employee->npwp,
            $payroll->employee->ptkp_status,
            $payroll->employee->position,
            $payroll->gross_salary,
            $payroll->allowance,
            $payroll->bonus,
            $payroll->total_income,
            $payroll->pph21,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
