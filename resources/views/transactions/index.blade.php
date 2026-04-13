@extends('adminlte::page')

@section('title', 'Transactions')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Transactions</h1>
            <small class="text-muted">Monitoring peminjaman dan pengembalian aset</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Transactions</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $summary['total'] }}</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="icon"><i class="fas fa-file-invoice"></i></div>
                <div class="small-box-footer">Data transaksi aset</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $summary['active'] }}</h3>
                    <p>Masih Dipinjam</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
                <div class="small-box-footer">Belum dikembalikan</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $summary['returned'] }}</h3>
                    <p>Sudah Kembali</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <div class="small-box-footer">Riwayat selesai</div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-info">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Transactions</h3>
                <div class="d-flex align-items-center">
                    <span class="badge badge-info px-3 py-2 mr-2">{{ $transactions->count() }} record</span>
                    <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped text-nowrap">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Asset</th>
                        <th>Status Asset</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Biaya</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>
                                <strong>{{ $transaction->user->name }}</strong><br>
                                <small class="text-muted">{{ $transaction->user->role }}</small>
                            </td>
                            <td>{{ $transaction->asset->name_asset }} ({{ $transaction->asset->code_asset }})</td>
                            <td>
                                @php
                                    $statusBadge = match ($transaction->asset->status_asset) {
                                        'available' => 'success',
                                        'borrowed' => 'warning',
                                        default => 'danger',
                                    };
                                @endphp
                                <span class="badge badge-{{ $statusBadge }}">{{ ucfirst($transaction->asset->status_asset) }}</span>
                            </td>
                            <td>{{ $transaction->borrowed_at->format('d M Y') }}</td>
                            <td>{{ $transaction->returned_at?->format('d M Y') ?? '-' }}</td>
                            <td>Rp {{ number_format((float) $transaction->cost, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-xs btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus transaksi ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .small-box-footer {
            background: rgba(0, 0, 0, 0.08);
        }
    </style>
@stop
