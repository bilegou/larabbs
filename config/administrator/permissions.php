<?php 
use Spatie\Permission\Models\Permission;

return [

	'title'=>'权限',
	'single'=>'权限',
	'model'=>Permission::class,

	'permission'=>function(){

		return Auth::user()->can('manage_users');

	},

	'action_permission'=>[

		// 控制『新建按钮』的显示
		'create'=>function($model){

			return ture;
		},
 		// 允许更新
		'update'=>function($model){

			return ture;
		},
		 // 不允许删除
		'delete'=>function($model){

			return false;
		},
		// 允许查看
		'view'=>function($model){

			return ture;
		}

	],

	'columns'=>[

		'id'=>[
			'title'=>'ID',
		],
		'name'=>[
			'title'=>'标识',
		],
		'operation'=>[
			'operation'=>'管理',
			 'sortable' => false,
		],
	],

	'edit_fields'=>[

		'name'=>[
			'title'=>'标识（请慎重修改）',
			 // 表单条目标题旁的『提示信息』
			'hint'=>'修改权限标识会影响代码的调用，请不要轻易更改。',
		],

		'roles'=>[
			'type'=>'relationship',
			'title'=>'角色',
			'name_field'=>'name'
		],
	],

	'filters'=>[
		'name'=>[
			'title'=>'标识',
		],
	],

];
