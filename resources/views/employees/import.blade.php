<x-app-layout>
    <x-slot name="title">Import Pegawai</x-slot>
    <x-slot name="subtitle">Upload data pegawai secara massal via Excel</x-slot>

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <h4 class="font-bold text-sm">Gagal!</h4>
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="md:col-span-1 border border-slate-200 bg-white rounded-2xl shadow-sm p-6 lg:p-8 self-start">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Upload File</h3>
                        <p class="text-xs text-slate-500">Format .xlsx atau .xls</p>
                    </div>
                </div>

                <form action="{{ route('employees.processImport') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih File Excel <span class="text-red-500">*</span></label>
                        <input type="file" name="file" accept=".xlsx,.xls" required
                            class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit" class="w-full btn-primary text-white py-2.5 rounded-xl text-sm font-medium flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>

            <!-- Instructions Card -->
            <div class="md:col-span-2 bg-slate-50 border border-slate-200 rounded-2xl p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <h4 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Panduan Format Kolom Excel
                    </h4>
                    <a href="{{ route('employees.template') }}" class="btn-success text-white px-4 py-2 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Template
                    </a>
                </div>
                
                <p class="text-sm text-slate-600 mb-4">
                    Pastikan tabel data ada di sheet pertama dan baris ke-1 adalah **Header Kolom**. Kolom yang akan dibaca sistem (huruf besar/kecil diabaikan):
                </p>

                <div class="bg-white border text-sm border-slate-200 rounded-xl overflow-x-auto mb-4 p-4">
                    <table class="w-full min-w-max text-left text-slate-600">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-800">
                                <th class="pb-2 font-semibold">Header Kolom</th>
                                <th class="pb-2 font-semibold">Tipe Aturan</th>
                                <th class="pb-2 font-semibold">Contoh Isi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2.5 font-medium">Nama</td>
                                <td class="py-2.5"><span class="bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded text-xs">Wajib</span></td>
                                <td class="py-2.5">Budi Santoso</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-medium">NPWP</td>
                                <td class="py-2.5"><span class="bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded text-xs">Wajib & Unik</span></td>
                                <td class="py-2.5 font-mono text-xs">123456789012345</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-medium">NIK</td>
                                <td class="py-2.5"><span class="bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded text-xs">Opsional</span></td>
                                <td class="py-2.5 font-mono text-xs">3201234567890001</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-medium">PTKP</td>
                                <td class="py-2.5"><span class="bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded text-xs">Wajib</span> Harus TK/0, K/1, dsb</td>
                                <td class="py-2.5 font-mono text-xs">TK/0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-medium">Jabatan</td>
                                <td class="py-2.5"><span class="bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded text-xs">Opsional</span></td>
                                <td class="py-2.5">Staff IT</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-medium">Status</td>
                                <td class="py-2.5"><span class="bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded text-xs">Opsional</span> Tetap / Tidak Tetap</td>
                                <td class="py-2.5">Tetap</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <h5 class="font-semibold text-sm text-slate-800 mb-2">Penanganan Duplikat:</h5>
                <ul class="list-disc list-inside text-sm text-slate-600 space-y-1">
                    <li>Jika ada nomor <b>NPWP</b> yang sama dengan di database, baris tersebut akan **dilewati (skip)** secara otomatis.</li>
                    <li>Sistem tidak akan melakukan pembaruan (update) data karyawan pada proses import ini.</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
