@extends('adminlte::page')

@section('title', 'Maintenance')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Maintenance</h1>
            <small class="text-muted">Pantau proses perawatan dan perbaikan aset</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Maintenance</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $summary['total'] }}</h3>
                    <p>Total Maintenance</p>
                </div>
                <div class="icon"><i class="fas fa-tools"></i></div>
                <div class="small-box-footer">Semua perawatan aset</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $summary['open'] }}</h3>
                    <p>Masih Berjalan</p>
                </div>
                <div class="icon"><i class="fas fa-spinner"></i></div>
                <div class="small-box-footer">Pending dan in progress</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $summary['completed'] }}</h3>
                    <p>Selesai</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <div class="small-box-footer">Maintenance tuntas</div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-secondary">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Maintenance</h3>
                <div class="d-flex align-items-center">
                    <span class="badge badge-secondary px-3 py-2 mr-2">{{ $maintenances->count() }} record</span>
                    <a href="{{ route('maintenances.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped text-nowrap">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Biaya</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($maintenances as $maintenance)
                        <tr>
                            <td>
                                <strong>{{ $maintenance->asset->name_asset }}</strong><br>
                                <small class="text-muted">{{ $maintenance->asset->code_asset }}</small>
                            </td>
                            <td>{{ $maintenance->repair_description }}</td>
                            <td><span class="badge badge-{{ $maintenance->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}</span></td>
                            <td>Rp {{ number_format((float) $maintenance->cost, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-xs btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus data maintenance ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">Belum ada data maintenance.</td></tr>
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
