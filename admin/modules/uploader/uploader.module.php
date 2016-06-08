<?php

class Uploader {
	public function index() {
		die('Иди на хуй!');
	}
	
	public function uploadEditorImage() {
		$image = new upload($_FILES['file']);
		
		$image_name = Str::get()->generate(20);
		
		if ($image->uploaded) {
			$image->file_new_name_body 		= $image_name;
			$image->image_convert 			= 'jpg';
			$image->image_background_color 	= '#FFFFFF';
			$image->image_x            		= 600;
			
			$image->Process(UPLOADS . '/images/editor/');
				
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				return false;
			}
		}
		
		echo json_encode(array(
			'filelink' => '/uploads/images/editor/' . $image_name . '.jpg'
		));
	}
	
	public function uploadArticleImages() {
		if ($_FILES['qqfile']['name'] != null) {
			$image = new upload($_FILES['qqfile']);
			
			$image_name = Str::get()->generate(20);
			
			if ($image->uploaded) {
				$image->file_new_name_body 		= $image_name;
				$image->image_resize       		= true;
				$image->image_ratio		= true;
				$image->image_crop            	= '-3px -10%';
				$image->image_convert 			= 'jpg';
				$image->image_y            		= 560;
				$image->image_x            		= 700;
				$image->image_background_color 	= '#00ff00';
				
				$image->Process(UPLOADS . '/images/articles/full/');
				
				if (!$image->processed) {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
				
				$image->file_new_name_body 		= $image_name;
				$image->image_resize        	= true;
				$image->image_ratio_fill    	= true;
				$image->image_ratio_no_zoom_in	= true;
				$image->image_convert 			= 'jpg';
				$image->image_y             	= 100;
				$image->image_x             	= 150;
				$image->image_background_color 	= '#FFFFFF';
				
				$image->Process(UPLOADS . '/images/articles/100x150/');
				
				if (!$image->processed) {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
				
				$image->file_new_name_body 		= $image_name;
				$image->image_resize        	= true;
				$image->image_ratio_fill    	= true;
				$image->image_convert 			= 'jpg';
				$image->image_y             	= 50;
				$image->image_x             	= 75;
				$image->image_background_color 	= '#FFFFFF';
				
				$image->Process(UPLOADS . '/images/articles/50x75/');
				
				if (!$image->processed) {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
				
				$image->file_new_name_body 		= $image_name;
				$image->image_resize        	= true;
				$image->image_ratio_fill    	= true;
				$image->image_convert 			= 'jpg';
				$image->image_y             	= 175;
				$image->image_x             	= 250;
				$image->image_background_color 	= '#FFFFFF';
				
				$image->Process(UPLOADS . '/images/articles/175x250/');
				
				if (!$image->processed) {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
				
				if ($image->processed) {
					$image->Clean();
					
					$write = array(
						'url_full'	=> $image_name . '.jpg'
					);
					
					DB::insert('articles_images', $write);
					
					$image_id = DB::lastInsertId();
					
					$result = array(
						'uploadName' 	=> '/uploads/images/articles/full/' . $image_name . '.jpg',
						'success'		=> true,
						'image_id'		=> $image_id
					);
				}
				else {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
		
		header("Content-Type: text/plain");
		echo json_encode($result);
	}
	
	public function deleteArticleImages($image_id) {
		$image = DB::getColumn("SELECT url_full FROM `articles_images` WHERE id = $image_id");
		
	}
	
	public function uploadImages() {
		include_once(CLASSES . 'qqFileUploader.class.php');
		
		$uploader = new qqFileUploader();

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$uploader->allowedExtensions = array("jpeg", "jpg", "bmp", "png");
		
		// Specify max file size in bytes.
		$uploader->sizeLimit = 10 * 1024 * 1024;
		
		// Specify the input name set in the javascript.
		$uploader->inputName = 'qqfile';
		
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uploader->chunksFolder = UPLOADS;
		
		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		$result = $uploader->handleUpload(UPLOADS . '/images/', 'test_image.bmp');
		
		// To save the upload with a specified name, set the second parameter.
		// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());
		
		// To return a name used for uploaded file you can use the following line.
		$result['uploadName'] = '/uploads/images/' . $uploader->getUploadName();
		
		header("Content-Type: text/plain");
		echo json_encode($result);
	}
}