<x-app-layout>
    <x-slot name="title">Tambah Data Payroll</x-slot>
    <x-slot name="subtitle">Input data gaji pegawai secara manual</x-slot>

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Input Data Payroll Manual</h3>
                    <p class="text-sm text-slate-500">PPh 21 akan dihitung secara otomatis</p>
                </div>
            </div>

            <form method="POST" action="{{ route('payrolls.store') }}" id="payrollForm">
                @csrf

                <div class="space-y-5">
                    <!-- Pegawai -->
                    <div>
                        <label for="employee_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Pegawai <span class="text-red-500">*</span></label>
                        <select name="employee_id" id="employee_id" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('employee_id') border-red-400 @enderror">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    data-ptkp="{{ $emp->ptkp_status }}"
                                    data-position="{{ $emp->position }}"
                                    {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} — {{ $emp->formatted_npwp }} ({{ $emp->ptkp_status }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <!-- Employee Info Card -->
                        <div id="empInfo" class="mt-2 p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-sm hidden">
                            <div class="flex items-center gap-4">
                                <div>
                                    <span class="text-indigo-600 font-medium">PTKP:</span>
                                    <span id="empPtkp" class="text-indigo-800 font-bold">-</span>
                                </div>
                                <div>
                                    <span class="text-indigo-600 font-medium">Jabatan:</span>
                                    <span id="empPosition" class="text-indigo-800">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Periode -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="month" class="block text-sm font-semibold text-slate-700 mb-1.5">Bulan <span class="text-red-500">*</span></label>
                            <select name="month" id="month" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $m => $label)
                                    <option value="{{ $m }}" {{ old('month', date('m')) == $m ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                            <input type="number" name="year" id="year" value="{{ old('year', date('Y')) }}" min="2020" max="2099" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Gaji -->
                    <div>
                        <label for="gross_salary" class="block text-sm font-semibold text-slate-700 mb-1.5">Gaji Pokok <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                            <input type="number" name="gross_salary" id="gross_salary" value="{{ old('gross_salary') }}" required min="0"
                                class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('gross_salary') border-red-400 @enderror"
                                placeholder="0" oninput="calcTotal()">
                        </div>
                        @error('gross_salary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tunjangan & Bonus -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="allowance" class="block text-sm font-semibold text-slate-700 mb-1.5">Tunjangan</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                                <input type="number" name="allowance" id="allowance" value="{{ old('allowance', 0) }}" min="0"
                                    class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="0" oninput="calcTotal()">
                            </div>
                        </div>
                        <div>
                            <label for="bonus" class="block text-sm font-semibold text-slate-700 mb-1.5">Bonus</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                                <input type="number" name="bonus" id="bonus" value="{{ old('bonus', 0) }}" min="0"
                                    class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="0" oninput="calcTotal()">
                            </div>
                        </div>
                    </div>

                    <!-- Summary Preview -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-5 border border-slate-200">
                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Ringkasan
                        </h4>
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
                        <p class="text-xs text-slate-400 mt-3">
                            <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            PPh 21 akan dihitung otomatis berdasarkan PTKP pegawai saat disimpan
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan & Hitung PPh 21
                    </button>
                    <a href="{{ route('payrolls.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show employee info on select
        document.getElementById('employee_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const info = document.getElementById('empInfo');
            if (this.value) {
                info.classList.remove('hidden');
                document.getElementById('empPtkp').textContent = selected.dataset.ptkp || '-';
                document.getElementById('empPosition').textContent = selected.dataset.position || '-';
            } else {
                info.classList.add('hidden');
            }
        });

        // Live total calculation
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

        // Trigger on load for old values
        calcTotal();
        document.getElementById('employee_id').dispatchEvent(new Event('change'));
    </script>
    @endpush
</x-app-layout>
