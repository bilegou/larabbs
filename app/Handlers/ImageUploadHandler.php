<?php 
namespace App\Handlers;
use Image;
class ImageUploadHandler{
	
	protected $allowed_ext = ["png", "jpg", "gif", "jpeg","bmp"];


	public function save($file,$folder,$file_prefix,$max_width=false){

		//文件夹的名称
		$folder_name = "uploads/images/$folder/".date("Ym/d",time());

		//文件具体存储的物理路经
		$upload_path = public_path().'/'.$folder_name;

		//后缀名
		$extention  = strtolower($file->getClientOriginalExtension()) ?:'png';	

		//文件名
		$filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extention;

		if(!in_array($extention, $this->allowed_ext)){

			return false;
		}

		$file->move($upload_path,$filename);

		if($max_width && $extention != 'gif'){
			
				$this->reduceSize($upload_path.'/'.$filename,$max_width);
		}

		return ["path"=>config('app.url')."/$folder_name/$filename"];
	}

	public function reduceSize($file_path,$max_width){

			$image = Image::make($file_path);

			$image->resize($max_width,null,function($constraint){

				 $constraint->aspectRatio();
				 $constraint->upsize();
			});

			$image->save();
	}


	// public function avatarReduceSize($file_path,$max_width){

	// 		$image = Image::make($file_path);
	// 		$image->resize(180,180);
	// 		$image->save();
	// }

}