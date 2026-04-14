@extends('adminlte::page')

@section('title', 'Edit Maintenance')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Edit Maintenance</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenances.index') }}">Maintenance</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">Form Edit Maintenance</h3>
        </div>
        <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
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

                <div class="form-group">
                    <label>Asset</label>
                    <select name="asset_id" class="form-control">
                        @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}" @selected(old('asset_id', $maintenance->asset_id) == $asset->id)>{{ $asset->name_asset }} ({{ $asset->code_asset }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi Perbaikan</label>
                    <textarea name="repair_description" rows="4" class="form-control">{{ old('repair_description', $maintenance->repair_description) }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="pending" @selected(old('status', $maintenance->status) === 'pending')>Pending</option>
                            <option value="in_progress" @selected(old('status', $maintenance->status) === 'in_progress')>In Progress</option>
                            <option value="completed" @selected(old('status', $maintenance->status) === 'completed')>Completed</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Biaya</label>
                        <input type="number" step="0.01" min="0" name="cost" class="form-control" value="{{ old('cost', $maintenance->cost) }}">
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('maintenances.index') }}" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
@stop
