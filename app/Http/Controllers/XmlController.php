<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\XmlLog;
use App\Services\CoretaxXmlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class XmlController extends Controller
{
    protected CoretaxXmlGenerator $generator;

    public function __construct(CoretaxXmlGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function index()
    {
        return view('xml.generate');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $month = (int) $request->month;
        $year = (int) $request->year;

        $count = $this->generator->getRecordCount($month, $year);

        if ($count === 0) {
            return redirect()->route('xml.index')
                ->with('error', 'Tidak ada data payroll untuk periode ini. Silakan import data terlebih dahulu.');
        }

        $xmlContent = $this->generator->generate($month, $year);

        $payrolls = Payroll::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        return view('xml.preview', compact('xmlContent', 'payrolls', 'month', 'year', 'count'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $month = (int) $request->month;
        $year = (int) $request->year;

        $count = $this->generator->getRecordCount($month, $year);

        if ($count === 0) {
            return redirect()->route('xml.index')
                ->with('error', 'Tidak ada data payroll untuk periode ini.');
        }

        $xmlContent = $this->generator->generate($month, $year);

        $fileName = 'pph21_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '_' . $year . '.xml';
        $filePath = 'xml/' . $fileName;

        Storage::disk('local')->put($filePath, $xmlContent);

        // Log the generation
        $log = XmlLog::create([
            'period_month' => $month,
            'period_year' => $year,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'record_count' => $count,
        ]);

        return redirect()->route('xml.history')
            ->with('success', "XML berhasil di-generate: {$fileName} ({$count} record).");
    }

    public function download($id)
    {
        $log = XmlLog::findOrFail($id);

        if (!Storage::disk('local')->exists($log->file_path)) {
            // Regenerate if file is missing
            $xmlContent = $this->generator->generate($log->period_month, $log->period_year);
            Storage::disk('local')->put($log->file_path, $xmlContent);
        }

        return Storage::disk('local')->download($log->file_path, $log->file_name, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function history()
    {
        $logs = XmlLog::orderByDesc('created_at')->paginate(20);
        return view('xml.history', compact('logs'));
    }

    public function destroyLog($id)
    {
        $log = XmlLog::findOrFail($id);

        if (Storage::disk('local')->exists($log->file_path)) {
            Storage::disk('local')->delete($log->file_path);
        }

        $log->delete();

        return redirect()->route('xml.history')
            ->with('success', 'Log berhasil dihapus.');
    }
}
