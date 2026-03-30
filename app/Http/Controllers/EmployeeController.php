<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeTemplateExport;
use App\Imports\EmployeeImport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->where('employee_status', $request->status);
        }

        $employees = $query->orderBy('name')->paginate(15)->appends($request->query());

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'npwp' => 'required|string|max:20|unique:employees,npwp',
            'nik' => 'nullable|string|max:16',
            'nip' => 'nullable|string|max:50',
            'instansi' => 'nullable|string|max:255',
            'status_pegawai' => 'nullable|string|in:PNS,PPPK',
            'ptkp_status' => 'required|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3',
            'position' => 'nullable|string|max:255',
            'employee_status' => 'required|in:tetap,tidak_tetap',
        ], [
            'name.required' => 'Nama pegawai wajib diisi.',
            'npwp.required' => 'NPWP wajib diisi.',
            'npwp.unique' => 'NPWP sudah terdaftar.',
            'ptkp_status.required' => 'Status PTKP wajib dipilih.',
            'ptkp_status.in' => 'Status PTKP tidak valid.',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'npwp' => 'required|string|max:20|unique:employees,npwp,' . $employee->id,
            'nik' => 'nullable|string|max:16',
            'nip' => 'nullable|string|max:50',
            'instansi' => 'nullable|string|max:255',
            'status_pegawai' => 'nullable|string|in:PNS,PPPK',
            'ptkp_status' => 'required|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3',
            'position' => 'nullable|string|max:255',
            'employee_status' => 'required|in:tetap,tidak_tetap',
        ], [
            'name.required' => 'Nama pegawai wajib diisi.',
            'npwp.required' => 'NPWP wajib diisi.',
            'npwp.unique' => 'NPWP sudah terdaftar.',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    public function import()
    {
        return view('employees.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus berformat .xlsx atau .xls.',
        ]);

        $import = new EmployeeImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }

        $message = "Import selesai: {$import->getImportedCount()} data pegawai berhasil diimpor.";

        if ($import->getSkippedCount() > 0) {
            $message .= " {$import->getSkippedCount()} data dilewati (karena duplikat NPWP/NIK atau format salah).";
        }

        $errors = $import->getErrors();

        return redirect()->route('employees.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    public function downloadTemplate()
    {
        return Excel::download(new EmployeeTemplateExport(), 'template_import_pegawai.xlsx');
    }
}
