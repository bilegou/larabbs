<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar'=>'mimes:jpeg,bmp,png,gif|dimensions:min_width:=200,min_height=200',
        ];
    }

    public function messages(){

        return [

            'avatar.mimes'=>'头像必须是 jpeg, bmp, png, gif 格式的图片。',
            'avatar.dimensions'=>'图片的清晰度不够。',
            'name.unique'=>'名字已被占用',
            'name.regex'=>'只能使用英文字母，横杠和下划线作为名字',
            'name.between'=>'名称在3-25个字符之间',
            'name.required'=>'名称必填',

        ];
    }
}
