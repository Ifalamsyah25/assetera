@extends('adminlte::page')

@section('title', 'Input Stock Opname')

@section('content_header')
    <div class="input-header-wrap">
        <h1>Input Stock Opname</h1>
        <p>Catat hasil pencocokan kuantitas fisik aset dengan sistem</p>
    </div>
@stop

@section('content')
    <div class="asset-input-page">
        <div class="input-form-card">
            <div class="input-hero-card">
                <div class="hero-left">
                    <div class="hero-icon"><i class="fas fa-boxes"></i></div>
                    <div>
                        <h2>Stock Opname</h2>
                        <p>Lengkapi formulir pemeriksaan stok di bawah ini</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('stock-opnames.store') }}" method="POST" class="mt-3">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section class="group-section mt-4">
                    <h3>Pilih & Lokasi Aset</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="asset_id">Aset <span class="text-danger">*</span></label>
                            <select id="asset_id" name="asset_id" class="form-control input-pill select2 @error('asset_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Aset --</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" data-location="{{ $asset->lokasi_asset }}">
                                        {{ $asset->code_asset }} - {{ $asset->name_asset }} 
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lokasi">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" id="lokasi" name="lokasi" class="form-control input-pill @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}" placeholder="Masukkan lokasi (misal: SPPG Garuda)" required>
                            @error('lokasi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="group-section">
                    <h3>Kuantitas & Periode</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="qty_sistem">Kuantitas Sistem <span class="text-danger">*</span></label>
                            <input type="number" id="qty_sistem" name="qty_sistem" class="form-control input-pill @error('qty_sistem') is-invalid @enderror" value="{{ old('qty_sistem', 1) }}" min="0" required>
                            @error('qty_sistem')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qty_aktual">Kuantitas Aktual <span class="text-danger">*</span></label>
                            <input type="number" id="qty_aktual" name="qty_aktual" class="form-control input-pill @error('qty_aktual') is-invalid @enderror" value="{{ old('qty_aktual', 1) }}" min="0" required>
                            @error('qty_aktual')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="periode">Periode Audit <span class="text-danger">*</span></label>
                            <input type="text" id="periode" name="periode" class="form-control input-pill @error('periode') is-invalid @enderror" value="{{ old('periode', 'Juni 2026') }}" placeholder="Contoh: Juni 2026" required>
                            @error('periode')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_audit">Tanggal Audit <span class="text-danger">*</span></label>
                            <input type="date" id="tanggal_audit" name="tanggal_audit" class="form-control input-pill @error('tanggal_audit') is-invalid @enderror" value="{{ old('tanggal_audit', date('Y-m-d')) }}" required>
                            @error('tanggal_audit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="group-section">
                    <h3>Catatan & Keterangan</h3>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-control input-area @error('keterangan') is-invalid @enderror" rows="4" placeholder="Masukkan catatan temuan audit fisik di lapangan (misal: barang rusak ringan, hilang, dll)...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </section>

                <div class="form-footer">
                    <a href="{{ route('stock-opnames.index') }}" class="btn btn-light btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Simpan Hasil
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .input-header-wrap { max-width: 760px; margin: 0 auto 15px auto; }
        .input-header-wrap h1 { font-size: 34px; font-weight: 700; color: #253656; margin: 0; }
        .input-header-wrap p { margin: 6px 0 0; color: #7f8ca5; }
        .asset-input-page { padding-bottom: 20px; max-width: 760px; margin: 0 auto; }
        .input-form-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.08); }
        .input-hero-card { background: #f9fbff; border-radius: 14px; padding: 16px 18px; display: flex; align-items: center; }
        .hero-left { display: flex; align-items: center; gap: 14px; }
        .hero-icon { width: 46px; height: 46px; border-radius: 999px; display: grid; place-items: center; background: #dfe9ff; color: #4669c9; }
        .hero-left h2 { margin: 0; font-size: 24px; font-weight: 700; color: #253656; }
        .hero-left p { margin: 2px 0 0; color: #7f8ca5; }
        .group-section { margin-bottom: 18px; }
        .group-section h3 { margin: 0 0 12px; font-size: 18px; color: #253656; font-weight: 700; border-left: 4px solid #6b87b5; padding-left: 10px; }
        .group-section label { color: #415170; font-weight: 700; font-size: 12px; }
        .input-pill { border-radius: 999px; border: 1px solid #e2e8f3; background: #f3f6fb; height: 44px; color: #2f3f5f; }
        .input-area { border-radius: 16px; border: 1px solid #e2e8f3; background: #f3f6fb; color: #2f3f5f; padding: 14px; resize: none; }
        .form-footer { border: 1px solid #edf1f7; border-radius: 12px; padding: 10px; display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
        .btn-cancel { border-radius: 999px; min-width: 90px; }
        .btn-save { border-radius: 999px; min-width: 140px; background: #6b87b5; color: #fff; font-weight: 700; border: none; }
        .btn-save:hover { background: #5a759e; color: #fff; }
        @media (max-width: 768px) {
            .input-hero-card { padding: 12px; }
            .hero-left h2 { font-size: 20px; }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Handle change on asset selection
            $('#asset_id').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const location = selectedOption.attr('data-location') || '';
                $('#lokasi').val(location);
            });
        });
    </script>
@stop
