<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { margin: 0 0 8px; font-size: 20px; }
        .meta { margin-bottom: 12px; }
        .meta p { margin: 2px 0; }
        .summary { width: 100%; margin: 10px 0 16px; border-collapse: collapse; }
        .summary td { border: 1px solid #d1d5db; padding: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 7px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi</h1>
    <div class="meta">
        <p>Dibuat: {{ $generatedAt->format('d M Y H:i') }}</p>
        <p>Filter Tanggal: {{ $filters['from'] ?: '-' }} s/d {{ $filters['to'] ?: '-' }}</p>
        <p>Filter Status: {{ $filters['status'] ?: 'semua' }}</p>
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Data</strong><br>{{ number_format($summary['count']) }}</td>
            <td><strong>Pendapatan Lunas</strong><br>Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</td>
            <td><strong>Pending</strong><br>{{ number_format($summary['pending']) }}</td>
            <td><strong>Dibatalkan</strong><br>{{ number_format($summary['cancelled']) }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
