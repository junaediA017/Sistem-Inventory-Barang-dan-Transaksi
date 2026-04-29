@extends('layouts.dashboard')

@section('title', "$application->name - Transaksi (Masuk)")

@section('description', 'Halaman yang berisi daftar data transaksi masuk yang dibuat.')

@section('route_name', 'Transaksi (Masuk)')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
       @can('isAdmin')
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="{{ route('income-transactions.create') }}" class="btn btn-sm btn-primary mb-3"style="background-color: #ff0000;">
                <i class="fas fa-plus mr-1"></i>
                Tambah
            </a>
        </div>
    </div>
    @endcan
    @can('isPemilik')
    <div class="row justify-content-end">
        <div class="col-auto" >
            <a href="javascript:void(0)" class="btn btn-sm btn-primary mb-3" style="background-color: #ff0000;" onclick="printContent()">
                Cetak
            </a>
        </div>
    </div>
    @endcan
    <!-- Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('income-transactions.index') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Sortir / Saring Transaksi (Masuk)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"style="background-color: #ff0000;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                        <label for="order_by">Kolom Urut</label>
                        <select class="form-control form-control-sm" id="order_by" name="order_by">
                            <option value="created_at"
                                {{ $input['order_by'] === 'created_at' ? 'selected' : '' }}>
                                Tanggal
                            </option>
                            <option value="supplier"
                                {{ $input['order_by'] === 'supplier' ? 'selected' : '' }}>
                                Pemasok
                            </option>
                            <option value="reference_number"
                                {{ $input['order_by'] === 'reference_number' ? 'selected' : '' }}>
                                Nomor Nota
                            </option>
                            <option value="remarks"
                                {{ $input['order_by'] === 'remarks' ? 'selected' : '' }}>
                                Catatan
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order_direction">Arah Urut</label>
                        <select name="order_direction" id="order_direction" class="form-control form-control-sm">
                            <option value="desc"
                                {{ $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                                Turun
                            </option>
                            <option value="asc"
                                {{ $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                Naik
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="string_start_date">Tanggal Mulai</label>
                                <input type="date"
                                    class="form-control"
                                    value="{{ $input['string_start_date'] }}"
                                    id="string_start_date"
                                    name="string_start_date"
                                    onkeyup="document.getElementById('start_date').value = new Date(this.value).getTime() / 1000"
                                    onchange="document.getElementById('start_date').value = new Date(this.value).getTime() / 1000">
                                <input type="hidden"
                                    name="start_date"
                                    id="start_date"
                                    value="{{ $input['start_date'] }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="string_end_date">Tanggal Akhir</label>
                                <input type="date"
                                    class="form-control"
                                    value="{{ $input['string_end_date'] }}"
                                    id="string_end_date"
                                    name="string_end_date"
                                    onkeyup="document.getElementById('end_date').value = new Date(this.value).getTime() / 1000"
                                    onchange="document.getElementById('end_date').value = new Date(this.value).getTime() / 1000">
                                <input type="hidden"
                                    name="end_date"
                                    id="end_date"
                                    value="{{ $input['end_date'] }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-white">
            <div class="row justify-content-center justify-content-lg-between align-items-center">
                <div class="col-md-7 col-lg-4 mb-2 mb-lg-10">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#filterModal" style="background-color: #ff0000;">
                        <i class="fas fa-filter mr-1"></i>
                        Sortir / Saring
                    </button>
                </div>
                <div class="col-lg-auto col-md-6">
                    <form action="{{ route('income-transactions.index') }}" method="get">
                        <input type="hidden" name="order_by" value="{{ $input['order_by'] }}">
                        <input type="hidden" name="order_direction" value="{{ $input['order_direction'] }}">
                        <input type="hidden" name="start_date" value="{{ $input['start_date'] }}">
                        <input type="hidden" name="end_date" value="{{ $input['end_date'] }}">
                        <div class="input-group input-group-sm">
                            <input type="search"
                                class="form-control"
                                name="keyword"
                                id="q"
                                placeholder="Pencarian"
                                value="{{ empty($input['keyword']) ? '' : $input['keyword'] }}">
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" style="width: 2px">No</th>
                            <th class="align-middle text-center">Tanggal (WIB)</th>
                            <th class="align-middle text-center">Nomor Nota</th>
                            <th class="align-middle">Pemasok</th>
                            <th class="align-middle">Catatan</th>
                            <th class="align-middle">Cabang</th>
                            <th class="align-middle">Keterangan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($items->isEmpty())
                            <tr>
                                <td class="text-center" colspan="6">
                                    Data tidak ditemukan.
                                </td>
                            </tr>
                        @else
                            @foreach ($items as $item)
                                <tr>
                                    <td class="text-center align-middle">
                                        {{ $number }}
                                    </td>
                                    <td class="align-middle text-center unix-column">
                                        {{ $item->created_at }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $item->reference_number }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->supplier }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($item->remarks == '1')
                                            "Diterima"
                                        @elseif ($item->remarks == '2')
                                            "Belum Disetujui"
                                        @else
                                            "Ditolak"
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->keterangan_cabang }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->keterangan }}
                                    </td>
                                    @can('isAdmin')    
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                              Aksi
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('income-transactions.show', $item->id) }}" class="dropdown-item">
                                                    Detail
                                                </a>
                                                <a href="{{ route('income-transactions.edit', $item->id) }}" class="dropdown-item">
                                                    Ubah
                                                </a>
                                                   @can('isAdmin')
                                                <form action="{{ route('income-transactions.destroy', $item->id) }}" method="post" class="dropdown-item">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-left p-0" onclick="return confirm('Transaksi {{ $item->reference_number }} akan dihapus. Lanjutkan')">
                                                        Hapus
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                    @endcan
                                       
                                    @can('isPemilik')
