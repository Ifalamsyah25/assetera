@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Tambah User</h1></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header"><h3 class="card-title">Form User</h3></div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
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
                    <div class="form-group col-md-6"><label>Nama</label><input type="text" name="name" class="form-control" value="{{ old('name') }}"></div>
                    <div class="form-group col-md-6"><label>Username</label><input type="text" name="username" class="form-control" value="{{ old('username') }}"></div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
                    <div class="form-group col-md-6">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            <option value="pimpinan" @selected(old('role') === 'pimpinan')>Pimpinan</option>
                            <option value="staff" @selected(old('role') === 'staff')>Staff</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label>Password</label><input type="password" name="password" class="form-control"></div>
                    <div class="form-group col-md-6"><label>Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-control"></div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('users.index') }}" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@stop
