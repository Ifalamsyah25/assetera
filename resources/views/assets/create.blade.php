@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{{ $pageTitle }}</h1>
            <small class="text-muted">{{ $pageSubtitle }}</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Assets</a></li>
                <li class="breadcrumb-item active">{{ $pageTitle }}</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-warning card-outline">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Form Asset</h3>
                        <a href="{{ route('assets.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ $formAction }}" method="POST">
                    @csrf
                    @if ($formMethod !== 'POST')
                        @method($formMethod)
                    @endif
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="code_asset">Kode Asset</label>
                                <input type="text" class="form-control" id="code_asset" name="code_asset" value="{{ old('code_asset', $asset->code_asset) }}" placeholder="AST-001">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name_asset">Nama Asset</label>
                                <input type="text" class="form-control" id="name_asset" name="name_asset" value="{{ old('name_asset', $asset->name_asset) }}" placeholder="Kompor Industri">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="category_asset">Kategori</label>
                                <input type="text" class="form-control" id="category_asset" name="category_asset" value="{{ old('category_asset', $asset->category_asset) }}" placeholder="Peralatan Dapur">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status_asset">Status</label>
                                <select id="status_asset" name="status_asset" class="form-control">
                                    <option value="available" @selected(old('status_asset', $asset->status_asset) === 'available')>Tersedia</option>
                                    <option value="borrowed" @selected(old('status_asset', $asset->status_asset) === 'borrowed')>Dipinjam</option>
                                    <option value="damaged" @selected(old('status_asset', $asset->status_asset) === 'damaged')>Rusak</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="purchase_date">Tanggal Pengadaan</label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', optional($asset->purchase_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="purchase_price">Harga Pengadaan</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $asset->purchase_price) }}" placeholder="2500000">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $summary['total'] }}</h3>
                    <p>Total Assets</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <div class="small-box-footer">Semua aset terdaftar</div>
            </div>
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $summary['available'] }}</h3>
                    <p>Tersedia</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <div class="small-box-footer">Siap digunakan</div>
            </div>
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $summary['borrowed'] }}</h3>
                    <p>Dipinjam</p>
                </div>
                <div class="icon"><i class="fas fa-handshake"></i></div>
                <div class="small-box-footer">Sedang digunakan</div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .small-box-footer {
            background: rgba(0, 0, 0, 0.08);
        }
    </style>
@stop
