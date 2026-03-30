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
            'NIP',
            'Instansi',
            'PTKP',
            'Jabatan',
            'Status',
            'Jenis_Pegawai'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Budi Santoso',     // Nama
                '123456789012345',  // NPWP
                '3201234567890001', // NIK
                '199001012020121001',// NIP
                'Pemerintah Daerah',// Instansi
                'TK/0',             // PTKP
                'Staff IT',         // Jabatan
                'Tetap',            // Status
                'PNS'               // Jenis_Pegawai
            ],
            [
                'Siti Rahayu',
                '987654321098765',
                '3201234567890002',
                '',
                'Perusahaan Swasta',
                'K/1',
                'Marketing',
                'Tidak Tetap',
                'Lainnya'
            ]
        ];
    }
}
