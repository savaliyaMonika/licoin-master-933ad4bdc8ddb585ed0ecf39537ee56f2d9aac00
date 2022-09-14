@extends('admin.layouts.app')

@section('title', 'Edit Translation Keys')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            {{-- {{dd($key)}} --}}
            {!! Form::model($key, ['method' => 'PATCH', 'action' => ['Admin\TrnslKeyController@update',$key->id], 'id'
            => 'createKeyForm', 'enctype' => 'multipart/form-data']) !!}

            {{-- <div class="col-md-10 col-md-offset-1 ibox-title">
                <h5 class="visible-lg-inline-block">Edit key</h5>
                <button type="submit" class="btn btn-primary btn-sm pull-right">Update</button>
            </div> --}}

            @include('admin.trnsl-keys.form')

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection