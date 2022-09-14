@extends('layouts.sslemail')
@section('content')
<h2 style="font-size: 18px; color:#383838; margin-bottom: 6px; margin-top: 21px; font-weight: normal;">
    Hi There,
    <br/>
    Your SSL Certificate for {{$certificate['web_site']}} will expire in just 7 days.
</h2>
<ul>
    <li>
        <strong>Expiration Date</strong> : {{$certificate['expiration_date']}} ({{$certificate['expiration_date_diff']}} hours).
    </li>
    <li>
        <strong>Server IP address</strong> : {{$certificate['server_ip_address']}}
    </li>
    <li>
        <strong>Issuer</strong> : {{$certificate['issuer']}}
    </li>
    <li>
        <strong>Valid From</strong> : {{$certificate['valid_from_date']}}
    </li>
</ul>
<p>
  <br/>
  <h3>
      Need help renewing your SSL Certificate? <a href="https://www.logisticinfotech.com/contactus/">Contact us </a>today and get quick help.
  </h3>
</p>
@endsection