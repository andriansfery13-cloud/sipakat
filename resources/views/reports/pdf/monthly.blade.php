<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan PPh 21 - {{ $monthName }} {{ $year }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1e293b; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #4f46e5; padding-bottom: 15px; }
        .header h1 { font-size: 18px; color: #4f46e5; margin-bottom: 4px; }
        .header h2 { font-size: 14px; color: #334155; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #64748b; }

        .info-box { background: #f1f5f9; border-radius: 6px; padding: 10px 15px; margin-bottom: 15px; display: inline-block; width: 100%; }
        .info-row { display: flex; margin-bottom: 3px; }
        .info-label { font-weight: bold; width: 120px; color: #475569; }
        .info-value { color: #1e293b; }

        .summary-grid { width: 100%; margin-bottom: 15px; }
        .summary-grid td { padding: 8px 12px; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; text-align: center; }
        .summary-box .label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-box .value { font-size: 14px; font-weight: bold; color: #1e293b; margin-top: 2px; }
        .summary-box .value.red { color: #dc2626; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #4f46e5; color: white; padding: 6px 8px; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: 0.3px; }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; }
        table.data tr:nth-child(even) td { background: #f8fafc; }
        table.data .right { text-align: right; }
        table.data .mono { font-family: 'DejaVu Sans Mono', monospace; font-size: 8px; }
        table.data tfoot td { border-top: 2px solid #4f46e5; font-weight: bold; background: #eef2ff !important; padding: 7px 8px; }

        .footer { margin-top: 20px; text-align: center; color: #94a3b8; font-size: 8px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PPh 21 BULANAN</h1>
        <h2>{{ $company['name'] }}</h2>
        <p>NPWP: {{ $company['npwp'] }} &bull; NITKU: {{ $company['nitku'] }}</p>
        <p style="margin-top: 4px;">Periode: {{ $monthName }} {{ $year }}</p>
    </div>

    <table class="summary-grid">
        <tr>
            <td width="25%">
                <div class="summary-box">
                    <div class="label">Jumlah Pegawai</div>
                    <div class="value">{{ $summary['total_employees'] }}</div>
                </div>
            </td>
            <td width="25%">
                <div class="summary-box">
                    <div class="label">Total Penghasilan</div>
                    <div class="value">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="25%">
                <div class="summary-box">
                    <div class="label">Total PPh 21</div>
                    <div class="value red">Rp {{ number_format($summary['total_pph21'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="25%">
                <div class="summary-box">
                    <div class="label">Tarif Efektif</div>
                    <div class="value">{{ $summary['avg_effective_rate'] }}%</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>NPWP</th>
                <th>Status PTKP</th>
                <th class="right">Gaji Pokok</th>
                <th class="right">Tunjangan</th>
                <th class="right">Bonus</th>
                <th class="right">Total Penghasilan</th>
                <th class="right">PPh 21</th>
                <th class="right">Tarif (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $index => $payroll)
                @php
                    $rate = $payroll->total_income > 0 ? round(($payroll->pph21 / $payroll->total_income) * 100, 2) : 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payroll->employee->name ?? '-' }}</td>
                    <td class="mono">{{ $payroll->employee->formatted_npwp ?? '-' }}</td>
                    <td>{{ $payroll->employee->ptkp_status ?? '-' }}</td>
                    <td class="right mono">{{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
                    <td class="right mono">{{ number_format($payroll->allowance, 0, ',', '.') }}</td>
                    <td class="right mono">{{ number_format($payroll->bonus, 0, ',', '.') }}</td>
                    <td class="right mono">{{ number_format($payroll->total_income, 0, ',', '.') }}</td>
                    <td class="right mono" style="color: #dc2626;">{{ number_format($payroll->pph21, 0, ',', '.') }}</td>
                    <td class="right mono">{{ $rate }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="right"><strong>TOTAL</strong></td>
                <td class="right mono">{{ number_format($summary['total_gross_salary'], 0, ',', '.') }}</td>
                <td class="right mono">{{ number_format($summary['total_allowance'], 0, ',', '.') }}</td>
                <td class="right mono">{{ number_format($summary['total_bonus'], 0, ',', '.') }}</td>
                <td class="right mono">{{ number_format($summary['total_income'], 0, ',', '.') }}</td>
                <td class="right mono" style="color: #dc2626;">{{ number_format($summary['total_pph21'], 0, ',', '.') }}</td>
                <td class="right mono">{{ $summary['avg_effective_rate'] }}%</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem Coretax Payroll &bull; {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
