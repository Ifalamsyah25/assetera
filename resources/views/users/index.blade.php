@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Users</h1>
            <small class="text-muted">Kelola akun admin, pimpinan, dan staff</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner"><h3>{{ $summary['total'] }}</h3><p>Total Users</p></div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="small-box-footer">Semua akun sistem</div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner"><h3>{{ $summary['admin'] }}</h3><p>Admin</p></div>
                <div class="icon"><i class="fas fa-user-shield"></i></div>
                <div class="small-box-footer">Akses penuh</div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner"><h3>{{ $summary['pimpinan'] }}</h3><p>Pimpinan</p></div>
                <div class="icon"><i class="fas fa-user-tie"></i></div>
                <div class="small-box-footer">Akses monitoring</div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner"><h3>{{ $summary['staff'] }}</h3><p>Staff</p></div>
                <div class="icon"><i class="fas fa-user"></i></div>
                <div class="small-box-footer">Operasional harian</div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->has('user'))
        <div class="alert alert-danger">{{ $errors->first('user') }}</div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Users</h3>
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i> Tambah User
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped text-nowrap">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'pimpinan' ? 'warning' : 'info') }}">{{ ucfirst($user->role) }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus user ini?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">Belum ada data user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
