<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Dingo\Api\Routing\Helpers;

class Controller extends BaseController
{
    use Helpers;


    public function errorResponse($statusCode,$message=null,$code=0){


		throw new HttpException($statusCode, $message, null, [], $code);
    }
}
