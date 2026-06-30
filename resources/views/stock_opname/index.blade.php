@extends('adminlte::page')

@section('title', 'Hasil Stock Opname')
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="dashboard-heading d-flex justify-content-between align-items-center">
        <div>
            <h1>Stock Opname</h1>
            <p>Hasil Stock Opname Periode {{ $periode }}</p>
        </div>
        @can('create', \App\Models\Asset::class)
            <a href="{{ route('stock-opnames.create') }}" class="btn btn-add">
                <i class="fas fa-plus mr-1"></i> Input Stock Opname
            </a>
        @endcan
    </div>
@stop

@section('content')
    <x-flash-message />
    <div class="dashboard-wrapper">
        
        <!-- Top Stats Row (4 Cards) -->
        <div class="row mb-4">
            <!-- Total Aset -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-blue">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                        <p>Total Aset</p>
                        <h3>{{ $summary['total_assets'] }}</h3>
                        <span class="text-xs text-muted">Tercatat seluruh Aset</span>
                    </div>
                </div>
            </div>
            <!-- Pengadaan Bulan Ini -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-indigo">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <p>Pengadaan Bulan Ini</p>
                        <h3>Rp{{ number_format($summary['pengadaan_bulan_ini'] / 1000000, 0, ',', '.') }}jt</h3>
                        <span class="text-xs text-muted">32% sedang dalam pembelian</span>
                    </div>
                </div>
            </div>
            <!-- Pengajuan Pending -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-green">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p>Pengajuan Pending</p>
                        <h3>{{ $summary['pengajuan_pending'] }}</h3>
                        <span class="text-xs text-muted">Menunggu Persetujuan</span>
                    </div>
                </div>
            </div>
            <!-- Selisih Stock Opname -->
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p>Selisih Stock Opname</p>
                        <h3>{{ $summary['selisih_ditemukan'] }}</h3>
                        <span class="text-xs text-muted">Audit Periode {{ $periode }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Row Stats (3 Cards) -->
        <div class="row mb-4">
            <!-- Total Aset diaudit -->
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-blue">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <p>Total Aset diaudit</p>
                        <h3>{{ $summary['total_diaudit'] }}</h3>
                        <span class="text-xs text-muted">Dari target {{ $summary['total_assets'] }}</span>
                    </div>
                </div>
            </div>
            <!-- Audit Sesuai -->
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-green">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <p>Audit Sesuai</p>
                        @php
                            $accuracy = $summary['total_diaudit'] > 0 ? round(($summary['audit_sesuai'] / $summary['total_diaudit']) * 100) : 0;
                        @endphp
                        <h3>{{ $summary['audit_sesuai'] }}</h3>
                        <span class="text-xs text-muted">Akurasi {{ $accuracy }}%</span>
                    </div>
                </div>
            </div>
            <!-- Selisih Ditemukan -->
            <div class="col-lg-4 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-red">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <p>Selisih Ditemukan</p>
                        <h3>{{ $summary['selisih_ditemukan'] }}</h3>
                        <span class="text-xs text-muted">Perlu tindak lanjut</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Data Table Card -->
        <div class="asset-table-card">
            <div class="table-header-row d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Hasil Stock Opname Periode {{ $periode }}</h3>
                    <p class="text-sm text-muted">Mencocokan kuantitas sistem dan aktual</p>
                </div>
                <div class="action-buttons d-flex align-items-center gap-3">
                    <form method="GET" action="{{ route('stock-opnames.index') }}" class="mr-2 d-flex align-items-center">
                        <select name="periode" onchange="this.form.submit()" class="filter-select rounded-pill">
                            @foreach ($allPeriodes as $p)
                                <option value="{{ $p }}" @selected($periode === $p)>Periode: {{ $p }}</option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route('stock-opnames.export', ['periode' => $periode]) }}" class="btn btn-export">
                        <i class="fas fa-download mr-1"></i> Ekspor File
                    </a>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table dashboard-table">
                    <thead>
                        <tr>
                            <th>KODE ASET</th>
                            <th>NAMA BARANG</th>
                            <th>LOKASI</th>
                            <th>SISTEM</th>
                            <th>AKTUAL</th>
                            <th>SELISIH</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockOpnames as $so)
                            @php
                                $statusBadge = $so->status === 'sesuai' ? 'badge-available' : 'badge-damaged';
                            @endphp
                            <tr>
                                <td><span class="asset-code">{{ $so->asset->code_asset ?? 'N/A' }}</span></td>
                                <td class="asset-name">{{ $so->asset->name_asset ?? 'N/A' }}</td>
                                <td><span class="category-pill">{{ $so->asset->lokasi_asset ?? 'N/A' }}</span></td>
                                <td>{{ $so->qty_sistem }}</td>
                                <td>{{ $so->qty_aktual }}</td>
                                <td>
                                    <span class="{{ $so->selisih > 0 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                        {{ $so->selisih }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-pill {{ $statusBadge }}">
                                        {{ ucfirst($so->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">Belum ada data stock opname untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $stockOpnames->links() }}
            </div>
        </div>

    </div>
@stop

@section('css')
    <style>
        .dashboard-heading h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2f3d59;
            margin-bottom: 0;
        }

        .dashboard-heading p {
            color: #7f8ba4;
            margin: 4px 0 0;
        }

        .dashboard-wrapper {
            padding-bottom: 20px;
        }

        .asset-stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 8px 20px rgba(34, 66, 122, 0.08);
            height: 100%;
        }

        .asset-stat-card p {
            margin: 0;
            color: #71809f;
            font-size: 14px;
        }

        .asset-stat-card h3 {
            margin: 4px 0 0;
            font-size: 30px;
            font-weight: 700;
            color: #203152;
        }

        .asset-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: grid;
            place-items: center;
        }

        .bg-soft-blue { background: #dbe9ff; color: #3f6fd8; }
        .bg-soft-green { background: #d9f5e4; color: #2ea36b; }
        .bg-soft-indigo { background: #dde4ff; color: #4669e8; }
        .bg-soft-red { background: #ffe2e2; color: #d85050; }

        .asset-table-card {
            background: #fff;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 10px 24px rgba(29, 55, 104, 0.08);
        }

        .table-header-row h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #202f4c;
        }

        .btn-export, .btn-add {
            border-radius: 999px;
            border: 0;
            color: #fff;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .btn-export { background: #f1f4fa; color: #506690; border: 1px solid #e1e8f5; }
        .btn-export:hover { background: #e2ebf7; color: #3b5075; }
        .btn-add { background: #6b87b5; color: white; }
        .btn-add:hover { opacity: 0.9; color: white; }

        .filter-select {
            border: 1px solid #e5eaf4;
            background: #f8fafe;
            color: #33415e;
            padding: 8px 16px;
            height: 38px;
            font-size: 13px;
            outline: none;
        }

        .dashboard-table thead th {
            border-top: 0;
            border-bottom: 1px solid #eef2f9;
            color: #8d9ab2;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .dashboard-table tbody td {
            vertical-align: middle;
            border-top: 1px solid #f1f4fa;
            padding: 14px 8px;
        }

        .asset-code {
            color: #3e67bf;
            font-weight: 700;
            font-size: 13px;
        }

        .asset-name {
            font-weight: 600;
            color: #253658;
        }

        .category-pill {
            background: #e8efff;
            color: #5875ba;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pill {
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-available { background: #dff6e8; color: #2d9b60; }
        .badge-damaged { background: #ffe0e0; color: #d34f4f; }

        .empty-state {
            text-align: center;
            color: #8a95aa;
            padding: 28px 0;
        }

        /* Custom Pagination styles matching theme */
        .pagination .page-item.active .page-link {
            background-color: #6b87b5 !important;
            border-color: #6b87b5 !important;
            color: #fff !important;
        }
        .pagination .page-link {
            color: #6b87b5;
            border-radius: 6px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
        }
        .pagination .page-link:hover {
            background-color: #eef2f9;
            color: #5a759e;
        }
    </style>
@stop
