<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function root(){

    	return view('pages.root');
    }

    public function permissionDenied()
    {
    	if(config('administator.permission')){

    		return redirect(url(config('administator.url')),302);
    	}
    	
    	return view('pages.permission_denied');
    }
}
