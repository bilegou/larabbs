<?php 
	
use App\Models\Link;

return[

	'title'=>'资源推荐',
	'single'=>'资源推荐',

	'model'=>Link::class,

	'permission'=>function(){
		
		return Auth::user()->hasRole('Founder');
	},

	'columns'=>[

		'id'=>[

			'title'=>'iD'
		],

		'title'=>[

			'title'=>'资源推荐标题',
			'sortable' => false,
		],

		'link'=>[

			'title'=>'链接',
			'sortable' => false,
		],
		
		'operation'=>[

		'title'=>'管理',
		'sortable' => false,

		],

	],

	'edit_fields'=>[

		'title'=>[

			'title'=>'推荐标题'
		],

		'link'=>[
			'title'=>'链接'
		]

	],

	 'filters' => [
        'id' => [
            'title' => '标签 ID',
        ],
        'title' => [
            'title' => '名称',
        ],
    ],

];