<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetRequestController extends Controller
{
    public function index()
    {
        $requests = AssetRequest::with('user')->latest()->get();

        return view('asset-requests.index', compact('requests'));
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
            'estimated_price' => 'nullable|numeric|min:0',
            'reason' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = AssetRequest::STATUS_PENDING;

        AssetRequest::create($validated);

        return redirect()->route('asset-requests.index')->with('success', 'Pengajuan aset berhasil dikirim.');
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
                    'purchase_price' => $assetRequest->estimated_price ?? 0,
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
            'reject_reason' => $request->reject_reason
        ]);

        if ($assetRequest->user) {
            $assetRequest->user->notify(new \App\Notifications\RequestRejected($assetRequest));
        }

        return back()->with('success', 'Pengajuan aset telah ditolak.');
    }
}
