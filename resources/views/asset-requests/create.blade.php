@extends('adminlte::page')

@section('title', 'Pengadaan / Request Aset Baru')

@section('content_header')
@endsection

@section('content')
    <x-flash-message />

    {{-- Hero --}}
    <div class="req-hero">
        <div class="d-flex align-items-center flex-grow-1">
            <div class="req-hero-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="ml-3">
                <h1>Pengadaan / Request Aset Baru</h1>
                <p>Ajukan permintaan aset baru untuk kebutuhan operasional perusahaan.</p>
            </div>
        </div>
        <div>
            <span class="pill status-pending" style="font-size:13px;padding:6px 16px;">
                <i class="fas fa-circle mr-1" style="font-size:6px;vertical-align:middle;"></i> Menunggu persetujuan
            </span>
        </div>
    </div>

    <form action="{{ route('asset-requests.store') }}" method="POST">
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
            {{-- Left: Form --}}
            <div class="col-lg-7">
                <div class="req-form-card">
                    {{-- Section 1: Informasi Pemohon --}}
                    <div class="req-section">
                        <h3>
                            <span class="req-step-num">01</span>
                            Informasi Pemohon
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Pemohon</label>
                                    <input type="text" class="form-control req-input" value="{{ auth()->user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Pengajuan</label>
                                    <input type="text" class="form-control req-input" value="{{ now()->translatedFormat('d F Y') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Detail Permintaan --}}
                    <div class="req-section mt-3">
                        <h3>
                            <span class="req-step-num">02</span>
                            Detail Permintaan Aset
                        </h3>
                        
                        <div class="form-group">
                            <label>Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" class="form-control req-input @error('item_name') is-invalid @enderror" value="{{ old('item_name') }}" placeholder="Contoh: Freezer" required>
                            @error('item_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="row">
                            {{-- Kolom Kategori (Sudah Diperbaiki) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Kategori Aset <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control req-input @error('category') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Elektronik" {{ old('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                        <option value="Furnitur" {{ old('category') == 'Furnitur' ? 'selected' : '' }}>Furnitur</option>
                                        <option value="Kendaraan" {{ old('category') == 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                                    </select>
                                    @error('category') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            {{-- Kolom Jumlah --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control req-input @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                                    @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- Footer --}}
                <div class="req-form-footer mt-3">
                    <a href="{{ route('asset-requests.index') }}" class="btn btn-light req-btn-cancel">Batal</a>
                    <button type="submit" class="btn req-btn-submit">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Permintaan
                    </button>
                </div>
            </div>

            {{-- Right: Info Panel --}}
            <div class="col-lg-5">
                <div class="req-info-card req-info-warning">
                    <h4>Pastikan data yang diinput sudah sesuai.</h4>
                    <p>Tim sarana dan prasarana akan meninjau permintaan Anda dalam waktu maksimal 3 hari kerja.</p>
                </div>

                <div class="req-info-card mt-3">
                    <h4>Panduan Pengajuan</h4>
                    <ul class="req-guide-list">
                        <li>
                            <span class="req-guide-dot blue"></span>
                            Prioritas 'High' memerlukan approval mendadak dari Admin.
                        </li>
                        <li>
                            <span class="req-guide-dot blue"></span>
                            Pantau status permintaan di tab 'Pengadaan Asset'.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        .req-hero {
            background: linear-gradient(135deg, #e8edf6 0%, #dce4f2 60%, #f3f6fb 100%);
            border-radius: 16px;
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .req-hero-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: rgba(86, 124, 192, 0.12);
            color: #557cc0;
            display: grid; place-items: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .req-hero h1 { font-size: 22px; font-weight: 800; color: #2f3b52; margin: 0; }
        .req-hero p { font-size: 14px; color: #6b7fa5; margin: 4px 0 0; }

        .req-form-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(37, 59, 102, 0.06);
            padding: 28px;
        }
        .req-section h3 {
            font-size: 18px; font-weight: 700; color: #273753;
            display: flex; align-items: center; gap: 10px;
            margin: 0 0 18px;
        }
        .req-step-num {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: #eaf0fb;
            color: #5a78b4;
            font-size: 12px;
            font-weight: 800;
            display: inline-grid; place-items: center;
            flex-shrink: 0;
        }
        .req-input {
            height: 44px;
            border-radius: 999px;
            border: 1px solid #e2e8f3;
            background: #f3f6fb;
            color: #2f3f5f;
            font-size: 14px;
        }
        .req-input:focus {
            border-color: #5e87cc;
            box-shadow: 0 0 0 3px rgba(94, 135, 204, 0.1);
        }
        .form-group label {
            font-size: 12px;
            color: #485776;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .req-form-footer {
            background: #f8fafe;
            border: 1px solid #edf2fa;
            border-radius: 14px;
            padding: 14px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .req-btn-cancel {
            border-radius: 999px;
            min-width: 90px;
            font-weight: 600;
            border: 1px solid #dde3ef;
        }
        .req-btn-submit {
            border-radius: 999px;
            min-width: 160px;
            background: #3d5f98;
            color: #fff;
            font-weight: 700;
        }
        .req-btn-submit:hover { color: #fff; background: #35528a; }

        .req-info-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(37, 59, 102, 0.06);
            padding: 24px;
        }
        .req-info-warning {
            background: linear-gradient(135deg, #fff9eb 0%, #fff5da 100%);
            border-left: 4px solid #e6a817;
        }
        .req-info-card h4 {
            font-size: 16px; font-weight: 700; color: #2f3b52; margin: 0 0 8px;
        }
        .req-info-card p {
            font-size: 13px; color: #6b7fa5; margin: 0; line-height: 1.5;
        }

        .req-guide-list {
            list-style: none;
            padding: 0; margin: 12px 0 0;
        }
        .req-guide-list li {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 13px; color: #3b506b; line-height: 1.5;
            margin-bottom: 10px;
        }
        .req-guide-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }
        .req-guide-dot.blue { background: #5e87cc; }

        .pill { border-radius: 999px; font-size: 11px; font-weight: 700; padding: 4px 12px; }
        .status-pending { background: #fff1cf; color: #b78800; }

        @media (max-width: 992px) {
            .req-hero { flex-direction: column; align-items: flex-start; }
        }
    </style>
@stop