@extends('adminlte::page')

@section('title', '403 Forbidden')

@section('content_header')
    <h1>403 Error Page</h1>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-danger"> 403</h2>

        <div class="error-content">
            <h3><i class="fas fa-ban text-danger"></i> Oops! Akses Ditolak.</h3>

            <p>
                Anda tidak memiliki izin (role) yang cukup untuk mengakses halaman ini.
                Silakan <a href="{{ route('dashboard') }}">kembali ke dashboard</a>.
            </p>
        </div>
    </div>
@stop
