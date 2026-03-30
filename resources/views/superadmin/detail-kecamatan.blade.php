<x-app-layout>
    <x-slot name="title">Detail Kecamatan: {{ $kecamatan->nama_kecamatan }}</x-slot>
    <x-slot name="subtitle">Data Pegawai dan Payroll ({{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }})</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('superadmin.dashboard') }}" class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm text-white rounded-lg shadow font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('superadmin.kecamatan.detail', $kecamatan->id) }}" class="flex bg-white rounded-lg shadow-sm border border-slate-200 p-1">
             <select name="month" class="px-3 py-1.5 border-none focus:ring-0 text-sm font-medium text-slate-700 bg-transparent rounded-l-md w-32 cursor-pointer outline-none">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
            <div class="w-px bg-slate-200 my-1"></div>
            <select name="year" class="px-3 py-1.5 border-none focus:ring-0 text-sm font-medium text-slate-700 bg-transparent rounded-r-md w-24 cursor-pointer outline-none">
                @php $currentY = date('Y') @endphp
                @foreach(range($currentY - 1, $currentY + 1) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-indigo-50 text-indigo-700 px-4 py-1.5 text-sm font-semibold hover:bg-indigo-100 transition-colors rounded-lg ml-2">
                Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pegawai Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in-up">
            <div class="p-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Daftar Pegawai</h3>
                <span class="badge bg-indigo-100 text-indigo-700">{{ $employees->count() }} Orang</span>
            </div>
            <div class="overflow-x-auto max-h-[500px]">
                <table class="w-full text-left data-table">
                    <thead class="sticky top-0 shadow-sm z-10">
                        <tr>
                            <th class="px-5 py-3 border-b">Nama & NIP</th>
                            <th class="px-5 py-3 border-b">NPWP</th>
                            <th class="px-5 py-3 border-b">Status PTKP</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($employees as $emp)
                            <tr class="border-b border-slate-100">
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-800">{{ $emp->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">NIP: {{ $emp->nip ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $emp->formatted_npwp }}</td>
                                <td class="px-5 py-3">
                                    <span class="badge bg-amber-100 text-amber-700">{{ $emp->ptkp_status ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-6 text-center text-slate-400">Data pegawai tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payroll Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in-up animate-delay-1">
            <div class="p-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Data Gaji & PPh 21</h3>
                <span class="badge bg-emerald-100 text-emerald-700">{{ $payrolls->count() }} Data Bulan Ini</span>
            </div>
            <div class="overflow-x-auto max-h-[500px]">
                <table class="w-full text-left data-table">
                    <thead class="sticky top-0 shadow-sm z-10">
                        <tr>
                            <th class="px-5 py-3 border-b">Pegawai</th>
                            <th class="px-5 py-3 border-b text-right">Gaji Bruto</th>
                            <th class="px-5 py-3 border-b text-right">Potongan PPh 21</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($payrolls as $pr)
                            <tr class="border-b border-slate-100">
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-800">{{ optional($pr->employee)->name ?? 'Terhapus' }}</div>
                                </td>
                                <td class="px-5 py-3 text-right font-medium text-emerald-600">
                                    Rp {{ number_format((float) $pr->total_income, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-rose-600">
                                    Rp {{ number_format((float) $pr->pph21, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-6 text-center text-slate-400">Belum ada data gaji untuk periode ini di kecamatan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
