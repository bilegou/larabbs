<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Auth;

class RepliesController extends Controller
{
   
	public function store(ReplyRequest $request,Reply $reply)
	{


 		$content = clean($request->get('content'));

		if(empty($content)){
			return redirect()->back()->with('success','别乱搞老哥。');
		}

		$reply->topic_id = $request->topic_id;
		$reply->user_id = Auth::user()->id;
		$reply->content = $request->content;
		$reply->save();

		return redirect()->to($reply->topic->link())->with('success', '创建成功！');
	}

	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();
		return redirect()->route('topics.show',$reply->topic_id)->with('success', '删除成功！');
	}
}