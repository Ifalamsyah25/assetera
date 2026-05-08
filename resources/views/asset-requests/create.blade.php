@extends('adminlte::page')

@section('title', 'Buat Pengajuan Baru')

@section('content_header')
    <h1>Buat Pengajuan Aset Baru</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Formulir Pengajuan</h3>
            </div>
            <form action="{{ route('asset-requests.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror" value="{{ old('item_name') }}" required placeholder="Contoh: Laptop Asus Vivobook">
                        @error('item_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Jumlah Unit <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') ?? 1 }}" min="1" required>
                            @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Estimasi Harga per Unit (Opsional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="number" name="estimated_price" class="form-control @error('estimated_price') is-invalid @enderror" value="{{ old('estimated_price') }}">
                            </div>
                            @error('estimated_price') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alasan Kebutuhan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required placeholder="Jelaskan untuk keperluan apa barang ini dibutuhkan...">{{ old('reason') }}</textarea>
                        @error('reason') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('asset-requests.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
