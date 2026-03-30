<x-app-layout>
    <x-slot name="title">Data Payroll</x-slot>
    <x-slot name="subtitle">Data gaji dan pajak pegawai</x-slot>

    <div class="animate-fade-in-up">
        <!-- Filter & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('payrolls.index') }}" class="flex gap-2 items-end">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Bulan</label>
                    <select name="month" class="border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-indigo-500">
                        @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $m => $label)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1">Tahun</label>
                    <input type="number" name="year" value="{{ $year }}" min="2020" max="2099"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2.5 w-24 focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit" class="btn-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium">Filter</button>
            </form>

            <div class="flex flex-wrap justify-end gap-2 mt-4 sm:mt-0">
                <a href="{{ route('payrolls.export', ['month' => $month, 'year' => $year]) }}" class="btn-success text-white px-4 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </a>
                <a href="{{ route('payrolls.import') }}" class="btn-primary-outline text-indigo-600 border border-indigo-600 hover:bg-indigo-50 px-4 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Import Excel
                </a>
                <a href="{{ route('payrolls.create') }}" class="btn-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Manual
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="stat-card p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Record</p>
                    <p class="text-lg font-bold text-slate-800">{{ number_format($totalRecords) }}</p>
                </div>
            </div>
            <div class="stat-card p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Penghasilan</p>
                    <p class="text-lg font-bold text-slate-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="stat-card p-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total PPh 21</p>
                    <p class="text-lg font-bold text-slate-800">Rp {{ number_format($totalTax, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <form method="POST" action="{{ route('payrolls.bulkDelete') }}" id="bulkDeleteForm">
                @csrf
                <div class="overflow-x-auto">
                    <table class="data-table w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left w-8">
                                    <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-4 py-3 text-left">Pegawai</th>
                                <th class="px-4 py-3 text-right">Gaji Pokok</th>
                                <th class="px-4 py-3 text-right">Tunjangan</th>
                                <th class="px-4 py-3 text-right">Bonus</th>
                                <th class="px-4 py-3 text-right">Total Income</th>
                                <th class="px-4 py-3 text-right">PPh 21</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($payrolls as $payroll)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" name="ids[]" value="{{ $payroll->id }}" class="row-check rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                                                <span class="text-white text-xs font-bold">{{ strtoupper(substr($payroll->employee->name ?? '?', 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-800">{{ $payroll->employee->name ?? '-' }}</p>
                                                <p class="text-xs text-slate-400">
                                                    {{ $payroll->employee->ptkp_status ?? '-' }}
                                                    @if($payroll->employee && isset(\App\Services\Pph21Calculator::TER_CATEGORY[$payroll->employee->ptkp_status]))
                                                        <span class="font-semibold text-indigo-500">(TER {{ \App\Services\Pph21Calculator::TER_CATEGORY[$payroll->employee->ptkp_status] }})</span>
                                                    @endif
                                                    · {{ $payroll->employee->position ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-600 font-mono text-xs">Rp {{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-slate-600 font-mono text-xs">Rp {{ number_format($payroll->allowance, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-slate-600 font-mono text-xs">Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-800 font-mono text-xs">Rp {{ number_format($payroll->total_income, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-red-600 font-semibold font-mono text-xs">Rp {{ number_format($payroll->pph21, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('payrolls.edit', $payroll) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-slate-400 text-sm">Belum ada data payroll untuk periode ini</p>
                                        <div class="mt-4 flex gap-2 justify-center">
                                            <a href="{{ route('payrolls.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">Input Manual</a>
                                            <a href="{{ route('payrolls.import') }}" class="btn-primary-outline border border-indigo-600 text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium">Import Excel</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payrolls->count() > 0)
                    <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between">
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus data yang dipilih?')"
                            class="btn-danger text-white px-4 py-2 rounded-xl text-xs font-medium inline-flex items-center gap-2 opacity-50" id="bulkDeleteBtn" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Terpilih
                        </button>
                        <div>{{ $payrolls->links() }}</div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const selectAll = document.getElementById('selectAll');
        const checks = document.querySelectorAll('.row-check');
        const bulkBtn = document.getElementById('bulkDeleteBtn');

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checks.forEach(c => c.checked = selectAll.checked);
                toggleBulk();
            });
            checks.forEach(c => c.addEventListener('change', toggleBulk));
        }

        function toggleBulk() {
            const any = [...checks].some(c => c.checked);
            if (bulkBtn) {
                bulkBtn.disabled = !any;
                bulkBtn.classList.toggle('opacity-50', !any);
            }
        }
    </script>
    @endpush
</x-app-layout>
