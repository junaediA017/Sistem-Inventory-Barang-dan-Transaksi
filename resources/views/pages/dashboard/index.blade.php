@extends('layouts.dashboard')

@section('title', "$application->name - Dashboard")

@section('description', 'Halaman ringkasan informasi dari aplikasi.')

@section('route_name', 'Dashboard')

@section('content')
    <div class="body">
    <div class="p-5" >
        <div class="text-center" style="color:#ffffff;background-color: rgba(255, 0, 0, 0.5);">
            <h2 class="h2">Selamat Datang {{ auth()->user()->name }} di Sistem Informasi Inventory</h2>
            <h6 class="h4">Auto Sunrise Mandiri</h6>
        </div>
    </div>
    <div class="row">
                {{-- Jumlah Barang --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jumlah Barang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $itemTotal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jumlah Transaksi (Masuk)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $incomeTransactionTotal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jumlah Transaksi (Keluar)
                            </div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $expenditureTransactionTotal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

    

    </div>
@endsection