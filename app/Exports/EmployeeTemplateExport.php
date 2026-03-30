<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Nama',
            'NPWP',
            'NIK',
            'PTKP',
            'Jabatan',
            'Status'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Budi Santoso',     // Nama
                '123456789012345',  // NPWP
                '3201234567890001', // NIK
                'TK/0',             // PTKP
                'Staff IT',         // Jabatan
                'Tetap'             // Status
            ],
            [
                'Siti Rahayu',
                '987654321098765',
                '3201234567890002',
                'K/1',
                'Marketing',
                'Tidak Tetap'
            ]
        ];
    }
}
