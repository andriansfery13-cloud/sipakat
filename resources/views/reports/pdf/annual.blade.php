<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan PPh 21 Tahunan - {{ $year }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1e293b; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #059669; padding-bottom: 15px; }
        .header h1 { font-size: 18px; color: #059669; margin-bottom: 4px; }
        .header h2 { font-size: 14px; color: #334155; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #64748b; }

        .section-title { font-size: 12px; font-weight: bold; color: #334155; margin: 20px 0 10px 0; padding-bottom: 5px; border-bottom: 2px solid #e2e8f0; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.data th { background: #059669; color: white; padding: 6px 8px; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: 0.3px; }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; }
        table.data tr:nth-child(even) td { background: #f8fafc; }
        table.data .right { text-align: right; }
        table.data .mono { font-family: 'DejaVu Sans Mono', monospace; font-size: 8px; }
        table.data tfoot td { border-top: 2px solid #059669; font-weight: bold; background: #ecfdf5 !important; padding: 7px 8px; }
        table.data .dimmed { opacity: 0.4; }

        .footer { margin-top: 20px; text-align: center; color: #94a3b8; font-size: 8px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PPh 21 TAHUNAN</h1>
        <h2>{{ $company['name'] }}</h2>
        <p>NPWP: {{ $company['npwp'] }} &bull; NITKU: {{ $company['nitku'] }}</p>
        <p style="margin-top: 4px;">Tahun Pajak: {{ $year }}</p>
    </div>

    {{-- Monthly Summary --}}
    <div class="section-title">Ringkasan Per Bulan</div>

    @php
        $grandTotalIncome = collect($monthlySummaries)->sum('total_income');
        $grandTotalPph = collect($monthlySummaries)->sum('total_pph21');
        $grandTotalEmployees = collect($monthlySummaries)->sum('total_employees');
    @endphp

    <table class="data">
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="right">Jumlah Pegawai</th>
                <th class="right">Total Penghasilan</th>
                <th class="right">Total PPh 21</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlySummaries as $m => $ms)
                <tr class="{{ $ms['total_employees'] == 0 ? 'dimmed' : '' }}">
                    <td>{{ $ms['month_name'] }}</td>
                    <td class="right mono">{{ $ms['total_employees'] }}</td>
                    <td class="right mono">{{ number_format($ms['total_income'], 0, ',', '.') }}</td>
                    <td class="right mono" style="color: #dc2626;">{{ number_format($ms['total_pph21'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="right mono">{{ $grandTotalEmployees }}</td>
                <td class="right mono">{{ number_format($grandTotalIncome, 0, ',', '.') }}</td>
                <td class="right mono" style="color: #dc2626;">{{ number_format($grandTotalPph, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Employee Summary --}}
    <div class="section-title" style="margin-top: 30px;">Rekap Per Pegawai</div>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>NPWP</th>
                <th>Status PTKP</th>
                <th class="right">Bulan</th>
                <th class="right">Total Penghasilan</th>
                <th class="right">Total PPh 21</th>
                <th class="right">Tarif (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employeeSummaries as $index => $es)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $es['employee']->name }}</td>
                    <td class="mono">{{ $es['employee']->formatted_npwp }}</td>
                    <td>{{ $es['employee']->ptkp_status ?? '-' }}</td>
                    <td class="right mono">{{ $es['total_months'] }}</td>
                    <td class="right mono">{{ number_format($es['total_income'], 0, ',', '.') }}</td>
                    <td class="right mono" style="color: #dc2626;">{{ number_format($es['total_pph21'], 0, ',', '.') }}</td>
                    <td class="right mono">{{ $es['avg_effective_rate'] }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="right"><strong>TOTAL</strong></td>
                <td class="right mono">{{ number_format($grandTotalIncome, 0, ',', '.') }}</td>
                <td class="right mono" style="color: #dc2626;">{{ number_format($grandTotalPph, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem Coretax Payroll &bull; {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
