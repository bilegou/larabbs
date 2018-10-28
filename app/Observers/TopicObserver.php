<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use DB;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic){
        //xss
		$topic->body = clean($topic->body, 'user_topic_body');
        //生成摘录
    	$topic->excerpt = make_excerpt($topic->body);

        //每保存一篇文章，category类多增加一个文章数量。

      
    

    }

    public function saved(Topic $topic){

        if(!$topic->slug){
            dispatch(new TranslateSlug($topic));
        }

          DB::table('categories')->where('id',$topic->category_id)->increment('post_count',1);
    }

    public function deleted(Topic $topic){

            //每删除一个文章，便删除所有的回复，以免报错。
            \DB::table('replies')->where('topic_id',$topic->id)->delete();  

            //每保存一篇文章，category类多减少一个文章数量。
            DB::table('categories')->where('id',$topic->category_id)->decrement('post_count',1);
    }
}