<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Lunas</title>
</head>
<body>
    <table border="1">
        <tr><td colspan="5"><strong>Laporan Transaksi Lunas</strong></td></tr>
        <tr><td colspan="5">Dibuat: {{ $generatedAt->format('d M Y H:i') }}</td></tr>
        <tr><td colspan="5">Filter Tanggal: {{ $filters['from'] ?: '-' }} s/d {{ $filters['to'] ?: '-' }}</td></tr>
    </table>

    <br>

    <table border="1">
        <tr>
            <td><strong>Total Data</strong></td>
            <td><strong>Pendapatan Lunas</strong></td>
            <td><strong>Total Laba</strong></td>
        </tr>
        <tr>
            <td>{{ number_format($summary['count']) }}</td>
            <td>{{ $summary['revenue'] }}</td>
            <td>{{ $summary['profit'] }}</td>
        </tr>
    </table>

    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Laba</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                @php
                    $costOfGoods = $transaction->items->sum(fn ($item) => ($item->product->purchase_price ?? 0) * $item->quantity);
                    $laba = $transaction->total_amount - $costOfGoods;
                @endphp
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>{{ $transaction->total_amount }}</td>
                    <td>{{ $laba }}</td>
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
