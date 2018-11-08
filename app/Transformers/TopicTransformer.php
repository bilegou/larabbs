<?php 
namespace app\Transformers;

use App\Models\Topic;
use League\Fractal\TransformerAbstract;
use App\Transformers\UserTransformer;
use App\Transformers\CategoryTransformer;


class TopicTransformer extends TransformerAbstract{

	protected $availableIncludes = ['user', 'category'];//启用包括的关联方法
	
	public function transform(Topic $topic){

		return [

			'id' => $topic->id,
            'title' => $topic->title,
            'body' => $topic->body,
            'user_id' => (int) $topic->user_id,
            'category_id' => (int) $topic->category_id,
            'reply_count' => (int) $topic->reply_count,
            'view_count' => (int) $topic->view_count,
            'last_reply_user_id' => (int) $topic->last_reply_user_id,
            'excerpt' => $topic->excerpt,
            'slug' => $topic->slug,
            'created_at' => $topic->created_at->toDateTimeString(),
            'updated_at' => $topic->updated_at->toDateTimeString(),

		];

	}
	//关联用户的方法
	public function includeUser(Topic $topic){

		return $this->item($topic->user,new UserTransformer());
	}
	//关联分类的方法
	public function includeCategory(Topic $topic){

		return $this->item($topic->category,new CategoryTransformer());
	}
}