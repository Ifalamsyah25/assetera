@extends('adminlte::page')

@section('title', 'Asset Summary')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Asset Summary</h1>
            <small class="text-muted">Ringkasan aset, transaksi, maintenance, dan aktivitas pengguna</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Asset Summary</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                <div class="mb-3 mb-lg-0">
                    <h2 class="h4 mb-1">Snapshot Operasional</h2>
                    <p class="text-muted mb-0">Pantau kondisi inventaris dan aktivitas terbaru dalam satu halaman.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-primary btn-sm mr-2 mb-2">
                        <i class="fas fa-boxes mr-1"></i> Kelola Aset
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-warning btn-sm mr-2 mb-2">
                        <i class="fas fa-exchange-alt mr-1"></i> Lihat Transaksi
                    </a>
                    <a href="{{ route('maintenances.index') }}" class="btn btn-outline-danger btn-sm mb-2">
                        <i class="fas fa-tools mr-1"></i> Cek Maintenance
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $summary['assets']['total'] }}</h3>
                    <p>Total Assets</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="{{ route('assets.index') }}" class="small-box-footer">Lihat aset <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $summary['transactions']['active'] }}</h3>
                    <p>Transaksi Aktif</p>
                </div>
                <div class="icon"><i class="fas fa-exchange-alt"></i></div>
                <a href="{{ route('transactions.index') }}" class="small-box-footer">Lihat transaksi <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $summary['maintenances']['open'] }}</h3>
                    <p>Maintenance Berjalan</p>
                </div>
                <div class="icon"><i class="fas fa-tools"></i></div>
                <a href="{{ route('maintenances.index') }}" class="small-box-footer">Lihat maintenance <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $summary['users']['total'] }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="small-box-footer">Admin {{ $summary['users']['admin'] }} / Pimpinan {{ $summary['users']['pimpinan'] }} / Staff {{ $summary['users']['staff'] }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted text-uppercase small font-weight-bold">Nilai Transaksi</div>
                            <div class="h3 mb-1">Rp {{ number_format($summary['transactions']['total_cost'], 0, ',', '.') }}</div>
                            <div class="small text-muted">{{ $summary['transactions']['returned'] }} transaksi sudah selesai</div>
                        </div>
                        <span class="badge badge-warning badge-pill px-3 py-2">Transaksi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted text-uppercase small font-weight-bold">Biaya Maintenance</div>
                            <div class="h3 mb-1">Rp {{ number_format($summary['maintenances']['total_cost'], 0, ',', '.') }}</div>
                            <div class="small text-muted">{{ $summary['maintenances']['completed'] }} maintenance telah selesai</div>
                        </div>
                        <span class="badge badge-danger badge-pill px-3 py-2">Perawatan</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted text-uppercase small font-weight-bold">Komposisi Pengguna</div>
                            <div class="h3 mb-1">{{ $summary['users']['total'] }} akun</div>
                            <div class="small text-muted">Admin {{ $summary['users']['admin'] }}, Pimpinan {{ $summary['users']['pimpinan'] }}, Staff {{ $summary['users']['staff'] }}</div>
                        </div>
                        <span class="badge badge-success badge-pill px-3 py-2">Akun</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Ringkasan Statistik</h3>
                <a href="{{ route('reports.summary.export') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-file-export mr-1"></i> Export CSV
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-info"><i class="fas fa-box-open"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Assets Tersedia</span>
                            <span class="info-box-number">{{ $summary['assets']['available'] }}</span>
                            <span class="text-muted">Dipinjam: {{ $summary['assets']['borrowed'] }} | Rusak: {{ $summary['assets']['damaged'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-warning"><i class="fas fa-hand-holding"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Transaksi</span>
                            <span class="info-box-number">{{ $summary['transactions']['total'] }}</span>
                            <span class="text-muted">Returned: {{ $summary['transactions']['returned'] }} | Biaya: Rp {{ number_format($summary['transactions']['total_cost'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-danger"><i class="fas fa-screwdriver-wrench"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Maintenance</span>
                            <span class="info-box-number">{{ $summary['maintenances']['total'] }}</span>
                            <span class="text-muted">Completed: {{ $summary['maintenances']['completed'] }} | Biaya: Rp {{ number_format($summary['maintenances']['total_cost'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aset Terbaru</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentAssets as $asset)
                                <tr>
                                    <td><strong>{{ $asset->code_asset }}</strong></td>
                                    <td>{{ $asset->name_asset }}</td>
                                    <td>
                                        @php
                                            $assetBadge = match ($asset->status_asset) {
                                                'available' => 'success',
                                                'borrowed' => 'warning',
                                                default => 'danger',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $assetBadge }}">{{ ucfirst(str_replace('_', ' ', $asset->status_asset)) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada data aset.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Aset</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->asset->code_asset }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->returned_at ? 'success' : 'warning' }}">
                                            {{ $transaction->returned_at ? 'Returned' : 'Active' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Terbaru</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Aset</th>
                                <th>Status</th>
                                <th>Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentMaintenances as $maintenance)
                                <tr>
                                    <td><strong>{{ $maintenance->asset->code_asset }}</strong></td>
                                    <td>
                                        @php
                                            $maintenanceBadge = $maintenance->status === 'completed' ? 'success' : 'warning';
                                        @endphp
                                        <span class="badge badge-{{ $maintenanceBadge }}">{{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}</span>
                                    </td>
                                    <td>Rp {{ number_format((float) $maintenance->cost, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada maintenance.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Audit Logs Terbaru</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentAuditLogs as $log)
                                <tr>
                                    <td>{{ $log->user?->name ?? 'System' }}</td>
                                    <td>
                                        <div class="font-weight-semibold">{{ $log->description }}</div>
                                        <small class="text-muted">{{ $log->action }}</small>
                                    </td>
                                    <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Audit log belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f6f9fc 100%);
        }

        .font-weight-semibold {
            font-weight: 600;
        }
    </style>
@stop
