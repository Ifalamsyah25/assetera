@extends('adminlte::page')

@section('title', 'Histori Pengajuan')

@section('content_header')
    <h1>Histori Pengajuan Saya</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <x-flash-message />

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengajuan Anda</h3>
                <div class="card-tools">
                    <a href="{{ route('asset-requests.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buat Pengajuan Baru
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barang</th>
                                <th>Jml</th>
                                <th>Estimasi Harga</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $req->item_name }}</td>
                                <td>{{ $req->quantity }}</td>
                                <td>Rp {{ number_format($req->estimated_cost, 0, ',', '.') }}</td>
                                <td>{{ $req->reason }}</td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge badge-warning">Sedang Diproses</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                        <br><small class="text-muted">Alasan: {{ $req->rejection_reason }}</small>
                                    @endif
                                </td>
                                <td>{{ $req->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Anda belum pernah membuat pengajuan aset.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
