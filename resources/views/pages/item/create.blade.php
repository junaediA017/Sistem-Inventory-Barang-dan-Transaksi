@extends('layouts.dashboard')

@section('title', "$application->name - Barang - Tambah")

@section('description', 'Halaman yang berisi formulir untuk membuat data barang.')

@section('route_name', 'Tambah Barang')

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
    <div class="card">
        <div class="card-header bg-white">
            Isi Formulir
        </div>
        <div class="card-body">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="part_number">Kode Barang</label>
                            <input type="text" class="form-control" id="part_number" value="{{ old('part_number') }}" name="part_number">
                        </div>
                    </div>
              
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}">
                        </div>
                    </div>
                      <div class="col-md-6">
    <div class="form-group">
        <label for="satuan_brg">Satuan Barang</label>
        <select class="form-control" id="satuan_brg" name="satuan_brg">
            <option value="gram">Gram</option>
            <option value="kilogram">Kilogram</option>
            <option value="pcs">Pcs</option>
        </select>
    </div>
</div>
                 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection