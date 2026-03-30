<?php

namespace App\Http\Controllers;

use App\Exports\PayrollExport;
use App\Imports\PayrollImport;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\Pph21Calculator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PayrollController extends Controller
{
    protected Pph21Calculator $calculator;

    public function __construct(Pph21Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $payrolls = Payroll::with('employee')
            ->forPeriod($month, $year)
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->appends($request->query());

        $totalIncome = Payroll::forPeriod($month, $year)->sum('total_income');
        $totalTax = Payroll::forPeriod($month, $year)->sum('pph21');
        $totalRecords = Payroll::forPeriod($month, $year)->count();

        return view('payrolls.index', compact('payrolls', 'month', 'year', 'totalIncome', 'totalTax', 'totalRecords'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
            'gross_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ], [
            'employee_id.required' => 'Pegawai wajib dipilih.',
            'employee_id.exists' => 'Pegawai tidak ditemukan.',
            'month.required' => 'Bulan wajib dipilih.',
            'year.required' => 'Tahun wajib diisi.',
            'gross_salary.required' => 'Gaji pokok wajib diisi.',
            'gross_salary.min' => 'Gaji pokok tidak boleh negatif.',
        ]);

        // Check duplicate
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()
                ->with('error', 'Data payroll untuk pegawai ini pada periode tersebut sudah ada.');
        }

        $allowance = $validated['allowance'] ?? 0;
        $bonus = $validated['bonus'] ?? 0;
        $totalIncome = $validated['gross_salary'] + $allowance + $bonus;

        // Get employee PTKP status
        $employee = Employee::findOrFail($validated['employee_id']);
        $pph21 = $this->calculator->calculateMonthly($totalIncome, $employee->ptkp_status, $validated['month'], $validated['year'], $employee->id);

        Payroll::create([
            'employee_id' => $validated['employee_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'gross_salary' => $validated['gross_salary'],
            'allowance' => $allowance,
            'bonus' => $bonus,
            'total_income' => $totalIncome,
            'pph21' => $pph21,
        ]);

        return redirect()->route('payrolls.index', ['month' => $validated['month'], 'year' => $validated['year']])
            ->with('success', "Data payroll untuk {$employee->name} berhasil ditambahkan. PPh 21: Rp " . number_format($pph21, 0, ',', '.'));
    }

    public function edit(Payroll $payroll)
    {
        $payroll->load('employee');
        $employees = Employee::orderBy('name')->get();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
            'gross_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ], [
            'employee_id.required' => 'Pegawai wajib dipilih.',
            'gross_salary.required' => 'Gaji pokok wajib diisi.',
        ]);

        // Check duplicate (exclude current)
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('id', '!=', $payroll->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()
                ->with('error', 'Data payroll untuk pegawai ini pada periode tersebut sudah ada.');
        }

        $allowance = $validated['allowance'] ?? 0;
        $bonus = $validated['bonus'] ?? 0;
        $totalIncome = $validated['gross_salary'] + $allowance + $bonus;

        $employee = Employee::findOrFail($validated['employee_id']);
        $pph21 = $this->calculator->calculateMonthly($totalIncome, $employee->ptkp_status, $validated['month'], $validated['year'], $employee->id, $payroll->id);

        $payroll->update([
            'employee_id' => $validated['employee_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'gross_salary' => $validated['gross_salary'],
            'allowance' => $allowance,
            'bonus' => $bonus,
            'total_income' => $totalIncome,
            'pph21' => $pph21,
        ]);

        return redirect()->route('payrolls.index', ['month' => $validated['month'], 'year' => $validated['year']])
            ->with('success', "Data payroll {$employee->name} berhasil diperbarui.");
    }

    public function destroy(Payroll $payroll)
    {
        $month = $payroll->month;
        $year = $payroll->year;
        $name = $payroll->employee->name ?? '';
        $payroll->delete();

        return redirect()->route('payrolls.index', ['month' => $month, 'year' => $year])
            ->with('success', "Data payroll {$name} berhasil dihapus.");
    }

    public function import()
    {
        return view('payrolls.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus berformat .xlsx atau .xls.',
            'month.required' => 'Bulan wajib dipilih.',
            'year.required' => 'Tahun wajib diisi.',
        ]);

        $import = new PayrollImport($request->month, $request->year);

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }

        $message = "Import selesai: {$import->getImportedCount()} data berhasil diimpor.";

        if ($import->getSkippedCount() > 0) {
            $message .= " {$import->getSkippedCount()} data dilewati.";
        }

        $errors = $import->getErrors();

        return redirect()->route('payrolls.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    public function export(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $count = Payroll::where('month', $month)->where('year', $year)->count();
        if ($count === 0) {
            return redirect()->back()->with('error', 'Tidak ada data payroll untuk periode ini.');
        }

        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $fileName = "payroll_{$months[(int)$month]}_{$year}.xlsx";

        return Excel::download(new PayrollExport((int)$month, (int)$year), $fileName);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:payrolls,id',
        ]);

        Payroll::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' data payroll berhasil dihapus.');
    }
}
