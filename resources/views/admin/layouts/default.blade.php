<!DOCTYPE html>
<html>
<head>
    <title>ADMIN | @yield('title') </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{!! asset('admin-assets/css/style.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin-assets/css/vendor.css') !!}" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141896396-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-141896396-1');
    </script>

</head>
<body class="gray-bg">
    <div class="container eg-admin">
        @yield('content')
    </div>
    <script src="{!! asset('admin-assets/js/app.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('admin-assets/js/vendor.js') !!}" type="text/javascript"></script>


</body>
</html>