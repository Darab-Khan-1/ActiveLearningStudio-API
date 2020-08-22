<?php

use Illuminate\Support\Facades\Route;

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

Route::post('register', 'Auth\AuthController@register')->name('register');
Route::post('login', 'Auth\AuthController@login')->name('login');
Route::post('forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('reset-password', 'Auth\ResetPasswordController@reset');
Route::post('verify-email', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('verify-email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::post('logout', 'Auth\AuthController@logout')->name('logout')->middleware(['auth:api', 'verified']);

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::get('users/me', 'UserController@me');
        Route::apiResource('users', 'UserController');

        Route::post('projects/upload-thumb', 'ProjectController@uploadThumb');
        Route::post('projects/{project}/share-project', 'ProjectController@share');
        Route::post('projects/{project}/remove-share-project', 'ProjectController@removeShare');
        Route::apiResource('projects', 'ProjectController');

        Route::apiResource('projects.playlists', 'PlaylistController');

        Route::apiResource('playlists.activities', 'ActivityController');
    });

    Route::get('error', 'ErrorController@show')->name('api/error');
});