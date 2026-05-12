<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MobileTransactionController extends Controller
{
    public function index(): JsonResponse
    {
        $transactions = Transaction::with('items.product')
            ->latest()
            ->take(50)
            ->get()
            ->map(fn(Transaction $t) => $this->formatTransaction($t));

        return response()->json(['data' => $transactions]);
    }

    public function store(Request $request): JsonResponse
    {
        // Multipart form mengirim 'items' sebagai JSON string — decode dulu
        if ($request->isMethod('POST') && is_string($request->input('items'))) {
            $decoded = json_decode($request->input('items'), true);
            if (is_array($decoded)) {
                $request->merge(['items' => $decoded]);
            }
        }

        $validated = $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|integer|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment_proof'  => 'nullable|file|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi (sisa: {$product->stock})",
                    ]);
                }

                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;

                $itemsData[] = [
                    'product'    => $product,
                    'qty'        => $item['qty'],
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                ];
            }

            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            $transaction = Transaction::create([
                'total_amount'    => $total,
                'paid_amount'     => $validated['paid_amount'],
                'status'          => 'paid',
                'payment_method'  => $validated['payment_method'],
                'payment_proof'   => $proofPath,
            ]);

            foreach ($itemsData as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product']->id,
                    'quantity'       => $item['qty'],
                    'unit_price'     => $item['unit_price'],
                    'subtotal'       => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'message'        => 'Transaksi berhasil',
                'invoice_number' => $transaction->invoice_number,
                'total_amount'   => (int) $transaction->total_amount,
                'paid_amount'    => (int) $transaction->paid_amount,
                'change'         => (int) ($transaction->paid_amount - $transaction->total_amount),
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaksi gagal: ' . $e->getMessage()], 500);
        }
    }

    private function formatTransaction(Transaction $t): array
    {
        return [
            'id'             => $t->id,
            'invoice_number' => $t->invoice_number,
            'total_amount'   => (int) $t->total_amount,
            'paid_amount'    => (int) $t->paid_amount,
            'change'         => (int) ($t->paid_amount - $t->total_amount),
            'payment_method' => $t->payment_method,
            'payment_proof_url' => $t->payment_proof
                ? Storage::disk('public')->url($t->payment_proof)
                : null,
            'status'         => $t->status,
            'created_at'     => $t->created_at?->format('d/m/Y H:i'),
            'items'          => $t->items->map(fn(TransactionItem $i) => [
                'product_name' => $i->product?->name,
                'quantity'     => (int) $i->quantity,
                'unit_price'   => (int) $i->unit_price,
                'subtotal'     => (int) $i->subtotal,
            ])->values(),
        ];
    }
}
