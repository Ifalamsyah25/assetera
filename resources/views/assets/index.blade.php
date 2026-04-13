@extends('adminlte::page')

@section('title', 'Dashboard Assets')
@section('plugins.Sweetalert2', true)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $summary['total'] }}</h3>
                    <p>Total Assets</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="{{ route('assets.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $summary['available'] }}</h3>
                    <p>Tersedia</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ route('assets.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $summary['borrowed'] }}</h3>
                    <p>Dipinjam</p>
                </div>
                <div class="icon"><i class="fas fa-handshake"></i></div>
                <a href="{{ route('transactions.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $summary['damaged'] }}</h3>
                    <p>Rusak</p>
                </div>
                <div class="icon"><i class="fas fa-triangle-exclamation"></i></div>
                <a href="{{ route('maintenances.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Recent Assets</h3>
                <div class="d-flex">
                    <a href="{{ route('assets.export', request()->query()) }}" class="btn btn-sm btn-outline-secondary mr-2">
                        <i class="fas fa-file-export mr-1"></i> Export CSV
                    </a>
                    @can('create', \App\Models\Asset::class)
                        <a href="{{ route('assets.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus mr-1"></i> Tambah Aset
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('assets.index') }}">
                <div class="form-row">
                    <div class="form-group col-md-4 mb-2">
                        <label for="search">Cari</label>
                        <input type="text" id="search" name="search" class="form-control" value="{{ $filters['search'] }}" placeholder="Kode atau nama aset">
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">Semua status</option>
                            <option value="available" @selected($filters['status'] === 'available')>Tersedia</option>
                            <option value="borrowed" @selected($filters['status'] === 'borrowed')>Dipinjam</option>
                            <option value="damaged" @selected($filters['status'] === 'damaged')>Rusak</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" class="form-control">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block mr-2">Filter</button>
                        <a href="{{ route('assets.index') }}" class="btn btn-default btn-block">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                    <th>Kode Asset</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Harga</th>
                    <th>Aktivitas</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($assets as $asset)
                    <tr>
                        <td><strong>{{ $asset->code_asset }}</strong></td>
                        <td>{{ $asset->name_asset }}</td>
                        <td>{{ $asset->category_asset }}</td>
                        <td>
                            @php
                                $badge = match ($asset->status_asset) {
                                    'available' => 'success',
                                    'borrowed' => 'warning',
                                    default => 'danger',
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $asset->status_asset)) }}</span>
                        </td>
                        <td>Rp {{ number_format((float) $asset->purchase_price, 0, ',', '.') }}</td>
                        <td>
                            <small class="text-muted">{{ $asset->transactions_count }} transaksi / {{ $asset->maintenances_count }} maintenance</small>
                        </td>
                        <td class="text-center">
                            @can('update', $asset)
                                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-xs btn-warning mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('delete', $asset)
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline js-delete-asset-form">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-xs btn-danger"
                                        data-asset-name="{{ $asset->name_asset }}"
                                        data-asset-code="{{ $asset->code_asset }}"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada aset tersimpan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
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
