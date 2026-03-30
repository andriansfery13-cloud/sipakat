<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Ringkasan data payroll perusahaan</x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <div class="stat-card p-6 animate-fade-in-up animate-delay-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="badge bg-indigo-100 text-indigo-700">Aktif</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalEmployees) }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Pegawai</p>
        </div>

        <div class="stat-card p-6 animate-fade-in-up animate-delay-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="badge bg-emerald-100 text-emerald-700">Bulan Ini</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalSalaryThisMonth, 0, ',', '.') }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Gaji</p>
        </div>

        <div class="stat-card p-6 animate-fade-in-up animate-delay-3">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <span class="badge bg-amber-100 text-amber-700">PPh 21</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($totalTaxThisMonth, 0, ',', '.') }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Pajak</p>
        </div>

        <div class="stat-card p-6 animate-fade-in-up animate-delay-4">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="badge bg-purple-100 text-purple-700">Record</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($totalPayrollRecords) }}</h3>
            <p class="text-sm text-slate-500 mt-1">Payroll Bulan Ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 stat-card p-6 animate-fade-in-up" style="animation-delay: 0.3s; opacity: 0;">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Tren Gaji & Pajak (6 Bulan Terakhir)</h3>
            <div class="relative" style="height: 250px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Recent Payrolls -->
        <div class="stat-card p-6 animate-fade-in-up" style="animation-delay: 0.4s; opacity: 0;">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Payroll Terbaru</h3>
            <div class="space-y-3">
                @forelse($recentPayrolls as $payroll)
                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ substr($payroll->employee->name ?? '?', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ $payroll->employee->name ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $payroll->month }}/{{ $payroll->year }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-700">Rp {{ number_format($payroll->total_income, 0, ',', '.') }}</p>
                            <p class="text-xs text-red-500">-Rp {{ number_format($payroll->pph21, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <p class="text-sm">Belum ada data payroll</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('trendChart').getContext('2d');
        const monthlySummary = @json($monthlySummary);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlySummary.map(d => d.label),
                datasets: [
                    {
                        label: 'Total Gaji',
                        data: monthlySummary.map(d => d.salary),
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                    {
                        label: 'PPh 21',
                        data: monthlySummary.map(d => d.tax),
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(0) + ' jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
