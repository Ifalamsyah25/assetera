<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetRequestController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->string('search'));
        $status = trim((string) $request->string('status'));

        $allRequests = AssetRequest::with('user')->latest()->get();

        $requests = AssetRequest::with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('item_name', 'like', '%'.$search.'%')
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10);

        $summary = [
            'total' => $allRequests->count(),
            'pending' => $allRequests->where('status', 'pending')->count(),
            'approved' => $allRequests->where('status', 'approved')->count(),
            'rejected' => $allRequests->where('status', 'rejected')->count(),
        ];

        return view('asset-requests.index', compact('requests', 'summary', 'search', 'status'));
    }

    public function history()
    {
        $user = Auth::user();
        $requests = AssetRequest::where('user_id', $user->id)->latest()->get();

        return view('asset-requests.history', compact('requests'));
    }

    public function create()
    {
        return view('asset-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'estimated_cost' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = AssetRequest::STATUS_PENDING;

        AssetRequest::create($validated);

        $route = Auth::user()->role === 'staff'
            ? 'asset-requests.history'
            : 'asset-requests.index';

        return redirect()->route($route)->with('success', 'Pengajuan aset berhasil dikirim.');
    }

    public function approve(Request $request, AssetRequest $assetRequest)
    {
        if ($assetRequest->status !== AssetRequest::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($assetRequest) {
            $assetRequest->update([
                'status' => AssetRequest::STATUS_APPROVED
            ]);

            // Create new assets based on request
            for ($i = 0; $i < $assetRequest->quantity; $i++) {
                Asset::create([
                    'code_asset' => 'REQ-' . strtoupper(uniqid()),
                    'name_asset' => $assetRequest->item_name,
                    'category_asset' => 'Uncategorized', // Default required field
                    'status_asset' => Asset::STATUS_AVAILABLE,
                    'kondisi_asset' => 'Baik',
                    'purchase_date' => now(), // Default required field
                    'purchase_price' => $assetRequest->estimated_cost ?? 0,
                    // Merk, Lokasi, etc can be updated later by Admin
                ]);
            }
        });

        return back()->with('success', 'Pengajuan aset disetujui dan ditambahkan ke daftar Master Aset.');
    }

    public function reject(Request $request, AssetRequest $assetRequest)
    {
        $request->validate([
            'reject_reason' => 'required|string'
        ]);

        if ($assetRequest->status !== AssetRequest::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        $assetRequest->update([
            'status' => AssetRequest::STATUS_REJECTED,
            'rejection_reason' => $request->reject_reason
        ]);

        try {
            if ($assetRequest->user) {
                $assetRequest->user->notify(new \App\Notifications\RequestRejected($assetRequest));
            }
        } catch (\Exception $e) {
            // Mail server not available, skip notification silently
        }

        return back()->with('success', 'Pengajuan aset telah ditolak.');
    }
}
