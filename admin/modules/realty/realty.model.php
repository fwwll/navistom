<?php

class ModelRealty {
	
	public function getRealtyList($filter = null) {
		
		switch ($filter) {
			case 'moderation':
				$where = "WHERE flag_moder = 0";
			break;
			case 'removed':
				$where = "WHERE flag_delete = 1";
			break;
		}
		
		$query = "SELECT realty_id, name, user_id, user_name, date_add, flag, flag_moder, flag_delete
			FROM `realty`
			$where
			ORDER BY date_add DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getRealtyData($realty_id) {
		$query = "SELECT *, (SELECT region_id FROM `cities` WHERE city_id = realty.city_id) AS region_id FROM `realty` WHERE realty_id = $realty_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getRealtyImages($realty_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/realty/80x100/', url_full) AS url_full, description
			FROM `realty_images` WHERE realty_id = $realty_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function editRealty($realty_id, $realty_data, $images, $images_descr) {
		if ($realty_id > 0 and is_array($realty_data)) {
			DB::update('realty', array(
				'categ_id'			=> $realty_data['categ_id'],
				'city_id'			=> $realty_data['city_id'],
				'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$realty_data['city_id']}"),
				'currency_id'		=> $realty_data['currency_id'],
				'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$realty_data['currency_id']}"),
				'price'				=> $realty_data['price'],
				'price_description'	=> $realty_data['price_description'],
				'name'				=> $realty_data['name'],
				'address'			=> $realty_data['address'],
				'content'			=> $realty_data['content'],
				'video_link'		=> $realty_data['video_link'],
				'flag'				=> $realty_data['flag'],
				'flag_moder'		=> $realty_data['flag_moder']
			), array(
				'realty_id'			=> $realty_id
			));
			
			if (is_array($images)) {
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('realty_images', array(
						'realty_id' 	=> $realty_id,
						'description'	=> $images_descr[$images[$i]],
						'sort_id'		=> $i
					), array(
						'image_id'		=> $images[$i]
					));
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	public function getCategoriesFromSelect() {
		$query = "SELECT categ_id, name FROM `categories_realty` ORDER BY sort_id";
		
		return DB::getAssocKey($query);
	}
	
	public function getCategoriesList() {
		$query = "SELECT categ_id, name, date_add, date_edit FROM `categories_realty` ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getCategoryData($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_realty` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function addCategory($name, $title, $meta_title, $meta_descr, $meta_keys) {
		DB::insert('categories_realty', array(
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
		DB::update('categories_realty', array(
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
	
	public function deleteCategory($categ_id) {
		DB::delete('categories_realty', array(
			'categ_id' => $categ_id
		));
		
		return true;
	}
	
	public function categorySorted($sort) {
		$query = "UPDATE `categories_realty` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['categ']); $i < $c; $i++) { 
			$query .= " WHEN categ_id = " . (int)$sort['categ'][$i] . " THEN $i ";
		}
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `realty_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/realty/full/' 	. $image);
			unlink(UPLOADS . '/images/realty/160x200/' 	. $image);
			unlink(UPLOADS . '/images/realty/80x100/' 	. $image);
			unlink(UPLOADS . '/images/realty/64x80/' 	. $image);
			
			DB::delete('realty_images', array('image_id' => $image_id));
			
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
			
			$image->Process(UPLOADS . '/images/realty/full/');
			
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
			
			$image->Process(UPLOADS . '/images/realty/160x200/');
			
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
			
			$image->Process(UPLOADS . '/images/realty/80x100/');
			
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
			
			$image->Process(UPLOADS . '/images/realty/64x80/');
			
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
				
				DB::insert('realty_images', $write);
				
				$image_id = DB::lastInsertId();
				
				$result = array(
					'uploadName' 	=> '/uploads/images/realty/80x100/' . $image_name . '.jpg',
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