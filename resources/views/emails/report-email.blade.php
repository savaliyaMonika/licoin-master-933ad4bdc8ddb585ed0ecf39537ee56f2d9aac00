@extends('layouts.sslemail')
@section('content')
<h2 style="font-size: 18px; color:#383838; margin-bottom: 6px; margin-top: 21px; font-weight: normal;">
    Hi There,
</h2>
<ul>
    <li>
        <strong>conversionInProcess</strong> : {{ $data['conversionInProcess'] }}
    </li>
    <li>
        <strong>conversionFailed</strong> : {{ $data['conversionFailed'] }}
    </li>
    <li>
        <strong>totalConvertedFiles</strong> : {{ $data['totalConvertedFiles'] }}
    </li>
    <li>
        <strong>totalPushTryRequest</strong> : {{ $data['totalPushTryRequest'] }}
    </li>
    <li>
        <strong>todayConvertedFiles</strong> : {{ $data['todayConvertedFiles'] }}
    </li>
    <li>
        <strong>todayPushTryRequest</strong> : {{ $data['todayPushTryRequest'] }}
    </li>
</ul>
@endsection