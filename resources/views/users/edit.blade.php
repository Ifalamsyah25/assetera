@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Edit User</h1></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-warning">
        <div class="card-header"><h3 class="card-title">Form Edit User</h3></div>
        <form action="{{ route('users.update', $user) }}" method="POST">
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
                    <div class="form-group col-md-6"><label>Nama</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"></div>
                    <div class="form-group col-md-6"><label>Username</label><input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}"></div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"></div>
                    <div class="form-group col-md-6">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                            <option value="pimpinan" @selected(old('role', $user->role) === 'pimpinan')>Pimpinan</option>
                            <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label>Password Baru</label><input type="password" name="password" class="form-control"></div>
                    <div class="form-group col-md-6"><label>Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-control"></div>
                </div>
                <p class="text-muted mb-0">Kosongkan field password jika tidak ingin mengganti password.</p>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('users.index') }}" class="btn btn-default">Batal</a>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
@stop
