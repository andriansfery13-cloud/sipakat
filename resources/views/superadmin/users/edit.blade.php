<x-app-layout>
    <x-slot name="title">Edit Akun Admin Kecamatan</x-slot>
    <x-slot name="subtitle">Ubah pengaturan dan akses pengguna tenant.</x-slot>

    <div class="mb-6 animate-fade-in-up">
        <a href="{{ route('superadmin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl animate-fade-in-up animate-delay-1">
        <div class="p-6 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-800">Formulir Ubah Data Akun</h3>
        </div>
        
        <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Data Akun -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap Admin <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                        placeholder="admin@kecamatan.go.id">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Ubah Password</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                            placeholder="Kosongkan jika tidak diubah">
                        @error('password')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                            placeholder="Ketik ulang password baru">
                        @error('password_confirmation')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-slate-200 pt-3">

                <!-- Penetapan Tenant -->
                <div>
                    <label for="kecamatan_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Tetapkan Lokasi Kecamatan (Tenant) <span class="text-rose-500">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none appearance-none form-select">
                        <option value="" disabled>-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $k)
                            <option value="{{ $k->id }}" {{ (old('kecamatan_id', $user->kecamatan_id) == $k->id) ? 'selected' : '' }}>
                                {{ $k->nama_kecamatan }} ({{ $k->kode_kecamatan }})
                            </option>
                        @endforeach
                    </select>
                    @error('kecamatan_id')
                        <p class="mt-1 text-xs text-rose-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                <button type="submit" class="btn-primary flex-1 sm:flex-none sm:w-auto px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
