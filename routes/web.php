<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SuperadminDashboardController;
use App\Http\Controllers\Superadmin\KecamatanController as SuperadminKecamatanController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\XmlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard - redirect based on role
Route::get('/dashboard', function () {
    if (auth()->user()->isSuperAdmin()) {
        return redirect()->route('superadmin.dashboard');
    }
    return app(DashboardController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employees
    Route::get('/employees/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.template');
    Route::get('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::post('/employees/import', [EmployeeController::class, 'processImport'])->name('employees.processImport');
    Route::resource('employees', EmployeeController::class)->except(['show']);

    // Payrolls
    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
    Route::post('/payrolls', [PayrollController::class, 'store'])->name('payrolls.store');
    Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])->name('payrolls.edit');
    Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');
    Route::delete('/payrolls/{payroll}', [PayrollController::class, 'destroy'])->name('payrolls.destroy');
    Route::get('/payrolls/import', [PayrollController::class, 'import'])->name('payrolls.import');
    Route::post('/payrolls/import', [PayrollController::class, 'processImport'])->name('payrolls.processImport');
    Route::get('/payrolls/export', [PayrollController::class, 'export'])->name('payrolls.export');
    Route::post('/payrolls/bulk-delete', [PayrollController::class, 'bulkDelete'])->name('payrolls.bulkDelete');

    // XML Coretax
    Route::get('/xml', [XmlController::class, 'index'])->name('xml.index');
    Route::post('/xml/preview', [XmlController::class, 'preview'])->name('xml.preview');
    Route::post('/xml/generate', [XmlController::class, 'generate'])->name('xml.generate');
    Route::get('/xml/download/{id}', [XmlController::class, 'download'])->name('xml.download');
    Route::get('/xml/history', [XmlController::class, 'history'])->name('xml.history');
    Route::delete('/xml/log/{id}', [XmlController::class, 'destroyLog'])->name('xml.destroyLog');

    // Settings
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Reports PPh 21
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/monthly/pdf', [ReportController::class, 'exportMonthlyPdf'])->name('reports.monthly.pdf');
    Route::get('/reports/monthly/excel', [ReportController::class, 'exportMonthlyExcel'])->name('reports.monthly.excel');
    Route::get('/reports/annual', [ReportController::class, 'annual'])->name('reports.annual');
    Route::get('/reports/annual/pdf', [ReportController::class, 'exportAnnualPdf'])->name('reports.annual.pdf');
    Route::get('/reports/annual/excel', [ReportController::class, 'exportAnnualExcel'])->name('reports.annual.excel');
});

// Superadmin Routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kecamatan/{kecamatan}/detail', [SuperadminDashboardController::class, 'detailKecamatan'])->name('kecamatan.detail');

    Route::resource('kecamatans', SuperadminKecamatanController::class)->except(['show']);
    Route::resource('users', SuperadminUserController::class)->except(['show']);
});

require __DIR__.'/auth.php';
