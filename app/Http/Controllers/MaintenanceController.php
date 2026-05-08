<?php

namespace App\Http\Controllers;

use App\Http\Requests\Maintenances\StoreMaintenanceRequest;
use App\Http\Requests\Maintenances\UpdateMaintenanceRequest;
use App\Models\Asset;
use App\Models\Maintenance;
use App\Services\AuditLogService;
use App\Services\AssetStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function __construct(
        private readonly AssetStatusService $assetStatusService,
        private readonly AuditLogService $auditLogService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Maintenance::class);

        $search = trim((string) $request->string('search'));
        $status = trim((string) $request->string('status'));

        $maintenances = Maintenance::query()
                ->with(['asset:id,code_asset,name_asset,status_asset'])
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($builder) use ($search) {
                        $builder
                            ->whereHas('asset', function ($assetQuery) use ($search) {
                                $assetQuery
                                    ->where('code_asset', 'like', '%'.$search.'%')
                                    ->orWhere('name_asset', 'like', '%'.$search.'%');
                            })
                            ->orWhere('repair_description', 'like', '%'.$search.'%');
                    });
                })
                ->when($status !== '', fn ($query) => $query->where('status', $status))
                ->latest()
                ->get();

        return view('maintenances.index', [
            'maintenances' => $maintenances,
            'assets' => Asset::orderBy('name_asset')->get(['id', 'name_asset', 'code_asset']),
            'summary' => [
                'total' => $maintenances->count(),
                'active' => $maintenances->where('status', 'in_progress')->count(),
                'parts' => $maintenances->where('status', 'pending')->count(),
                'done' => $maintenances->where('status', 'completed')->count(),
            ],
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', Maintenance::class);

        return view('maintenances.create', [
            'assets' => Asset::orderBy('name_asset')->get(),
        ]);
    }

    public function store(StoreMaintenanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->authorize('create', Maintenance::class);

        $maintenance = DB::transaction(function () use ($validated) {
            $maintenance = Maintenance::create($validated);
            $this->assetStatusService->sync($maintenance->asset()->first());
            return $maintenance;
        });

        $this->auditLogService->record(
            $request->user(),
            'maintenance.created',
            'Membuat data maintenance',
            $maintenance,
            $maintenance->only(['asset_id', 'repair_description', 'cost', 'status'])
        );

        return back()->with('success', 'Data maintenance berhasil disimpan.');
    }

    public function edit(Maintenance $maintenance)
    {
        $this->authorize('update', $maintenance);

        return view('maintenances.edit', [
            'maintenance' => $maintenance->load('asset'),
            'assets' => Asset::orderBy('name_asset')->get(),
        ]);
    }

    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance): RedirectResponse
    {
        $validated = $request->validated();
        $this->authorize('update', $maintenance);
        $previousAsset = $maintenance->asset()->first();

        DB::transaction(function () use ($maintenance, $validated, $previousAsset): void {
            $maintenance->update($validated);

            if ($previousAsset) {
                $this->assetStatusService->sync($previousAsset);
            }

            $this->assetStatusService->sync($maintenance->asset()->first());
        });

        $this->auditLogService->record(
            $request->user(),
            'maintenance.updated',
            'Memperbarui data maintenance',
            $maintenance->fresh(),
            $maintenance->fresh()->only(['asset_id', 'repair_description', 'cost', 'status'])
        );

        return back()->with('success', 'Data maintenance berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        $this->authorize('delete', $maintenance);
        $asset = $maintenance->asset()->first();
        $snapshot = $maintenance->only(['id', 'asset_id', 'repair_description', 'cost', 'status']);

        DB::transaction(function () use ($maintenance, $asset): void {
            $maintenance->delete();

            if ($asset) {
                $this->assetStatusService->sync($asset);
            }
        });

        $this->auditLogService->record(
            request()->user(),
            'maintenance.deleted',
            'Menghapus data maintenance',
            null,
            $snapshot
        );

        return redirect()->route('maintenances.index')->with('success', 'Data maintenance berhasil dihapus.');
    }
}
