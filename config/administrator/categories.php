<?php 

use App\Models\Category;

return [

		'title'=>'分类',
		'single'=>'分类',
		'model'=>Category::class,

		//hasRole只有站长才能删除
		'action_permission'=>[
			'delete'=>function(){
				Auth::user()->hasRole('Founder');;
			}
		],

		'columns'=>[

			'id'=>[
				'title'=>'ID',
			],
			'name'=>[
				'title'=>'分类',
				'sortable'=>false,
				'output'=>function($name){
					return $name;
				}
			],

			'description' => [

				'title'=>'描述',
				'sortable'=>false,
			],

			'operation'=>[
				'title'=>'管理',
				'sortable'=>false,
			],
	
		],

		'edit_fields'=>[

			'name'=>[
				'title'=>'分类'
			],

			'description'=>[
				'title'=>'描述'
			],
		],

		'filters'=>[
			'id'=>[
				'title'=>'ID'
			],

			'name'=>[
				'title'=>'分类'
			],

			'description'=>[
				'title'=>'描述'
			],
		],

		'rules'=>[

			'name'=>'required|min:1|unique:categories'
		],

		'message'=>[
			'name.unique'   => '分类名在数据库里有重复，请选用其他名称。',
       		'name.required' => '请确保名字至少一个字符以上',

		],


];