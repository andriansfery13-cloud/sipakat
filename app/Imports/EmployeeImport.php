<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    protected array $errors = [];
    protected int $importedCount = 0;
    protected int $skippedCount = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 for 1-based index and heading row

            // Mapping kolom dari excel
            $name = $this->getColumnValue($row, ['nama', 'name', 'nama_pegawai', 'nama_karyawan']);
            $npwp = $this->getColumnValue($row, ['npwp', 'no_npwp', 'nomor_npwp']);
            $nik = $this->getColumnValue($row, ['nik', 'no_nik', 'nomor_nik']);
            $ptkp_status = strtoupper($this->getColumnValue($row, ['ptkp', 'status_ptkp', 'ptkp_status', 'status_pajak']) ?? '');
            $position = $this->getColumnValue($row, ['jabatan', 'posisi', 'position']);
            $status_tetap = strtolower($this->getColumnValue($row, ['status', 'status_tetap', 'employee_status']) ?? '');
            
            // New columns
            $nip = $this->getColumnValue($row, ['nip', 'no_nip', 'nomor_nip']);
            $instansi = $this->getColumnValue($row, ['instansi', 'organisasi', 'perusahaan']);
            $jenis_pegawai = strtoupper($this->getColumnValue($row, ['jenis_pegawai', 'status_pegawai', 'pns_pppk']) ?? '');

            if (empty($name)) {
                $this->errors[] = "Baris {$rowNum}: Nama pegawai kosong, dilewati.";
                $this->skippedCount++;
                continue;
            }

            if (empty($npwp)) {
                $this->errors[] = "Baris {$rowNum}: NPWP untuk '{$name}' kosong, dilewati.";
                $this->skippedCount++;
                continue;
            }

            // Validasi PTKP Status
            $validPtkp = ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'];
            if (!in_array($ptkp_status, $validPtkp)) {
                $this->errors[] = "Baris {$rowNum}: Status PTKP '{$ptkp_status}' tidak valid. Harus salah satu dari (TK/0, TK/1, dsb), dilewati.";
                $this->skippedCount++;
                continue;
            }

            // Validasi Status Pegawai
            $employee_status = 'tetap'; // default
            if (in_array($status_tetap, ['tidak tetap', 'tidak_tetap', 'kontrak', 'freelance'])) {
                $employee_status = 'tidak_tetap';
            }

            // Validasi Jenis Pegawai (PNS / PPPK)
            $status_pegawai_db = null;
            if (in_array($jenis_pegawai, ['PNS', 'PPPK'])) {
                $status_pegawai_db = $jenis_pegawai;
            } elseif (!empty($jenis_pegawai)) {
                $status_pegawai_db = 'Lainnya';
            }

            // Clean up NPWP / NIK / NIP to be string
            $npwp = (string) $npwp;
            $nik = $nik ? (string) $nik : null;
            $nip = $nip ? (string) $nip : null;

            // Check duplicate NPWP
            if (Employee::where('npwp', $npwp)->exists()) {
                $this->errors[] = "Baris {$rowNum}: Pegawai dengan NPWP '{$npwp}' sudah ada di database, dilewati.";
                $this->skippedCount++;
                continue;
            }

            // Check duplicate NIK if exists
            if ($nik && Employee::where('nik', $nik)->exists()) {
                $this->errors[] = "Baris {$rowNum}: Pegawai dengan NIK '{$nik}' sudah ada di database, dilewati.";
                $this->skippedCount++;
                continue;
            }

            Employee::create([
                'name' => $name,
                'npwp' => $npwp,
                'nik' => $nik,
                'nip' => $nip,
                'instansi' => $instansi,
                'ptkp_status' => $ptkp_status,
                'position' => $position,
                'employee_status' => $employee_status,
                'status_pegawai' => $status_pegawai_db,
            ]);

            $this->importedCount++;
        }
    }

    /**
     * Get column value with flexible mapping
     */
    protected function getColumnValue($row, array $possibleKeys): ?string
    {
        foreach ($possibleKeys as $key) {
            // Coba exact match list (Array Access / Collection property access)
            if (isset($row[$key]) && !empty($row[$key])) {
                return trim($row[$key]);
            }
            // Coba spasi diubah ke underscore
            $altKey = str_replace(' ', '_', $key);
            if (isset($row[$altKey]) && !empty($row[$altKey])) {
                return trim($row[$altKey]);
            }
        }
        return null;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
