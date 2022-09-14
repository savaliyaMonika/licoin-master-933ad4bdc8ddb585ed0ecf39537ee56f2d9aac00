let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/admin/js/app.js', 'public/admin-assets/js')
    .sass('resources/assets/admin/scss/style.scss', 'public/admin-assets/css').options({
        processCssUrls: false
    });
mix.copy('resources/assets/admin/vendor/font-awesome/fonts', 'public/admin-assets/fonts');
mix.copy('resources/assets/admin/fonts/', 'public/admin-assets/fonts');
mix.copy('resources/assets/admin/img', 'public/admin-assets/img');
mix.copy('resources/assets/admin/scss/patterns', 'public/admin-assets/css/patterns');
// mix.copy('', 'public/admin-assets/js');
mix.styles([
    'resources/assets/admin/vendor/animate/animate.css',
    'resources/assets/admin/vendor/font-awesome/css/font-awesome.css',
    'resources/assets/admin/vendor/dataTables/datatables.min.css',
    'resources/assets/admin/vendor/sweetalert/sweetalert.css',
    'resources/assets/admin/vendor/toastr/toastr.min.css',
], 'public/admin-assets/css/vendor.css', './');
mix.scripts([
    'resources/assets/admin/vendor/metisMenu/jquery.metisMenu.js',
    'resources/assets/admin/vendor/slimscroll/jquery.slimscroll.min.js',
    'resources/assets/admin/vendor/pace/pace.min.js',
    'resources/assets/admin/vendor/dataTables/datatables.min.js',
    'resources/assets/admin/vendor/dataTables/dataTables.bootstrap4.min.js',
    'resources/assets/admin/vendor/sweetalert/sweetalert.min.js',
    'resources/assets/admin/vendor/toastr/toastr.min.js',
    'resources/assets/admin/vendor/moment/moment.min.js',
    'resources/assets/admin/vendor/inspinia/inspinia.js',
], 'public/admin-assets/js/vendor.js', './');
