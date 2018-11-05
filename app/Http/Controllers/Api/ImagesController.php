<?php
namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;
use App\Transformers\ImageTransformer;
use App\Http\Requests\Api\ImageRequest;

class ImagesController extends Controller
{
    public function store(ImageRequest $request,Image $image,ImageUploadHandler $upload){

    	$user = $this->user();

    	$size = $request->type  == 'avatar' ? 362 : 1024;

    	$result = $upload->save($request->image,str_plural($request->type),$user->id,$size);

    	$image->type = $request->type;

    	$image->path =  $result['path'];

    	$image->user_id = $user->id;

    	$image->save();


    	return $this->response->item($image,new ImageTransformer)->setStatusCode(201);

    }
}
