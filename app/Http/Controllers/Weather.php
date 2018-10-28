<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon;

class Weather{

	protected $token = 'API';

	public function respon(Request $Request){

		$timeStamp = $Request->timeStamp;

		$randomStr = $Request->randomStr;

		$signature = $Request->signature;

		$str = $this->arithmetic($timeStamp,$randomStr);

		  $arr['name'] = 'api';
		  $arr['age'] = 15;
		  $arr['address'] = 'zz';
		  $arr['ip'] = "192.168.0.1";	

		if($str !== $signature){

			echo "-1";
		}else{

		return json_encode($arr);
	}
}

	 private function arithmetic($timeStamp,$randomStr){
	  $arr['timeStamp'] = $timeStamp;
	  $arr['randomStr'] = $randomStr;
	  $arr['token'] = $this->token;
	  //按照首字母大小写顺序排序
	  sort($arr,SORT_STRING);
	  //拼接成字符串
	  $str = implode($arr);
	  //进行加密
	  $signature = sha1($str);
	  $signature = md5($signature);
	  //转换成大写
	  $signature = strtoupper($signature);
	  return $signature;
	 }


}