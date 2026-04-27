@extends('adminlte::page')

@section('title', '404 Not Found')

@section('content_header')
    <h1>404 Error Page</h1>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman tidak ditemukan.</h3>

            <p>
                Kami tidak dapat menemukan halaman yang Anda cari.
                Sementara itu, Anda dapat <a href="{{ route('dashboard') }}">kembali ke dashboard</a>.
            </p>
        </div>
    </div>
@stop
