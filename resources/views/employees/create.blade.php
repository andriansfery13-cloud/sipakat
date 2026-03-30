<x-app-layout>
    <x-slot name="title">Tambah Pegawai</x-slot>
    <x-slot name="subtitle">Tambahkan data pegawai baru</x-slot>

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <div class="space-y-5">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pegawai <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-400 @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- NPWP & NIK -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="npwp" class="block text-sm font-semibold text-slate-700 mb-1.5">NPWP <span class="text-red-500">*</span></label>
                            <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('npwp') border-red-400 @enderror"
                                placeholder="Contoh: 1234567890123456" maxlength="20">
                            @error('npwp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-slate-700 mb-1.5">NIK</label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="16 digit NIK KTP" maxlength="16">
                        </div>
                    </div>

                    <!-- NIP & Instansi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1.5">NIP</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Masukkan NIP (jika ada)" maxlength="50">
                        </div>
                        <div>
                            <label for="instansi" class="block text-sm font-semibold text-slate-700 mb-1.5">Instansi</label>
                            <input type="text" name="instansi" id="instansi" value="{{ old('instansi') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Nama instansi">
                        </div>
                    </div>

                    <!-- PTKP & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="ptkp_status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status PTKP <span class="text-red-500">*</span></label>
                            <select name="ptkp_status" id="ptkp_status" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="TK/0" {{ old('ptkp_status') == 'TK/0' ? 'selected' : '' }}>TK/0 - Tidak Kawin (Rp 54 jt)</option>
                                <option value="TK/1" {{ old('ptkp_status') == 'TK/1' ? 'selected' : '' }}>TK/1 - Tidak Kawin + 1 tanggungan (Rp 58,5 jt)</option>
                                <option value="TK/2" {{ old('ptkp_status') == 'TK/2' ? 'selected' : '' }}>TK/2 - Tidak Kawin + 2 tanggungan (Rp 63 jt)</option>
                                <option value="TK/3" {{ old('ptkp_status') == 'TK/3' ? 'selected' : '' }}>TK/3 - Tidak Kawin + 3 tanggungan (Rp 67,5 jt)</option>
                                <option value="K/0" {{ old('ptkp_status') == 'K/0' ? 'selected' : '' }}>K/0 - Kawin (Rp 58,5 jt)</option>
                                <option value="K/1" {{ old('ptkp_status') == 'K/1' ? 'selected' : '' }}>K/1 - Kawin + 1 anak (Rp 63 jt)</option>
                                <option value="K/2" {{ old('ptkp_status') == 'K/2' ? 'selected' : '' }}>K/2 - Kawin + 2 anak (Rp 67,5 jt)</option>
                                <option value="K/3" {{ old('ptkp_status') == 'K/3' ? 'selected' : '' }}>K/3 - Kawin + 3 anak (Rp 72 jt)</option>
                            </select>
                        </div>
                        <div>
                            <label for="employee_status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status Pegawai <span class="text-red-500">*</span></label>
                            <select name="employee_status" id="employee_status" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="tetap" {{ old('employee_status') == 'tetap' ? 'selected' : '' }}>Pegawai Tetap</option>
                                <option value="tidak_tetap" {{ old('employee_status') == 'tidak_tetap' ? 'selected' : '' }}>Pegawai Tidak Tetap</option>
                            </select>
                        </div>
                    </div>

                    <!-- Jenis Pegawai & Jabatan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="status_pegawai" class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Pegawai (PNS/PPPK)</label>
                            <select name="status_pegawai" id="status_pegawai"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="PNS" {{ old('status_pegawai') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                <option value="PPPK" {{ old('status_pegawai') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            </select>
                        </div>
                        <div>
                            <label for="position" class="block text-sm font-semibold text-slate-700 mb-1.5">Jabatan</label>
                            <input type="text" name="position" id="position" value="{{ old('position') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Contoh: Manager, Staff, dll">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium">
                        Simpan Pegawai
                    </button>
                    <a href="{{ route('employees.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
