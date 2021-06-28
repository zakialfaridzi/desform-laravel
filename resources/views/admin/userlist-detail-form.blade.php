@extends('layouts.master')

@section('title','DESForm Detil Pengguna')

@section('genericpanel')
    <div class="panel-header panel-header-sm">
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detil Form Yang Dimiliki Pengguna</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">

                            <th>
                                NO
                            </th>
                            <th>
                                Judul
                            </th>
                            <th>
                                Deskripsi
                            </th>
                            <th>
                                Status
                            </th>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->title }}</td>
                                    <td>{{ $user->description }}</td>
                                    <td>
                                        @if ($user->status=='open')
                                            <span class="badge badge-pill badge-primary">Dibuka</span>
                                            @elseif($user->status=='pending')
                                            <span class="badge badge-pill badge-warning">Sedang Pending</span>
                                            @elseif($user->status=='draft')
                                            <span class="badge badge-pill badge-info">Dalam Draft</span>
                                            @elseif($user->status=='closed')
                                            <span class="badge badge-pill badge-danger">Ditutup</span>
                                        @endif
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
