@extends('adminlte::page')

@section('title', 'Tambah Perbaikan Aset')

@section('content_header')
    <div class="repair-form-header">
        <div class="header-icon"><i class="fas fa-wrench"></i></div>
        <div>
            <h1>Tambah Perbaikan Aset</h1>
            <p>Input data perbaikan untuk aset yang sedang mengalami kerusakan</p>
        </div>
    </div>
@stop

@section('content')
    <div class="repair-form-page">
        <form action="{{ route('maintenances.store') }}" method="POST">
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

            <div class="row">
                <div class="col-lg-8">
                    <div class="repair-main-card">
                        <section class="group-section">
                            <h3>Informasi Aset</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="asset_id">Kode Aset <span class="text-danger">*</span></label>
                                    <select id="asset_id" name="asset_id" class="form-control input-pill" required>
                                        <option value="">Contoh: AST-C225</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}" @selected(old('asset_id') == $asset->id)>
                                                {{ $asset->code_asset }} - {{ $asset->name_asset }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="asset_name_preview">Nama Aset</label>
                                    <input type="text" id="asset_name_preview" name="asset_name_snapshot" class="form-control input-pill" value="{{ old('asset_name_snapshot') }}" placeholder="contoh: Rice Cooker 30L">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="category_preview">Kategori</label>
                                    <input type="text" id="category_preview" name="category_snapshot" class="form-control input-pill" value="{{ old('category_snapshot') }}" placeholder="Contoh: Peralatan Dapur">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="location_preview">Lokasi</label>
                                    <input type="text" id="location_preview" name="location_snapshot" class="form-control input-pill" value="{{ old('location_snapshot') }}" placeholder="Dapur">
                                </div>
                            </div>
                        </section>

                        <section class="group-section">
                            <h3>Detail Kerusakan</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="checkin_date">Tanggal Masuk Service <span class="text-danger">*</span></label>
                                    <input type="date" id="checkin_date" name="checkin_date" class="form-control input-pill" value="{{ old('checkin_date') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="condition_now">Kondisi Saat Ini</label>
                                    <input type="text" id="condition_now" name="current_condition" class="form-control input-pill" value="{{ old('current_condition') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="repair_description">Deskripsi Kerusakan</label>
                                <textarea id="repair_description" name="repair_description" rows="4" class="form-control input-area" placeholder="Jelaskan detail kerusakan yang terjadi...">{{ old('repair_description') }}</textarea>
                            </div>
                        </section>

                        <section class="group-section">
                            <h3>Informasi Perbaikan</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="estimate_date">Estimasi Selesai</label>
                                    <input type="date" id="estimate_date" name="estimated_completion_date" class="form-control input-pill" value="{{ old('estimated_completion_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cost">Estimasi Biaya</label>
                                    <input type="number" step="0.01" min="0" id="cost" name="cost" class="form-control input-pill" value="{{ old('cost', 0) }}">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label for="status">Status Service <span class="text-danger">*</span></label>
                                <select id="status" name="status" class="form-control input-pill" required>
                                    <option value="">Contoh: In Progress</option>
                                    <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                                    <option value="in_progress" @selected(old('status') === 'in_progress')>In Progress</option>
                                    <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                                </select>
                            </div>
                        </section>

                        <div class="form-footer">
                            <a href="{{ route('maintenances.index') }}" class="btn btn-light btn-cancel">Batal</a>
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim Permintaan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="bubble"></div>
                        <h4>Informasi Service</h4>
                        <p>Pastikan data perbaikan diisi dengan benar untuk memudahkan pelacakan status aset dan audit pemeliharaan rutin.</p>
                        <div class="tip-item"><i class="fas fa-check-circle"></i> Verifikasi vendor perbaikan secara berkala.</div>
                        <div class="tip-item"><i class="fas fa-file-invoice"></i> Simpan bukti invoice dari vendor untuk input biaya akhir.</div>
                    </div>
                    <div class="side-card">
                        <h5>Maintenance Tips</h5>
                        <p>Lakukan pengecekan berkala setiap 6 bulan untuk aset kategori Hardware guna memperpanjang usia penggunaan.</p>
                    </div>
                    <div class="side-card">
                        <h5>Under Service Summary</h5>
                        <div class="summary-line">
                            <span>Total Under Service</span>
                            <strong>{{ $assets->count() }} Aset</strong>
                        </div>
                        <div class="progress-track"><span></span></div>
                        <div class="summary-foot">
                            <small>In Progress</small>
                            <small>Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .repair-form-header { display: flex; align-items: center; gap: 12px; }
        .repair-form-header .header-icon { width: 46px; height: 46px; border-radius: 12px; display: grid; place-items: center; background: #dde8ff; color: #4a6ec8; }
        .repair-form-header h1 { margin: 0; font-size: 36px; font-weight: 700; color: #263654; }
        .repair-form-header p { margin: 2px 0 0; color: #7f8ca5; }
        .repair-form-page { padding-bottom: 20px; }
        .repair-main-card { background: #fff; border-radius: 16px; padding: 18px; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.08); }
        .group-section { margin-bottom: 18px; }
        .group-section h3 { margin: 0 0 12px; font-size: 29px; color: #253656; font-weight: 700; border-left: 4px solid #526eaa; padding-left: 10px; }
        .group-section label { color: #415170; font-weight: 700; font-size: 12px; }
        .input-pill { border-radius: 999px; border: 1px solid #e2e8f3; background: #f3f6fb; height: 44px; color: #2f3f5f; }
        .input-area { border-radius: 14px; border: 1px solid #e2e8f3; background: #f3f6fb; color: #2f3f5f; padding: 12px; resize: none; }
        .form-footer { border: 1px solid #edf1f7; border-radius: 12px; padding: 10px; display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
        .btn-cancel { border-radius: 999px; min-width: 90px; }
        .btn-save { border-radius: 999px; min-width: 160px; background: #3d5f98; color: #fff; font-weight: 700; }
        .btn-save:hover { color: #fff; }
        .info-card { position: relative; background: linear-gradient(180deg, #6f8fc4, #6484bb); color: #fff; border-radius: 16px; padding: 18px; overflow: hidden; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.12); }
        .info-card .bubble { position: absolute; width: 120px; height: 120px; border-radius: 999px; background: rgba(255, 255, 255, 0.12); top: -30px; right: -30px; }
        .info-card h4 { margin: 0 0 8px; font-size: 25px; font-weight: 700; position: relative; }
        .info-card p { margin: 0 0 10px; font-size: 13px; line-height: 1.45; position: relative; }
        .tip-item { background: rgba(255, 255, 255, 0.14); border-radius: 10px; padding: 10px; font-size: 12px; margin-top: 8px; position: relative; }
        .tip-item i { margin-right: 6px; }
        .side-card { background: #fff; border-radius: 16px; padding: 16px; margin-top: 14px; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.08); }
        .side-card h5 { margin: 0 0 8px; color: #2f3f5f; font-size: 18px; font-weight: 700; }
        .side-card p { margin: 0; color: #7888a3; font-size: 13px; }
        .summary-line { display: flex; justify-content: space-between; align-items: center; color: #52627f; font-size: 13px; margin-bottom: 8px; }
        .summary-line strong { color: #2a3c5f; }
        .progress-track { height: 6px; border-radius: 999px; background: #e9eff8; overflow: hidden; }
        .progress-track span { display: block; width: 68%; height: 100%; background: #3d5f98; }
        .summary-foot { display: flex; justify-content: space-between; margin-top: 8px; color: #8391a9; }
    </style>
@stop
