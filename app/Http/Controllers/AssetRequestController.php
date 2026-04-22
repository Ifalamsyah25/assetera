<?php

namespace App\Http\Controllers;

use App\Models\AssetRequest;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AssetRequestController extends Controller
{
    public function index()
    {
        $requests = AssetRequest::with('user')->get();
 
        return view('asset_requests.index', compact('requests'));
    }

    public function history()
    {
        $user = Auth::user();
        $requests = AssetRequest::where('user_id', $user->id)->latest()->get();

        return view('asset_requests.history', compact('requests'));
    }

    public function create()
    {
        return view('asset_requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'estimated_cost' => 'nullable|numeric|min:0',
            'reason' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = AssetRequest::STATUS_PENDING;
        
        AssetRequest::create($validated);

        return redirect()->route('asset_requests.index')->with('success', 'Asset request submitted successfully.');
    }

        public function approve(Request $request, AssetRequest $assetRequest)
    {
        if($assetRequest->status !== AssetRequest::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        DB::transaction(function() use ($assetRequest) {
            $assetRequest->update([
                'status' => AssetRequest::STATUS_APPROVED
            ]);
        
           // Buat aset baru berdasarkan pengajuan 
        for ($i = 0 ; $i < $assetRequest->quantity; $i++) {
            Asset::create([
                'code_asset' => 'REQ-' . strtoupper(uniqid()),
                'name' => $assetRequest->item_name,
                'categori_asset' => 'Uncategorized',
                'status_asset' => Asset::STATUS_AVAILABLE,
                'kondisi_asset' => 'Baik',
                'purchase_date' => now(),
                'purchase_price' => $assetRequest->estimated_price ?? 0,
                // Merk, Lokasi, Deskripsi dll bisa ditambahkan jika diperlukan
            ]);
        }

        });

        return back()->with('success', 'Pengajuan berhasil disetujui dan aset telah dibuat.');
}

    public function reject(Request $request, AssetRequest $assetRequest)
    {
        $request->validate([
            'reject_reason' => 'required|string',
        ]);

        if($assetRequest->status !== AssetRequest::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        $assetRequest->update([
            'status' => AssetRequest::STATUS_REJECTED,
            'reject_reason' => $request->reject_reason,
        ]);

        if ($assetRequest-> user) {
            $assetRequest->user->notify(new \App\Notifications\AssetRequestRejected($assetRequest));
        }

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
