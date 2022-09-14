@extends('layouts.email')
@section('content')
    <h2 style="font-size: 18px; color:#383838; margin-bottom: 6px; margin-top: 21px; font-weight: normal;">
        Hi, @if($email != "") {!! $email !!} @endif
    </h2>
    <p>Thank you for choosing Logistic Infotech Translation Service. Use the following OTP to verify your email address.</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">{!! $otp !!}</h2>
@endsection