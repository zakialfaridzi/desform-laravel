@extends('layouts.master')

@section('title','DESForm Tabel Pengguna')

@section('genericpanel')
    <div class="panel-header panel-header-sm">
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detil Pengguna</h4>
            </div>
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <td>{{ $users->id }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $users->first_name." ".$users->last_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $users->email }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Pengguna</th>
                            <td>{{ $users->usertype }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pembuatan Akun</th>
                            <td>{{ date("Y-m-d H:i:s", strtotime($users["created_at"]." +7 hours")) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Ubah Akun</th>
                            <td>{{ date("Y-m-d H:i:s", strtotime($users["updated_at"]." +7 hours")) }}</td>
                        </tr>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
