<?php

namespace App\Exports;

use App\Models\Payroll;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportAnnualExport implements WithMultipleSheets
{
    private int $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function sheets(): array
    {
        return [
            new ReportAnnualSummarySheet($this->year),
            new ReportAnnualDetailSheet($this->year),
        ];
    }
}
