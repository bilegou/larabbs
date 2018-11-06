<?php 

namespace app\Transformers;

use app\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract{


	public function transform(Category $category){

		return [

			'id'=>$category->id,
			'name'=>$category->name,
			'description'=>$category->description,
		];
	}
}