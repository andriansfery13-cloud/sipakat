<?php

namespace App\Services;

use App\Models\Payroll;

class Pph21Calculator
{
    /**
     * PTKP values per year based on status
     */
    const PTKP = [
        'TK/0' => 54000000,
        'K/0'  => 58500000,
        'TK/1' => 58500000,
        'K/1'  => 63000000,
        'TK/2' => 63000000,
        'K/2'  => 67500000,
        'TK/3' => 67500000,
        'K/3'  => 72000000,
    ];

    /**
     * Peta Kategori TER (PP 58 Tahun 2023)
     */
    const TER_CATEGORY = [
        'TK/0' => 'A',
        'TK/1' => 'A',
        'K/0'  => 'A',
        'TK/2' => 'B',
        'TK/3' => 'B',
        'K/1'  => 'B',
        'K/2'  => 'B',
        'K/3'  => 'C',
    ];

    /**
     * Progressive tax brackets (Pasal 17) for December/Year-end calculation
     */
    const TAX_BRACKETS = [
        ['limit' => 60000000, 'rate' => 0.05],
        ['limit' => 250000000, 'rate' => 0.15],
        ['limit' => 500000000, 'rate' => 0.25],
        ['limit' => 5000000000, 'rate' => 0.30],
        ['limit' => PHP_INT_MAX, 'rate' => 0.35],
    ];

