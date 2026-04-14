@extends('adminlte::page')

@section('title', 'Edit Transaction')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Edit Transaction</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transactions</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">Form Edit Transaction</h3>
        </div>
        <form action="{{ route('transactions.update', $transaction) }}" method="POST">
            @csrf
            @method('PUT')
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
                        <label>Peminjam</label>
                        <select name="user_id" class="form-control">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id', $transaction->user_id) == $user->id)>{{ $user->name }} ({{ $user->role }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Asset</label>
                        <select name="asset_id" class="form-control">
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" @selected(old('asset_id', $transaction->asset_id) == $asset->id)>{{ $asset->name_asset }} ({{ $asset->code_asset }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Tanggal Pinjam</label>
                        <input type="date" name="borrowed_at" class="form-control" value="{{ old('borrowed_at', $transaction->borrowed_at->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tanggal Kembali</label>
                        <input type="date" name="returned_at" class="form-control" value="{{ old('returned_at', $transaction->returned_at?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Biaya</label>
                        <input type="number" step="0.01" min="0" name="cost" class="form-control" value="{{ old('cost', $transaction->cost) }}">
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('transactions.index') }}" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
@stop
