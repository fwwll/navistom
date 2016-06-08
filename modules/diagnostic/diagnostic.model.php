<?php

class ModelDiagnostic {
	
	public function getDiagnosticList($country_id = 1, $city_id = 0, $user_id = 0, $page = 1, $count = 10, $search = null, $is_updates = 0, $flag = null) {
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND IF(d.user_id = '" . User::isUser() . "', 1, d.flag = 1 AND d.flag_moder = 1)";
		}
		
		if ($city_id > 0) {
			$where .= "AND d.city_id = $city_id";
		}
		
		if ($user_id > 0) {
			$where .= "AND d.user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND d.flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), d.date_add) > 13';
		}
		
		if ($search != null) {
			$match = "AND MATCH(d.name, d.content) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
			/* $orderr_by = "ORDER BY IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), d.date_add DESC"; */
			$orderr_by = "ORDER BY IFNULL(sort__id, 99999), d.date_add DESC";
		}
		
		$date = DB::now(1);
		
		$query = "SELECT d.diagnostic_id, d.user_id, d.user_name, d.contact_phones, d.city_id, d.city_name, d.address, d.name, d.flag, d.flag_moder, flag_vip_add,
			d.date_add, i.url_full, 
			(SELECT t.sort_id FROM `top_to_section` as t INNER JOIN liqpay_status as liq ON liq.ads_id=t.resource_id and liq.section_id =10  WHERE t.section_id = 10 AND t.resource_id = d.diagnostic_id AND DATE_SUB(liq.start_competitor, INTERVAL 1 DAY) < '$date' AND liq.end_competitor > '$date') AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 10 AND resource_id = d.diagnostic_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			flag_moder_view,
			liq.color_yellow,
			liq.urgently
			FROM `diagnostic` AS d
			LEFT JOIN liqpay_status liq ON d.diagnostic_id=liq.ads_id AND  liq.section_id=10
			LEFT JOIN `diagnostic_images` AS i ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
			WHERE flag_delete = 0 $match
			$where
			$orderr_by";
		
		$diagnostic = DB::DBObject()->query($query);
		$diagnostic->execute();

		while ($array = $diagnostic->fetch(PDO::FETCH_ASSOC)) {
			$array['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $array['contact_phones']));
			$result[] = $array;
		}
		
		return $result;
	}
	
	public function getDiagnosticFull($diagnostic_id) {
		$query = "SELECT d.diagnostic_id, d.user_id, d.user_name, d.contact_phones, d.city_id, d.city_name, d.address, d.name, d.attach, d.link, d.flag_delete, d.country_id,
			d.date_add, i.url_full, d.video_link, d.content, flag, flag_moder,
			(SELECT COUNT(*) FROM `diagnostic_views` WHERE diagnostic_id = d.diagnostic_id) AS views,
			liq.urgently,
			liq.color_yellow
			FROM `diagnostic` AS d
			LEFT JOIN liqpay_status liq ON d.diagnostic_id=liq.ads_id AND  liq.section_id=10
			LEFT JOIN `diagnostic_images` AS i ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
			WHERE " . (User::isAdmin() ? '1' : "IF(d.user_id = '" . User::isUser() . "', 1, d.flag = 1 AND d.flag_moder = 1)") . " AND d.diagnostic_id = $diagnostic_id";
		
		$diagnostic = DB::getAssocArray($query, 1);
		
		$diagnostic['phones'] 		= @explode(',', $diagnostic['contact_phones']);
		$diagnostic['video_link']	= str_replace('watch?v=', '', end(explode('/',  $diagnostic['video_link'])));
		
		return $diagnostic;
	}
	
	public function getVIP($country_id, $city_id, $diagnostic_id) {
		$date = DB::now(1);
		
		$query = "SELECT 
			d.diagnostic_id,
			d.name,
			d.city_name,
			d.user_name,
			i.url_full AS image
			FROM `liqpay_status` AS t
			INNER JOIN `diagnostic` AS d  ON d.diagnostic_id = t.ads_id AND d.city_id = $city_id AND d.country_id = $country_id
			LEFT JOIN `diagnostic_images` AS i ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
			
			WHERE t.section_id = 10 AND t.ads_id != $diagnostic_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date' AND show_competitor >2
			ORDER BY RAND()";
		
		return DB::getAssocArray($query);
	}
	
	public function getDiagnosticGallery($diagnostic_id) {
		$query = "SELECT url_full, description FROM `diagnostic_images` WHERE diagnostic_id = $diagnostic_id AND sort_id > 0 ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function setViews($user_id, $diagnostic_id) {
		if (Request::getCookie('diagnostic_view_' . $diagnostic_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'diagnostic_id'	=> $diagnostic_id,
				'user_id'		=> $user_id,
				'date_view'		=> DB::now()
			);
			
			DB::insert('diagnostic_views', $write);
			
			Request::setCookie('diagnostic_view_' . $diagnostic_id, 1);
		}
		
		return true;
	}
	
