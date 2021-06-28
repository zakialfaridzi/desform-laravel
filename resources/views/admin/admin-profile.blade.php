@extends('layouts.master') @section('title','Edit Profile Admin')

@section('genericpanel')
    <div class="panel-header panel-header-sm">
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="title">Ubah Profil Admin</h5>
            </div>
            <div class="card-body">
                <form id="update-profile-form" action="{{ route('profile.updateadmin') }}" method="post">
                        @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label for="email"
                                    >Alamat Email</label
                                >
                                <input
                                    type="email"
                                    class="form-control"
                                    placeholder="Email"
                                    id="email"
                                    name="email"
                                    value="{{ $current_user->email }}"
                                />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label>Nama Awal</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Company"
                                    name='first_name' id="first_name" aria-describedby="first_name"
                                    value="{{ old('first_name') ?? $current_user->first_name }}" required
                                />
                                @if ($errors->has('first_name'))
                                        <span class="help-block">{{ $errors->first('first_name') }}</span>
                                    @endif
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label>Nama Akhir</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Last Name"
                                    name='last_name' id="last_name" aria-describedby="last_name"
                                    value="{{ old('last_name') ?? $current_user->last_name }}" required
                                />
                                @if ($errors->has('last_name'))
                                        <span class="help-block">{{ $errors->first('last_name') }}</span>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                       <button type="reset" class="btn mt-20 btn-danger">Ulangi</button>
                       <button type="submit" id="submit" class="btn mt-20 btn-primary">Ubah</button>
                   </div>
                </div>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
 @endsection
