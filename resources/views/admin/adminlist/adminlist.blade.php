@extends('layouts.master')

@section('title','DESForm Tabel Admin')

@section('genericpanel')
    <div class="panel-header panel-header-sm">
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Admin</h4>
                <a href="/AdminList/create" class="btn btn-success">Tambah</a>
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
                        <thead class=" text-primary">
                            <th>
                                No
                            </th>
                            <th>
                                Nama
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Tipe User
                            </th>
                            <th>
                                Aksi
                            </th>
                        </thead>
                        <tbody>
                            @foreach($users as $item)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $item->first_name." ".$item->last_name }}
                                    </td>
                                    <td>
                                        {{ $item->email }}
                                    </td>
                                    <td>
                                        @if($item->usertype=="")
                                            -
                                        @elseif($item->usertype=="admin")
                                            Admin
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/AdminList/{{$item->id}}" class="btn btn-info">Detil</a>
                                    </td>
                                    <td>
                                        <a href="/AdminList/{{$item->id}}/edit" class="btn btn-success">Ubah</a>
                                    </td>
                                    <td>
                                        <form action="/AdminList/{{ $item->id }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
