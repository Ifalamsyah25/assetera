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

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category_asset');

        // Apply filters conditionally via a closure
        $applyDateFilter = function ($query) use ($startDate, $endDate, $category) {
            $query->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                  ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
                  ->when($category, fn($q) => $q->where('category_asset', $category));
        };

        // For transactions, we might want to filter by borrowed_at as well, but created_at is fine for a general filter.
        $applyTransactionDateFilter = function ($query) use ($startDate, $endDate) {
            $query->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                  ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate));
        };

        $summary = [
            'assets' => [
                'total' => Asset::where($applyDateFilter)->count(),
                'available' => Asset::where('status_asset', Asset::STATUS_AVAILABLE)->where($applyDateFilter)->count(),
                'borrowed' => Asset::where('status_asset', Asset::STATUS_BORROWED)->where($applyDateFilter)->count(),
                'damaged' => Asset::where('status_asset', Asset::STATUS_DAMAGED)->where($applyDateFilter)->count(),
            ],
            'transactions' => [
                'total' => Transaction::where($applyTransactionDateFilter)->count(),
                'active' => Transaction::active()->where($applyTransactionDateFilter)->count(),
                'returned' => Transaction::whereNotNull('returned_at')->where($applyTransactionDateFilter)->count(),
                'total_cost' => (float) Transaction::where($applyTransactionDateFilter)->sum('cost'),
            ],
            'maintenances' => [
                'total' => Maintenance::where($applyDateFilter)->count(),
                'open' => Maintenance::open()->where($applyDateFilter)->count(),
                'completed' => Maintenance::where('status', Maintenance::STATUS_COMPLETED)->where($applyDateFilter)->count(),
                'total_cost' => (float) Maintenance::where($applyDateFilter)->sum('cost'),
            ],
            'users' => [
                'total' => User::where($applyDateFilter)->count(),
                'admin' => User::where('role', User::ROLE_ADMIN)->where($applyDateFilter)->count(),
                'pimpinan' => User::where('role', User::ROLE_PIMPINAN)->where($applyDateFilter)->count(),
                'staff' => User::where('role', User::ROLE_STAFF)->where($applyDateFilter)->count(),
            ],
        ];

        if ($request->expectsJson()) {
            return response()->json($summary);
        }

        return view('reports.summary', [
            'summary' => $summary,
            'recentAssets' => Asset::query()
                ->where($applyDateFilter)
                ->latest()
                ->take(5)
                ->get(['id', 'code_asset', 'name_asset', 'category_asset', 'status_asset', 'created_at']),
            'recentTransactions' => Transaction::query()
                ->where($applyTransactionDateFilter)
                ->with(['user:id,name', 'asset:id,code_asset,name_asset'])
                ->latest()
                ->take(5)
                ->get(['id', 'user_id', 'asset_id', 'borrowed_at', 'returned_at', 'cost', 'created_at']),
            'recentMaintenances' => Maintenance::query()
                ->where($applyDateFilter)
                ->with(['asset:id,code_asset,name_asset'])
                ->latest()
                ->take(5)
                ->get(['id', 'asset_id', 'repair_description', 'status', 'cost', 'created_at']),
            'recentAuditLogs' => Schema::hasTable('audit_logs')
                ? AuditLog::query()
                    ->where($applyDateFilter)
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
        $summary = $this->getSummaryDataForExport($request);

        $this->auditLogService->record(
            $request->user(),
            'report.summary.exported',
            'Mengekspor ringkasan laporan CSV',
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

    public function exportPdf(Request $request)
    {
        $this->authorize('export-reports');
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category_asset');

        // Apply filters conditionally via a closure
        $applyDateFilter = function ($query) use ($startDate, $endDate, $category) {
            $query->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                  ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
                  ->when($category, fn($q) => $q->where('category_asset', $category));
        };

        $applyTransactionDateFilter = function ($query) use ($startDate, $endDate) {
            $query->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                  ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate));
        };

        $summary = [
            'assets' => [
                'total' => Asset::where($applyDateFilter)->count(),
                'available' => Asset::where('status_asset', Asset::STATUS_AVAILABLE)->where($applyDateFilter)->count(),
                'borrowed' => Asset::where('status_asset', Asset::STATUS_BORROWED)->where($applyDateFilter)->count(),
                'damaged' => Asset::where('status_asset', Asset::STATUS_DAMAGED)->where($applyDateFilter)->count(),
            ],
            'transactions' => [
                'total' => Transaction::where($applyTransactionDateFilter)->count(),
                'active' => Transaction::active()->where($applyTransactionDateFilter)->count(),
                'returned' => Transaction::whereNotNull('returned_at')->where($applyTransactionDateFilter)->count(),
                'total_cost' => (float) Transaction::where($applyTransactionDateFilter)->sum('cost'),
            ],
            'maintenances' => [
                'total' => Maintenance::where($applyDateFilter)->count(),
                'open' => Maintenance::open()->where($applyDateFilter)->count(),
                'completed' => Maintenance::where('status', Maintenance::STATUS_COMPLETED)->where($applyDateFilter)->count(),
                'total_cost' => (float) Maintenance::where($applyDateFilter)->sum('cost'),
            ]
        ];

        $this->auditLogService->record(
            $request->user(),
            'report.summary.exported',
            'Mengekspor ringkasan laporan PDF',
            null,
            []
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.print-pdf', compact('summary'));
        return $pdf->download('summary-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        $summary = $this->getSummaryDataForExport($request);

        $this->auditLogService->record(
            $request->user(),
            'report.summary.exported',
            'Mengekspor ringkasan laporan Excel',
            null,
            ['rows' => count($summary) - 1]
        );

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SummaryExport($summary), 'summary-report.xlsx');
    }

    private function getSummaryDataForExport(Request $request)
    {
        $this->authorize('export-reports');
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category_asset');

        // Apply filters conditionally via a closure
        $applyDateFilter = function ($query) use ($startDate, $endDate, $category) {
            $query->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                  ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
                  ->when($category, fn($q) => $q->where('category_asset', $category));
        };

        $applyTransactionDateFilter = function ($query) use ($startDate, $endDate) {
            $query->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                  ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate));
        };

        return [
            ['Kategori', 'Metrik', 'Nilai'],
            ['Assets', 'Total', Asset::where($applyDateFilter)->count()],
            ['Assets', 'Available', Asset::where('status_asset', Asset::STATUS_AVAILABLE)->where($applyDateFilter)->count()],
            ['Assets', 'Borrowed', Asset::where('status_asset', Asset::STATUS_BORROWED)->where($applyDateFilter)->count()],
            ['Assets', 'Damaged', Asset::where('status_asset', Asset::STATUS_DAMAGED)->where($applyDateFilter)->count()],
            ['Transactions', 'Total', Transaction::where($applyTransactionDateFilter)->count()],
            ['Transactions', 'Active', Transaction::active()->where($applyTransactionDateFilter)->count()],
            ['Transactions', 'Returned', Transaction::whereNotNull('returned_at')->where($applyTransactionDateFilter)->count()],
            ['Transactions', 'Total Cost', (float) Transaction::where($applyTransactionDateFilter)->sum('cost')],
            ['Maintenances', 'Total', Maintenance::where($applyDateFilter)->count()],
            ['Maintenances', 'Open', Maintenance::open()->where($applyDateFilter)->count()],
            ['Maintenances', 'Completed', Maintenance::where('status', Maintenance::STATUS_COMPLETED)->where($applyDateFilter)->count()],
            ['Maintenances', 'Total Cost', (float) Maintenance::where($applyDateFilter)->sum('cost')],
            ['Users', 'Total', User::where($applyDateFilter)->count()],
            ['Users', 'Admin', User::where('role', User::ROLE_ADMIN)->where($applyDateFilter)->count()],
            ['Users', 'Pimpinan', User::where('role', User::ROLE_PIMPINAN)->where($applyDateFilter)->count()],
            ['Users', 'Staff', User::where('role', User::ROLE_STAFF)->where($applyDateFilter)->count()],
        ];
    }
}