    /**
     * Data TER Representation (PP 58/2023)
     * Limit is upper bound of Gross Income
     */
    private function getTerRate(string $category, float $grossIncome): float
    {
        // TER A (TK/0, TK/1, K/0)
        if ($category === 'A') {
            if ($grossIncome <= 5400000) return 0.0;
            if ($grossIncome <= 5650000) return 0.0025;
            if ($grossIncome <= 5950000) return 0.005;
            if ($grossIncome <= 6300000) return 0.0075;
            if ($grossIncome <= 6750000) return 0.01;
            if ($grossIncome <= 7500000) return 0.0125;
            if ($grossIncome <= 8550000) return 0.015;
            if ($grossIncome <= 9650000) return 0.0175;
            if ($grossIncome <= 10050000) return 0.02;
            if ($grossIncome <= 10350000) return 0.0225;
            if ($grossIncome <= 10700000) return 0.025;
            if ($grossIncome <= 11050000) return 0.03;
            if ($grossIncome <= 11600000) return 0.035;
            if ($grossIncome <= 12500000) return 0.04;
            if ($grossIncome <= 13750000) return 0.05;
            if ($grossIncome <= 15100000) return 0.06;
            if ($grossIncome <= 16950000) return 0.07;
            if ($grossIncome <= 19750000) return 0.08;
            if ($grossIncome <= 24150000) return 0.09;
            if ($grossIncome <= 26450000) return 0.10;
            if ($grossIncome <= 28000000) return 0.11;
            if ($grossIncome <= 30050000) return 0.12;
            if ($grossIncome <= 32400000) return 0.13;
            if ($grossIncome <= 35400000) return 0.14;
            if ($grossIncome <= 39100000) return 0.15;
            if ($grossIncome <= 43850000) return 0.16;
            if ($grossIncome <= 47800000) return 0.17;
            if ($grossIncome <= 51400000) return 0.18;
            if ($grossIncome <= 56300000) return 0.19;
            if ($grossIncome <= 62200000) return 0.20;
            if ($grossIncome <= 68600000) return 0.21;
            if ($grossIncome <= 77500000) return 0.22;
            if ($grossIncome <= 89000000) return 0.23;
            if ($grossIncome <= 103000000) return 0.24;
            if ($grossIncome <= 125000000) return 0.25;
            if ($grossIncome <= 157000000) return 0.26;
            if ($grossIncome <= 206000000) return 0.27;
            if ($grossIncome <= 337000000) return 0.28;
            if ($grossIncome <= 454000000) return 0.29;
            if ($grossIncome <= 550000000) return 0.30;
            if ($grossIncome <= 695000000) return 0.31;
            if ($grossIncome <= 910000000) return 0.32;
            if ($grossIncome <= 1400000000) return 0.33;
            return 0.34;
        }
        
        // TER B (TK/2, TK/3, K/1, K/2)
        if ($category === 'B') {
            if ($grossIncome <= 6200000) return 0.0;
            if ($grossIncome <= 6500000) return 0.0025;
            if ($grossIncome <= 6850000) return 0.005;
            if ($grossIncome <= 7300000) return 0.0075;
            if ($grossIncome <= 9200000) return 0.01;
            if ($grossIncome <= 10750000) return 0.015;
            if ($grossIncome <= 11250000) return 0.02;
            if ($grossIncome <= 11600000) return 0.025;
            if ($grossIncome <= 12600000) return 0.03;
            if ($grossIncome <= 13600000) return 0.04;
            if ($grossIncome <= 14950000) return 0.05;
            if ($grossIncome <= 16400000) return 0.06;
            if ($grossIncome <= 18450000) return 0.07;
            if ($grossIncome <= 21850000) return 0.08;
            if ($grossIncome <= 26000000) return 0.09;
            if ($grossIncome <= 27700000) return 0.10;
            if ($grossIncome <= 29350000) return 0.11;
            if ($grossIncome <= 31450000) return 0.12;
            if ($grossIncome <= 33950000) return 0.13;
            if ($grossIncome <= 37100000) return 0.14;
            if ($grossIncome <= 41100000) return 0.15;
            if ($grossIncome <= 45800000) return 0.16;
            if ($grossIncome <= 49500000) return 0.17;
            if ($grossIncome <= 53800000) return 0.18;
            if ($grossIncome <= 58500000) return 0.19;
            if ($grossIncome <= 64000000) return 0.20;
            if ($grossIncome <= 71000000) return 0.21;
            if ($grossIncome <= 80000000) return 0.22;
            if ($grossIncome <= 93000000) return 0.23;
            if ($grossIncome <= 109000000) return 0.24;
            if ($grossIncome <= 129000000) return 0.25;
            if ($grossIncome <= 163000000) return 0.26;
            if ($grossIncome <= 211000000) return 0.27;
            if ($grossIncome <= 374000000) return 0.28;
            if ($grossIncome <= 459000000) return 0.29;
            if ($grossIncome <= 555000000) return 0.30;
            if ($grossIncome <= 704000000) return 0.31;
            if ($grossIncome <= 957000000) return 0.32;
            if ($grossIncome <= 1405000000) return 0.33;
            return 0.34;
        }

        // TER C (K/3)
        if ($category === 'C') {
            if ($grossIncome <= 6600000) return 0.0;
            if ($grossIncome <= 6950000) return 0.0025;
            if ($grossIncome <= 7350000) return 0.005;
            if ($grossIncome <= 7800000) return 0.0075;
            if ($grossIncome <= 8850000) return 0.01;
            if ($grossIncome <= 9800000) return 0.0125;
            if ($grossIncome <= 10950000) return 0.015;
            if ($grossIncome <= 11200000) return 0.0175;
            if ($grossIncome <= 12050000) return 0.02;
            if ($grossIncome <= 12950000) return 0.03;
            if ($grossIncome <= 14150000) return 0.04;
            if ($grossIncome <= 15550000) return 0.05;
            if ($grossIncome <= 17050000) return 0.06;
            if ($grossIncome <= 19500000) return 0.07;
            if ($grossIncome <= 22700000) return 0.08;
            if ($grossIncome <= 26600000) return 0.09;
            if ($grossIncome <= 28100000) return 0.10;
            if ($grossIncome <= 30100000) return 0.11;
            if ($grossIncome <= 32600000) return 0.12;
            if ($grossIncome <= 35400000) return 0.13;
            if ($grossIncome <= 38900000) return 0.14;
            if ($grossIncome <= 43000000) return 0.15;
            if ($grossIncome <= 47400000) return 0.16;
            if ($grossIncome <= 51200000) return 0.17;
            if ($grossIncome <= 55800000) return 0.18;
            if ($grossIncome <= 60400000) return 0.19;
            if ($grossIncome <= 66700000) return 0.20;
            if ($grossIncome <= 74500000) return 0.21;
            if ($grossIncome <= 83200000) return 0.22;
            if ($grossIncome <= 95600000) return 0.23;
            if ($grossIncome <= 110000000) return 0.24;
            if ($grossIncome <= 134000000) return 0.25;
            if ($grossIncome <= 169000000) return 0.26;
            if ($grossIncome <= 221000000) return 0.27;
            if ($grossIncome <= 390000000) return 0.28;
            if ($grossIncome <= 463000000) return 0.29;
            if ($grossIncome <= 561000000) return 0.30;
            if ($grossIncome <= 709000000) return 0.31;
            if ($grossIncome <= 965000000) return 0.32;
            if ($grossIncome <= 1419000000) return 0.33;
            return 0.34;
        }

        return 0.0;
    }

