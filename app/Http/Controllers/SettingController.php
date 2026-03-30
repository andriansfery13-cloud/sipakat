<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'company_npwp' => Setting::getValue('company_npwp', ''),
            'company_name' => Setting::getValue('company_name', ''),
            'company_nitku' => Setting::getValue('company_nitku', ''),
        ];

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_npwp' => 'required|string|max:30',
            'company_name' => 'required|string|max:255',
            'company_nitku' => 'required|string|max:22',
        ], [
            'company_npwp.required' => 'NPWP Perusahaan wajib diisi.',
            'company_name.required' => 'Nama Perusahaan wajib diisi.',
            'company_nitku.required' => 'NITKU wajib diisi.',
        ]);

        Setting::setValue('company_npwp', $request->company_npwp);
        Setting::setValue('company_name', $request->company_name);
        Setting::setValue('company_nitku', $request->company_nitku);

        return redirect()->route('settings.edit')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
