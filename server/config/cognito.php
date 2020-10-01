<?php
return [
    'region'            => 'ap-northeast-1',
    'version'           => '2016-04-18',
    'app_client_id'     => env('COGNITO_CLIENT_ID'),
    'app_client_secret' => env('COGNITO_CLIENT_SECRET'),
    'user_pool_id'      => env('USER_POOL_ID'),
];