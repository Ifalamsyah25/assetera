@extends('adminlte::page')

@section('title', 'Pengadaan Aset')
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="procurement-header">
        <h1>List Pengadaan Aset</h1>
        <p>Kelola dan pantau semua permintaan aset dari staff</p>
    </div>
@stop

@section('content')
    <div class="procurement-page">
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-blue"><i class="fas fa-receipt"></i></div>
                    <p>Total Request</p>
                    <h3>{{ $summary['total'] }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-yellow"><i class="fas fa-hourglass-half"></i></div>
                    <p>Pending</p>
                    <h3>{{ $summary['borrowed'] }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-green"><i class="fas fa-check-circle"></i></div>
                    <p>Approved</p>
                    <h3>{{ $summary['available'] }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-red"><i class="fas fa-times-circle"></i></div>
                    <p>Rejected</p>
                    <h3>{{ $summary['damaged'] }}</h3>
                </div>
            </div>
        </div>

        <div class="request-card">
            <div class="request-head">
                <div>
                    <h3>List Request Aset</h3>
                    <p>Menampilkan data pengadaan terbaru bulan ini</p>
                </div>
                <div class="request-tools">
                    <form method="GET" action="{{ route('assets.create') }}" class="search-form">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari Kode atau Nama Aset...">
                        </div>
                    </form>
                    <a href="{{ route('assets.input') }}" class="btn btn-add-asset">
                        <i class="fas fa-plus mr-1"></i> Input Asset
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table procurement-table">
                    <thead>
                    <tr>
                        <th>ID Request</th>
                        <th>Nama Pemohon</th>
                        <th>Divisi</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($assets as $row)
                        @php
                            $requestId = 'REQ-'.str_pad((string) $loop->iteration, 3, '0', STR_PAD_LEFT);
                            $priority = $row->status_asset === 'damaged' ? 'High' : 'Normal';
                            $priorityClass = $priority === 'High' ? 'priority-high' : 'priority-normal';
                            $statusLabel = match ($row->status_asset) {
                                'available' => 'Approved',
                                'borrowed' => 'Pending',
                                default => 'Rejected',
                            };
                            $statusClass = match ($row->status_asset) {
                                'available' => 'status-approved',
                                'borrowed' => 'status-pending',
                                default => 'status-rejected',
                            };
                        @endphp
                        <tr>
                            <td class="text-primary font-weight-bold">{{ $requestId }}</td>
                            <td>
                                <div class="name-main">{{ auth()->user()->name ?? 'Staff' }}</div>
                                <small>Staff</small>
                            </td>
                            <td>Staff</td>
                            <td>
                                <div class="name-main">{{ $row->name_asset }}</div>
                                <small>Kategori: {{ $row->category_asset }}</small>
                            </td>
                            <td>1</td>
                            <td><span class="pill {{ $priorityClass }}">{{ $priority }}</span></td>
                            <td><span class="pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('assets.edit', $row) }}" class="btn btn-xs btn-light mr-1"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('assets.edit', $row) }}" class="btn btn-xs btn-primary mr-1"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('assets.destroy', $row) }}" method="POST" class="d-inline js-delete-asset-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" data-asset-name="{{ $row->name_asset }}" data-asset-code="{{ $row->code_asset }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data pengadaan.</td>
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
        .procurement-header h1 { font-size: 38px; font-weight: 700; color: #273753; margin: 0; }
        .procurement-header p { color: #7f8ca5; margin: 6px 0 0; }
        .stat-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 8px 22px rgba(37, 59, 102, 0.08); }
        .stat-card p { margin: 10px 0 0; color: #7c8ca7; font-size: 13px; }
        .stat-card h3 { margin: 2px 0 0; font-size: 34px; color: #1d2d4b; font-weight: 700; }
        .stat-icon { width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; }
        .bg-soft-blue { background: #dde9ff; color: #4c74d5; }
        .bg-soft-yellow { background: #fff1c8; color: #d3a521; }
        .bg-soft-green { background: #daf5e3; color: #2ba166; }
        .bg-soft-red { background: #ffe1e1; color: #d44f4f; }
        .request-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 22px rgba(37, 59, 102, 0.08); margin-top: 8px; overflow: hidden; }
        .request-head { padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; gap: 12px; border-bottom: 1px solid #eef2f9; }
        .request-head h3 { margin: 0; font-size: 30px; font-weight: 700; color: #1f3050; }
        .request-head p { margin: 2px 0 0; font-size: 13px; color: #8b98af; }
        .request-tools { display: flex; gap: 10px; align-items: center; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 10px; top: 11px; color: #9aa6bc; font-size: 12px; }
        .search-box input { background: #f3f6fb; border: 1px solid #e5ebf6; border-radius: 999px; height: 36px; min-width: 230px; padding: 6px 12px 6px 30px; font-size: 13px; }
        .btn-add-asset { background: #3f68b8; color: #fff; border-radius: 10px; padding: 8px 16px; font-weight: 600; }
        .btn-add-asset:hover { color: #fff; }
        .procurement-table thead th { border-top: 0; border-bottom: 1px solid #eaf0f9; color: #8f9db4; font-size: 12px; text-transform: uppercase; }
        .procurement-table tbody td { border-top: 1px solid #f0f4fb; vertical-align: middle; font-size: 13px; }
        .name-main { font-weight: 700; color: #273753; }
        .procurement-table small { color: #8a97ad; }
        .pill { border-radius: 999px; font-size: 11px; font-weight: 700; padding: 4px 10px; }
        .priority-high { background: #ffe6e6; color: #d54e4e; }
        .priority-normal { background: #edf2fb; color: #5870a3; }
        .status-approved { background: #dff5e8; color: #289e62; }
        .status-pending { background: #fff1cf; color: #b78800; }
        .status-rejected { background: #ffe2e2; color: #cc4c4c; }
        @media (max-width: 768px) {
            .request-head { flex-direction: column; align-items: flex-start; }
            .request-tools { width: 100%; flex-wrap: wrap; }
            .search-box input { min-width: 100%; }
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
                    title: 'Hapus data aset?',
                    text: assetCode ? `${assetCode} - ${assetName}` : assetName,
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
