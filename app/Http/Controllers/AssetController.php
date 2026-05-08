<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assets\StoreAssetRequest;
use App\Http\Requests\Assets\UpdateAssetRequest;
use App\Models\Asset;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssetController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Asset::class);

        $filters = [
            'search' => trim((string) $request->string('search')),
            'status' => trim((string) $request->string('status')),
            'category' => trim((string) $request->string('category')),
        ];

        $assets = Asset::query()
            ->withCount(['transactions', 'maintenances'])
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $query->where(function ($builder) use ($filters) {
                    $builder
                        ->where('code_asset', 'like', '%'.$filters['search'].'%')
                        ->orWhere('name_asset', 'like', '%'.$filters['search'].'%');
                });
            })
            ->when($filters['status'] !== '', fn ($query) => $query->where('status_asset', $filters['status']))
            ->when($filters['category'] !== '', fn ($query) => $query->where('category_asset', $filters['category']))
            ->latest()
            ->get();

        return view('assets.index', [
            'assets' => $assets,
            'summary' => $this->buildSummary($assets),
            'filters' => $filters,
            'categories' => Asset::query()
                ->select('category_asset')
                ->distinct()
                ->orderBy('category_asset')
                ->pluck('category_asset'),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Asset::class);

        $search = trim((string) $request->string('search'));
        $assets = Asset::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('code_asset', 'like', '%'.$search.'%')
                    ->orWhere('name_asset', 'like', '%'.$search.'%');
            })
            ->latest()
            ->take(12)
            ->get();

        return view('assets.create', [
            'assets' => $assets,
            'summary' => $this->buildSummary(Asset::all()),
            'search' => $search,
        ]);
    }

    public function createForm()
    {
        $this->authorize('create', Asset::class);

        return view('assets.input', [
            'asset' => new Asset([
                'status_asset' => Asset::STATUS_AVAILABLE,
            ]),
            'formAction' => route('assets.store'),
            'formMethod' => 'POST',
            'submitLabel' => 'Simpan Asset',
            'pageTitle' => 'Tambah Asset',
            'pageSubtitle' => 'Input data inventaris baru',
        ]);
    }

    public function store(StoreAssetRequest $request)
    {
        $this->authorize('create', Asset::class);

        $asset = Asset::create($request->validated());

        $this->auditLogService->record(
            $request->user(),
            'asset.created',
            'Membuat aset baru',
            $asset,
            $asset->only(['code_asset', 'name_asset', 'category_asset', 'status_asset'])
        );

        return redirect()->route('assets.create')->with('success', 'Asset created successfully.');
    }

    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);

        return view('assets.input', [
            'asset' => $asset,
            'formAction' => route('assets.update', $asset),
            'formMethod' => 'PUT',
            'submitLabel' => 'Update Asset',
            'pageTitle' => 'Edit Asset',
            'pageSubtitle' => 'Perbarui data inventaris',
        ]);
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        $activeTransaction = $asset->transactions()->whereNull('returned_at')->exists();
        $openMaintenance = $asset->maintenances()->whereIn('status', ['pending', 'in_progress'])->exists();
        $validated = $request->validated();

        if ($activeTransaction && $validated['status_asset'] !== Asset::STATUS_BORROWED) {
            return back()->withErrors([
                'status_asset' => 'Status aset harus tetap dipinjam selama masih ada transaksi aktif.',
            ])->withInput();
        }

        if ($openMaintenance && $validated['status_asset'] !== Asset::STATUS_DAMAGED) {
            return back()->withErrors([
                'status_asset' => 'Status aset harus tetap rusak selama maintenance masih berjalan.',
            ])->withInput();
        }

        $asset->update($validated);

        $this->auditLogService->record(
            $request->user(),
            'asset.updated',
            'Memperbarui data aset',
            $asset,
            $asset->only(['code_asset', 'name_asset', 'category_asset', 'status_asset'])
        );

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);

        $asset->loadCount(['transactions', 'maintenances']);

        if ($asset->transactions_count > 0 || $asset->maintenances_count > 0) {
            return redirect()->route('assets.index')->withErrors([
                'asset' => 'Aset tidak bisa dihapus karena sudah memiliki riwayat transaksi atau maintenance.',
            ]);
        }

        $snapshot = $asset->only(['code_asset', 'name_asset', 'category_asset', 'status_asset']);
        $asset->delete();

        $this->auditLogService->record(
            request()->user(),
            'asset.deleted',
            'Menghapus aset',
            null,
            $snapshot
        );

        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('export', Asset::class);

        $filters = [
            'search' => trim((string) $request->string('search')),
            'status' => trim((string) $request->string('status')),
            'category' => trim((string) $request->string('category')),
        ];

        $assets = Asset::query()
            ->withCount(['transactions', 'maintenances'])
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $query->where(function ($builder) use ($filters) {
                    $builder
                        ->where('code_asset', 'like', '%'.$filters['search'].'%')
                        ->orWhere('name_asset', 'like', '%'.$filters['search'].'%');
                });
            })
            ->when($filters['status'] !== '', fn ($query) => $query->where('status_asset', $filters['status']))
            ->when($filters['category'] !== '', fn ($query) => $query->where('category_asset', $filters['category']))
            ->orderBy('name_asset')
            ->get();

        $this->auditLogService->record(
            $request->user(),
            'asset.exported',
            'Mengekspor data aset',
            null,
            ['filters' => $filters, 'count' => $assets->count()]
        );

        return response()->streamDownload(function () use ($assets) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Asset', 'Nama', 'Kategori', 'Status', 'Tanggal Pengadaan', 'Harga', 'Total Transaksi', 'Total Maintenance']);

            foreach ($assets as $asset) {
                fputcsv($handle, [
                    $asset->code_asset,
                    $asset->name_asset,
                    $asset->category_asset,
                    $asset->status_asset,
                    $asset->purchase_date?->format('Y-m-d'),
                    (float) $asset->purchase_price,
                    $asset->transactions_count,
                    $asset->maintenances_count,
                ]);
            }

            fclose($handle);
        }, 'assets-report.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function buildSummary($assets): array
    {
        return [
            'total' => $assets->count(),
            'available' => $assets->where('status_asset', Asset::STATUS_AVAILABLE)->count(),
            'borrowed' => $assets->where('status_asset', Asset::STATUS_BORROWED)->count(),
            'damaged' => $assets->where('status_asset', Asset::STATUS_DAMAGED)->count(),
        ];
    }
}
