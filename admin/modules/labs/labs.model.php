<?php

class ModelLabs {
	
	public function getLabsList($filter = null) {
		switch ($filter) {
			case 'moderation':
				$where = "WHERE flag_moder = 0";
			break;
			case 'removed':
				$where = "WHERE flag_delete = 1";
			break;
		}
		
		$query = "SELECT lab_id, user_id, user_name, date_add, date_edit, flag, flag_moder,
			(SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_labs` WHERE categ_id IN(SELECT categ_id FROM `labs_categs` WHERE lab_id = labs.lab_id)) AS categs
			FROM `labs`
			$where
			ORDER BY date_add DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getLabData($lab_id) {
		$query = "SELECT *,
			(SELECT GROUP_CONCAT(categ_id) FROM `labs_categs` WHERE lab_id = labs.lab_id) AS categ_id 
			FROM `labs` WHERE lab_id = $lab_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getLabImages($lab_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/labs/80x100/', url_full) AS url_full, description
			FROM `labs_images` WHERE lab_id = $lab_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function edit($lab_id, $lab_data, $categs, $images, $images_descr) {
		DB::update('labs', array(
			'user_name'			=> $lab_data['user_name'],
			'region_id'			=> $lab_data['region_id'],
			'region_name'		=> DB::getColumn("SELECT name FROM `regions` WHERE region_id = {$lab_data['region_id']}"),
			'address'			=> $lab_data['address'],
			'content'			=> $lab_data['content'],
			'video_link'		=> $lab_data['video_link'],
			'flag_moder'		=> $lab_data['flag_moder']
		), array(
			'lab_id'			=> $lab_id
		));
		
		DB::delete('labs_categs', array(
			'lab_id'	=> $lab_id
		));
		
		for ($i = 0, $c = count($categs); $i < $c; $i++) {
			DB::insert('labs_categs', array(
				'lab_id'	=> $lab_id,
				'categ_id'	=> $categs[$i]
			));
		}
		
		if (is_array($images)) {
			for ($i = 0, $c = count($images); $i < $c; $i++) {
				DB::update('labs_images', array(
					'lab_id' 		=> $lab_id,
					'description'	=> $images_descr[$images[$i]],
					'sort_id'		=> $i
				), array(
					'image_id'	=> $images[$i]
				));
			}
		}
	}
	
	public function getCategoriesList() {
		$query = "SELECT categ_id, name, date_add, date_edit FROM `categories_labs` ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getCategoriesFromSelect() {
		$query = "SELECT categ_id, name FROM `categories_labs` ORDER BY sort_id";
		
		return DB::getAssocKey($query);
	}
	
	public function addCategory($name, $title, $meta_title, $meta_descr, $meta_keys) {
		DB::insert('categories_labs', array(
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
		DB::update('categories_labs', array(
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
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_labs` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function deleteCategory($categ_id) {
		DB::delete('categories_labs', array(
			'categ_id' => $categ_id
		));
		
		return true;
	}
	
	public function getJobsList() {
		$query = "SELECT job_id, name, date_add, date_edit, flag_moder FROM `jobs` ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getJobData($job_id) {
		$query = "SELECT name, meta_title, meta_description, meta_keys, flag_moder FROM `jobs` WHERE job_id = $job_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function addJob($name, $meta_title, $meta_descr, $meta_keys, $flag_moder) {
		DB::insert('jobs', array(
			'name'				=> $name,
			'meta_title'		=> $meta_title,
			'meta_description'	=> $meta_descr,
			'meta_keys'			=> $meta_keys,
			'flag_moder'		=> $flag_moder,
			'date_add'			=> DB::now()
		));
		
		return DB::lastInsertId();
	}
	
	public function editJob($job_id, $name, $meta_title, $meta_descr, $meta_keys, $flag_moder) {
		DB::update('jobs', array(
			'name'				=> $name,
			'meta_title'		=> $meta_title,
			'meta_description'	=> $meta_descr,
			'meta_keys'			=> $meta_keys,
			'flag_moder'		=> $flag_moder
		), array(
			'job_id'			=> $job_id
		));
		
		return true;
	}
	
	public function deleteJob($job_id) {
		DB::delete('jobs', array(
			'job_id' => $job_id
		));
		
		return true;
	}
	
	public function jobSorted($sort) {
		$query = "UPDATE `jobs` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['categ']); $i < $c; $i++) { 
			$query .= " WHEN job_id = " . (int)$sort['categ'][$i] . " THEN $i ";
		}
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
	}
	
	public function categorySorted($sort) {
		$query = "UPDATE `categories_labs` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['categ']); $i < $c; $i++) { 
			$query .= " WHEN categ_id = " . (int)$sort['categ'][$i] . " THEN $i ";
		}
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `labs_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/labs/full/' 		. $image);
			unlink(UPLOADS . '/images/labs/160x200/' 	. $image);
			unlink(UPLOADS . '/images/labs/80x100/' 	. $image);
			unlink(UPLOADS . '/images/labs/64x80/' 		. $image);
			
			DB::delete('labs_images', array('image_id' => $image_id));
			
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
			
			$image->Process(UPLOADS . '/images/labs/full/');
			
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
			
			$image->Process(UPLOADS . '/images/labs/160x200/');
			
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
			
			$image->Process(UPLOADS . '/images/labs/80x100/');
			
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
			
			$image->Process(UPLOADS . '/images/labs/64x80/');
			
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
				
				DB::insert('labs_images', $write);
				
				$image_id = DB::lastInsertId();
				
				$result = array(
					'uploadName' 	=> '/uploads/images/labs/80x100/' . $image_name . '.jpg',
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