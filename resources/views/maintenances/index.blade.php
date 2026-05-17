@extends('adminlte::page')

@section('title', 'Under Service')
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="service-header-card">
        <div class="header-left">
            <div class="header-icon"><i class="fas fa-wrench"></i></div>
            <div>
                <h1>Aset Dalam Perbaikan (Under Service)</h1>
                <p>Pantau dan kelola aset yang sedang dalam proses perbaikan atau maintenance</p>
            </div>
        </div>
    </div>
@stop

@section('content')
    <x-flash-message />
    <div class="service-page">
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-blue"><i class="fas fa-toolbox"></i></div>
                    <small>Total</small>
                    <h3>{{ $summary['total'] }}</h3>
                    <p>Total Under Service</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-indigo"><i class="fas fa-cog"></i></div>
                    <small>Active</small>
                    <h3>{{ $summary['active'] }}</h3>
                    <p>Sedang Diperbaiki</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-orange"><i class="fas fa-box-open"></i></div>
                    <small>Parts</small>
                    <h3>{{ $summary['parts'] }}</h3>
                    <p>Menunggu Sparepart</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-green"><i class="fas fa-check-circle"></i></div>
                    <small>Done</small>
                    <h3>{{ $summary['done'] }}</h3>
                    <p>Selesai</p>
                </div>
            </div>
        </div>

        <div class="service-table-card">
            <div class="table-head">
                <h3>Daftar Aset Under Service</h3>
                <form method="GET" action="{{ route('maintenances.index') }}" class="table-tools">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari Kode atau Nama Aset...">
                    </div>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Filter</option>
                        <option value="in_progress" @selected($filters['status'] === 'in_progress')>In Progress</option>
                        <option value="pending" @selected($filters['status'] === 'pending')>Waiting Sparepart</option>
                        <option value="completed" @selected($filters['status'] === 'completed')>Selesai</option>
                    </select>
                    <a href="{{ route('maintenances.create') }}" class="btn btn-add-service">
                        <i class="fas fa-plus mr-1"></i> Tambah Service
                    </a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table service-table">
                    <thead>
                    <tr>
                        <th>Kode Aset</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Masuk Service</th>
                        <th>Estimasi Selesai</th>
                        <th>Teknisi / PIC</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($maintenances as $maintenance)
                        @php
                            $statusClass = match ($maintenance->status) {
                                'in_progress' => 'status-progress',
                                'completed' => 'status-done',
                                default => 'status-pending',
                            };
                            $statusLabel = match ($maintenance->status) {
                                'in_progress' => 'In Progress',
                                'completed' => 'Selesai',
                                default => 'Waiting Sparepart',
                            };
                            $startDate = $maintenance->created_at ?? now();
                            $estimateDate = $maintenance->status === 'completed'
                                ? ($maintenance->updated_at ?? $startDate)
                                : $startDate->copy()->addDays(7);
                        @endphp
                        <tr>
                            <td><span class="asset-code">{{ $maintenance->asset->code_asset }}</span></td>
                            <td><span class="main-text">{{ $maintenance->asset->name_asset }}</span></td>
                            <td><span class="category-pill">{{ $maintenance->asset->category_asset ?? 'Elektronik' }}</span></td>
                            <td>{{ $maintenance->asset->status_asset === 'damaged' ? 'Ruang Kantor' : 'Dapur' }}</td>
                            <td>{{ $startDate->format('d M Y') }}</td>
                            <td class="{{ $maintenance->status === 'pending' ? 'late-text' : '' }}">{{ $estimateDate->format('d M Y') }}</td>
                            <td>{{ auth()->user()->name ?? 'Teknisi' }}</td>
                            <td><span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-xs btn-light mr-1"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-xs btn-outline-secondary mr-1"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="d-inline js-delete-maintenance-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-secondary" data-asset="{{ $maintenance->asset->name_asset }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data under service.</td>
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
        .service-header-card { background: #fff; border-radius: 16px; padding: 16px 18px; box-shadow: 0 8px 22px rgba(37, 59, 102, 0.08); }
        .header-left { display: flex; align-items: center; gap: 12px; }
        .header-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; background: #dde8ff; color: #4a6ec8; }
        .service-header-card h1 { margin: 0; font-size: 30px; color: #263654; font-weight: 700; }
        .service-header-card p { margin: 2px 0 0; color: #7f8ca5; }
        .stat-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 8px 22px rgba(37, 59, 102, 0.08); }
        .stat-icon { width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; }
        .stat-card small { color: #95a3bb; font-weight: 700; display: block; margin-top: 8px; text-transform: uppercase; font-size: 10px; }
        .stat-card h3 { margin: 0; font-size: 34px; color: #1d2d4b; font-weight: 700; }
        .stat-card p { margin: 2px 0 0; color: #7c8ca7; font-size: 12px; }
        .bg-soft-blue { background: #dde9ff; color: #4c74d5; }
        .bg-soft-indigo { background: #e6ecff; color: #4c66bf; }
        .bg-soft-orange { background: #ffe8d2; color: #da7a2e; }
        .bg-soft-green { background: #daf5e3; color: #2ba166; }
        .service-table-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 22px rgba(37, 59, 102, 0.08); overflow: hidden; }
        .table-head { padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; gap: 12px; border-bottom: 1px solid #edf2fa; }
        .table-head h3 { margin: 0; font-size: 20px; color: #253656; font-weight: 700; max-width: 220px; }
        .table-tools { display: flex; gap: 8px; align-items: center; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 10px; top: 11px; color: #9aa6bc; font-size: 12px; }
        .search-box input, .filter-select { background: #f3f6fb; border: 1px solid #e5ebf6; border-radius: 999px; height: 36px; padding: 6px 12px; font-size: 13px; }
        .search-box input { min-width: 230px; padding-left: 30px; }
        .filter-select { min-width: 110px; }
        .btn-add-service { background: #3f68b8; color: #fff; border-radius: 10px; padding: 8px 14px; font-weight: 600; }
        .btn-add-service:hover { color: #fff; }
        .service-table thead th { border-top: 0; border-bottom: 1px solid #eaf0f9; color: #8f9db4; font-size: 12px; text-transform: uppercase; }
        .service-table tbody td { border-top: 1px solid #f0f4fb; vertical-align: middle; font-size: 13px; }
        .asset-code { color: #4169b8; font-weight: 700; }
        .main-text { font-weight: 700; color: #273753; }
        .category-pill { background: #edf2fb; color: #5b72a6; border-radius: 999px; padding: 4px 10px; font-size: 11px; font-weight: 700; }
        .status-pill { border-radius: 999px; padding: 4px 10px; font-size: 11px; font-weight: 700; }
        .status-progress { background: #dce7ff; color: #496ada; }
        .status-pending { background: #ffe8cc; color: #cf7a25; }
        .status-done { background: #dff6e8; color: #2d9b60; }
        .late-text { color: #d34f4f; font-weight: 700; }
        @media (max-width: 768px) {
            .table-head { flex-direction: column; align-items: flex-start; }
            .table-tools { width: 100%; flex-wrap: wrap; }
            .search-box input { min-width: 100%; }
        }
    </style>
@stop

@section('js')
    <script>
        document.querySelectorAll('.js-delete-maintenance-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const button = form.querySelector('button[type="submit"]');
                const assetName = button?.dataset.asset || 'aset ini';

                Swal.fire({
                    title: 'Selesaikan / Hapus service?',
                    text: assetName,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop
