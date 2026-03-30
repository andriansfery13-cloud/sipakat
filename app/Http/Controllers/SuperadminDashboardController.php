<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Kecamatan;
use App\Models\Payroll;
use Illuminate\Http\Request;

class SuperadminDashboardController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::withCount('employees')->get();

        $totalKecamatan = Kecamatan::count();
        $totalEmployees = Employee::withoutGlobalScopes()->count();

        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');

        $totalSalaryThisMonth = Payroll::withoutGlobalScopes()
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->sum('total_income');

        $totalTaxThisMonth = Payroll::withoutGlobalScopes()
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->sum('pph21');

        // Per-kecamatan recap
        $kecamatanRecap = [];
        foreach ($kecamatans as $kec) {
            $employees = Employee::withoutGlobalScopes()
                ->where('kecamatan_id', $kec->id)
                ->pluck('id');

            $kecamatanRecap[] = [
                'kecamatan' => $kec,
                'total_employees' => $employees->count(),
                'total_salary' => Payroll::withoutGlobalScopes()
                    ->whereIn('employee_id', $employees)
                    ->where('month', $currentMonth)
                    ->where('year', $currentYear)
                    ->sum('total_income'),
                'total_tax' => Payroll::withoutGlobalScopes()
                    ->whereIn('employee_id', $employees)
                    ->where('month', $currentMonth)
                    ->where('year', $currentYear)
                    ->sum('pph21'),
            ];
        }

        return view('superadmin.dashboard', compact(
            'totalKecamatan',
            'totalEmployees',
            'totalSalaryThisMonth',
            'totalTaxThisMonth',
            'kecamatanRecap',
            'currentMonth',
            'currentYear'
        ));
    }

    public function detailKecamatan(Kecamatan $kecamatan, Request $request)
    {
        $month = $request->get('month', (int) date('m'));
        $year = $request->get('year', (int) date('Y'));

        $employees = Employee::withoutGlobalScopes()
            ->where('kecamatan_id', $kecamatan->id)
            ->get();

        $payrolls = Payroll::withoutGlobalScopes()
            ->with(['employee' => function ($q) {
                $q->withoutGlobalScopes();
            }])
            ->whereHas('employee', function ($q) use ($kecamatan) {
                $q->withoutGlobalScopes()->where('kecamatan_id', $kecamatan->id);
            })
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        return view('superadmin.detail-kecamatan', compact(
            'kecamatan',
            'employees',
            'payrolls',
            'month',
            'year'
        ));
    }
}
