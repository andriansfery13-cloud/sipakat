<x-app-layout>
    <x-slot name="title">Generate XML Coretax</x-slot>
    <x-slot name="subtitle">Generate file XML PPh 21 untuk Coretax DJP</x-slot>

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Generate XML Coretax PPh 21</h3>
                    <p class="text-sm text-slate-500">Pilih periode untuk generate file XML</p>
                </div>
            </div>

            <!-- Preview Form -->
            <form method="POST" action="{{ route('xml.preview') }}" class="mb-4">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="month" class="block text-sm font-semibold text-slate-700 mb-1.5">Bulan</label>
                        <select name="month" id="month" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $m => $label)
                                <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun</label>
                        <input type="number" name="year" id="year" value="{{ date('Y') }}" min="2020" max="2099" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-3 rounded-xl text-sm font-medium inline-flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview XML
                    </button>
                </div>
            </form>

            <!-- Generate Form -->
            <form method="POST" action="{{ route('xml.generate') }}">
                @csrf
                <input type="hidden" name="month" id="gen_month">
                <input type="hidden" name="year" id="gen_year">
                <button type="submit" class="w-full btn-primary text-white px-6 py-3 rounded-xl text-sm font-medium inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Generate & Download XML
                </button>
            </form>

            <!-- Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-blue-700 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi
                </h4>
                <ul class="text-xs text-blue-600 space-y-1">
                    <li>• File XML mengikuti format Coretax DJP (MmPayrollBulk)</li>
                    <li>• Pastikan data payroll sudah diimport sebelum generate</li>
                    <li>• NPWP perusahaan dapat diatur di halaman <a href="{{ route('settings.edit') }}" class="underline font-medium">Pengaturan</a></li>
                    <li>• File tersimpan di history dan bisa didownload ulang</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Sync month/year between forms
        const month = document.getElementById('month');
        const year = document.getElementById('year');
        const genMonth = document.getElementById('gen_month');
        const genYear = document.getElementById('gen_year');

        function syncValues() {
            genMonth.value = month.value;
            genYear.value = year.value;
        }
        month.addEventListener('change', syncValues);
        year.addEventListener('change', syncValues);
        syncValues();
    </script>
    @endpush
</x-app-layout>
