<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Asetera</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .section-title {
            background-color: #f4f4f4;
            padding: 8px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #007bff;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Ringkasan Asetera</h1>
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        @if(request('start_date') || request('end_date'))
            <p>
                Periode: 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'Awal' }} 
                s.d. 
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Sekarang' }}
            </p>
        @endif
    </div>

    <div class="section-title">Status Aset</div>
    <table>
        <tr>
            <th>Total Aset</th>
            <th>Tersedia</th>
            <th>Dipinjam</th>
            <th>Rusak</th>
        </tr>
        <tr>
            <td>{{ $summary['assets']['total'] }}</td>
            <td>{{ $summary['assets']['available'] }}</td>
            <td>{{ $summary['assets']['borrowed'] }}</td>
            <td>{{ $summary['assets']['damaged'] }}</td>
        </tr>
    </table>

    <div class="section-title">Aktivitas Transaksi</div>
    <table>
        <tr>
            <th>Total Transaksi</th>
            <th>Sedang Berjalan</th>
            <th>Selesai (Returned)</th>
            <th>Total Nilai Aset Dipinjam</th>
        </tr>
        <tr>
            <td>{{ $summary['transactions']['total'] }}</td>
            <td>{{ $summary['transactions']['active'] }}</td>
            <td>{{ $summary['transactions']['returned'] }}</td>
            <td>Rp {{ number_format($summary['transactions']['total_cost'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">Pemeliharaan (Maintenance)</div>
    <table>
        <tr>
            <th>Total Maintenance</th>
            <th>Sedang Dikerjakan</th>
            <th>Selesai</th>
            <th>Total Biaya Perbaikan</th>
        </tr>
        <tr>
            <td>{{ $summary['maintenances']['total'] }}</td>
            <td>{{ $summary['maintenances']['open'] }}</td>
            <td>{{ $summary['maintenances']['completed'] }}</td>
            <td>Rp {{ number_format($summary['maintenances']['total_cost'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Dicetak oleh sistem Assetera - Laporan Internal
    </div>
</body>
</html>
