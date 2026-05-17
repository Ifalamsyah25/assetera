@extends('adminlte::page')

@section('title', 'List Peminjaman Aset')

@section('plugins.Sweetalert2', true)

@section('content_header')
@endsection

@section('content')
    <x-flash-message />

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Validation Error:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Hero --}}
    <div class="assetera-hero">
        <div class="assetera-hero-text">
            <div class="d-flex align-items-center mb-2">
                <div class="assetera-stat-icon blue mr-3" style="width:52px;height:52px;font-size:22px;">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <h1 class="mb-0" style="font-size:28px;font-weight:800;color:#2f3b52;">Peminjaman Asset</h1>
                    <p class="mb-0" style="color:#6b7fa5;font-size:14px;">Kelola dan pantau aset yang sedang dipinjam oleh pengguna</p>
                </div>
            </div>
        </div>
        @can('create', \App\Models\Transaction::class)
            <a href="{{ route('transactions.create') }}" class="btn btn-save" style="border-radius:999px;min-width:180px;background:#3d5f98;color:#fff;font-weight:700;font-size:15px;padding:12px 24px;">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Peminjam
            </a>
        @endcan
    </div>

    {{-- Summary Cards --}}
    <div class="row assetera-stat-row">
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon blue"><i class="fas fa-clipboard-list"></i></div>
                <div>
                    <h3>{{ $summary['total'] }}</h3>
                    <p>Total Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon amber"><i class="fas fa-desktop"></i></div>
                <div>
                    <h3>{{ $summary['active'] }}</h3>
                    <p>Sedang Dipakai</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h3>{{ $summary['returned'] }}</h3>
                    <p>Sudah Dikembalikan</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon red"><i class="fas fa-exclamation-circle"></i></div>
                <div>
                    <h3>{{ $summary['late'] }}</h3>
                    <p>Terlambat</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card assetera-card border-0">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h3 class="card-title mb-0">Daftar Peminjaman Asset</h3>
        </div>

        <form method="GET" action="{{ route('transactions.index') }}" class="assetera-toolbar-filters">
            <input type="text" name="search" class="form-control flex-grow-1" style="min-width:180px;max-width:320px;"
                   value="{{ $filters['search'] ?? '' }}" placeholder="Cari Kode atau Nama Aset...">
            <select name="status" class="form-control" style="max-width:160px;">
                <option value="">Filter</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Sedang Dipinjam</option>
                <option value="returned" @selected(($filters['status'] ?? '') === 'returned')>Dikembalikan</option>
                <option value="late" @selected(($filters['status'] ?? '') === 'late')>Terlambat</option>
            </select>
            <button type="submit" class="btn btn-sm text-white font-weight-bold rounded-pill px-3" style="background:#7695c5;"><i class="fas fa-search mr-1"></i> Cari</button>
            @if(($filters['search'] ?? '') !== '' || ($filters['status'] ?? '') !== '')
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-default rounded-pill">Reset</a>
            @endif
        </form>

        <div class="assetera-table-wrap table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID Peminjaman</th>
                    <th>Peminjam</th>
                    <th>Asset</th>
                    <th>Kategori</th>
                    <th>Timeline</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($transactions as $transaction)
                    @php
                        $isReturned = $transaction->returned_at !== null;
                        $isLate = !$isReturned && $transaction->borrowed_at->lt(now()->subDays(7));
                        $statusText = $isReturned ? 'Dikembalikan' : ($isLate ? 'Terlambat' : ($transaction->returned_at === null ? 'Dipinjam' : 'Menunggu'));
                        $statusClass = $isReturned ? 'assetera-pill-status-ok' : ($isLate ? 'assetera-pill-status-bad' : 'assetera-pill-status-warn');
                    @endphp
                    <tr>
                        <td>
                            <strong>#LM-{{ $transaction->borrowed_at->format('Y') }}-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</strong>
                        </td>
                        <td>
                            <div><strong>{{ $transaction->user->name ?? '—' }}</strong></div>
                            <small class="text-muted">{{ $transaction->division ?? $transaction->user->username ?? '' }}</small>
                        </td>
                        <td>
                            <div><strong>{{ $transaction->asset->name_asset ?? '—' }}</strong></div>
                            <small class="text-muted">SN: {{ $transaction->asset->code_asset ?? '—' }}</small>
                        </td>
                        <td>
                            <span class="assetera-pill assetera-pill-cat">{{ $transaction->asset_category_snapshot ?? $transaction->asset->category_asset ?? '—' }}</span>
                        </td>
                        <td>
                            <div class="small">{{ $transaction->borrowed_at->format('d M Y') }}</div>
                            <div class="small {{ $isLate ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                {{ $transaction->returned_at ? $transaction->returned_at->format('d M Y') : ($isLate ? $transaction->borrowed_at->addDays(7)->format('d M Y') : '—') }}
                            </div>
                        </td>
                        <td>
                            <span class="assetera-pill {{ $statusClass }}">
                                <i class="fas fa-circle mr-1" style="font-size:6px;vertical-align:middle;"></i> {{ $statusText }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="d-inline-flex" style="gap:4px;">
                                {{-- View/Edit --}}
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-light" style="border-radius:10px;width:34px;height:34px;display:inline-grid;place-items:center;" title="Lihat Detail">
                                    <i class="fas fa-eye text-muted"></i>
                                </a>
                                @can('update', $transaction)
                                    {{-- Return/Approve --}}
                                    @if(!$isReturned)
                                        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="d-inline js-return-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="user_id" value="{{ $transaction->user_id }}">
                                            <input type="hidden" name="asset_id" value="{{ $transaction->asset_id }}">
                                            <input type="hidden" name="borrowed_at" value="{{ $transaction->borrowed_at->format('Y-m-d') }}">
                                            <input type="hidden" name="returned_at" value="{{ now()->format('Y-m-d') }}">
                                            <input type="hidden" name="cost" value="{{ $transaction->cost ?? 0 }}">
                                            <button type="submit" class="btn btn-sm btn-light" style="border-radius:10px;width:34px;height:34px;display:inline-grid;place-items:center;" title="Kembalikan" data-asset-name="{{ $transaction->asset->name_asset ?? '' }}">
                                                <i class="fas fa-check text-success"></i>
                                            </button>
                                        </form>
                                    @endif
                                    {{-- Photo placeholder --}}
                                    <button class="btn btn-sm btn-light" style="border-radius:10px;width:34px;height:34px;display:inline-grid;place-items:center;" title="Foto" disabled>
                                        <i class="fas fa-camera text-muted"></i>
                                    </button>
                                    {{-- Edit --}}
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-light" style="border-radius:10px;width:34px;height:34px;display:inline-grid;place-items:center;" title="Edit">
                                        <i class="fas fa-pencil-alt text-muted"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada data peminjaman aset.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
            <div class="assetera-pagination-bar">
                <div>Menampilkan {{ $transactions->firstItem() }}-{{ $transactions->lastItem() }} dari {{ $transactions->total() }} Peminjaman</div>
                <div>{{ $transactions->withQueryString()->links() }}</div>
            </div>
        @else
            <div class="assetera-pagination-bar">
                <div>Menampilkan {{ $transactions->count() }} Peminjaman</div>
            </div>
        @endif
    </div>
@stop

@section('js')
    <script>
        document.querySelectorAll('.js-return-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const btn = form.querySelector('button[type="submit"]');
                const assetName = btn?.dataset.assetName || 'aset ini';

                Swal.fire({
                    title: 'Kembalikan aset?',
                    text: `Tandai "${assetName}" sebagai dikembalikan hari ini?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, kembalikan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop