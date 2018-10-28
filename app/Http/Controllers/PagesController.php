<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use App\Models\User;
use App\Http\Controllers\Test;
use DB;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    protected $token = 'API';

    public function root(){

        $data  = $user->find(1);
        return response()->json(['status'=>1,'msg'=>'查询成功！','data'=>$data]);

    }

    public function ajax(){

        return view('pages.ajax');

    }

    public function getCount($id,$name){

         $users = DB::table('users')->where(['id'=>$id,'name'=>$name])->get();

         return response()->json($users);

    }

    public function postCount(Request $Request,$id = '',$name = ''){

        $id = $Request->id;

        $name = $Request->name;

        $users = DB::table('users')->where(['id'=>$id,'name'=>$name])->get();

        return $users;
    }
    
    public function permissionDenied()
    {
    	if(config('administator.permission')){

    		return redirect(url(config('administator.url')),302);
    	}
    
    	return view('pages.permission_denied');
    }


    public function getWeatherData(){

        $timeStamp = time();
        $randomStr = $this->createNonceStr();
        $signature = $this->arithmetic($timeStamp,$randomStr);

        $url = "http://larabbs.test/respon_weather/t/{$timeStamp}/r/{$randomStr}/s/{$signature}";

        $client = new Client();

        $response = $client->request('GET',$url);

        $result = $response->getbody();

        return $result;
        

    }

    private function createNonceStr($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            }
        return "z".$str;
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
