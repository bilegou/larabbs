<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class Test{



	public function requestData(Request $Request){

		$email = $Request->email;
		$username = $Request->name;

		$pattern = "/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/";

		 //用户名验证
		if(strlen($username)<3){

			return json_encode("字符不能小于3");

		}else{

			return json_encode("正确");

		}

		//邮箱验证

		if($result = preg_match($pattern, $email)){

			return json_encode("正确");

		}else{

			return json_encode("邮箱格式不符合！");
		}



	}

}