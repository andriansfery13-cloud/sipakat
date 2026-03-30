<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportMonthlyExport;
use App\Exports\ReportAnnualExport;

class ReportController extends Controller
{
    /**
     * Show monthly PPh 21 report
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $payrolls = Payroll::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('id')
            ->get();

        $company = $this->getCompanyProfile();
        $summary = $this->calculateMonthlySummary($payrolls);

        return view('reports.monthly', compact('payrolls', 'company', 'summary', 'month', 'year'));
    }

    /**
     * Show annual PPh 21 report
     */
    public function annual(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $payrolls = Payroll::with('employee')
            ->where('year', $year)
            ->orderBy('month')
            ->orderBy('id')
            ->get();

        $company = $this->getCompanyProfile();
        $monthlySummaries = $this->calculateAnnualSummary($payrolls, $year);
        $employeeSummaries = $this->calculateEmployeeAnnualSummary($payrolls);

        return view('reports.annual', compact('payrolls', 'company', 'monthlySummaries', 'employeeSummaries', 'year'));
    }

    /**
     * Export monthly report to PDF
     */
    public function exportMonthlyPdf(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $payrolls = Payroll::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('id')
            ->get();

        $company = $this->getCompanyProfile();
        $summary = $this->calculateMonthlySummary($payrolls);
        $monthName = $this->getMonthName($month);

        $pdf = Pdf::loadView('reports.pdf.monthly', compact('payrolls', 'company', 'summary', 'month', 'year', 'monthName'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_PPh21_{$monthName}_{$year}.pdf");
    }

    /**
     * Export monthly report to Excel
     */
    public function exportMonthlyExcel(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        $monthName = $this->getMonthName($month);

        return Excel::download(
            new ReportMonthlyExport($month, $year),
            "Laporan_PPh21_{$monthName}_{$year}.xlsx"
        );
    }

    /**
     * Export annual report to PDF
     */
    public function exportAnnualPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $payrolls = Payroll::with('employee')
            ->where('year', $year)
            ->orderBy('month')
            ->orderBy('id')
            ->get();

        $company = $this->getCompanyProfile();
        $monthlySummaries = $this->calculateAnnualSummary($payrolls, $year);
        $employeeSummaries = $this->calculateEmployeeAnnualSummary($payrolls);

        $pdf = Pdf::loadView('reports.pdf.annual', compact('payrolls', 'company', 'monthlySummaries', 'employeeSummaries', 'year'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_PPh21_Tahunan_{$year}.pdf");
    }

    /**
     * Export annual report to Excel
     */
    public function exportAnnualExcel(Request $request)
    {
        $year = $request->get('year', date('Y'));

        return Excel::download(
            new ReportAnnualExport($year),
            "Laporan_PPh21_Tahunan_{$year}.xlsx"
        );
    }

    /**
     * Get company profile data
     */
    private function getCompanyProfile(): array
    {
        return [
            'name' => Setting::getValue('company_name', '-'),
            'npwp' => Setting::getValue('company_npwp', '-'),
            'nitku' => Setting::getValue('company_nitku', '-'),
        ];
    }

    /**
     * Calculate monthly summary
     */
    private function calculateMonthlySummary($payrolls): array
    {
        return [
            'total_employees' => $payrolls->count(),
            'total_gross_salary' => $payrolls->sum('gross_salary'),
            'total_allowance' => $payrolls->sum('allowance'),
            'total_bonus' => $payrolls->sum('bonus'),
            'total_income' => $payrolls->sum('total_income'),
            'total_pph21' => $payrolls->sum('pph21'),
            'avg_effective_rate' => $payrolls->sum('total_income') > 0
                ? round(($payrolls->sum('pph21') / $payrolls->sum('total_income')) * 100, 2)
                : 0,
        ];
    }

    /**
     * Calculate annual summary per month
     */
    private function calculateAnnualSummary($payrolls, $year): array
    {
        $summaries = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthPayrolls = $payrolls->where('month', $m);
            $summaries[$m] = [
                'month_name' => $this->getMonthName($m),
                'total_employees' => $monthPayrolls->count(),
                'total_income' => $monthPayrolls->sum('total_income'),
                'total_pph21' => $monthPayrolls->sum('pph21'),
            ];
        }
        return $summaries;
    }

    /**
     * Calculate employee annual summary
     */
    private function calculateEmployeeAnnualSummary($payrolls): array
    {
        $grouped = $payrolls->groupBy('employee_id');
        $summaries = [];

        foreach ($grouped as $employeeId => $employeePayrolls) {
            $employee = $employeePayrolls->first()->employee;
            $summaries[] = [
                'employee' => $employee,
                'total_months' => $employeePayrolls->count(),
                'total_gross_salary' => $employeePayrolls->sum('gross_salary'),
                'total_allowance' => $employeePayrolls->sum('allowance'),
                'total_bonus' => $employeePayrolls->sum('bonus'),
                'total_income' => $employeePayrolls->sum('total_income'),
                'total_pph21' => $employeePayrolls->sum('pph21'),
                'avg_effective_rate' => $employeePayrolls->sum('total_income') > 0
                    ? round(($employeePayrolls->sum('pph21') / $employeePayrolls->sum('total_income')) * 100, 2)
                    : 0,
            ];
        }

        // Sort by name
        usort($summaries, fn($a, $b) => strcmp($a['employee']->name, $b['employee']->name));

        return $summaries;
    }

    /**
     * Get Indonesian month name
     */
    private function getMonthName(int $month): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $months[$month] ?? '';
    }
}
