@extends('layouts.auth')

@section('title', "$application->name - Login")

@section('description', 'Halaman formulir pengisian akun untuk masuk ke aplikasi.')

@section('content')
<div class="body" > 
    <div class="p-5">
        <div class="text-center" >
            <h1 class="h1 text-gray-900 mx-12"  ><b>{{ $application->name }}</b></h1>
            <h6 class="h4 text-gray-900 mb-12">Auto Sunrise Mandiri</h6>
        </div>
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
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form class="user" method="POST">
            @method('POST')
            @csrf
            <div class="form-group">
                <input type="text"
                    class="form-control"
                    id="exampleInputEmail"
                    placeholder="Username"
                    name="username">
            </div>
            <div class="form-group">
                <input type="password"
                    class="form-control"
                    id="exampleInputPassword"
                    placeholder="Kata sandi"
                    name="password">
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="customCheck" name="remember_me" value="1">
                    <label class="custom-control-label" for="customCheck">
                        Tetap Masuk
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-user btn-block" style="background-color: #000000;">
                Login
            </button>
        </form>
    </div>
</div>
@endsection