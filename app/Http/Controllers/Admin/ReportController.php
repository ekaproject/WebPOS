<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $query = $this->buildQuery($filters);

        $transactions = (clone $query)->latest()->paginate(20)->withQueryString();
        $summary = $this->summary((clone $query));

        return view('admin.reports.index', [
            'transactions' => $transactions,
            'summary' => $summary,
            'filters' => $filters,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $query = $this->buildQuery($filters);

        $transactions = (clone $query)->latest()->get();
        $summary = $this->summary((clone $query));

        $pdf = Pdf::loadView('admin.reports.export-pdf', [
            'transactions' => $transactions,
            'summary' => $summary,
            'filters' => $filters,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-'.now()->format('Ymd-His').'.pdf');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $filters = $this->validatedFilters($request);
        $query = $this->buildQuery($filters);

        $transactions = (clone $query)->latest()->get();
        $summary = $this->summary((clone $query));

        $filename = 'laporan-transaksi-'.now()->format('Ymd-His').'.xls';

        return response()->streamDownload(function () use ($transactions, $summary, $filters) {
            echo view('admin.reports.export-excel', [
                'transactions' => $transactions,
                'summary' => $summary,
                'filters' => $filters,
                'generatedAt' => now(),
            ])->render();
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    private function validatedFilters(Request $request): array
    {
        $validated = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'status' => 'nullable|in:paid,pending,cancelled',
        ]);

        return [
            'from' => $validated['from'] ?? null,
            'to' => $validated['to'] ?? null,
            'status' => $validated['status'] ?? null,
        ];
    }

    private function buildQuery(array $filters)
    {
        $query = Transaction::query()->with('user');

        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }

    private function summary($query): array
    {
        return [
            'count' => (clone $query)->count(),
            'revenue' => (float) (clone $query)->where('status', 'paid')->sum('total_amount'),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
        ];
    }
}
