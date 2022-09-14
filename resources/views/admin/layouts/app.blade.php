<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}} - @yield('title') </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{!! asset('admin-assets/css/vendor.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin-assets/css/style.css') !!}" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141896396-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-141896396-1');
    </script>

    @stack('style')
</head>
<body>

  <!-- Wrapper-->
    <div id="wrapper">

        <!-- Navigation -->
        @include('admin.includes.navigation')

        <!-- Page wraper -->
        <div id="page-wrapper" class="gray-bg">

            <!-- Page wrapper -->
            @include('admin.includes.topnavbar')

            <!-- Main view  -->
            @yield('content')

            <!-- Footer -->
            @include('admin.includes.footer')

          </div>
          <!-- End page wrapper-->
    </div>
    <!-- End wrapper-->

<script src="{!! asset('admin-assets/js/app.js') !!}" type="text/javascript"></script>
<script src="{!! asset('admin-assets/js/vendor.js') !!}" type="text/javascript"></script>
<script>
$.extend( $.fn.dataTable.defaults, {
    dom:
      "<'row'<'col-sm-4'l><'col-sm-4'r><'col-sm-4'f>>" +
      "<'row'<'col-sm-12't>>" +
      "<'row'<'col-sm-5'i><'col-sm-7 justify-content-end d-flex'p>>",
    renderer: 'bootstrap',
} );
</script>
@stack('script')


</body>
</html>
