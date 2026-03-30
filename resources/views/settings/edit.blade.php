<x-app-layout>
    <x-slot name="title">Pengaturan</x-slot>
    <x-slot name="subtitle">Konfigurasi data perusahaan</x-slot>

    <div class="max-w-2xl animate-fade-in-up">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Pengaturan Perusahaan</h3>
                    <p class="text-sm text-slate-500">Data ini akan digunakan dalam file XML Coretax</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.update') }}">
                @csrf @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="company_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings['company_name']) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('company_name') border-red-400 @enderror"
                            placeholder="PT Contoh Perusahaan">
                        @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="company_npwp" class="block text-sm font-semibold text-slate-700 mb-1.5">NPWP Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="company_npwp" id="company_npwp" value="{{ old('company_npwp', $settings['company_npwp']) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('company_npwp') border-red-400 @enderror"
                            placeholder="1234567890123456" maxlength="30">
                        @error('company_npwp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">NPWP ini akan tertulis di tag &lt;TIN&gt; pada XML Coretax</p>
                    </div>

                    <div>
                        <label for="company_nitku" class="block text-sm font-semibold text-slate-700 mb-1.5">NITKU <span class="text-red-500">*</span></label>
                        <input type="text" name="company_nitku" id="company_nitku" value="{{ old('company_nitku', $settings['company_nitku']) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('company_nitku') border-red-400 @enderror"
                            placeholder="0000000000000000000000" maxlength="22">
                        @error('company_nitku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Nomor Identitas Tempat Kegiatan Usaha (22 digit). Digunakan untuk tag &lt;IDPlaceOfBusinessActivity&gt; pada XML Coretax. Dapat dilihat di akun Coretax Anda.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
