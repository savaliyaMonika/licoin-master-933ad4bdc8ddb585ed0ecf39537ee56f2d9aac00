@extends('layouts.email')
@section('content')
    <h2 style="font-size: 18px; color:#383838; margin-bottom: 6px; margin-top: 21px; font-weight: normal;">
        Hi,@if($mailType != "")
        {!! $mailType !!}
        @endif
    </h2>

    @if($extraText == "")
    <p>Thank you for using our services.</p>
    <p>Here is your download link:</p>
    <p>{!! $fileTranslated !!}</p>
    @endif

    @if($extraText != "")
    <p>{!! $extraText !!}</p>
    @endif
@endsection