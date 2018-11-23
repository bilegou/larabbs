<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class FollowersController extends Controller
{

	 public function __construct()
    {
        $this->middleware('auth');
    }

	//关注此人，并做他的粉丝。
    public function store(User $user){

    	if(!Auth::user()->isfollowing($user->id)){

    		Auth::user()->follow($user->id);
    	}

    	return redirect()->route('users.show', $user->id);

    }

    public function destroy(User $user){

    	if(Auth::user()->isfollowing($user->id)){

    		Auth::user()->unfollow($user->id);
    	}
        
    	return redirect()->route('users.show', $user->id);
    }
}
