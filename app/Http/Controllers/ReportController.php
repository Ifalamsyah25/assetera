<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Maintenance;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function summary(Request $request): JsonResponse|View
    {
        $this->authorize('view-reports');

        $summary = [
            'assets' => [
                'total' => Asset::count(),
                'available' => Asset::where('status_asset', Asset::STATUS_AVAILABLE)->count(),
                'borrowed' => Asset::where('status_asset', Asset::STATUS_BORROWED)->count(),
                'damaged' => Asset::where('status_asset', Asset::STATUS_DAMAGED)->count(),
            ],
            'transactions' => [
                'total' => Transaction::count(),
                'active' => Transaction::active()->count(),
                'returned' => Transaction::whereNotNull('returned_at')->count(),
                'total_cost' => (float) Transaction::sum('cost'),
            ],
            'maintenances' => [
                'total' => Maintenance::count(),
                'open' => Maintenance::open()->count(),
                'completed' => Maintenance::where('status', Maintenance::STATUS_COMPLETED)->count(),
                'total_cost' => (float) Maintenance::sum('cost'),
            ],
            'users' => [
                'total' => User::count(),
                'admin' => User::where('role', User::ROLE_ADMIN)->count(),
                'pimpinan' => User::where('role', User::ROLE_PIMPINAN)->count(),
                'staff' => User::where('role', User::ROLE_STAFF)->count(),
            ],
        ];

        if ($request->expectsJson()) {
            return response()->json($summary);
        }

        return view('reports.summary', [
            'summary' => $summary,
            'recentAssets' => Asset::query()
                ->latest()
                ->take(5)
                ->get(['id', 'code_asset', 'name_asset', 'category_asset', 'status_asset', 'created_at']),
            'recentTransactions' => Transaction::query()
                ->with(['user:id,name', 'asset:id,code_asset,name_asset'])
                ->latest()
                ->take(5)
                ->get(['id', 'user_id', 'asset_id', 'borrowed_at', 'returned_at', 'cost', 'created_at']),
            'recentMaintenances' => Maintenance::query()
                ->with(['asset:id,code_asset,name_asset'])
                ->latest()
                ->take(5)
                ->get(['id', 'asset_id', 'repair_description', 'status', 'cost', 'created_at']),
            'recentAuditLogs' => Schema::hasTable('audit_logs')
                ? AuditLog::query()
                    ->with('user:id,name')
                    ->latest()
                    ->take(5)
                    ->get()
                : collect(),
        ]);
    }

    public function activity(Request $request): JsonResponse
    {
        $this->authorize('view-reports');

        return response()->json([
            'recent_assets' => Asset::query()
                ->latest()
                ->take(5)
                ->get(['id', 'code_asset', 'name_asset', 'category_asset', 'status_asset', 'created_at']),
            'recent_transactions' => Transaction::query()
                ->with(['user:id,name', 'asset:id,code_asset,name_asset'])
                ->latest()
                ->take(5)
                ->get(['id', 'user_id', 'asset_id', 'borrowed_at', 'returned_at', 'cost', 'created_at']),
            'recent_maintenances' => Maintenance::query()
                ->with(['asset:id,code_asset,name_asset'])
                ->latest()
                ->take(5)
                ->get(['id', 'asset_id', 'repair_description', 'status', 'cost', 'created_at']),
        ]);
    }

    public function auditLogs(Request $request): JsonResponse
    {
        $this->authorize('view-reports');

        if (! Schema::hasTable('audit_logs')) {
            return response()->json(new LengthAwarePaginator([], 0, 20));
        }

        $logs = AuditLog::query()
            ->with('user:id,name,email,role')
            ->latest()
            ->paginate(20);

        return response()->json($logs);
    }

    public function exportSummary(Request $request): StreamedResponse
    {
        $this->authorize('export-reports');

        $summary = [
            ['Kategori', 'Metrik', 'Nilai'],
            ['Assets', 'Total', Asset::count()],
            ['Assets', 'Available', Asset::where('status_asset', Asset::STATUS_AVAILABLE)->count()],
            ['Assets', 'Borrowed', Asset::where('status_asset', Asset::STATUS_BORROWED)->count()],
            ['Assets', 'Damaged', Asset::where('status_asset', Asset::STATUS_DAMAGED)->count()],
            ['Transactions', 'Total', Transaction::count()],
            ['Transactions', 'Active', Transaction::active()->count()],
            ['Transactions', 'Returned', Transaction::whereNotNull('returned_at')->count()],
            ['Transactions', 'Total Cost', (float) Transaction::sum('cost')],
            ['Maintenances', 'Total', Maintenance::count()],
            ['Maintenances', 'Open', Maintenance::open()->count()],
            ['Maintenances', 'Completed', Maintenance::where('status', Maintenance::STATUS_COMPLETED)->count()],
            ['Maintenances', 'Total Cost', (float) Maintenance::sum('cost')],
            ['Users', 'Total', User::count()],
            ['Users', 'Admin', User::where('role', User::ROLE_ADMIN)->count()],
            ['Users', 'Pimpinan', User::where('role', User::ROLE_PIMPINAN)->count()],
            ['Users', 'Staff', User::where('role', User::ROLE_STAFF)->count()],
        ];

        $this->auditLogService->record(
            $request->user(),
            'report.summary.exported',
            'Mengekspor ringkasan laporan',
            null,
            ['rows' => count($summary) - 1]
        );

        return response()->streamDownload(function () use ($summary) {
            $handle = fopen('php://output', 'w');

            foreach ($summary as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 'summary-report.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
