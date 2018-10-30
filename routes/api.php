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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/test', 'PagesController@root')->name('root');

// Route::get('/test/{year}/{month}', 'PagesController@getCount')->name('getcount');

// Route::match(['get','post'],'/postuser/{id?}/{name?}','PagesController@postCount')->name('postcount');

// Route::post('/validate','Test@requestData');

//dingo api
$api = app('Dingo\Api\Routing\Router');

$api->version('v1',['namespace' => 'App\Http\Controllers\Api'],function($api) {


	$api->group([
		'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'), 
        'expires' => config('api.rate_limits.sign.expires'),
	],function($api){
	//短信验证码
    $api->post('verificationCodes', 'VerificationCodesController@store')->name('api.verificationCodes.store');

   //用户验证码
    $api->post('users', 'UsersController@store')->name('api.users.store');
    
    $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');

   });

});



