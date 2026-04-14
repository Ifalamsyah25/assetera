@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@endsection

@section('plugins.Sweetalert2', true)

@section('content')
    @php
        $statusLabel = static fn (?string $s) => match ($s) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'damaged' => 'Rusak',
            default => ucfirst(str_replace('_', ' ', (string) $s)),
        };
    @endphp

    <div class="assetera-hero">
        <div class="assetera-hero-text">
            <span class="assetera-hero-watermark" aria-hidden="true">Welcome</span>
            <p class="assetera-hero-greet">Hi, {{ auth()->user()->name }}</p>
            <p class="assetera-hero-sub">
                Your activity will be updated here. Click on the name section to set your configuration.
            </p>
        </div>
        <div class="assetera-hero-art" aria-hidden="true">
            <svg viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg" class="w-100">
                <ellipse cx="100" cy="140" rx="70" ry="12" fill="rgba(118,149,197,0.15)"/>
                <rect x="45" y="35" width="110" height="95" rx="12" fill="#fff" stroke="rgba(118,149,197,0.35)" stroke-width="2"/>
                <rect x="55" y="48" width="90" height="12" rx="3" fill="rgba(118,149,197,0.25)"/>
                <rect x="55" y="68" width="40" height="8" rx="2" fill="rgba(118,149,197,0.15)"/>
                <rect x="100" y="68" width="35" height="8" rx="2" fill="rgba(118,149,197,0.15)"/>
                <circle cx="100" cy="105" r="22" fill="rgba(118,149,197,0.2)"/>
                <path d="M100 92c-6 0-11 5-11 11v8h22v-8c0-6-5-11-11-11z" fill="#7695c5"/>
                <rect x="92" y="112" width="16" height="18" rx="4" fill="#7695c5"/>
            </svg>
        </div>
    </div>

    <div class="row assetera-stat-row">
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon blue"><i class="fas fa-cube"></i></div>
                <div>
                    <h3>{{ $summary['total'] }}</h3>
                    <p>Total Asset</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon green"><i class="fas fa-check"></i></div>
                <div>
                    <h3>{{ $summary['available'] }}</h3>
                    <p>Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon amber"><i class="fas fa-clipboard-list"></i></div>
                <div>
                    <h3>{{ $summary['borrowed'] }}</h3>
                    <p>Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="assetera-stat-card">
                <div class="assetera-stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <h3>{{ $summary['damaged'] }}</h3>
                    <p>Rusak</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card assetera-card border-0">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h3 class="card-title mb-0">Recent Asset</h3>
            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0" style="gap: 0.5rem;">
                <a href="{{ route('assets.export', request()->query()) }}" class="btn btn-sm text-white font-weight-bold px-3 rounded-pill" style="background:#22c55e;">
                    <i class="fas fa-download mr-1"></i> Export CSV
                </a>
                @can('create', \App\Models\Asset::class)
                    <a href="{{ route('assets.create') }}" class="btn btn-sm text-white font-weight-bold px-3 rounded-pill" style="background:#2f4a6e;">
                        <i class="fas fa-plus mr-1"></i> Tambah Asset
                    </a>
                @endcan
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="assetera-toolbar-filters">
            <input type="text" name="search" class="form-control flex-grow-1" style="min-width:180px;max-width:320px;"
                   value="{{ $filters['search'] ?? '' }}" placeholder="Search asset">
            <button type="button" class="btn btn-filter btn-sm px-3" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <div class="collapse w-100" id="filterCollapse">
                <div class="row pt-2">
                    <div class="form-group col-md-4 mb-2">
                        <label class="small text-muted mb-1">Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua status</option>
                            <option value="available" @selected(($filters['status'] ?? '') === 'available')>Tersedia</option>
                            <option value="borrowed" @selected(($filters['status'] ?? '') === 'borrowed')>Dipinjam</option>
                            <option value="damaged" @selected(($filters['status'] ?? '') === 'damaged')>Rusak</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 mb-2">
                        <label class="small text-muted mb-1">Kategori</label>
                        <select name="category" class="form-control">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm text-white font-weight-bold mr-2 rounded-pill px-3" style="background:#7695c5;">Terapkan</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-default rounded-pill">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="assetera-table-wrap">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Kode Aset</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Location</th>
                    <th>Condition</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($assets as $asset)
                    <tr>
                        <td><strong>{{ $asset->code_asset }}</strong></td>
                        <td>{{ $asset->name_asset }}</td>
                        <td><span class="assetera-pill assetera-pill-cat">{{ $asset->category_asset }}</span></td>
                        <td>
                            <span class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i>{{ $asset->lokasi_asset ?? '—' }}</span>
                        </td>
                        <td>
                            {{ $asset->kondisi_asset ?? 'Baik' }}
                        </td>
                        <td>
                            @php
                                $st = $asset->status_asset;
                                $pillClass = $st === 'available' ? 'assetera-pill-status-ok' : ($st === 'borrowed' ? 'assetera-pill-status-warn' : 'assetera-pill-status-bad');
                            @endphp
                            <span class="assetera-pill {{ $pillClass }}">{{ $statusLabel($st) }}</span>
                        </td>
                        <td class="text-right">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-link btn-sm text-muted px-2" type="button" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                    @can('update', $asset)
                                        <a class="dropdown-item" href="{{ route('assets.edit', $asset) }}"><i class="fas fa-edit mr-2 text-muted"></i> Edit</a>
                                    @endcan
                                    @can('delete', $asset)
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="js-delete-asset-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                    data-asset-name="{{ $asset->name_asset }}"
                                                    data-asset-code="{{ $asset->code_asset }}">
                                                <i class="fas fa-trash mr-2"></i> Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada aset tersimpan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($assets->hasPages())
            <div class="assetera-pagination-bar">
                <div>
                    Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} entries
                </div>
                <div>
                    {{ $assets->withQueryString()->links() }}
                </div>
            </div>
        @endif
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
