<?php
$APP_PATH = storage_path('app/public');
$APP_URL = env('APP_URL');
return [

    'yandex_api_key' => env('yandex_api_key', 'YANDEX_KEY_REQUIRED'),

    // Upload PATH
    "PATH" => [
        "UPLOAD_TRANSLATION_FILE"       => $APP_PATH . "/filelangjson/",
        "UPLOAD_CERTIFICATE_FILE"       => $APP_PATH . "/",
    ],

    // Download Path
    "URL" => [
        "TRANSLATION_FILE"                   => $APP_URL . "storage/filelangjson/",
        "CERTIFICATE_FILE"                   => $APP_URL . "storage/",
    ],

];
