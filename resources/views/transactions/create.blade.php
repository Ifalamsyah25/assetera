@extends('adminlte::page')

@section('title', 'Form Peminjaman Asset')

@section('content_header')
    <div class="loan-form-header">
        <h1>Form Peminjaman Asset</h1>
        <p>Isi data peminjaman untuk mendistribusikan asset kepada pengguna</p>
    </div>
@stop

@section('content')
    <div class="loan-form-page">
        <form action="{{ route('transactions.store') }}" method="POST">
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

            <div class="loan-form-card">
                <div class="row">
                    <div class="col-md-6">
                        <section class="group-section">
                            <h3><i class="fas fa-user mr-2"></i>Data Peminjam</h3>
                            <div class="form-group">
                                <label for="user_id">Nama Peminjam</label>
                                <select id="user_id" name="user_id" class="form-control input-pill">
                                    <option value="">Pilih user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="employee_id">ID Karyawan</label>
                                <input type="text" id="employee_id" name="employee_id" class="form-control input-pill" value="{{ old('employee_id') }}" placeholder="Contoh: MBG0112026001">
                            </div>
                            <div class="form-group mb-0">
                                <label for="division">Divisi</label>
                                <input type="text" id="division" name="division" class="form-control input-pill" value="{{ old('division') }}">
                            </div>
                        </section>
                    </div>

                    <div class="col-md-6">
                        <section class="group-section">
                            <h3><i class="fas fa-laptop-house mr-2"></i>Data Asset</h3>
                            <div class="form-group">
                                <label for="asset_id">Pilih Asset</label>
                                <select id="asset_id" name="asset_id" class="form-control input-pill">
                                    <option value="">Contoh: Blender</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}" @selected(old('asset_id') == $asset->id)>
                                            {{ $asset->name_asset }} ({{ $asset->code_asset }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="asset_code_preview">Kode Asset</label>
                                    <input type="text" id="asset_code_preview" name="asset_code_snapshot" class="form-control input-pill" value="{{ old('asset_code_snapshot') }}" placeholder="AST-PD-001">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="category_preview">Kategori</label>
                                    <input type="text" id="category_preview" name="asset_category_snapshot" class="form-control input-pill" value="{{ old('asset_category_snapshot') }}" placeholder="Peralatan Dapur">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label for="location_preview">Lokasi Asset</label>
                                <input type="text" id="location_preview" name="asset_location_snapshot" class="form-control input-pill" value="{{ old('asset_location_snapshot') }}" placeholder="Dapur Jagakarsa">
                            </div>
                        </section>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <section class="group-section">
                            <h3><i class="far fa-calendar-alt mr-2"></i>Detail Peminjaman</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="borrowed_at">Tanggal Pinjam</label>
                                    <input type="date" id="borrowed_at" name="borrowed_at" class="form-control input-pill" value="{{ old('borrowed_at') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="returned_at">Tanggal Kembali</label>
                                    <input type="date" id="returned_at" name="returned_at" class="form-control input-pill" value="{{ old('returned_at') }}">
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-6">
                        <section class="group-section">
                            <h3><i class="far fa-file-alt mr-2"></i>Kondisi & Catatan</h3>
                            <div class="form-group mb-0">
                                <label for="condition_note">Kondisi Awal Asset</label>
                                <input type="text" id="condition_note" name="condition_note" class="form-control input-pill" value="{{ old('condition_note') }}" placeholder="Contoh: Baik">
                            </div>
                        </section>
                    </div>
                </div>

                <div class="extra-panel">
                    <div class="upload-card">
                        <label>Foto Asset (Serah Terima)</label>
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Upload Image</span>
                        </div>
                    </div>
                    <div class="check-card">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="inspection_confirm" name="inspection_confirmed" value="1" @checked(old('inspection_confirmed'))>
                            <label for="inspection_confirm" class="custom-control-label">
                                <strong>Konfirmasi Pemeriksaan</strong>
                                <small>Saya menyatakan bahwa asset sudah diperiksa kondisi fisiknya dan berfungsi normal sebelum dipinjamkan.</small>
                            </label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="cost" value="{{ old('cost', 0) }}">

                <div class="form-footer">
                    <a href="{{ route('transactions.index') }}" class="btn btn-light btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-lock mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .loan-form-header h1 { font-size: 40px; font-weight: 700; color: #1f2c44; margin: 0; }
        .loan-form-header p { margin: 6px 0 0; color: #7f8ba1; }
        .loan-form-page { max-width: 1100px; padding-bottom: 20px; }
        .loan-form-card { background: #fff; border-radius: 16px; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.08); padding: 20px; }
        .group-section { background: #fff; border-radius: 12px; padding: 8px 8px 12px; }
        .group-section h3 { margin: 0 0 12px; color: #273753; font-size: 30px; font-weight: 700; display: flex; align-items: center; }
        .group-section h3 i { color: #5a78b4; background: #eaf0fb; border-radius: 8px; width: 24px; height: 24px; display: inline-grid; place-items: center; font-size: 12px; }
        .group-section label { font-size: 12px; color: #485776; font-weight: 700; }
        .input-pill { height: 44px; border-radius: 999px; border: 1px solid #e2e8f3; background: #f3f6fb; color: #2f3f5f; }
        .extra-panel { margin-top: 12px; background: #f8fafe; border: 1px solid #edf2fa; border-radius: 14px; padding: 16px; display: flex; gap: 16px; align-items: center; }
        .upload-card { width: 320px; }
        .upload-card label { display: block; font-size: 12px; color: #556480; font-weight: 700; margin-bottom: 8px; }
        .upload-placeholder { height: 130px; border: 2px solid #b8c6dc; border-radius: 18px; display: grid; place-items: center; color: #7e8fae; text-align: center; }
        .upload-placeholder i { font-size: 20px; display: block; margin-bottom: 5px; }
        .upload-placeholder span { font-size: 13px; font-weight: 700; }
        .check-card { flex: 1; background: #fff; border: 1px solid #edf2fa; border-radius: 14px; padding: 14px; }
        .custom-control-label strong { display: block; color: #2f3d57; font-size: 15px; margin-bottom: 4px; }
        .custom-control-label small { display: block; color: #7d8ca7; font-size: 13px; line-height: 1.35; }
        .form-footer { margin-top: 14px; border: 1px solid #edf1f7; border-radius: 12px; padding: 10px; display: flex; justify-content: flex-end; gap: 10px; }
        .btn-cancel { border-radius: 999px; min-width: 90px; }
        .btn-save { border-radius: 999px; min-width: 140px; background: #3d5f98; color: #fff; font-weight: 700; }
        .btn-save:hover { color: #fff; }
        @media (max-width: 992px) {
            .extra-panel { flex-direction: column; align-items: stretch; }
            .upload-card { width: 100%; }
        }
    </style>
@stop
