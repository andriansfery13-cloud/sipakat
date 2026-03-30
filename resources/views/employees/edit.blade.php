<x-app-layout>
    <x-slot name="title">Edit Pegawai</x-slot>
    <x-slot name="subtitle">Edit data pegawai: {{ $employee->name }}</x-slot>

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <form method="POST" action="{{ route('employees.update', $employee) }}">
                @csrf @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pegawai <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="npwp" class="block text-sm font-semibold text-slate-700 mb-1.5">NPWP <span class="text-red-500">*</span></label>
                            <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $employee->npwp) }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('npwp') border-red-400 @enderror" maxlength="20">
                            @error('npwp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-slate-700 mb-1.5">NIK</label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik', $employee->nik) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" maxlength="16">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1.5">NIP</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $employee->nip) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" maxlength="50">
                        </div>
                        <div>
                            <label for="instansi" class="block text-sm font-semibold text-slate-700 mb-1.5">Instansi</label>
                            <input type="text" name="instansi" id="instansi" value="{{ old('instansi', $employee->instansi) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="ptkp_status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status PTKP <span class="text-red-500">*</span></label>
                            <select name="ptkp_status" id="ptkp_status" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @foreach(['TK/0' => 'TK/0 - Tidak Kawin (Rp 54 jt)', 'TK/1' => 'TK/1 - Tidak Kawin + 1 tanggungan (Rp 58,5 jt)', 'TK/2' => 'TK/2 - Tidak Kawin + 2 tanggungan (Rp 63 jt)', 'TK/3' => 'TK/3 - Tidak Kawin + 3 tanggungan (Rp 67,5 jt)', 'K/0' => 'K/0 - Kawin (Rp 58,5 jt)', 'K/1' => 'K/1 - Kawin + 1 anak (Rp 63 jt)', 'K/2' => 'K/2 - Kawin + 2 anak (Rp 67,5 jt)', 'K/3' => 'K/3 - Kawin + 3 anak (Rp 72 jt)'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('ptkp_status', $employee->ptkp_status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="employee_status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status Pegawai <span class="text-red-500">*</span></label>
                            <select name="employee_status" id="employee_status" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="tetap" {{ old('employee_status', $employee->employee_status) == 'tetap' ? 'selected' : '' }}>Pegawai Tetap</option>
                                <option value="tidak_tetap" {{ old('employee_status', $employee->employee_status) == 'tidak_tetap' ? 'selected' : '' }}>Pegawai Tidak Tetap</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="status_pegawai" class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Pegawai (PNS/PPPK)</label>
                            <select name="status_pegawai" id="status_pegawai"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="PNS" {{ old('status_pegawai', $employee->status_pegawai) == 'PNS' ? 'selected' : '' }}>PNS</option>
                                <option value="PPPK" {{ old('status_pegawai', $employee->status_pegawai) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            </select>
                        </div>
                        <div>
                            <label for="position" class="block text-sm font-semibold text-slate-700 mb-1.5">Jabatan</label>
                            <input type="text" name="position" id="position" value="{{ old('position', $employee->position) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium">Simpan Perubahan</button>
                    <a href="{{ route('employees.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
