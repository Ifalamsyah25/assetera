@extends('adminlte::page')

@section('title', 'Dashboard Assets')
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="dashboard-heading">
        <h1>Dashboard</h1>
        <p>Hi, {{ auth()->user()->name ?? 'Pengguna' }}</p>
    </div>
@stop

@section('content')
    <div class="dashboard-wrapper">
        <div class="hero-banner">
            <div>
                <p class="hero-greeting">Hi, {{ auth()->user()->name ?? 'Pengguna' }}</p>
                <h2>Your activity will be updated here. Click on the name section to set your configuration.</h2>
            </div>
            <div class="hero-illustration">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-blue">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                        <p>Total Asset</p>
                        <h3>{{ $summary['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p>Tersedia</p>
                        <h3>{{ $summary['available'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-indigo">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div>
                        <p>Dipinjam</p>
                        <h3>{{ $summary['borrowed'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="asset-stat-card">
                    <div class="asset-stat-icon bg-soft-red">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <p>Rusak</p>
                        <h3>{{ $summary['damaged'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="asset-table-card">
            <div class="table-header-row">
                <h3>Recent Asset</h3>
                <div class="action-buttons">
                    <a href="{{ route('assets.export', request()->query()) }}" class="btn btn-export">
                        <i class="fas fa-download mr-1"></i> Export CSV
                    </a>
                    @can('create', \App\Models\Asset::class)
                        <a href="{{ route('assets.create') }}" class="btn btn-add">
                            <i class="fas fa-plus mr-1"></i> Tambah Asset
                        </a>
                    @endcan
                </div>
            </div>

            <form method="GET" action="{{ route('assets.index') }}">
                <div class="table-filter-row">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="Search asset">
                    </div>

                    <select id="status" name="status" class="filter-select">
                        <option value="">Filter</option>
                        <option value="available" @selected($filters['status'] === 'available')>Tersedia</option>
                        <option value="borrowed" @selected($filters['status'] === 'borrowed')>Dipinjam</option>
                        <option value="damaged" @selected($filters['status'] === 'damaged')>Rusak</option>
                    </select>

                    <select id="category" name="category" class="filter-select">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }}</option>
                        @endforeach
                    </select>

                    <div class="filter-buttons">
                        <button type="submit" class="btn btn-filter">Terapkan</button>
                        <a href="{{ route('assets.index') }}" class="btn btn-reset">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table dashboard-table">
                    <thead>
                    <tr>
                        <th>Kode Aset</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Condition</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($assets->take(10) as $asset)
                        @php
                            $isGood = $asset->status_asset !== 'damaged';
                            $statusBadge = match ($asset->status_asset) {
                                'available' => 'badge-available',
                                'borrowed' => 'badge-borrowed',
                                default => 'badge-damaged',
                            };
                        @endphp
                        <tr>
                            <td><span class="asset-code">{{ $asset->code_asset }}</span></td>
                            <td class="asset-name">{{ $asset->name_asset }}</td>
                            <td><span class="category-pill">{{ $asset->category_asset }}</span></td>
                            <td>
                                <span class="condition {{ $isGood ? 'good' : 'bad' }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $isGood ? 'Baik' : 'Rusak' }}
                                </span>
                            </td>
                            <td><span class="status-pill {{ $statusBadge }}">{{ ucfirst($asset->status_asset) }}</span></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-action" type="button" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @can('update', $asset)
                                            <a href="{{ route('assets.edit', $asset) }}" class="dropdown-item">
                                                <i class="fas fa-edit mr-2 text-warning"></i>Edit
                                            </a>
                                        @endcan
                                        @can('delete', $asset)
                                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="js-delete-asset-form">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-danger"
                                                    data-asset-name="{{ $asset->name_asset }}"
                                                    data-asset-code="{{ $asset->code_asset }}"
                                                >
                                                    <i class="fas fa-trash mr-2"></i>Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">Belum ada aset tersimpan.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
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

        .hero-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f4f7fc;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 18px;
        }

        .hero-greeting {
            color: #7d8eb0;
            margin-bottom: 10px;
        }

        .hero-banner h2 {
            max-width: 520px;
            font-size: 22px;
            line-height: 1.35;
            font-weight: 700;
            color: #6f8fc5;
            margin: 0;
        }

        .hero-illustration {
            min-width: 120px;
            min-height: 120px;
            border-radius: 20px;
            background: linear-gradient(145deg, #e2ebff, #f7f9ff);
            display: grid;
            place-items: center;
            color: #5f7fbb;
            font-size: 44px;
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

        .table-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .table-header-row h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #202f4c;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-export,
        .btn-add,
        .btn-filter,
        .btn-reset {
            border-radius: 999px;
            border: 0;
            color: #fff;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-export { background: #1ab767; }
        .btn-add { background: #376acb; }
        .btn-filter { background: #376acb; }
        .btn-reset { background: #9ea9bd; }

        .table-filter-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .search-box {
            flex: 1 1 260px;
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 11px;
            color: #94a0b7;
            font-size: 13px;
        }

        .search-box input,
        .filter-select {
            width: 100%;
            border: 1px solid #e5eaf4;
            border-radius: 999px;
            background: #f8fafe;
            color: #33415e;
            padding: 8px 14px;
            height: 38px;
            font-size: 13px;
        }

        .search-box input {
            padding-left: 32px;
        }

        .filter-select {
            width: auto;
            min-width: 130px;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
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

        .condition {
            font-size: 13px;
            font-weight: 600;
        }

        .condition i {
            font-size: 8px;
            margin-right: 6px;
        }

        .condition.good { color: #24a261; }
        .condition.bad { color: #db4f4f; }

        .status-pill {
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-available { background: #dff6e8; color: #2d9b60; }
        .badge-borrowed { background: #e0e8ff; color: #456be0; }
        .badge-damaged { background: #ffe0e0; color: #d34f4f; }

        .btn-action {
            border: 0;
            background: transparent;
            color: #7a88a5;
        }

        .empty-state {
            text-align: center;
            color: #8a95aa;
            padding: 28px 0;
        }

        @media (max-width: 768px) {
            .hero-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .table-header-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@stop

@section('js')
    <script>
        document.querySelectorAll('.js-delete-asset-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                const button = form.querySelector('button[type="submit"]');
                const assetName = button?.dataset.assetName || 'aset ini';
                const assetCode = button?.dataset.assetCode || '';

                Swal.fire({
                    title: 'Hapus aset?',
                    text: assetCode ? `${assetCode} - ${assetName}` : assetName,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                timer: 1800,
                showConfirmButton: false,
            });
        @endif

        @if ($errors->has('asset'))
            Swal.fire({
                icon: 'error',
                title: 'Tidak bisa dihapus',
                text: @json($errors->first('asset')),
            });
        @endif
    </script>
@stop
