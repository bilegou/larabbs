<?php 

namespace App\Models\Traits;
use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use DB;
use Cache;

trait ActiveUserHelper{

	//计算积分相关的参数
	protected $users =[];
	protected $topic_weight = 4;
	protected $reply_weight = 1;
	protected $pass_days = 7;
	protected $user_number = 6;
	 // 缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_minutes = 65;


    public function getActiveUsers(){

    	// 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key,$this->cache_expire_in_minutes,function(){

        	return $this->calculateActiveUsers();
        });
    }

	public function cacheActiveUsers($active_users){
		// 将数据放入缓存中
		Cache::put($this->cache_key,$active_users,$this->cache_expire_in_minutes);
	}

	//生成活跃用户的方法。
	public function calculateAndCacheActiveUsers(){

		$active_users = $this->calculateActiveUsers();
		
		$this->cacheActiveUsers($active_users);		
	}

	private function calculateActiveUsers(){

		$this->calculateTopicScore();
		$this->calculateReplyScore();

		//按照积分的多少排序
		$users = array_sort($this->users,function($user){
			return $user['score'];
		});

		//倒序排行，高分考前，键名不变
		$users = array_reverse($users,true);

		//获取我们想要的数量 ，0-6之间 ，并且保留原来的键名
		$users = array_slice($users, 0,$this->user_number,true);

		//创建一个空数组。
		$active_users = collect();

		foreach ($users as $user_id => $user) {
		
			// 找寻下是否可以找到用户
			$user = $this->find($user_id);

		// 如果数据库里有该用户的话
			if($user){
				 // 将此用户实体放入集合的末尾
				$active_users->push($user);
			}
		}

		return $active_users;
	}

	private function calculateTopicScore(){

		$topic_users = Topic::query()
						->select(DB::raw('user_id,count(*) as topic_count'))
						->where('created_at','>=',Carbon::now()->subDays($this->pass_days))
						->groupBy('user_id')
						->get();

		foreach ($topic_users as  $value) {
			# code...
			$this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
		}
	}

	private function calculateReplyScore(){

		$reply_users = Reply::query()
						->select(DB::raw('user_id,count(*) as reply_count'))
						->where('created_at','>=',Carbon::now()->subDays($this->pass_days))
						->groupBy('user_id')
						->get();

		foreach ($reply_users as $value) {
				
			$reply_socore = $this->reply_weight * $value->reply_count;

			if(isset($this->users[$value->user_id])){

				$this->users[$value->user_id]['score'] += $reply_socore;
		}else{
				$this->users[$value->user_id]['score'] = $reply_socore;
			}
		}
	}

}

