<x-app-layout>
    <x-slot name="title">Import Gaji Bulanan</x-slot>
    <x-slot name="subtitle">Upload file Excel untuk import data gaji</x-slot>

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <form method="POST" action="{{ route('payrolls.processImport') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-5">
                    <!-- Period -->
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

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">File Excel <span class="text-red-500">*</span></label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-indigo-400 transition-colors" id="dropZone">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <p class="text-sm text-slate-500 mb-2">Drag & drop file Excel atau</p>
                            <label for="file" class="btn-primary text-white px-4 py-2 rounded-xl text-sm font-medium cursor-pointer inline-block">
                                Pilih File
                            </label>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls" required class="hidden" onchange="showFileName(this)">
                            <p class="text-xs text-slate-400 mt-2" id="fileName">Format: .xlsx atau .xls (maks 10MB)</p>
                        </div>
                    </div>

                    <!-- Guide -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <h4 class="text-sm font-semibold text-slate-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Format Kolom Excel
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="py-2 px-3 text-left text-slate-600">Kolom</th>
                                        <th class="py-2 px-3 text-left text-slate-600">Header yang Diterima</th>
                                        <th class="py-2 px-3 text-left text-slate-600">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="text-slate-500">
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2 px-3 font-medium">Nama</td>
                                        <td class="py-2 px-3"><code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">nama</code>, <code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">name</code></td>
                                        <td class="py-2 px-3">Harus sama dengan nama di master pegawai</td>
                                    </tr>
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2 px-3 font-medium">Gaji Pokok</td>
                                        <td class="py-2 px-3"><code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">gaji_pokok</code>, <code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">gaji</code>, <code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">salary</code></td>
                                        <td class="py-2 px-3">Angka (tanpa Rp atau titik)</td>
                                    </tr>
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2 px-3 font-medium">Tunjangan</td>
                                        <td class="py-2 px-3"><code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">tunjangan</code>, <code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">allowance</code></td>
                                        <td class="py-2 px-3">Opsional, default 0</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 font-medium">Bonus</td>
                                        <td class="py-2 px-3"><code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">bonus</code>, <code class="bg-slate-200 px-1.5 py-0.5 rounded text-xs">insentif</code></td>
                                        <td class="py-2 px-3">Opsional, default 0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        Import Data
                    </button>
                    <a href="{{ route('payrolls.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function showFileName(input) {
            const label = document.getElementById('fileName');
            if (input.files.length > 0) {
                label.textContent = '📄 ' + input.files[0].name;
                label.classList.add('text-indigo-600', 'font-medium');
            }
        }

        // Drag & Drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('file');

        ['dragenter', 'dragover'].forEach(e => {
            dropZone.addEventListener(e, (ev) => { ev.preventDefault(); dropZone.classList.add('border-indigo-400', 'bg-indigo-50'); });
        });
        ['dragleave', 'drop'].forEach(e => {
            dropZone.addEventListener(e, (ev) => { ev.preventDefault(); dropZone.classList.remove('border-indigo-400', 'bg-indigo-50'); });
        });
        dropZone.addEventListener('drop', (ev) => {
            ev.preventDefault();
            if (ev.dataTransfer.files.length > 0) {
                fileInput.files = ev.dataTransfer.files;
                showFileName(fileInput);
            }
        });
    </script>
    @endpush
</x-app-layout>
