<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetRequest;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'Juni 2026');

        $query = StockOpname::query()
            ->with('asset')
            ->where('periode', $periode);

        // Stats
        $totalAssets = Asset::count();
        $totalDiaudit = StockOpname::where('periode', $periode)->count();
        $auditSesuai = StockOpname::where('periode', $periode)->where('status', 'sesuai')->count();
        $selisihDitemukan = StockOpname::where('periode', $periode)->where('status', 'selisih')->count();
        
        // Pengadaan Bulan Ini: Sum of purchase price for assets purchased in the selected month/year.
        // For Juni 2026, we check '2026-06-%'
        $pengadaanBulanIni = Asset::where('purchase_date', 'like', '2026-06-%')->sum('purchase_price');
        
        // Pengajuan Pending
        $pengajuanPending = AssetRequest::where('status', 'pending')->count();

        $stockOpnames = $query->latest()->paginate(10)->withQueryString();

        $allPeriodes = StockOpname::select('periode')
            ->distinct()
            ->pluck('periode');

        return view('stock_opname.index', [
            'stockOpnames' => $stockOpnames,
            'periode' => $periode,
            'allPeriodes' => $allPeriodes,
            'summary' => [
                'total_assets' => $totalAssets,
                'total_diaudit' => $totalDiaudit,
                'audit_sesuai' => $auditSesuai,
                'selisih_ditemukan' => $selisihDitemukan,
                'pengadaan_bulan_ini' => $pengadaanBulanIni,
                'pengajuan_pending' => $pengajuanPending,
            ]
        ]);
    }

    public function create()
    {
        $assets = Asset::orderBy('name_asset')->get();
        return view('stock_opname.create', [
            'assets' => $assets
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'qty_sistem' => 'required|integer|min:0',
            'qty_aktual' => 'required|integer|min:0',
            'periode' => 'required|string',
            'tanggal_audit' => 'required|date',
            'keterangan' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
        ]);

        $qtySistem = $validated['qty_sistem'];
        $qtyAktual = $validated['qty_aktual'];
        $selisih = abs($qtySistem - $qtyAktual);
        $status = ($selisih === 0) ? 'sesuai' : 'selisih';

        // Update the asset's location in the database
        $asset = Asset::findOrFail($validated['asset_id']);
        $asset->update([
            'lokasi_asset' => $validated['lokasi']
        ]);

        StockOpname::create([
            'asset_id' => $validated['asset_id'],
            'qty_sistem' => $qtySistem,
            'qty_aktual' => $qtyAktual,
            'selisih' => $selisih,
            'status' => $status,
            'periode' => $validated['periode'],
            'tanggal_audit' => $validated['tanggal_audit'],
            'auditor_id' => auth()->id(),
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname berhasil ditambahkan.');
    }

    public function export(Request $request): StreamedResponse
    {
        $periode = $request->input('periode', 'Juni 2026');
        $stockOpnames = StockOpname::with('asset')
            ->where('periode', $periode)
            ->get();

        return response()->streamDownload(function () use ($stockOpnames, $periode) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Asset', 'Nama Barang', 'Lokasi', 'Sistem', 'Aktual', 'Selisih', 'Status', 'Keterangan']);

            foreach ($stockOpnames as $so) {
                fputcsv($handle, [
                    $so->asset->code_asset,
                    $so->asset->name_asset,
                    $so->asset->lokasi_asset,
                    $so->qty_sistem,
                    $so->qty_aktual,
                    $so->selisih,
                    ucfirst($so->status),
                    $so->keterangan,
                ]);
            }

            fclose($handle);
        }, "stock-opname-{$periode}.csv", [
            'Content-Type' => 'text/csv',
        ]);
    }
}
