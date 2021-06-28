@extends('layouts.master')

@section('title','DESForm Ubah Data Admin')

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
						<h3>Ubah Data Admin</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<form action="/AdminList/{{$user->id}}" method="POST">
									@csrf
									@method('PUT')
									<div class="form-group">
										<label for="email">Email</label>
										<input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Masukkan Email" value="{{$user->email}}">
									</div>
									<div class="form-group">
										<label for="name">Nama Awal</label>
										<input type="text" class="form-control" name='first_name' id="first_name" aria-describedby="first_name" placeholder="Masukkan Nama Awal" value="{{$user->first_name}}">
									</div>
									<div class="form-group">
										<label for="name">Nama Akhir</label>
										<input type="text" class="form-control" name='last_name' id="last_name" aria-describedby="last_name" placeholder="Masukkan Nama Akhir" value="{{$user->last_name}}">
									</div>
									<div class="form-group">
										<label for="role">Peran</label>
										<select name="usertype" id="role" class="form-control">
											<option value="admin" selected>Admin</option>
											<option value="user">Pengguna</option>
										</select>
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
