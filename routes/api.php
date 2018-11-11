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

$api->version('v1',[
                    'namespace' => 'App\Http\Controllers\Api',
                    'middleware'=>['serializer:array', 'bindings']
],function($api) {

	$api->group([
		'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'), 
        'expires' => config('api.rate_limits.sign.expires'),
	],function($api){
	//短信验证码
    $api->post('verificationCodes', 'VerificationCodesController@store')->name('api.verificationCodes.store');

    //用户接口注册
    $api->post('users', 'UsersController@store')->name('api.users.store');

    //用户验证码
    $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');

    //第三方登录
    $api->post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')->name('api.socials.authorizations.store');

    //登陆
    $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');

    //更新token
    $api->put('authorizations/current','AuthorizationsController@update')->name('api.authorizations.update');

    //删除token
    $api->delete('authorizations/current','AuthorizationsController@destroy')->name('api.authorizations.destroy');

   });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 游客可以访问的接口

        //文章显示接口
        $api->get('topics','TopicsController@index')->name('api.topics.index');

        //指定用户文章显示接口
        $api->get('users/{user}/topics','TopicsController@userIndex')->name('api.topics.userIndex');

        //文章内容显示接口
        $api->get('topics/{topic}', 'TopicsController@show')->name('api.topics.show');

        //获取文章回复接口
        $api->get('topics/{topic}/replies','RepliesController@index')->name('api.topics.replies.index');
        
        //按用户查询回复接口
        $api->get('users/{user}/replies','RepliesController@userIndex')->name('api.users.replies.index');


        // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function($api) {

            // 当前登录用户信息接口
            $api->get('user', 'UsersController@me')->name('api.user.show');

            //更新用户资料接口
            $api->patch('user','UsersController@update')->name('api.user.update');

            //图片资源接口
            $api->post('images', 'ImagesController@store')->name('api.images.store');

            //分类接口
            $api->get('categories','CategoriesController@index')->name('api.categories.index');

            //增添文章接口
            $api->post('topics','TopicsController@store')->name('api.topics.store');

            //修改文章接口
            $api->patch('topics/{topic}','TopicsController@update')->name('api.topics.update');

            //删除文章的接口
            $api->delete('topics/{topic}','TopicsController@destroy')->name('api.topics.destroy');

            //新增回复接口
            $api->post('topics/{topic}/replies','RepliesController@store')->name('api.topics.replies.store');

            //删除回复接口
            $api->delete('topics/{topic}/replies/{reply}','RepliesController@destroy')->name('api.topics.replies.destroy');


        });
    });

});




























