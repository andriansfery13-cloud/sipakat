<x-app-layout>
    <x-slot name="title">Tambah Kecamatan</x-slot>
    <x-slot name="subtitle">Formulir pendaftaran data kecamatan baru sebagai tenant.</x-slot>

    <div class="mb-6 animate-fade-in-up">
        <a href="{{ route('superadmin.kecamatans.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl animate-fade-in-up animate-delay-1">
        <div class="p-6 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-800">Formulir Data Kecamatan</h3>
        </div>
        
        <form action="{{ route('superadmin.kecamatans.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-5">
                <!-- Nama Kecamatan -->
                <div>
                    <label for="nama_kecamatan" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kecamatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kecamatan" id="nama_kecamatan" value="{{ old('nama_kecamatan') }}" required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                        placeholder="Contoh: Biringkanaya">
                    @error('nama_kecamatan')
                        <p class="mt-1 text-xs text-rose-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Kode Kecamatan -->
                <div>
                    <label for="kode_kecamatan" class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Kecamatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="kode_kecamatan" id="kode_kecamatan" value="{{ old('kode_kecamatan') }}" required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                        placeholder="Contoh: KEC-BRK-01">
                    @error('kode_kecamatan')
                        <p class="mt-1 text-xs text-rose-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat / Keterangan</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                        placeholder="Masukkan alamat lengkap kantor kecamatan (Opsional)">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-xs text-rose-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                <button type="submit" class="btn-primary flex-1 sm:flex-none sm:w-auto px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Kecamatan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
