@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Jumlah Transaksi Hari Ini</div>
                                <div class="card-body">{{$jumlahTransaksiNow}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Total Transaksi Hari Ini</div>
                                <div class="card-body">Rp. {{$totalTransaksiNow > 0 ? $totalTransaksiNow : 0}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Jumlah Transaksi Bulan Ini</div>
                                <div class="card-body">{{$jumlahTransaksiMonth}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Total Transaksi Bulan Ini</div>
                                <div class="card-body">Rp. {{$totalTransaksiMonth > 0 ? $totalTransaksiMonth : 0}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
