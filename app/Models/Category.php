<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    	protected $fillable = [  'name', 'description',];

    	// public function topics(){

    	// 	return $this->hasOne(Topics::class,'category_id','id');
    	// }

}
