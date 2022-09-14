@extends('admin.layouts.default')

@section('content')
<div class="middle-box text-center loginscreen animated fadeInDown">
  <div>
      <div>

          <h1 class="logo-name">LI</h1>

      </div>
      <h3>Welcome to {{env('APP_NAME')}}</h3>
      <p>Login in. To see it in action.</p>
      <form class="m-t" role="form" method="POST" action="{{ route('adminlogin') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Enter Email" required autofocus>

            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Enter Password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>
          <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
      </form>
      <p class="m-t"> <small> {{env('APP_NAME')}} &copy; {{date('Y')}} </small> </p>
  </div>
</div>
@endsection
