<?php

class ModelDiagnostic {
	
	public function getDiagnosticList($filter = null) {
		switch ($filter) {
			case 'moderation':
				$where = "WHERE flag_moder = 0";
			break;
			case 'removed':
				$where = "WHERE flag_delete = 1";
			break;
		}
		
		$query = "SELECT diagnostic_id, user_id, user_name, name, date_add, date_edit, flag, flag_moder, flag_delete
			FROM `diagnostic`
			$where
			ORDER BY date_add DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getDiagnosticData($diagnostic_id) {
		$query = "SELECT *,
			(SELECT region_id FROM `cities` WHERE city_id = diagnostic.city_id) AS region_id
			FROM `diagnostic`
			WHERE diagnostic_id = $diagnostic_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getDiagnosticImages($diagnostic_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/diagnostic/80x100/', url_full) AS url_full, description
			FROM `diagnostic_images` WHERE diagnostic_id = $diagnostic_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function edit($diagnostic_id, $diagnostic_data, $images, $images_descr) {
		DB::update('diagnostic', array(
			'user_name'			=> $diagnostic_data['user_name'],
			'city_id'			=> $diagnostic_data['city_id'],
			'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$diagnostic_data['city_id']}"),
			'address'			=> $diagnostic_data['address'],
			'name'				=> $diagnostic_data['name'],
			'content'			=> $diagnostic_data['content'],
			'video_link'		=> $diagnostic_data['video_link'],
			'flag_moder'		=> $diagnostic_data['flag_moder'],
			'flag'				=> $diagnostic_data['flag']
		), array(
			'diagnostic_id'		=> $diagnostic_id
		));
		
		if (is_array($images)) {
			for ($i = 0, $c = count($images); $i < $c; $i++) {
				DB::update('diagnostic_images', array(
					'diagnostic_id' 	=> $diagnostic_id,
					'description'		=> $images_descr[$images[$i]],
					'sort_id'			=> $i
				), array(
					'image_id'			=> $images[$i]
				));
			}
		}
	}
	
	public function getStatisticWeek() {
		$query = "SELECT d.diagnostic_id, d.name,
			COUNT(v.diagnostic_id) AS views,
			MAX(v.date_view) AS last_view
			FROM `diagnostic_views` AS v
			INNER JOIN `diagnostic` AS d USING(diagnostic_id)
			GROUP BY d.diagnostic_id
			ORDER BY views DESC
			LIMIT 10";
		
		return DB::getAssocArray($query);
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `diagnostic_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/diagnostic/full/' 	. $image);
			unlink(UPLOADS . '/images/diagnostic/160x200/' 	. $image);
			unlink(UPLOADS . '/images/diagnostic/80x100/' 	. $image);
			unlink(UPLOADS . '/images/diagnostic/64x80/' 	. $image);
			
			DB::delete('diagnostic_images', array('image_id' => $image_id));
			
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
			
			$image->Process(UPLOADS . '/images/diagnostic/full/');
			
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
			
			$image->Process(UPLOADS . '/images/diagnostic/160x200/');
			
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
			
			$image->Process(UPLOADS . '/images/diagnostic/80x100/');
			
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
			
			$image->Process(UPLOADS . '/images/diagnostic/64x80/');
			
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
				
				DB::insert('diagnostic_images', $write);
				
				$image_id = DB::lastInsertId();
				
				$result = array(
					'uploadName' 	=> '/uploads/images/diagnostic/80x100/' . $image_name . '.jpg',
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