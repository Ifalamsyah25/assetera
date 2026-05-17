@extends('adminlte::page')

@section('title', 'Tampilan Pengadaan Aset')

@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="procurement-header">
        <h1>Daftar Pengadaan Aset</h1>
        <p>Kelola dan pantau semua permintaan aset dari staff</p>
    </div>
@stop

@section('content')
    <x-flash-message />

    {{-- Stat Cards --}}
    <div class="row">
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="proc-stat-card">
                <div class="proc-stat-icon bg-soft-blue"><i class="fas fa-receipt"></i></div>
                <p>Total Request</p>
                <h3>{{ $summary['total'] }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="proc-stat-card">
                <div class="proc-stat-icon bg-soft-yellow"><i class="fas fa-hourglass-half"></i></div>
                <p>Pending</p>
                <h3>{{ $summary['pending'] }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="proc-stat-card">
                <div class="proc-stat-icon bg-soft-green"><i class="fas fa-check-circle"></i></div>
                <p>Approved</p>
                <h3>{{ $summary['approved'] }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-3">
            <div class="proc-stat-card">
                <div class="proc-stat-icon bg-soft-red"><i class="fas fa-times-circle"></i></div>
                <p>Rejected</p>
                <h3>{{ $summary['rejected'] }}</h3>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="request-card">
        <div class="request-head">
            <div>
                <h3>List Request Aset</h3>
                <p>Menampilkan data pengadaan terbaru bulan ini</p>
            </div>
            <div class="request-tools">
                <form method="GET" action="{{ route('asset-requests.index') }}" class="search-form d-flex align-items-center" style="gap:8px;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari Kode atau Nama Aset...">
                    </div>
                    <select name="status" class="form-control form-control-sm" style="border-radius:999px;height:36px;max-width:130px;border:1px solid #e5ebf6;background:#f3f6fb;font-size:13px;" onchange="this.form.submit()">
                        <option value="">Filter</option>
                        <option value="pending" @selected($status === 'pending')>Pending</option>
                        <option value="approved" @selected($status === 'approved')>Approved</option>
                        <option value="rejected" @selected($status === 'rejected')>Rejected</option>
                    </select>
                </form>
                @can('create', \App\Models\AssetRequest::class)
                    <a href="{{ route('asset-requests.create') }}" class="btn btn-add-asset">
                        <i class="fas fa-plus mr-1"></i> Request Asset
                    </a>
                @else
                    <a href="{{ route('asset-requests.create') }}" class="btn btn-add-asset">
                        <i class="fas fa-plus mr-1"></i> Request Asset
                    </a>
                @endcan
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
                @forelse ($requests as $req)
                    @php
                        $requestId = 'REQ-00' . str_pad((string) $req->id, 3, '0', STR_PAD_LEFT);
                        $priority = ($req->estimated_cost ?? 0) > 500000 ? 'High' : 'Normal';
                        $priorityClass = $priority === 'High' ? 'priority-high' : 'priority-normal';
                        $statusLabel = match ($req->status) {
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'pending' => 'Pending',
                            default => ucfirst($req->status),
                        };
                        $statusClass = match ($req->status) {
                            'approved' => 'status-approved',
                            'rejected' => 'status-rejected',
                            'pending' => 'status-pending',
                            default => 'status-pending',
                        };
                    @endphp
                    <tr>
                        <td class="text-primary font-weight-bold">{{ $requestId }}</td>
                        <td>
                            <div class="name-main">{{ $req->user->name ?? '—' }}</div>
                            <small>{{ ucfirst($req->user->role ?? 'Staff') }}</small>
                        </td>
                        <td>{{ ucfirst($req->user->role ?? 'Staff') }}</td>
                        <td>
                            <div class="name-main">{{ $req->item_name }}</div>
                            <small>Kategori: {{ $req->category ?? '—' }}</small>
                        </td>
                        <td>{{ $req->quantity }}</td>
                        <td><span class="pill {{ $priorityClass }}">{{ $priority }}</span></td>
                        <td><span class="pill {{ $statusClass }}"><i class="fas fa-circle mr-1" style="font-size:6px;vertical-align:middle;"></i> {{ $statusLabel }}</span></td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center" style="gap:4px;">
                                {{-- View --}}
                                <button class="btn btn-xs btn-light rounded-circle" style="width:30px;height:30px;" title="Lihat Detail" disabled>
                                    <i class="fas fa-eye text-muted"></i>
                                </button>

                                @if($req->status === 'pending')
                                    {{-- Approve --}}
                                    <button class="btn btn-xs btn-light rounded-circle" style="width:30px;height:30px;background:#dff5e8;" data-toggle="modal" data-target="#approveModal-{{ $req->id }}" title="Setujui">
                                        <i class="fas fa-check text-success"></i>
                                    </button>
                                    {{-- Reject --}}
                                    <button class="btn btn-xs btn-light rounded-circle" style="width:30px;height:30px;background:#ffe2e2;" data-toggle="modal" data-target="#rejectModal-{{ $req->id }}" title="Tolak">
                                        <i class="fas fa-times text-danger"></i>
                                    </button>

                                    <x-approval-modal :req="$req" />
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">Belum ada data pengajuan aset.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($requests->hasPages())
            <div class="proc-pagination">
                <div>Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} entries</div>
                <div>{{ $requests->withQueryString()->links() }}</div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .procurement-header h1 { font-size: 32px; font-weight: 800; color: #273753; margin: 0; }
        .procurement-header p { color: #7f8ca5; margin: 6px 0 0; font-size: 14px; }

        .proc-stat-card { background: #fff; border-radius: 16px; padding: 18px 20px; box-shadow: 0 2px 12px rgba(37, 59, 102, 0.06); transition: transform 0.2s, box-shadow 0.2s; }
        .proc-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 59, 102, 0.12); }
        .proc-stat-card p { margin: 10px 0 0; color: #7c8ca7; font-size: 13px; font-weight: 600; }
        .proc-stat-card h3 { margin: 2px 0 0; font-size: 32px; color: #1d2d4b; font-weight: 800; }
        .proc-stat-icon { width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; font-size: 16px; }
        .bg-soft-blue { background: #dde9ff; color: #4c74d5; }
        .bg-soft-yellow { background: #fff1c8; color: #d3a521; }
        .bg-soft-green { background: #daf5e3; color: #2ba166; }
        .bg-soft-red { background: #ffe1e1; color: #d44f4f; }

        .request-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(37, 59, 102, 0.06); margin-top: 8px; overflow: hidden; }
        .request-head { padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; gap: 12px; border-bottom: 1px solid #eef2f9; flex-wrap: wrap; }
        .request-head h3 { margin: 0; font-size: 18px; font-weight: 700; color: #1f3050; }
        .request-head p { margin: 2px 0 0; font-size: 13px; color: #8b98af; }
        .request-tools { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

        .search-box { position: relative; }
        .search-box i { position: absolute; left: 12px; top: 11px; color: #9aa6bc; font-size: 12px; }
        .search-box input { background: #f3f6fb; border: 1px solid #e5ebf6; border-radius: 999px; height: 36px; min-width: 220px; padding: 6px 14px 6px 32px; font-size: 13px; }

        .btn-add-asset { background: #3f68b8; color: #fff; border-radius: 10px; padding: 8px 18px; font-weight: 700; font-size: 13px; white-space: nowrap; }
        .btn-add-asset:hover { color: #fff; background: #35599e; }

        .procurement-table thead th { border-top: 0; border-bottom: 1px solid #eaf0f9; color: #8f9db4; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.04em; padding: 12px 16px; }
        .procurement-table tbody td { border-top: 1px solid #f0f4fb; vertical-align: middle; font-size: 13px; padding: 14px 16px; }
        .procurement-table tbody tr:hover { background: #f8fafd; }
        .name-main { font-weight: 700; color: #273753; }
        .procurement-table small { color: #8a97ad; font-size: 11px; }

        .pill { border-radius: 999px; font-size: 11px; font-weight: 700; padding: 4px 12px; white-space: nowrap; }
        .priority-high { background: #ffe6e6; color: #d54e4e; }
        .priority-normal { background: #edf2fb; color: #5870a3; }
        .status-approved { background: #dff5e8; color: #289e62; }
        .status-pending { background: #fff1cf; color: #b78800; }
        .status-rejected { background: #ffe2e2; color: #cc4c4c; }

        .proc-pagination { display: flex; justify-content: space-between; align-items: center; padding: 14px 24px; border-top: 1px solid #eef2f9; font-size: 13px; color: #8a96ad; flex-wrap: wrap; gap: 8px; }
        .proc-pagination .pagination { margin: 0; }
        .proc-pagination .page-link { border: 0; border-radius: 8px; margin: 0 2px; padding: 6px 12px; color: #667795; font-weight: 600; font-size: 13px; }
        .proc-pagination .page-item.active .page-link { background: #5e87cc; color: #fff; }

        @media (max-width: 768px) {
            .request-head { flex-direction: column; align-items: flex-start; }
            .request-tools { width: 100%; }
            .search-box input { min-width: 100%; }
        }
    </style>
@stop
