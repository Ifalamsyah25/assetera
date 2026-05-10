@extends('adminlte::page')

@section('title', 'Daftar Pengajuan Aset')

@section('content_header')
    <h1>Daftar Pengajuan Aset</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <x-flash-message />

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Antrean & Approval Pengajuan</h3>
                @if(Auth::user()->role === 'admin')
                <div class="card-tools">
                    <a href="{{ route('asset-requests.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buat Pengajuan Baru
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pengajuan</th>
                                <th>Barang</th>
                                <th>Jml</th>
                                <th>Estimasi Harga</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $req->user->name ?? '-' }}</td>
                                <td>{{ $req->item_name }}</td>
                                <td>{{ $req->quantity }}</td>
                                <td>Rp {{ number_format($req->estimated_price, 0, ',', '.') }}</td>
                                <td>{{ $req->reason }}</td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                        <br><small class="text-muted">Alasan: {{ $req->reject_reason }}</small>
                                    @endif
                                </td>
                                <td>{{ $req->created_at->format('d M Y H:i') }}</td>
                                
                                <td>
                                    @if($req->status === 'pending')
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal-{{ $req->id }}">
                                        <i class="fas fa-check"></i> ACC
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal-{{ $req->id }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>

                                    <x-approval-modal :req="$req" />
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data pengajuan aset.</td>
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