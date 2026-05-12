<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background-color: #f0f0f0;
            border-bottom: 2px solid #333;
        }
        
        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .status-paid {
            background-color: #c8e6c9;
            color: #1b5e20;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .status-pending {
            background-color: #fff9c4;
            color: #f57f17;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .status-cancelled {
            background-color: #ffcdd2;
            color: #b71c1c;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ILS Mart - Laporan Transaksi</h1>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    @if($transactions->count() > 0)
        <div class="summary">
            <div class="summary-row">
                <span class="summary-label">Total Transaksi:</span>
                <span>{{ $transactions->count() }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Penjualan:</span>
                <span>Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Diterima:</span>
                <span>Rp {{ number_format($transactions->sum('paid_amount'), 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Sisa Piutang:</span>
                <span>Rp {{ number_format($transactions->sum('total_amount') - $transactions->sum('paid_amount'), 0, ',', '.') }}</span>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th class="text-center">Invoice</th>
                    <th>Kasir</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Dibayar</th>
                    <th>Metode</th>
                    <th class="text-center">Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $tx)
                <tr>
                    <td class="text-center" style="font-family: monospace;">{{ $tx->invoice_number }}</td>
                    <td>{{ $tx->user->name ?? 'N/A' }}</td>
                    <td class="text-right">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($tx->paid_amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($tx->payment_method) }}</td>
                    <td class="text-center">
                        @if($tx->status === 'paid')
                            <span class="status-paid">Lunas</span>
                        @elseif($tx->status === 'pending')
                            <span class="status-pending">Pending</span>
                        @else
                            <span class="status-cancelled">Batal</span>
                        @endif
                    </td>
                    <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <p>Laporan ini adalah dokumen resmi dari sistem POS ILS Mart</p>
            <p>Dicetak pada {{ now()->format('d-m-Y H:i:s') }}</p>
        </div>
    @else
        <div class="text-center">
            <p>Tidak ada data transaksi untuk ditampilkan</p>
        </div>
    @endif
</body>
</html>
