<?php

class ModelDemand {
	
	public function getDemandList($filter = null) {
		switch ($filter) {
			case 'moderation':
				$where = "WHERE flag_moder = 0";
			break;
			case 'removed':
				$where = "WHERE flag_delete = 1";
			break;
		}
		
		$query = "SELECT demand_id, user_id, user_name, name, date_add, date_edit, flag, flag_moder, flag_delete, flag_vip_add
			FROM `demand`
			$where
			ORDER BY date_add DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getDemandData($demand_id) {
		$query = "SELECT * FROM `demand` WHERE demand_id = $demand_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getDemandImages($demand_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/demand/80x100/', url_full) AS url_full, description
			FROM `demand_images` WHERE demand_id = $demand_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function edit($demand_id, $demand_data, $images, $images_descr) {
		DB::update('demand', array(
			'name'			=> $demand_data['name'],
			'content'		=> $demand_data['content'],
			'video_link'	=> $demand_data['video_link'],
			'flag_moder'	=> $demand_data['flag_moder'],
			'flag'			=> $demand_data['flag']
		), array(
			'demand_id'		=> $demand_id
		));
		
		if (is_array($images)) {
			for ($i = 0, $c = count($images); $i < $c; $i++) {
				DB::update('demand_images', array(
					'demand_id' 	=> $demand_id,
					'description'	=> $images_descr[$images[$i]],
					'sort_id'		=> $i
				), array(
					'image_id'		=> $images[$i]
				));
			}
		}
		
		return true;
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `demand_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/demand/full/' 	. $image);
			unlink(UPLOADS . '/images/demand/160x200/' 	. $image);
			unlink(UPLOADS . '/images/demand/80x100/' 	. $image);
			unlink(UPLOADS . '/images/demand/64x80/' 	. $image);
			
			DB::delete('demand_images', array('image_id' => $image_id));
			
			return true;
		}
		
		return false;
	}
	
	public function uploadImage() {
		
		include_once(LIBS.'upload/upload.class.php');
		
		$image = new upload($_FILES['qqfile']);
		
		$image_name = Str::get()->generate(20);
		
		if ($image->uploaded) {
			$image->file_new_name_body 		= $image_name;
			$image->image_resize       		= true;
			$image->image_ratio_fill   		= true;
			$image->image_convert 			= 'jpg';
			$image->image_y            		= 560;
			$image->image_x            		= 700;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/demand/full/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 160;
			$image->image_x             	= 200;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/demand/160x200/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 80;
			$image->image_x             	= 100;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/demand/80x100/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 64;
			$image->image_x             	= 80;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/demand/64x80/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			if ($image->processed) {
				$image->Clean();
				
				$write = array(
					'url_full'	=> $image_name . '.jpg'
				);
				
				DB::insert('demand_images', $write);
				
				$image_id = DB::lastInsertId();
				
				$result = array(
					'uploadName' 	=> '/uploads/images/demand/80x100/' . $image_name . '.jpg',
					'success'		=> true,
					'image_id'		=> $image_id
				);
			}
			else {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
		}
		else {
			$result = array(
				'uploadName' 	=> '',
				'success'		=> false,
				'image_id'		=> 0
			);
		}
		
		return $result;
	}
}