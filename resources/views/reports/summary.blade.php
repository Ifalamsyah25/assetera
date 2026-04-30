@extends('adminlte::page')

@section('title', 'Laporan Aset')

@section('content_header')
    <h1>Laporan Aset</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ringkasan Aset</h3>
    </div>

    <div class="card-body">

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>120</h3>
                        <p>Total Aset</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>45</h3>
                        <p>Aset Dipinjam</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>10</h3>
                        <p>Aset Rusak</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Laptop Asus</td>
                        <td>Elektronik</td>
                        <td>Dipinjam</td>
                        <td>2026-04-30</td>
                    </tr>
                    <tr>
                        <td>Proyektor Epson</td>
                        <td>Elektronik</td>
                        <td>Tersedia</td>
                        <td>2026-04-28</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
@stop