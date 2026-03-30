<x-app-layout>
    <x-slot name="title">Edit Data Payroll</x-slot>
    <x-slot name="subtitle">Edit data gaji: {{ $payroll->employee->name ?? '-' }}</x-slot>

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Edit Data Payroll</h3>
                    <p class="text-sm text-slate-500">PPh 21 akan dihitung ulang otomatis</p>
                </div>
            </div>

            <form method="POST" action="{{ route('payrolls.update', $payroll) }}">
                @csrf @method('PUT')

                <div class="space-y-5">
                    <!-- Pegawai -->
                    <div>
                        <label for="employee_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Pegawai <span class="text-red-500">*</span></label>
                        <select name="employee_id" id="employee_id" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    data-ptkp="{{ $emp->ptkp_status }}"
                                    data-position="{{ $emp->position }}"
                                    {{ old('employee_id', $payroll->employee_id) == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} — {{ $emp->formatted_npwp }} ({{ $emp->ptkp_status }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Periode -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="month" class="block text-sm font-semibold text-slate-700 mb-1.5">Bulan <span class="text-red-500">*</span></label>
                            <select name="month" id="month" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $m => $label)
                                    <option value="{{ $m }}" {{ old('month', $payroll->month) == $m ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                            <input type="number" name="year" id="year" value="{{ old('year', $payroll->year) }}" min="2020" max="2099" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Gaji -->
                    <div>
                        <label for="gross_salary" class="block text-sm font-semibold text-slate-700 mb-1.5">Gaji Pokok <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                            <input type="number" name="gross_salary" id="gross_salary" value="{{ old('gross_salary', $payroll->gross_salary) }}" required min="0"
                                class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                oninput="calcTotal()">
                        </div>
                        @error('gross_salary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tunjangan & Bonus -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="allowance" class="block text-sm font-semibold text-slate-700 mb-1.5">Tunjangan</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                                <input type="number" name="allowance" id="allowance" value="{{ old('allowance', $payroll->allowance) }}" min="0"
                                    class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    oninput="calcTotal()">
                            </div>
                        </div>
                        <div>
                            <label for="bonus" class="block text-sm font-semibold text-slate-700 mb-1.5">Bonus</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                                <input type="number" name="bonus" id="bonus" value="{{ old('bonus', $payroll->bonus) }}" min="0"
                                    class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    oninput="calcTotal()">
                            </div>
                        </div>
                    </div>

                    <!-- Current PPh 21 info -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-amber-700 font-medium">PPh 21 saat ini:</span>
                            <span class="text-amber-800 font-bold font-mono">Rp {{ number_format($payroll->pph21, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-amber-600 mt-1">Akan dihitung ulang saat disimpan</p>
                    </div>

                    <!-- Summary Preview -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-5 border border-slate-200">
                        <h4 class="text-sm font-bold text-slate-700 mb-3">Ringkasan</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Gaji Pokok</span>
                                <span class="font-mono text-slate-700" id="previewGaji">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tunjangan</span>
                                <span class="font-mono text-slate-700" id="previewTunjangan">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Bonus</span>
                                <span class="font-mono text-slate-700" id="previewBonus">Rp 0</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-300 pt-2">
                                <span class="text-slate-700 font-bold">Total Income</span>
                                <span class="font-mono font-bold text-indigo-600" id="previewTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('payrolls.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function calcTotal() {
            const gaji = parseFloat(document.getElementById('gross_salary').value) || 0;
            const tunjangan = parseFloat(document.getElementById('allowance').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus').value) || 0;
            const total = gaji + tunjangan + bonus;

            document.getElementById('previewGaji').textContent = formatRp(gaji);
            document.getElementById('previewTunjangan').textContent = formatRp(tunjangan);
            document.getElementById('previewBonus').textContent = formatRp(bonus);
            document.getElementById('previewTotal').textContent = formatRp(total);
        }

        function formatRp(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        calcTotal();
    </script>
    @endpush
</x-app-layout>
