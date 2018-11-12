<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\NotificationTransformer;
use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationsController extends Controller
{
    public function index(){

    	$notifications = $this->user->notifications()->paginate(20);

    	return $this->response->paginator($notifications,new NotificationTransformer());
    }

    public function stat(){

    	return [

    		'unread_count'=>$this->user()->notification_count
    	];
    }


    public function read(Notification $notification){

        $notification->id ? $this->user()->markAsRead($notification) : $this->user()->markAsRead();

        return $this->response->noContent();
    }
}