	public function getDiagnosticCities($country_id = 1) {
		$query = "SELECT city_id, name,
			(SELECT COUNT(*) FROM `diagnostic` WHERE city_id = cities.city_id) AS count
			FROM `cities`
			WHERE city_id IN(SELECT DISTINCT city_id FROM `diagnostic` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id)
			ORDER BY sort_id, name";
		
		return DB::getAssocArray($query);
	}
	
	public function add($user_id, $country_id, $diagnostic_data, $images, $flag_moder = 0) {
		if (is_array($diagnostic_data)) {
			DB::insert('diagnostic', array(
				'user_id'			=> $user_id,
				'user_name'			=> $diagnostic_data['user_name'],
				'contact_phones'	=> $diagnostic_data['contact_phones'],
				'country_id'		=> $country_id,
				'city_id'			=> $diagnostic_data['city_id'],
				'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$diagnostic_data['city_id']}"),
				'address'			=> $diagnostic_data['address'],
				'name'				=> $diagnostic_data['name'],
				'content'			=> $diagnostic_data['content'],
				'attach'			=> $diagnostic_data['attach'],
				'link'				=> $diagnostic_data['link'],
				'video_link'		=> $diagnostic_data['video_link'],
				'date_add'			=> DB::now(),
				'flag_moder'		=> $flag_moder,
				'flag_vip_add'		=> $diagnostic_data['flag_vip_add']
			));
			
			$diagnostic_id = DB::lastInsertId();
			
			if (is_array($images)) {
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('diagnostic_images', array(
						'diagnostic_id' 	=> $diagnostic_id,
						'sort_id'			=> $i
					), array(
						'image_id'	=> $images[$i]
					));
				}
			}
			
			return $diagnostic_id;
		}
		
		return false;
	}
	
	public function edit($diagnostic_id, $diagnostic_data, $images, $images_descr) {
		DB::update('diagnostic', array(
			'user_name'			=> $diagnostic_data['user_name'],
			'city_id'			=> $diagnostic_data['city_id'],
			'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$diagnostic_data['city_id']}"),
			'address'			=> $diagnostic_data['address'],
			'name'				=> $diagnostic_data['name'],
			'content'			=> $diagnostic_data['content'],
			'attach'			=> $diagnostic_data['attach'],
			'link'				=> $diagnostic_data['link'],
			'video_link'		=> $diagnostic_data['video_link'],
			'contact_phones'	=> $diagnostic_data['contact_phones']
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
		
		if(Site::isModerView('diagnostic', 'diagnostic_id', $diagnostic_id)) {
			Site::PublicLink(
                'http://navistom.com/diagnostic/' .
				$diagnostic_id . '-' . Str::get($diagnostic_data['name'])->truncate(60)->translitURL()
			);
		}
	}
	
	public function delete($diagnostic_id) {
		DB::update('diagnostic', array(
			'flag_delete'		=> 1
		), array(
			'diagnostic_id'		=> $diagnostic_id
		));
		
		return true;
	}
	
	public function editFlag($diagnostic_id, $flag = 0) {
		DB::update('diagnostic', array(
			'flag'			=> $flag
		), array(
			'diagnostic_id'	=> $diagnostic_id
		));
		
		return true;
	}
	
	public function editFlagModer($diagnostic_id, $flag_moder = 0) {
		DB::update('diagnostic', array(
			'flag_moder'	=> $flag_moder
		), array(
			'diagnostic_id'	=> $diagnostic_id
		));
		
		return true;
	}
	
	public function getUserId($diagnostic_id) {
		return DB::getColumn("SELECT user_id FROM `diagnostic` WHERE diagnostic_id = $diagnostic_id");
	}
	
	public function getDiagnosticData($diagnostic_id) {
		$query = "SELECT *,
			(SELECT region_id FROM `cities` WHERE city_id = diagnostic.city_id) AS region_id
			FROM `diagnostic`
			WHERE diagnostic_id = $diagnostic_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getDiagnosticImages($diagnostic_id) {
		$images = "SELECT image_id, url_full, description
			FROM `diagnostic_images` WHERE diagnostic_id = $diagnostic_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function uploadAttach($file) {
		include_once(LIBS.'upload/upload.class.php');
		
		$attach = new upload($file);
		$attach->allowed = array('application/pdf','application/msword');
		
		$file_name = Str::get()->generate(20);
		
		if ($attach->uploaded) {
			$attach->file_new_name_body = $file_name;
			$attach->Process(UPLOADS . '/docs/');
			
			if (!$attach->processed) {
				Debug::setError('UPLOAD', $attach->error, 0, __FILE__, __LINE__);
				
				return false;
			}
			
			return $file_name . '.' . $attach->file_src_name_ext;
		}
		
		return false;
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
		
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/diagnostic/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/diagnostic/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/diagnostic/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/diagnostic/64x80/'
			))
		);
		
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
		
		return $result;
	}
}