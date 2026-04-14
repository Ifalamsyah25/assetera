@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="input-header-wrap">
        <h1>{{ $pageTitle }}</h1>
        <p>{{ $pageSubtitle }}</p>
    </div>
@stop

@section('content')
    <div class="asset-input-page">
        <div class="input-form-card">
            <div class="input-hero-card">
                <div class="hero-left">
                    <div class="hero-icon"><i class="fas fa-truck-loading"></i></div>
                    <div>
                        <h2>Input Aset</h2>
                        <p>Lengkapi informasi aset yang akan ditambahkan ke sistem</p>
                    </div>
                </div>
            </div>

            <form action="{{ $formAction }}" method="POST" class="mt-3">
                @csrf
                @if ($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section class="group-section">
                    <h3>Informasi Utama</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="code_asset">Kode Aset</label>
                            <input type="text" class="form-control input-pill" id="code_asset" name="code_asset" value="{{ old('code_asset', $asset->code_asset) }}" placeholder="AST-2026-XXXX">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name_asset">Nama Aset</label>
                            <input type="text" class="form-control input-pill" id="name_asset" name="name_asset" value="{{ old('name_asset', $asset->name_asset) }}" placeholder="Masukkan nama aset">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="category_asset">Kategori</label>
                            <input type="text" class="form-control input-pill" id="category_asset" name="category_asset" value="{{ old('category_asset', $asset->category_asset) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="merk_asset">Merk</label>
                            <input type="text" class="form-control input-pill" id="merk_asset" name="merk_asset" value="{{ old('merk_asset', $asset->merk_asset) }}" placeholder="Contoh: Sharp, Philips, etc.">
                        </div>
                    </div>
                </section>

                <section class="group-section">
                    <h3>Detail Lokasi & Kondisi</h3>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="lokasi_asset">Lokasi</label>
                            <input type="text" class="form-control input-pill" id="lokasi_asset" name="lokasi_asset" value="{{ old('lokasi_asset', $asset->lokasi_asset) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="kondisi_asset">Kondisi</label>
                            <input type="text" class="form-control input-pill" id="kondisi_asset" name="kondisi_asset" value="{{ old('kondisi_asset', $asset->kondisi_asset) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status_asset">Status</label>
                            <select id="status_asset" name="status_asset" class="form-control input-pill">
                                <option value="available" @selected(old('status_asset', $asset->status_asset) === 'available')>Tersedia</option>
                                <option value="borrowed" @selected(old('status_asset', $asset->status_asset) === 'borrowed')>Dipinjam</option>
                                <option value="damaged" @selected(old('status_asset', $asset->status_asset) === 'damaged')>Rusak</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="group-section">
                    <h3>Informasi Tambahan</h3>
                    <div class="form-group">
                        <label for="purchase_price">Harga Pengadaan</label>
                        <input type="number" step="0.01" min="0" class="form-control input-pill" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $asset->purchase_price) }}" placeholder="Contoh: 2500000">
                    </div>
                    <div class="form-group">
                        <label for="purchase_date">Tanggal Masuk</label>
                        <input type="date" class="form-control input-pill" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', optional($asset->purchase_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_asset">Deskripsi</label>
                        <textarea id="deskripsi_asset" name="deskripsi_asset" class="form-control input-area" rows="4" placeholder="Berikan catatan tambahan mengenai aset ini...">{{ old('deskripsi_asset', $asset->deskripsi_asset) }}</textarea>
                    </div>
                </section>

                <div class="form-footer">
                    <a href="{{ route('assets.create') }}" class="btn btn-light btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-lock mr-1"></i> {{ $submitLabel }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .input-header-wrap h1 { font-size: 34px; font-weight: 700; color: #253656; margin: 0; }
        .input-header-wrap p { margin: 6px 0 0; color: #7f8ca5; }
        .asset-input-page { padding-bottom: 20px; max-width: 920px; }
        .input-form-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.08); }
        .input-hero-card { background: #f9fbff; border-radius: 14px; padding: 16px 18px; display: flex; align-items: center; }
        .hero-left { display: flex; align-items: center; gap: 14px; }
        .hero-icon { width: 46px; height: 46px; border-radius: 999px; display: grid; place-items: center; background: #dfe9ff; color: #4669c9; }
        .hero-left h2 { margin: 0; font-size: 40px; font-weight: 700; color: #253656; }
        .hero-left p { margin: 2px 0 0; color: #7f8ca5; }
        .group-section { margin-bottom: 18px; }
        .group-section h3 { margin: 0 0 12px; font-size: 33px; color: #253656; font-weight: 700; border-left: 4px solid #526eaa; padding-left: 10px; }
        .group-section label { color: #415170; font-weight: 700; font-size: 12px; }
        .input-pill { border-radius: 999px; border: 1px solid #e2e8f3; background: #f3f6fb; height: 44px; color: #2f3f5f; }
        .input-area { border-radius: 16px; border: 1px solid #e2e8f3; background: #f3f6fb; color: #2f3f5f; padding: 14px; resize: none; }
        .form-footer { border: 1px solid #edf1f7; border-radius: 12px; padding: 10px; display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
        .btn-cancel { border-radius: 999px; min-width: 90px; }
        .btn-save { border-radius: 999px; min-width: 140px; background: #3d5f98; color: #fff; font-weight: 700; }
        .btn-save:hover { color: #fff; }
        @media (max-width: 768px) {
            .input-hero-card { padding: 12px; }
            .hero-left h2 { font-size: 30px; }
        }
    </style>
@stop
