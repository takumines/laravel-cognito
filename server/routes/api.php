<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('Api')->group(function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset/{token}', 'Auth\ResetPasswordController@reset');
});

Route::namespace('Api')->middleware('auth:api')->group(function () {
    Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify');

    Route::middleware('verified')->group(function () {
        Route::post('email/resend', 'Auth\VerificationController@resend');
        Route::get('/', 'ApiController@index');
        Route::get('/users/{user}', 'ApiController@show');

        Route::namespace('Admin')->middleware('admin')->group(function () {
            Route::get('admin', 'HomeController@index');
            Route::get('admin/users/{user}', 'UserController@show');
        });
    });
});
