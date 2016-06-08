<?php

class ModelServices {
	
	public function getServicesList($filter = null) {
		switch ($filter) {
			case 'moderation':
				$where = "WHERE flag_moder = 0";
			break;
			case 'removed':
				$where = "WHERE flag_delete = 1";
			break;
		}
		
		$query = "SELECT service_id, name, user_id, user_name, date_add, date_edit, flag, flag_moder
			FROM `services`
			$where
			ORDER BY date_add DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getServiceData($service_id) {
		$query = "SELECT *,
			(SELECT GROUP_CONCAT(categ_id) FROM `services_categs` WHERE service_id = services.service_id) AS categ_id 
			FROM `services` WHERE service_id = $service_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getServiceImages($service_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/services/80x100/', url_full) AS url_full, description
			FROM `services_images` WHERE service_id = $service_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function edit($service_id, $service_data, $categs, $images, $images_descr) {
		DB::update('services', array(
			'user_name'			=> $service_data['user_name'],
			'region_id'			=> $service_data['region_id'],
			'city_id'			=> $service_data['city_id'],
			'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$service_data['city_id']}"),
			'address'			=> $service_data['address'],
			'name'				=> $service_data['name'],
			'content'			=> $service_data['content'],
			'video_link'		=> $service_data['video_link'],
			'flag_moder'		=> $service_data['flag_moder'],
			'flag'				=> $service_data['flag']
		), array(
			'service_id'		=> $service_id
		));
		
		DB::delete('services_categs', array(
			'service_id'	=> $service_id
		));
		
		for ($i = 0, $c = count($categs); $i < $c; $i++) {
			DB::insert('services_categs', array(
				'service_id'	=> $service_id,
				'categ_id'		=> $categs[$i]
			));
		}
		
		if (is_array($images)) {
			for ($i = 0, $c = count($images); $i < $c; $i++) {
				DB::update('services_images', array(
					'service_id' 	=> $service_id,
					'description'	=> $images_descr[$images[$i]],
					'sort_id'		=> $i
				), array(
					'image_id'		=> $images[$i]
				));
			}
		}
	}
	
	public function getCategoriesFromSelect() {
		$query = "SELECT categ_id, name FROM `categories_services` ORDER BY sort_id";
		
		return DB::getAssocKey($query);
	}
	
	public function getCategoriesList() {
		$query = "SELECT categ_id, name, date_add, date_edit FROM `categories_services` ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function addCategory($name, $title, $meta_title, $meta_descr, $meta_keys) {
		DB::insert('categories_services', array(
			'name'				=> $name,
			'title'				=> $title,
			'meta_title'		=> $meta_title,
			'meta_description'	=> $meta_descr,
			'meta_keys'			=> $meta_keys,
			'date_add'			=> DB::now()
		));
		
		return DB::lastInsertId();
	}
	
	public function editCategory($categ_id, $name, $title, $meta_title, $meta_descr, $meta_keys) {
		DB::update('categories_services', array(
			'name'				=> $name,
			'title'				=> $title,
			'meta_title'		=> $meta_title,
			'meta_description'	=> $meta_descr,
			'meta_keys'			=> $meta_keys
		), array(
			'categ_id'			=> $categ_id
		));
		
		return true;
	}
	
	public function getCategoryData($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_services` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function deleteCategory($categ_id) {
		DB::delete('categories_services', array(
			'categ_id' => $categ_id
		));
		
		return true;
	}
	
	public function categorySorted($sort) {
		$query = "UPDATE `categories_services` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['categ']); $i < $c; $i++) { 
			$query .= " WHEN categ_id = " . (int)$sort['categ'][$i] . " THEN $i ";
		}
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `services_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/services/full/' 		. $image);
			unlink(UPLOADS . '/images/services/160x200/' 	. $image);
			unlink(UPLOADS . '/images/services/80x100/' 	. $image);
			unlink(UPLOADS . '/images/services/64x80/' 		. $image);
			
			DB::delete('services_images', array('image_id' => $image_id));
			
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
			
			$image->Process(UPLOADS . '/images/services/full/');
			
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
			
			$image->Process(UPLOADS . '/images/services/160x200/');
			
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
			
			$image->Process(UPLOADS . '/images/services/80x100/');
			
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
			
			$image->Process(UPLOADS . '/images/services/64x80/');
			
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
				
				DB::insert('services_images', $write);
				
				$image_id = DB::lastInsertId();
				
				$result = array(
					'uploadName' 	=> '/uploads/images/services/80x100/' . $image_name . '.jpg',
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