    /**
     * Calculate monthly PPh 21 using TER Coretax rules
     */
    public function calculateMonthly(float $monthlyIncome, string $ptkpStatus, int $month = 1, int $year = 2024, ?int $employeeId = null, ?int $ignorePayrollId = null): float
    {
        // For Jan - Nov, strictly use TER
        if ($month < 12) {
            $category = self::TER_CATEGORY[$ptkpStatus] ?? 'A';
            $rate = $this->getTerRate($category, $monthlyIncome);
            return round($monthlyIncome * $rate, 2);
        }

        // For December, calculate annual progressive (Pasal 17) minus already paid tax in Jan-Nov
        if ($month == 12 && $employeeId) {
            // Get all payroll records for this employee for Jan-Nov in this year
            $query = Payroll::where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('month', '<', 12);
                
            if ($ignorePayrollId) {
                $query->where('id', '!=', $ignorePayrollId);
            }
                
            $previousPayrolls = $query->get();

            $totalIncome1To11 = $previousPayrolls->sum('total_income');
            $totalTax1To11 = $previousPayrolls->sum('pph21');

            // Setahunan
            $annualGrossIncome = $totalIncome1To11 + $monthlyIncome;
            // Assuming Biaya Jabatan (5%, max 6jt/tahun)
            $biayaJabatan = min($annualGrossIncome * 0.05, 6000000);
            
            $netIncome = $annualGrossIncome - $biayaJabatan;
            $ptkp = self::PTKP[$ptkpStatus] ?? self::PTKP['TK/0'];
            $pkp = $netIncome - $ptkp;

            if ($pkp <= 0) {
                return 0; // Negative or 0 means no tax for December (and potentially overpaid)
            }

            // Calculate Pasal 17
            $annualTax = $this->calculateProgressiveTax($pkp);
            
            // Tax for Dec is Annual Tax - previously paid
            $decemberTax = $annualTax - $totalTax1To11;
            
            return max(round($decemberTax, 2), 0);
        }

        // Fallback if no employee ID or default calculation for testing
        $category = self::TER_CATEGORY[$ptkpStatus] ?? 'A';
        $rate = $this->getTerRate($category, $monthlyIncome);
        return round($monthlyIncome * $rate, 2);
    }

    /**
     * Calculate progressive tax based on PKP (Pasal 17)
     */
    protected function calculateProgressiveTax(float $pkp): float
    {
        $tax = 0;
        $remaining = $pkp;

        $previousLimit = 0;
        foreach (self::TAX_BRACKETS as $bracket) {
            $bracketSize = $bracket['limit'] - $previousLimit;
            $taxableInBracket = min($remaining, $bracketSize);

            if ($taxableInBracket <= 0) {
                break;
            }

            $tax += $taxableInBracket * $bracket['rate'];
            $remaining -= $taxableInBracket;
            $previousLimit = $bracket['limit'];
        }

        return $tax;
    }

    /**
     * Get PTKP value for a given status
     */
    public static function getPtkpValue(string $status): float
    {
        return self::PTKP[$status] ?? self::PTKP['TK/0'];
    }
}
