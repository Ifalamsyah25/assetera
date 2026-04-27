@extends('adminlte::page')

@section('title', '500 Server Error')

@section('content_header')
    <h1>500 Error Page</h1>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-danger"> 500</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Terjadi Kesalahan Server.</h3>

            <p>
                Kami akan segera memperbaiki masalah ini.
                Sementara itu, Anda dapat <a href="{{ route('dashboard') }}">kembali ke dashboard</a>.
            </p>
        </div>
    </div>
@stop
