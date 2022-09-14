<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect('https://www.logisticinfotech.com/');
});

Route::get('/test-mail-template', function () {
    return new App\Mail\LangTrasnlateMail("", "test");
});

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//-------- Push Try -----------

Route::group(['prefix' => '/pushtry' ], function () {
    Route::get('/', 'PushTryController@index');

    Route::post('/apnsFileUpload', 'PushTryController@apnsFileUpload');
    Route::post('/sendApnsPush', 'PushTryController@sendApnsPush');
    Route::get('/getPemFileFromSession', 'PushTryController@getPemFileFromSession');
});

Route::group(['prefix' => '/langtranslate' ], function () {
    Route::get('/', 'LangTranslateController@index');

    Route::get('/getlanguages', 'LangTranslateController@getLanguages');
    Route::post('/langFileUpload', 'LangTranslateController@langFileUpload');

    Route::post('/submitTranslateRequest', 'LangTranslateController@submitTranslateRequest');
    Route::post('/send-otp', 'LangTranslateController@sendOtp');
    Route::post('/verify-otp', 'LangTranslateController@verifyOtp');
    // Route::post('/sendApnsPush', 'PushTryController@sendApnsPush');
    // Route::get('/getPemFileFromSession', 'PushTryController@getPemFileFromSession');
});

Route::group(['prefix' => '/dnsutility' ], function () {
    Route::get('/', "DNSUtilityController@index")->name("dnsutility-home");
    Route::get('fetchDNSTypeRecords', "DNSUtilityController@fetchDNSTypeRecords")->name("fetchDNSTypeRecords");
    Route::get('downloadDNSTypeRecords', "DNSUtilityController@downloadDNSTypeRecords")->name("downloadDNSTypeRecords");
    Route::get('downloadDNSCountryRecords', "DNSUtilityController@downloadDNSCountryRecords")->name("downloadDNSCountryRecords");
});

Route::group(['prefix' => '/xml404' ], function () {
    Route::get('/', "XML404Controller@index")->name("xml404-home");
    Route::get('validate-sitemap-xml', "XML404Controller@validateSiteMapXml")->name("validateSiteMapXml");
});


Route::group(['prefix' => 'admin', "namespace" => "Admin"], function () {

  Route::get('/', 'Auth\LoginController@showLoginForm')->name('adminlogin');
  Route::get('/login', 'Auth\LoginController@showLoginForm')->name('adminloginpage');
  Route::post('/', 'Auth\LoginController@login');
  Route::post('logout', 'Auth\LoginController@logout')->name('adminlogout');

  Route::group(['middleware' => 'AdminAuth'], function () {
      /****************     Routes For Dashboard          ****************/
      Route::get('dashboard', 'HomeController@index')->name('dashboard');
      Route::get('users', 'HomeController@listUser');
      Route::get('users/datatable', 'HomeController@datatable');

      /****************     Routes For Logs          ****************/
      Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

      Route::get('logs/{fileName}', 'HomeController@viewLogFile');

      /****************     Routes For Translations Keys          ****************/
      Route::get('trnsl-keys/datatable', 'TrnslKeyController@datatable');
      Route::put('trnsl-keys/reset-counters', 'TrnslKeyController@resetCounts');
      Route::resource('trnsl-keys', 'TrnslKeyController');
      Route::post('trnsl-keys/status', 'TrnslKeyController@changeStatus');


      /****************     Routes For Translations Requests          ****************/
      Route::get('trnsl-requests/datatable', 'TrnslRequestsController@datatable');
      Route::get('trnsl-requests/send-mail/{id}', 'TrnslRequestsController@sendMail');
      Route::get('trnsl-requests/{id}', 'TrnslRequestsController@enqueueTrnslRequests');
      Route::resource('trnsl-requests', 'TrnslRequestsController');

      /****************     Routes For Push Try Requests          ****************/
      Route::get('pushtry-requests/datatable', 'PushTryRequestController@datatable');
      Route::resource('pushtry-requests', 'PushTryRequestController');

      /****************     Routes For Jobs Table           ****************/
      Route::get('jobs-table/datatable', 'JobsController@datatable');
      Route::resource('jobs-table', 'JobsController');

      /****************     Routes For Jobs Table           ****************/
      Route::get('ssl-checker/datatable', 'SSLCheckerController@datatable');
      Route::resource('ssl-checker', 'SSLCheckerController');

      /****************     Routes For Chrome Extension Table           ****************/
      Route::post('chrome-ext/upload-image', 'ChromeExtController@uploadImage');
      Route::resource('chrome-ext', 'ChromeExtController');

  });

});

Route::group(['prefix' => '/sslchecker'], function () {
    Route::get('/', "SslCheckController@index")->name("sslchecker.home");
    Route::post('/getSSLDetails', "SslCheckController@getSSLDetails")->name("sslchecker.getDetails");
    Route::post('/subscribe', "SslCheckController@updateMailForGetReminder")->name("sslchecker.subscribe");
});




