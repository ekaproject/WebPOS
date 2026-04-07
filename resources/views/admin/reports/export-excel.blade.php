<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
</head>
<body>
    <table border="1">
        <tr><td colspan="5"><strong>Laporan Transaksi</strong></td></tr>
        <tr><td colspan="5">Dibuat: {{ $generatedAt->format('d M Y H:i') }}</td></tr>
        <tr><td colspan="5">Filter Tanggal: {{ $filters['from'] ?: '-' }} s/d {{ $filters['to'] ?: '-' }}</td></tr>
        <tr><td colspan="5">Filter Status: {{ $filters['status'] ?: 'semua' }}</td></tr>
    </table>

    <br>

    <table border="1">
        <tr>
            <td><strong>Total Data</strong></td>
            <td><strong>Pendapatan Lunas</strong></td>
            <td><strong>Pending</strong></td>
            <td><strong>Dibatalkan</strong></td>
        </tr>
        <tr>
            <td>{{ number_format($summary['count']) }}</td>
            <td>{{ $summary['revenue'] }}</td>
            <td>{{ number_format($summary['pending']) }}</td>
            <td>{{ number_format($summary['cancelled']) }}</td>
        </tr>
    </table>

    <br>

    <table border="1">
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
                    <td>{{ $transaction->total_amount }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
