<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{

    use HasRoles;

    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    use Notifiable {
        notify as protected laravelNotify;
    }


    public function notify($instance){

        if ($this->id == Auth::id()) {
        return;
        }

        $this->increment('notification_count',1);
        $this->laravelNotify($instance);
    }

    public function markAsRead(Notification $notification = null) {
        if ($notification) {    //标记单条已读
            --$this->notification_count;
            $notification->markAsRead();
        } else {    //标记全部已读
            $this->notification_count = 0;
            $this->unreadNotifications->markAsRead();
        }

        $this->save();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar','phone','weixin_openid','weixin_unionid','registration_id',
    ];

    /**
     * The attributes that should be hidden     for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function topics(){

        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model){

      return $this->id == $model->user_id;
    }

    //一个用户对多条回复信息
    public function replies(){

        return $this->hasMany(Reply::class);
    }

    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if ( ! starts_with($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

        public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function followers(){

        return $this->belongsToMany(User::class,'followers','user_id','follower_id'); //其实查出来的是follower_id，也就是追随者，也就是粉丝
    }

    public function followings(){

        return $this->belongsToMany(User::class,'followers','follower_id','user_id');

    }

    public function follow($user_ids){

        if(!is_array($user_ids)){

            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);

    }

    public function unfollow($user_ids){

        if(!is_array($user_ids)){

            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids,false);
    }

    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }

    public function feed(){
        
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids,Auth::user()->id);

        return Topic::whereIn('user_id',$user_ids)->with('user')->orderBy('created_at', 'desc');
    }


}
