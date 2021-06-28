@section('title', "Form Saya | Ubah Form")

@extends('layouts.app')

@section('content')
    <div class="panel panel-flat bg-indigo border-left-xlg border-left-orange">
        <div class="panel-heading">
            <h4 class="panel-title text-semibold">Form Saya</h4>
        </div>
    </div>

    <div class="panel panel-flat border-top-lg border-top-primary">
        <div class="panel-heading">
            <h5 class="panel-title">Ubah Form</h5>
        </div>
        <div class="panel-body">
            @include('forms.form._form', ['type' => 'edit'])
        </div>
    </div>
@endsection
