<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Asset::class);

        $filters = [
            'search' => trim((string) $request->string('search')),
            'status' => trim((string) $request->string('status')),
            'category' => trim((string) $request->string('category')),
        ];

        $query = Asset::query()
            ->withCount(['transactions', 'maintenances'])
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $query->where(function ($builder) use ($filters) {
                    $builder
                        ->where('code_asset', 'like', '%'.$filters['search'].'%')
                        ->orWhere('name_asset', 'like', '%'.$filters['search'].'%');
                });
            })
            ->when($filters['status'] !== '', fn ($q) => $q->where('status_asset', $filters['status']))
            ->when($filters['category'] !== '', fn ($q) => $q->where('category_asset', $filters['category']));

        $summary = $this->buildSummary($query->get());

        $assets = (clone $query)->latest()->paginate(10)->withQueryString();

        return view('dashboard', [
            'assets' => $assets,
            'summary' => $summary,
            'filters' => $filters,
            'categories' => Asset::query()
                ->select('category_asset')
                ->distinct()
                ->orderBy('category_asset')
                ->pluck('category_asset'),
        ]);
    }

    private function buildSummary($assets)
    {
        return [
            'total' => $assets->count(),
            'available' => $assets->where('status_asset', 'available')->count(),
            'borrowed' => $assets->where('status_asset', 'borrowed')->count(),
            'damaged' => $assets->where('status_asset', 'damaged')->count(),
        ];
    }
}
