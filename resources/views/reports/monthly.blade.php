<x-app-layout>
    <x-slot name="title">Laporan PPh 21 Bulanan</x-slot>
    <x-slot name="subtitle">Laporan pemotongan pajak penghasilan pasal 21</x-slot>

    <div class="space-y-6 animate-fade-in-up">
        {{-- Filter --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form method="GET" action="{{ route('reports.monthly') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bulan</label>
                    <select name="month" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $name)
                            <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[100px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun</label>
                    <input type="number" name="year" value="{{ $year }}" min="2020" max="2099"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Tampilkan
                </button>
            </form>
        </div>

        {{-- Company Profile --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold">{{ $company['name'] }}</h3>
                    <p class="text-indigo-200 text-sm">NPWP: {{ $company['npwp'] }} &bull; NITKU: {{ $company['nitku'] }}</p>
                </div>
            </div>
        </div>

        {{-- Export Buttons --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-700">Export Laporan</p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('reports.monthly.pdf', ['month' => $month, 'year' => $year]) }}"
                        style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"
                        class="inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <svg class="w-5 h-5 drop-shadow-sm" fill="currentColor" viewBox="0 0 384 512"><path d="M181.9 256.1c-5-16-4.9-46.9-2-46.9 8.4 0 7.6 36.9 2 46.9zm-1.7 47.2c-7.7 20.2-17.3 43.3-28.4 62.7 18.3-7 39-17.2 62.9-21.9-12.7-9.6-24.9-23.4-34.5-40.8zM86.1 428.1c0 .8 13.2-5.4 34.9-40.2-6.7 6.3-29.1 24.5-34.9 40.2zM261.6 299c21.2 3.2 28.5 12.1 27.5 20.3-1 8.3-12.8 6.5-27.5-20.3zM384 121.9v358.1c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32V32C0 14.3 14.3 0 32 0h224l128 121.9zM224 136V0L384 160H248c-13.2 0-24-10.8-24-24zm115.5 186.2c-12.8-19.4-43.1-34.1-43.1-34.1s-10.3-27.3-15.5-50.5c-10.2-46.1 1.7-69.6 15.6-69.6 12 0 17.5 15.6 17.5 29.5 0 26.6-18.4 61-18.4 61s-17 40.5-30.8 77c-40.1 10.9-80.6 16-80.6 16s-20.3 35.6-32.9 61c-19.4 39.1-41.9 66-50.5 66-8.7 0-14.5-6.7-14.5-16 0-34.3 46.3-95.3 46.3-95.3s-14.7-27.9-14.7-60.6c0-43.7 18.6-69.5 35.8-69.5 9 0 14.8 5.7 14.8 15.6 0 16.5-12.4 46.5-12.4 75 0 13.8 3.5 26.1 3.5 26.1s16.6-4.6 37.8-15c2.3-1.1 4.7-2.3 7-3.6z"/></svg>
                        Export PDF
                    </a>
                    <a href="{{ route('reports.monthly.excel', ['month' => $month, 'year' => $year]) }}"
                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"
                        class="inline-flex items-center gap-2 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <svg class="w-5 h-5 drop-shadow-sm" fill="currentColor" viewBox="0 0 384 512"><path d="M224 136V0L384 160H248c-13.2 0-24-10.8-24-24zm160 144.1v227.9c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32V32C0 14.3 14.3 0 32 0h224l128 121.9v158.2zM212.1 274.6c2.8-5.3 4.2-11.4 4.2-18.1 0-8.9-2.3-16.1-7-21.7-4.7-5.5-11-8.3-19-8.3-9.5 0-16.6 3.1-21.4 9.4L137 282.4l-31.9-46.5c-4.8-6.3-11.9-9.4-21.4-9.4-8 0-14.3 2.8-19 8.3-4.7 5.5-7 12.8-7 21.7 0 6.8 1.4 12.8 4.2 18.1l42.6 62.1-42.6 62.1c-2.8 4.8-4.2 10.7-4.2 17.4 0 9.2 2.3 16.6 7 22.2 4.7 5.7 11.2 8.5 19.5 8.5 9.5 0 16.8-3.1 21.9-9.4l33.1-48 33.1 48c5.2 6.3 12.4 9.4 21.9 9.4 8.3 0 14.8-2.8 19.5-8.5 4.7-5.6 7-13 7-22.2 0-6.8-1.5-12.7-4.4-17.6l-42.8-61.9 42.8-62.1z"/></svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        @php
            $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
            $monthName = $months[$month] ?? '';
        @endphp

        {{-- Summary Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="stat-card p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Jumlah Pegawai</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $summary['total_employees'] }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Total Penghasilan</p>
                        <p class="text-lg font-bold text-slate-800">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Total PPh 21</p>
                        <p class="text-lg font-bold text-red-600">Rp {{ number_format($summary['total_pph21'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Tarif Efektif Rata-rata</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $summary['avg_effective_rate'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800">Detail Pemotongan PPh 21</h3>
                    <p class="text-xs text-slate-500">Periode: {{ $monthName }} {{ $year }}</p>
                </div>
                <span class="badge bg-indigo-100 text-indigo-700">{{ $payrolls->count() }} data</span>
            </div>

            @if($payrolls->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-slate-500 font-medium">Tidak ada data untuk periode ini</p>
                    <p class="text-sm text-slate-400 mt-1">Silakan pilih periode lain atau import data payroll terlebih dahulu</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full data-table text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Nama Pegawai</th>
                                <th class="px-4 py-3 text-left">NPWP</th>
                                <th class="px-4 py-3 text-left">Status PTKP</th>
                                <th class="px-4 py-3 text-right">Gaji Pokok</th>
                                <th class="px-4 py-3 text-right">Tunjangan</th>
                                <th class="px-4 py-3 text-right">Bonus</th>
                                <th class="px-4 py-3 text-right">Total Penghasilan</th>
                                <th class="px-4 py-3 text-right">PPh 21</th>
                                <th class="px-4 py-3 text-right">Tarif (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $index => $payroll)
                                @php
                                    $rate = $payroll->total_income > 0 ? round(($payroll->pph21 / $payroll->total_income) * 100, 2) : 0;
                                @endphp
                                <tr class="border-t border-slate-100">
                                    <td class="px-4 py-3 text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $payroll->employee->name ?? '-' }}</td>
                                    <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $payroll->employee->formatted_npwp ?? '-' }}</td>
                                    <td class="px-4 py-3"><span class="badge bg-slate-100 text-slate-600">{{ $payroll->employee->ptkp_status ?? '-' }}</span></td>
                                    <td class="px-4 py-3 text-right font-mono">{{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-mono">{{ number_format($payroll->allowance, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-mono">{{ number_format($payroll->bonus, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-mono font-semibold">{{ number_format($payroll->total_income, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-mono font-semibold text-red-600">{{ number_format($payroll->pph21, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-mono">{{ $rate }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200 bg-slate-50 font-bold">
                                <td colspan="4" class="px-4 py-3 text-right text-slate-700">TOTAL</td>
                                <td class="px-4 py-3 text-right font-mono">{{ number_format($summary['total_gross_salary'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono">{{ number_format($summary['total_allowance'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono">{{ number_format($summary['total_bonus'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono">{{ number_format($summary['total_income'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-red-600">{{ number_format($summary['total_pph21'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono">{{ $summary['avg_effective_rate'] }}%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
