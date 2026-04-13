@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Profile</h1>
            <small class="text-muted">Kelola informasi akun, password, dan keamanan akun</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ auth()->user()->username ?? 'user' }}</h3>
                    <p>Username aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="small-box-footer">{{ ucfirst(auth()->user()->role ?? 'user') }}</div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ auth()->user()->email }}</h3>
                    <p>Email akun</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="small-box-footer">Kontak utama pengguna</div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Secure</h3>
                    <p>Manajemen keamanan akun</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="small-box-footer">Jaga kredensial tetap aman</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="col-lg-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@stop
