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
    Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');
});

Route::namespace('Api')->middleware('auth:api')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::get('/', 'ApiController@index');
        Route::get('users/{user}', 'ApiController@show');

        Route::namespace('Member')->group(function () {
            Route::get('properties', 'PropertyController@index');
            Route::get('properties/{property}', 'PropertyController@show');

            Route::post('profiles', 'ProfileController@update');
            Route::post('profiles/identification', 'ProfileController@identificationUpload');
            Route::post('requests', 'RequestController@store');
            Route::post('settlements/authorization', 'SettlementController@authorization');
        });

        // 会員メンバーのみアクセス可能
        Route::middleware('membership')->group(function () {
            Route::get('works', 'WorkController@index');
            Route::post('works', 'WorkController@store');
            Route::get('works/{work}', 'WorkController@show');
            Route::put('works/{work}', 'WorkController@update');
            Route::delete('works/{work}', 'WorkController@destroy');
        });

        Route::namespace('Admin')->middleware('admin')->group(function () {
            Route::get('admin', 'HomeController@index');
            Route::get('admin/users/{user}', 'UserController@show');
            Route::get('admin/requests/{request}/download', 'RequestController@download');
            Route::post('admin/settlements/capture', 'SettlementController@capture');
        });
    });
});
