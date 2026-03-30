<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Payroll;
use App\Services\Pph21Calculator;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollImport implements ToArray, WithHeadingRow
{
    protected int $month;
    protected int $year;
    protected Pph21Calculator $calculator;
    protected array $errors = [];
    protected int $importedCount = 0;
    protected int $skippedCount = 0;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
        $this->calculator = new Pph21Calculator();
    }

    public function array(array $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because heading row is 1

            // Flexible column mapping - try multiple column name variants
            $name = $this->getColumnValue($row, ['nama', 'name', 'nama_pegawai', 'nama_karyawan']);
            $grossSalary = $this->getNumericValue($row, ['gaji_pokok', 'gaji', 'salary', 'gross_salary', 'basic_salary']);
            $allowance = $this->getNumericValue($row, ['tunjangan', 'allowance', 'tunjangan_tetap']);
            $bonus = $this->getNumericValue($row, ['bonus', 'insentif', 'incentive']);

            if (empty($name)) {
                $this->errors[] = "Baris {$rowNum}: Nama pegawai kosong, dilewati.";
                $this->skippedCount++;
                continue;
            }

            // Find employee by name (case-insensitive)
            $employee = Employee::whereRaw('LOWER(name) = ?', [strtolower(trim($name))])->first();

            if (!$employee) {
                $this->errors[] = "Baris {$rowNum}: Pegawai '{$name}' tidak ditemukan di database.";
                $this->skippedCount++;
                continue;
            }

            // Check for duplicate
            $existing = Payroll::where('employee_id', $employee->id)
                ->where('month', $this->month)
                ->where('year', $this->year)
                ->first();

            if ($existing) {
                $this->errors[] = "Baris {$rowNum}: Payroll untuk '{$name}' periode {$this->month}/{$this->year} sudah ada, dilewati.";
                $this->skippedCount++;
                continue;
            }

            $totalIncome = $grossSalary + $allowance + $bonus;

            // Calculate PPh 21
            $pph21 = $this->calculator->calculateMonthly($totalIncome, $employee->ptkp_status, $this->month, $this->year, $employee->id);

            Payroll::create([
                'employee_id' => $employee->id,
                'month' => $this->month,
                'year' => $this->year,
                'gross_salary' => $grossSalary,
                'allowance' => $allowance,
                'bonus' => $bonus,
                'total_income' => $totalIncome,
                'pph21' => $pph21,
            ]);

            $this->importedCount++;
        }
    }

    /**
     * Get column value with flexible mapping
     */
    protected function getColumnValue(array $row, array $possibleKeys): ?string
    {
        foreach ($possibleKeys as $key) {
            // Try exact match
            if (isset($row[$key]) && !empty($row[$key])) {
                return trim($row[$key]);
            }
            // Try with spaces replaced by underscores
            $altKey = str_replace(' ', '_', $key);
            if (isset($row[$altKey]) && !empty($row[$altKey])) {
                return trim($row[$altKey]);
            }
        }
        return null;
    }

    /**
     * Get numeric column value
     */
    protected function getNumericValue(array $row, array $possibleKeys): float
    {
        $value = $this->getColumnValue($row, $possibleKeys);
        if ($value === null) {
            return 0;
        }
        // Remove non-numeric characters except dot and comma
        $value = preg_replace('/[^0-9.,]/', '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
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
