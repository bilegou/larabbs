<?php
namespace app\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Follower;
use App\Models\User;

class FollowerTransformer extends TransformerAbstract{

	public function transform(Follower $follower){

		return [
			'id' =>$follower->id,
			'user_id' => $follower->user_id,
			'follower_id' => $follower->follower_id,
			'created_at' => $follower->created_at->toDateTimeString(),
            'updated_at' => $follower->updated_at->toDateTimeString(),
		];
	}
}