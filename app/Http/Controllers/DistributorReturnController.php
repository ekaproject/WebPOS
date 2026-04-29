<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\InventoryReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DistributorReturnController extends Controller
{
    public function index(Request $request)
    {
        $distributor = $this->resolveDistributor($request);

        $status = in_array($request->status, ['pending', 'confirmed', 'completed'], true)
            ? $request->status
            : 'pending';

        $query = InventoryReturn::query()
            ->with(['inboundItem'])
            ->where('distributor_id', $distributor->id)
            ->latest();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where('product_name', 'like', '%' . $keyword . '%');
        }

        $query->where('status', $status);

        $returns = $query->paginate(20)->withQueryString();

        return view('distributor.returns.index', compact('returns', 'status', 'distributor'));
    }

    public function confirm(Request $request, int $id)
    {
        $distributor = $this->resolveDistributor($request);

        $inventoryReturn = InventoryReturn::query()
            ->where('id', $id)
            ->where('distributor_id', $distributor->id)
            ->firstOrFail();

        if ($inventoryReturn->status === 'completed') {
            abort(422, 'Return sudah selesai.');
        }

        if ($inventoryReturn->status !== 'confirmed') {
            $inventoryReturn->update(['status' => 'confirmed']);

            Log::info('Distributor confirmed return.', [
                'return_id' => $inventoryReturn->id,
                'distributor_id' => $distributor->id,
                'user_id' => $request->user()->id,
                'status' => $inventoryReturn->status,
            ]);
        }

        return redirect()
            ->route('distributor.returns.index', ['status' => 'confirmed'])
            ->with('success', 'Return berhasil dikonfirmasi.');
    }

    public function complete(Request $request, int $id)
    {
        $distributor = $this->resolveDistributor($request);

        $inventoryReturn = InventoryReturn::query()
            ->where('id', $id)
            ->where('distributor_id', $distributor->id)
            ->firstOrFail();

        if ($inventoryReturn->status === 'completed') {
            return redirect()
                ->route('distributor.returns.index', ['status' => 'completed'])
                ->with('success', 'Return sudah selesai.');
        }

        $inventoryReturn->update([
            'status' => 'completed',
            'resolved_at' => now(),
        ]);

        Log::info('Distributor completed return.', [
            'return_id' => $inventoryReturn->id,
            'distributor_id' => $distributor->id,
            'user_id' => $request->user()->id,
            'status' => $inventoryReturn->status,
        ]);

        return redirect()
            ->route('distributor.returns.index', ['status' => 'completed'])
            ->with('success', 'Return berhasil diselesaikan.');
    }

    private function resolveDistributor(Request $request): Distributor
    {
        $this->ensureDistributorRole($request);

        $user = $request->user();

        if ($user->distributor_id) {
            $distributor = Distributor::query()->find($user->distributor_id);

            if ($distributor) {
                return $distributor;
            }
        }

        $distributor = Distributor::query()
            ->where('email', $user->email)
            ->first();

        if (!$distributor) {
            abort(403, 'Akun distributor belum terhubung ke data distributor.');
        }

        return $distributor;
    }

    private function ensureDistributorRole(Request $request): void
    {
        if ($request->user()?->role !== 'distributor') {
            abort(403, 'Hanya distributor yang dapat melakukan aksi ini.');
        }
    }
}
