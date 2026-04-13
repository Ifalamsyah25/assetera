<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\StoreTransactionRequest;
use App\Http\Requests\Transactions\UpdateTransactionRequest;
use App\Models\Asset;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\AssetStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct(
        private readonly AssetStatusService $assetStatusService,
        private readonly AuditLogService $auditLogService,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Transaction::class);

        $transactions = Transaction::query()
                ->with(['user:id,name,username,role', 'asset:id,code_asset,name_asset,status_asset'])
                ->latest()
                ->get();

        return view('transactions.index', [
            'transactions' => $transactions,
            'assets' => Asset::orderBy('name_asset')->get(['id', 'name_asset', 'code_asset', 'status_asset']),
            'users' => User::orderBy('name')->get(['id', 'name', 'username', 'role']),
            'summary' => [
                'total' => $transactions->count(),
                'active' => $transactions->whereNull('returned_at')->count(),
                'returned' => $transactions->whereNotNull('returned_at')->count(),
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', Transaction::class);

        return view('transactions.create', [
            'assets' => Asset::orderBy('name_asset')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->authorize('create', Transaction::class);

        $asset = Asset::findOrFail($validated['asset_id']);

        if ($asset->status_asset === Asset::STATUS_DAMAGED) {
            return back()->withErrors([
                'asset_id' => 'Aset berstatus rusak dan belum bisa dipinjam.',
            ])->withInput();
        }

        if ($asset->status_asset === Asset::STATUS_BORROWED && empty($validated['returned_at'])) {
            return back()->withErrors([
                'asset_id' => 'Aset sedang dipinjam dan belum dikembalikan.',
            ])->withInput();
        }

        $transaction = DB::transaction(function () use ($asset, $validated) {
            $transaction = Transaction::create($validated);
            $this->assetStatusService->sync($asset);
            return $transaction;
        });

        $this->auditLogService->record(
            $request->user(),
            'transaction.created',
            'Membuat transaksi peminjaman',
            $transaction,
            $transaction->only(['user_id', 'asset_id', 'borrowed_at', 'returned_at', 'cost'])
        );

        return back()->with('success', 'Transaksi peminjaman berhasil disimpan.');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('transactions.edit', [
            'transaction' => $transaction->load(['user', 'asset']),
            'assets' => Asset::orderBy('name_asset')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validated();
        $this->authorize('update', $transaction);

        $previousAsset = $transaction->asset()->first();

        if ((int) $validated['asset_id'] !== (int) $transaction->asset_id) {
            $newAsset = Asset::findOrFail($validated['asset_id']);

            if ($newAsset->status_asset === Asset::STATUS_DAMAGED) {
                return back()->withErrors([
                    'asset_id' => 'Aset pengganti berstatus rusak dan belum bisa dipinjam.',
                ])->withInput();
            }

            if ($newAsset->status_asset === Asset::STATUS_BORROWED && empty($validated['returned_at'])) {
                return back()->withErrors([
                    'asset_id' => 'Aset pengganti sedang dipinjam dan belum dikembalikan.',
                ])->withInput();
            }
        }

        DB::transaction(function () use ($transaction, $validated, $previousAsset): void {
            $transaction->update($validated);

            if ($previousAsset) {
                $this->assetStatusService->sync($previousAsset);
            }

            $this->assetStatusService->sync($transaction->asset()->first());
        });

        $this->auditLogService->record(
            $request->user(),
            'transaction.updated',
            'Memperbarui transaksi peminjaman',
            $transaction->fresh(),
            $transaction->fresh()->only(['user_id', 'asset_id', 'borrowed_at', 'returned_at', 'cost'])
        );

        return back()->with('success', 'Transaksi pengembalian berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);
        $asset = $transaction->asset()->first();
        $snapshot = $transaction->only(['id', 'user_id', 'asset_id', 'borrowed_at', 'returned_at', 'cost']);

        DB::transaction(function () use ($transaction, $asset): void {
            $transaction->delete();

            if ($asset) {
                $this->assetStatusService->sync($asset);
            }
        });

        $this->auditLogService->record(
            request()->user(),
            'transaction.deleted',
            'Menghapus transaksi peminjaman',
            null,
            $snapshot
        );

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
