<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\UserTransformer;
use App\Transformers\TopicTransformer;
use Auth;

class FollowersController extends Controller
{
    public function followersIndex(){

    	$user = $this->user();

    	$followers = $user->followers()->paginate(10);

    	return $this->response->paginator($followers,new UserTransformer());
    }

    public function followingsIndex(){

    	$user = $this->user();

    	$followings = $user->followings()->paginate(10);

    	return $this->response->paginator($followings,new UserTransformer());
    }


    public function store(User $user){

    	 if(!Auth::user()->isfollowing($user->id)){

    		Auth::user()->follow($user->id);

    		return "关注成功！";
    	}else{


    		return "关注失败，已关注。";
    	}

    	
    }

    public function destroy(User $user){

    	if(Auth::user()->isfollowing($user->id)){

    	   Auth::user()->unfollow($user->id);

    		return "取消关注成功！";

    	}else{

    		return "取消关注失败，并未关注。";
    	}

    }

    public function followingsTopics(){

        $feed_items = [];
        $feed_items = Auth::user()->feed()->paginate(30);
        
        return $this->response->paginator($feed_items,new TopicTransformer());
    }








}
