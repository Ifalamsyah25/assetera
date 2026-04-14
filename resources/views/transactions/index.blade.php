@extends('adminlte::page')

@section('title', 'Peminjaman Asset')
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="borrow-header-card">
        <div class="header-left">
            <div class="header-icon"><i class="fas fa-file-import"></i></div>
            <div>
                <h1>Peminjaman Asset</h1>
                <p>Kelola dan pantau aset yang sedang dipinjam oleh pengguna</p>
            </div>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn btn-add-borrow">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Peminjam
        </a>
    </div>
@stop

@section('content')
    <div class="borrow-page">
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-blue"><i class="fas fa-poll"></i></div>
                    <p>Total Dipinjam</p>
                    <h3>{{ $summary['total'] }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-indigo"><i class="fas fa-laptop"></i></div>
                    <p>Sedang Dipakai</p>
                    <h3>{{ $summary['active'] }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-purple"><i class="fas fa-check-double"></i></div>
                    <p>Sudah Dikembalikan</p>
                    <h3>{{ $summary['returned'] }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-soft-red"><i class="fas fa-history"></i></div>
                    <p>Terlambat</p>
                    <h3>{{ $summary['late'] }}</h3>
                </div>
            </div>
        </div>

        <div class="borrow-table-card">
            <div class="table-head">
                <h3>Daftar Peminjaman Asset</h3>
                <form method="GET" action="{{ route('transactions.index') }}" class="table-tools">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari Kode atau Nama Aset...">
                    </div>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Filter</option>
                        <option value="active" @selected($filters['status'] === 'active')>Dipinjam</option>
                        <option value="returned" @selected($filters['status'] === 'returned')>Dikembalikan</option>
                        <option value="late" @selected($filters['status'] === 'late')>Terlambat</option>
                    </select>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table borrow-table">
                    <thead>
                    <tr>
                        <th>ID Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Asset</th>
                        <th>Kategori</th>
                        <th>Timeline</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($transactions as $transaction)
                        @php
                            $isLate = $transaction->returned_at === null && $transaction->borrowed_at->lt(now()->subDays(7));
                            $statusLabel = $transaction->returned_at ? 'Dikembalikan' : ($isLate ? 'Terlambat' : 'Dipinjam');
                            $statusClass = $transaction->returned_at ? 'status-returned' : ($isLate ? 'status-late' : 'status-borrowed');
                            $loanCode = '#LM-'.now()->format('Y').'-'.str_pad((string) $loop->iteration, 3, '0', STR_PAD_LEFT);
                        @endphp
                        <tr>
                            <td><span class="loan-code">{{ $loanCode }}</span></td>
                            <td>
                                <div class="main-text">{{ $transaction->user->name }}</div>
                                <small>{{ ucfirst($transaction->user->role ?? 'staff') }}</small>
                            </td>
                            <td>
                                <div class="main-text">{{ $transaction->asset->name_asset }}</div>
                                <small>SN: {{ $transaction->asset->code_asset }}</small>
                            </td>
                            <td><span class="category-pill">{{ $transaction->asset->category_asset ?? 'Elektronik' }}</span></td>
                            <td>
                                <ul class="timeline-list">
                                    <li>{{ $transaction->borrowed_at->format('d M Y') }}</li>
                                    <li class="{{ $isLate ? 'late-date' : '' }}">{{ $transaction->returned_at?->format('d M Y') ?? '-' }}</li>
                                </ul>
                            </td>
                            <td><span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-xs btn-light mr-1"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-xs btn-secondary mr-1"><i class="fas fa-calendar-day"></i></a>
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-xs btn-outline-secondary"><i class="fas fa-pen"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data peminjaman.</td>
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
        .borrow-header-card { background: #fff; border-radius: 16px; padding: 16px 18px; box-shadow: 0 8px 22px rgba(37, 59, 102, 0.08); display: flex; justify-content: space-between; align-items: center; }
        .header-left { display: flex; align-items: center; gap: 12px; }
        .header-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; background: #dde8ff; color: #4a6ec8; }
        .borrow-header-card h1 { margin: 0; font-size: 36px; color: #263654; font-weight: 700; }
        .borrow-header-card p { margin: 2px 0 0; color: #7f8ca5; }
        .btn-add-borrow { background: #3f68b8; color: #fff; border-radius: 11px; padding: 10px 16px; font-weight: 700; }
        .btn-add-borrow:hover { color: #fff; }
        .stat-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 8px 22px rgba(37, 59, 102, 0.08); }
        .stat-icon { width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; }
        .stat-card p { margin: 10px 0 0; color: #7c8ca7; font-size: 13px; }
        .stat-card h3 { margin: 2px 0 0; font-size: 34px; color: #1d2d4b; font-weight: 700; }
        .bg-soft-blue { background: #dde9ff; color: #4c74d5; }
        .bg-soft-indigo { background: #e6ecff; color: #4c66bf; }
        .bg-soft-purple { background: #eee4ff; color: #7658bc; }
        .bg-soft-red { background: #ffe1e1; color: #d44f4f; }
        .borrow-table-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 22px rgba(37, 59, 102, 0.08); overflow: hidden; }
        .table-head { padding: 14px 18px; display: flex; justify-content: space-between; gap: 12px; align-items: center; border-bottom: 1px solid #edf2fa; }
        .table-head h3 { margin: 0; font-size: 20px; color: #253656; font-weight: 700; }
        .table-tools { display: flex; gap: 8px; align-items: center; }
        .search-box { position: relative; }
        .search-box i { position: absolute; left: 10px; top: 11px; color: #9aa6bc; font-size: 12px; }
        .search-box input, .filter-select { background: #f3f6fb; border: 1px solid #e5ebf6; border-radius: 999px; height: 36px; padding: 6px 12px; font-size: 13px; }
        .search-box input { min-width: 250px; padding-left: 30px; }
        .filter-select { min-width: 110px; }
        .borrow-table thead th { border-top: 0; border-bottom: 1px solid #eaf0f9; color: #8f9db4; font-size: 12px; text-transform: uppercase; }
        .borrow-table tbody td { border-top: 1px solid #f0f4fb; vertical-align: middle; font-size: 13px; }
        .loan-code { color: #4169b8; font-weight: 700; }
        .main-text { font-weight: 700; color: #273753; }
        .borrow-table small { color: #8a97ad; }
        .category-pill { background: #edf2fb; color: #5b72a6; border-radius: 999px; padding: 4px 10px; font-size: 11px; font-weight: 700; }
        .timeline-list { list-style: none; padding: 0; margin: 0; }
        .timeline-list li { position: relative; padding-left: 12px; font-size: 12px; color: #5c6f93; line-height: 1.5; }
        .timeline-list li::before { content: ""; width: 4px; height: 4px; border-radius: 999px; background: #5f7fc0; position: absolute; left: 0; top: 8px; }
        .timeline-list .late-date { color: #cf4c4c; font-weight: 700; }
        .timeline-list .late-date::before { background: #cf4c4c; }
        .status-pill { border-radius: 999px; padding: 4px 10px; font-size: 11px; font-weight: 700; }
        .status-borrowed { background: #dce7ff; color: #496ada; }
        .status-returned { background: #e3f6eb; color: #2c9f64; }
        .status-late { background: #ffe4e4; color: #d35050; }
        @media (max-width: 768px) {
            .borrow-header-card { flex-direction: column; align-items: flex-start; gap: 10px; }
            .table-head { flex-direction: column; align-items: flex-start; }
            .table-tools { width: 100%; flex-wrap: wrap; }
            .search-box input { min-width: 100%; }
        }
    </style>
@stop
