<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\WeappAuthorizationRequest;


class AuthorizationsController extends Controller
{

	public function store(AuthorizationRequest $request){ //一般登录，手机、email

    	$username = $request->username; //用户名（手机或email）

    	filter_var($username,FILTER_VALIDATE_EMAIL)?$credentials['email'] = $username:$credentials['phone'] = $username;

    	$credentials['password'] = $request->password;

    	if(!$token = Auth::guard('api')->attempt($credentials)){

    		$this->response->errorUnauthorized(trans('auth.failed'));
    	}

    	return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function weappStore(WeappAuthorizationRequest $request){

            $code = $request->code;

            $miniProgram = \EasyWeChat::miniProgram(); //调用easyWechat

            $data = $miniProgram->auth->session($code);//用code去获取openid和session_key
            
            if(isset($data['errcode'])){ //判断是否code错误返回

                return $this->response->errorUnauthorized('code 不正确');
            } 

            $user = User::where('weapp_openid',$data['openid'])->first();//用openid去查询是否存在openid注册登录过的用户

            $attributes['weixin_session_key'] = $data['session_key'];  //将session_key存储在$attributes数组做更新的内容

            if(!$user){ //若没有查询到这个用户

                if(!$request->username){
                    return $this->response->errorForbidden('用户不存在'); //如果未提交用户名，403 错误提示，给app跳转到登录界面
                }

                $username = $request->username;
                //判断登录用户是用email登录的还是用phone登录的
                filter_var($username,FILTER_VALIDATE_EMAIL)?$credentials['email'] = $username : $credentials['phone'] = $username;

                $credentials['password'] = $request->password; //密码赋值验证准备

                if(!Auth::guard('api')->attempt($credentials)){ //验证判断

                    return $this->response->errorUnauthorized('用户名或密码错误');  //账号密码登录错误的 直接返回登录账号和密码错误
                }

                $user = Auth::guard('api')->getUser(); //若正确则登录获取的用户整个model
                $attributes['weapp_openid'] = $data['openid']; //并且将获取到的openid赋值给$attributes数组一并更新
            }

            $user->update($attributes); //更新用户的session_key和openid

            $token = Auth::guard('api')->fromUser($user); //获得用户的token

            return $this->respondWithToken($token)->setStatusCode(201);//将token返回给app

    }

    public function socialStore($type,SocialAuthorizationRequest $request){ //第三方登录

    	if(!in_array($type,['weixin'])){

    		return $this->response()->errorBadRequest();
    	}

    	$driver = \Socialite::driver($type);

    	try{

    		if($code = $request->code){

                $response = $driver->getAccessTokenResponse($code);
                $token = array_get($response, 'access_token');

    		}else{

    			$token = $request->access_token;

    			if($type == 'weixin'){

    				$openid = $request->openid;
    				$driver->setOpenId($openid);
    			}
    		}

    		$oauthUser = $driver->userFromToken($token);

    	}catch (\Exception $e){

    		return $this->response->errorUnauthorized('参数错误，未获取用户信息');
    	}


    	switch ($type) {
    		case 'weixin':
    				$unionid = $oauthUser->offsetExists('unionid')?$oauthUser->offsetGet('unionid'):null;
    				if($unionid){

    					$user = User::where('weixin_unionid',$unionid)->first();
    				}else{

    					$user = User::where('weixin_openid',$oauthUser->getId())->first();
    				}

    				if(!$user){
    				$user = User::create([

    						'name'=>$oauthUser->getNickname(),
    						'avatar'=>$oauthUser->getAvatar(),
    						'weixin_openid'=>$oauthUser->getId(),
    						'weixin_unionid'=>$unionid,
    					]);
    				}
    				break;
    	}
    	$token = Auth::guard('api')->fromUser($user);
    	return $this->respondWithToken($token)->setStatusCode(201);
    }
    
    public function respondWithToken($token){	//统一请求返回token格式
        
    	return $this->response->array([
			'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
    	]);

    }

    public function update(){

    	$token = Auth::guard('api')->refresh();
    	return $this->respondWithToken($token);
    }

    public function destroy(){

    	Auth::guard('api')->logout();
    	return $this->response->noContent();

    }
}
