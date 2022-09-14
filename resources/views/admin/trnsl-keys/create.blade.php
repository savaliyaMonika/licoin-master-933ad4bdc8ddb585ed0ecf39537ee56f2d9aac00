@extends('admin.layouts.app')

@section('title', 'Create Translation Keys')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(['action' => 'Admin\TrnslKeyController@store', 'id' => 'createKeyForm', 'enctype' =>
            'multipart/form-data']) !!}

            @include('admin.trnsl-keys.form')

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
