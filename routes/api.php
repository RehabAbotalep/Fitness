<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

///////////////////////////////Auth Routes//////////////////////////
Route::group(['namespace' =>'Api'], function () {

	Route::post('register', 'AuthController@register');

	Route::post('verify','AuthController@verify');

	Route::post('resend','AuthController@resend');

	Route::post('login','AuthController@login');

	Route::post('password/forget','AuthController@forgetPassword');

	Route::post('password/change','AuthController@newPassword');

	Route::post('social/login','SocialController@socialLogin');

	Route::put('profile/update', 'ProfileController@update');

	Route::post('email/verify', 'ProfileController@verifyUpdatedMail');

	Route::get('profile/get', 'ProfileController@profile');
		
});

////////////////////////////////////////Trainer Routes////////////////////////

Route::group(['middleware'=> ['role:trainer' ,'auth:api' , 'approved_trainer'],
	'namespace'=>'Api\Trainer'], function () {

	Route::resource('courses', 'CourseController');

	Route::post('course/store/video', 'CourseController@storeVideo');
});



////////////////////////////////////////////Admin Routes//////////////////////

Route::group(['middleware'=> ['role:admin' ,'auth:api'],'namespace' => 'Api\Admin'], function () {

	Route::get('trainers','TrainerController@trainers');

	Route::get('trainers/{id}/approve','TrainerController@approve');

	Route::get('trainers/{id}/cancel','TrainerController@cancel');
});
