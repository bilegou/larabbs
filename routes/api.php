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
                    'middleware'=>['serializer:array', 'bindings','change-locale']
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

    //小程序注册接口
    $api->post('weapp/users', 'UsersController@weappStore')->name('api.weapp.users.store');

    //用户验证码
    $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');

    //第三方登录
    $api->post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')->name('api.socials.authorizations.store');

    //登陆
    $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');

    //小程序登录
    $api->post('weapp/authorizations', 'AuthorizationsController@weappStore')->name('api.weapp.authorizations.store');

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

        //资源推荐接口
        $api->get('links','LinksController@index')->name('api.links.index');

        //活跃用户接口
        $api->get('actived/users','UsersController@activedIndex')->name('api.actived.users.activedIndex');

        //分类接口
        $api->get('categories','CategoriesController@index')->name('api.categories.index');

        //获取用户信息（游客）
        $api->get('users/{user}','UsersController@show')->name('api.users.show');
        

        // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function($api) {

            // 当前登录用户信息接口
            $api->get('user', 'UsersController@me')->name('api.user.show');

            //更新用户资料接口
            $api->patch('user','UsersController@update')->name('api.user.update');

            //图片资源接口
            $api->post('images', 'ImagesController@store')->name('api.images.store');

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

            //通知列表接口
            $api->get('user/notifications','NotificationsController@index')->name('api.user.notifications.index');

            //未读消息统计接口
            $api->get('user/notifications/stats','NotificationsController@stat')->name('api.user.notifications.stat');

            // //标记已读接口
            // $api->get('user/notifications/read','NotificationsController@read')->name('api.user.notifications.read'); 
            $api->patch('user/notifications/read/{id?}', 'NotificationsController@read')->name('api.user.notifications.read');
            //put
            $api->put('user/read/notifications', 'NotificationsController@read')->name('api.user.notifications.read.put');
            //当前登录用户权限
            $api->get('user/permissions','permissionsController@index')->name('api.user.permissions.index');

            //粉丝接口
            $api->get('user/followers','FollowersController@followersIndex')->name('api.user.followers.followersIndex');

            //关注人接口
            $api->get('user/followings','FollowersController@followingsIndex')->name('api.user.followers.followingsIndex');

            //关注接口
            $api->post('users/{user}/followers','FollowersController@store')->name('api.user.follower.store');

            //取消关注接口
            $api->delete('users/{user}/followers','FollowersController@destroy')->name('api.user.follower.destroy');

            //关注人的文章接口
            $api->get('users/followingsTopics','FollowersController@followingsTopics')->name('api.user.followingsTopics');

            //小程序个人资料更新接口
            $api->put('user', 'UsersController@update')->name('api.user.update');

        });
    });

});




























