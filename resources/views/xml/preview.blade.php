<x-app-layout>
    <x-slot name="title">Preview XML</x-slot>
    <x-slot name="subtitle">Preview XML Coretax PPh 21 sebelum generate</x-slot>

    <div class="animate-fade-in-up">
        <!-- Summary -->
        @php
            $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        @endphp
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Periode: {{ $months[$month] }} {{ $year }}</h3>
                <p class="text-sm text-slate-500">{{ $count }} record ditemukan</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('xml.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 border border-slate-200 transition-colors">← Kembali</a>
                <form method="POST" action="{{ route('xml.generate') }}">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <button type="submit" class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Generate & Download XML
                    </button>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="data-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Pegawai</th>
                            <th class="px-4 py-3 text-left">NPWP</th>
                            <th class="px-4 py-3 text-left">PTKP</th>
                            <th class="px-4 py-3 text-right">Total Income</th>
                            <th class="px-4 py-3 text-right">PPh 21</th>
                            <th class="px-4 py-3 text-right">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payrolls as $i => $payroll)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-slate-500">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $payroll->employee->name ?? '-' }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $payroll->employee->formatted_npwp ?? '-' }}</td>
                                <td class="px-4 py-3"><span class="badge bg-blue-100 text-blue-700">{{ $payroll->employee->ptkp_status ?? '-' }}</span></td>
                                <td class="px-4 py-3 text-right font-mono text-xs">Rp {{ number_format($payroll->total_income, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs text-red-600 font-semibold">Rp {{ number_format($payroll->pph21, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-xs">
                                    {{ $payroll->total_income > 0 ? number_format(($payroll->pph21 / $payroll->total_income) * 100, 2) : '0.00' }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- XML Preview -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Preview XML
                </h3>
                <button onclick="copyXml()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    Copy
                </button>
            </div>
            <div class="p-6 bg-slate-900 overflow-x-auto">
                <pre class="text-sm text-emerald-400 font-mono leading-relaxed" id="xmlContent">{{ $xmlContent }}</pre>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyXml() {
            const text = document.getElementById('xmlContent').textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('XML berhasil disalin!');
            });
        }
    </script>
    @endpush
</x-app-layout>
