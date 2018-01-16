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
    return view('welcome');
});
Route::get('/forbidden', function () {
    return view('web.forbidden');
});

Route::get('/verifyEmail/{uuid}/{emailToken}', 'Verification\AccountController@verifyEmail');
/*Route::middleware('signedurl')->group(function (){
	Route::get('/forgotPassword/{uuid}/{forgotPasswordCode}','Web\AuthController@resetPasswordView');
});*/
Route::get('/forgotPassword/{uuid}/{forgotPasswordCode}', 'Web\AuthController@resetPasswordView');

// Routes for Admin Panel
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', 'AccountController@showLoginForm');
    Route::post('login', 'AccountController@login');
    Route::get('forgotPasswordPage', 'AccountController@forgotPasswordView');
    Route::post('forgotPassword', 'AccountController@forgotPassword');
    Route::get('resetPassword/{token}', 'AccountController@checkResetPassword');
    Route::post('updatePassword', 'AccountController@updatePassword');

    Route::group(['middleware' => 'auth.admin'], function () {
        Route::post('logout', 'AccountController@logout');
        Route::get('editProfile', 'DashboardController@adminEditProfile');
        Route::post('updateProfile', 'DashboardController@updateProfile');
        Route::get('changePassword', 'DashboardController@changePassword');
        Route::post('editPassword', 'DashboardController@editPassword');
        Route::get('dashboard', 'DashboardController@index');
        Route::resource('users', 'UserController', ['except' => [
            'create', 'edit'
        ]]);
        Route::get('users/userStatus/{uuid?}/{status?}', 'UserController@userStatus');

    });
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
