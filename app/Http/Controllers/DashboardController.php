<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');

        $totalEmployees = Employee::count();

        $totalSalaryThisMonth = Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->sum('total_income');

        $totalTaxThisMonth = Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->sum('pph21');

        $totalPayrollRecords = Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->count();

        // Recent payrolls
        $recentPayrolls = Payroll::with('employee')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Monthly summary for chart (last 6 months)
        $monthlySummary = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = (int) date('m', strtotime("-{$i} months"));
            $y = (int) date('Y', strtotime("-{$i} months"));
            $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

            $monthlySummary[] = [
                'label' => $months[$m] . ' ' . $y,
                'salary' => Payroll::where('month', $m)->where('year', $y)->sum('total_income'),
                'tax' => Payroll::where('month', $m)->where('year', $y)->sum('pph21'),
            ];
        }

        return view('dashboard', compact(
            'totalEmployees',
            'totalSalaryThisMonth',
            'totalTaxThisMonth',
            'totalPayrollRecords',
            'recentPayrolls',
            'monthlySummary',
            'currentMonth',
            'currentYear'
        ));
    }
}