<td class="text-center align-middle">
    <form id="remarksForm" action="{{ route('income-transactions.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control">{{ empty(old('keterangan')) ? $item->keterangan : old('keterangan') }}</textarea>
        </div>

        <div class="form-group">
            <label>Pilih Status</label>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-outline-success {{ $item->remarks === '1' ? 'active' : '' }}">
                    <input type="radio" name="remarks" value="1" autocomplete="off" {{ $item->remarks === '1' ? 'checked' : '' }}> Diterima
                </label>
                <label class="btn btn-outline-danger {{ $item->remarks === '0' ? 'active' : '' }}">
                    <input type="radio" name="remarks" value="0" autocomplete="off" {{ $item->remarks === '0' ? 'checked' : '' }}> Ditolak
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

        <a href="{{ route('income-transactions.show', $item->id) }}" class="btn btn-secondary">
            Detail
        </a>
    </form>
</td>

<script>
    const submitButton = document.querySelector('button[type="submit"]');
    const radioButtons = document.querySelectorAll('input[type="radio"]');

    radioButtons.forEach((radio) => {
        radio.addEventListener('click', () => {
            submitButton.removeAttribute('disabled');
        });
    });
</script>
@endcan

                                </tr>
                                @php
                                    $number++
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-auto">
                    <nav>
                        <ul class="pagination pagination-sm">
                            @if ($input['page'] === 1)
                                <li class="page-item disabled">
                                    <a href="" class="page-link"><<</a>
                                </li>

                                <li class="page-item disabled">
                                    <a href="" class="page-link"><</a>
                                </li>

                                <li class="page-item active">
                                    <a
                                        href="{{ route('income-transactions.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}"
                                        class="page-link">
                                        1
                                    </a>
                                </li>

                                @for ($i = 2; $i <= $pageTotal; $i++)
                                    @if ($i < 4)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ route('income-transactions.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <<
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $input['page'] - 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <
                                    </a>
                                </li>

                                @php
                                    $pageStartNumber = $input['page'] !== $pageTotal ? $input['page'] - 1 : $input['page'] - 2;
                                    $loopingNumberStop = $input['page'] !== $pageTotal ? $input['page'] + 1 : $input['page'];
                                    $pageStartNumber = $pageStartNumber < 1 ? 1 : $pageStartNumber;
                                @endphp

                                @for ($i = $pageStartNumber; $i <= $loopingNumberStop; $i++)
                                    <li class="page-item {{ $input['page'] === $i ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ route('income-transactions.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="display: none;" id="printall">
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" style="width: 2px">No</th>
                            <th class="align-middle text-center">Tanggal (WIB)</th>
                            <th class="align-middle text-center">Nomor Nota</th>
                            <th class="align-middle">Pemasok</th>
                            <th class="align-middle">Catatan</th>
                            <th class="align-middle">Cabang</th>
                            <th class="align-middle">Keterangan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="text-center align-middle">
                                        {{ $number }}
                                    </td>
                                    <td class="align-middle text-center unix-column">
                                        {{ $item->created_at }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $item->reference_number }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->supplier }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($item->remarks == '1')
                                            "Diterima"
                                        @elseif ($item->remarks == '2')
                                            "Belum Disetujui"
                                        @else
                                            "Ditolak"
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->keterangan_cabang }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->keterangan }}
                                    </td>                                   
                                </tr>
                                @php
                                    $number++
                                @endphp
                            @endforeach
                    </tbody>
                </table>
            </div>
        
    </div>
    <script>
        function printContent() {
            var content = document.getElementById("printall").innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = content;
            window.print();
            document.body.innerHTML = originalContent;
        }
        document.getElementById(    'remarks').addEventListener('change', function() {
            document.getElementById('remarksForm').submit();
        });

        function datetimeLocal(unix) {
            var dt = new Date(unix * 1000);
            dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
            return dt.toISOString().slice(0, 16);
        }

        var htmlCreatedAt = document.getElementById('html_created_at'),
            unix = parseInt(htmlCreatedAt.getAttribute('data-value')),
            date = datetimeLocal(unix);

        htmlCreatedAt.value = date;
    </script>
@endsection