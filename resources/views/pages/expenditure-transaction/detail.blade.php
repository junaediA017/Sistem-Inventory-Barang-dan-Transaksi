    @extends('layouts.dashboard')

    @section('title', "$application->name - Transaksi Keluar - $item->reference_number")

    @section('description', 'Halaman yang berisi informasi detail transaksi Kelar.')

    @section('route_name', 'Detail Transaksi (Keluar)')

    @section('content')
        <div class="card">
            <div class="card-header">
                <h5 class="m-0 text-dark">
                    {{ $item->reference_number }}
                </h5>
            </div>
            <div class="card-body" id="print-content">
                <div class="row mb-2">
                    <div class="col-md-4 col-lg-3">
                        <b>Merk <span class="d-md-none">:</span></b>
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <b class="d-none d-md-inline">:</b> {{ $item->picker }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 col-lg-3">
                        <b>Catatan <span class="d-md-none">:</span></b>
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <b class="d-none d-md-inline">:</b> {{ $item->remarks }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <b>Daftar Barang</b>
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="align-middle">No</th>
                                        <th class="align-middle text-left">ID Barang</th>
                                        <th class="align-middle text-left">Deskripsi</th>
                                        <th class="align-middle">Harga</th>
                                        <th class="align-middle text-right">Satuan Barang</th>
                                        <th class="align-middle text-right">Jumlah</th>
                                        
                                        <th class="align-middle text-right">Harga Total</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $number = 1;
                                        $itemTotal = 0;
                                        $totalPrice = 0;
                                    @endphp
                                    @foreach ($subitems as $expenditureTransactionItem)
                                        <tr>
                                            <td class="align-middle">
                                                {{ $number }}
                                            </td>
                                            <td class="align-middle text-left">
                                                {{ $expenditureTransactionItem->item->part_number }}
                                            </td>
                                            <td class="align-middle text-left">
                                                {{ $expenditureTransactionItem->item->description }}
                                            </td>
                                            <td class="align-middle">
                                            {{ 'Rp' . currency( $expenditureTransactionItem->item->price) }}
                                            </td>
                                            <td class="align-middle text-left">
                                                {{$expenditureTransactionItem->item->satuan_brg }}
                                            </td>
                                            <td class="align-middle text-right">
                                                {{ $expenditureTransactionItem->amount }}
                                            </td>
                                            <td class="align-middle text-right">
                                            {{ currency($expenditureTransactionItem->item->price) * $expenditureTransactionItem->amount }}
                                            </td>
                                        </tr>
                                        @php
                                            $number++;
                                            $itemTotal += $expenditureTransactionItem->amount;
                                            $totalPrice += $expenditureTransactionItem->item->price * $expenditureTransactionItem->amount;
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">
                                            Total
                                        </th>
                                        <th class="text-right">
                                            {{ $itemTotal }}
                                        </th>
                                        <th class="text-right">
                                            {{ currency($totalPrice) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
           <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                Kembali
            </a>
         <button class="btn btn-primary" style="background-color: #ff0000;" onclick="printCardBody()">Cetak</button>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function printCardBody() {
            var printContents = document.getElementById("print-content").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection