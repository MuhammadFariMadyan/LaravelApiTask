<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// user routes v1/users

Route::group(['prefix' => 'v1/users', 'namespace' => 'Api\v1\users'], function () {


    Route::post('register', 'UserAuthController@register');
    /*for multiple access token*/
    //Route::post('login', 'AuthController@multipleLogin');
    /*for single access token*/
    Route::post('login', 'UserAuthController@singleLogin');


    Route::middleware('VerifyJWTToken')->group(function () {

        // Add Mobile and Email Routes
        Route::post('mobileadd', 'UserAuthController@addMobileOnly');
        Route::post('emailadd', 'UserAuthController@addEmailOnly');

        // Update Mobile and Email Routes
        Route::post('mobileupdate', 'UserAuthController@updateMobileOnly');
        Route::post('emailupdate', 'UserAuthController@updateEmailOnly');

        // get User By Token
        Route::get('getuser', 'UserAuthController@getUser');

        //Delete Mobile and Email
        Route::post('mobiledelete', 'UserAuthController@deleteMobileOnly');
        Route::post('emaildelete', 'UserAuthController@deleteEmailOnly');

    });


    //Route::post('emailupdate', 'UserAuthController@addMobileOnly');

    Route::post('forgotPassword', 'AuthController@forgotPassword');
//
//    Route::middleware('VerifyJWTToken')->group(function () {
//
//        Route::post('updatePassword', 'UserController@updatePassword');
//        Route::get('getProfile/{uuid?}', 'UserController@getProfile');
//        Route::post('createProfile', 'UserController@createProfile');
//        Route::post('updateProfile', 'UserController@updateProfile');
//        /*for multiple access token*/
//        //Route::post('logout','AuthController@logout');
//        /*for single access token*/
//        Route::post('logout', 'AuthController@singleLogout');
//    });
//
//
});


