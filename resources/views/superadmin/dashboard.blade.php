<x-app-layout>
    <x-slot name="title">Dashboard Rekap</x-slot>
    <x-slot name="subtitle">Ringkasan Data Seluruh Kecamatan</x-slot>

    <!-- Stats summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card p-6 animate-fade-in-up">
            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4 text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="text-sm text-slate-500 font-medium mb-1">Total Kecamatan</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $totalKecamatan }}</h3>
        </div>

        <div class="stat-card p-6 animate-fade-in-up animate-delay-1">
            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-4 text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-sm text-slate-500 font-medium mb-1">Total Pegawai Punya Data</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $totalEmployees }}</h3>
        </div>

        <div class="stat-card p-6 animate-fade-in-up animate-delay-2">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm text-slate-500 font-medium mb-1">Gaji Bruto Bulan Ini</p>
            <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format((float) $totalSalaryThisMonth, 0, ',', '.') }}</h3>
        </div>

        <div class="stat-card p-6 animate-fade-in-up animate-delay-3">
            <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mb-4 text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <p class="text-sm text-slate-500 font-medium mb-1">Potongan PPh 21 Bulan Ini</p>
            <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format((float) $totalTaxThisMonth, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in-up animate-delay-4">
        <div class="p-6 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Rekapitulasi Tiap Kecamatan</h3>
            <span class="text-xs text-slate-500 font-medium">Data Bulan {{ $currentMonth }} Tahun {{ $currentYear }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 border-b border-slate-200">Kecamatan</th>
                        <th class="px-6 py-4 border-b border-slate-200">Total Pegawai</th>
                        <th class="px-6 py-4 border-b border-slate-200">Total Gaji Bulan Ini</th>
                        <th class="px-6 py-4 border-b border-slate-200">Total PPh 21 Bulan Ini</th>
                        <th class="px-6 py-4 border-b border-slate-200 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($kecamatanRecap as $recap)
                        <tr class="border-b border-slate-100 text-slate-700">
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-800">{{ $recap['kecamatan']->nama_kecamatan }}</span>
                                <div class="text-xs text-slate-400 mt-1">Kode: {{ $recap['kecamatan']->kode_kecamatan }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ number_format($recap['total_employees']) }}</td>
                            <td class="px-6 py-4 text-emerald-600 font-medium">Rp {{ number_format((float) $recap['total_salary'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-rose-600 font-medium">Rp {{ number_format((float) $recap['total_tax'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('superadmin.kecamatan.detail', $recap['kecamatan']->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors text-xs font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail Data
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 relative">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    Belum ada data kecamatan terdaftar.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
