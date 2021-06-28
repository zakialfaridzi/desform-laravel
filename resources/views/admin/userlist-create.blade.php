@extends('layouts.master')

@section('title','DESForm Tabel Pengguna')

@section('genericpanel')
<div class="panel-header panel-header-sm">
</div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Data Pengguna</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="/UserList" method="POST">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        aria-describedby="emailHelp" placeholder="Masukkan Email"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Awal</label>
                                    <input type="text" class="form-control" name='first_name' id="first_name"
                                        aria-describedby="first_name" placeholder="Masukkan Nama Awal"
                                        value="{{ old('first_name') }}">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Akhir</label>
                                    <input type="text" class="form-control" name='last_name' id="last_name"
                                        aria-describedby="last_name" placeholder="Masukkan Nama Akhir"
                                        value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name='password' id="password"
                                        aria-describedby="password" placeholder="Masukkan Password">
                                </div>

                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="/UserList" class="btn btn-danger">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
