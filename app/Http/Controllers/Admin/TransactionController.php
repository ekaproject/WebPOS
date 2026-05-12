<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'items.product');
        return view('admin.transactions.show', compact('transaction'));
    }

    public function exportPdf(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->get();

        $pdf = Pdf::loadView('admin.transactions.export-pdf', compact('transactions'));
        return $pdf->download('transaksi-'.date('Ymd-His').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->get();

        $filename = 'transaksi-'.date('Ymd-His').'.csv';
        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
        );

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM untuk UTF-8
            fputcsv($handle, ['Invoice', 'Kasir', 'Total', 'Dibayar', 'Metode', 'Terminal', 'Status', 'Waktu'], ',');

            foreach ($transactions as $tx) {
                fputcsv($handle, [
                    $tx->invoice_number,
                    $tx->user->name ?? 'N/A',
                    $tx->total_amount,
                    $tx->paid_amount,
                    ucfirst($tx->payment_method),
                    $tx->cashier_terminal ?? '-',
                    $this->getStatusLabel($tx->status),
                    $tx->created_at->format('d-m-Y H:i'),
                ], ',');
            }

            fclose($handle);
        }, $filename, $headers);
    }

    private function getStatusLabel($status)
    {
        return match($status) {
            'paid' => 'Lunas',
            'pending' => 'Pending',
            'cancelled' => 'Batal',
            default => $status,
        };
    }
}