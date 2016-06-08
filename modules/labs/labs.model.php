<?php

class ModelLabs {
	
	public function getLabsList($country_id = 1, $categ_id = 0, $city_id = 0, $user_id = 0, $page = 1, $count = 15, $search = null, $is_updates = 0, $city_id = 0, $flag = null) {
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND IF(user_id = '" . User::isUser() . "', 1, flag = 1 AND flag_moder = 1)";
		}
		
		if ($categ_id > 0) {
			$having = "HAVING FIND_IN_SET($categ_id, categs) > 0";
		}
		
		if ($region_id > 0) {
			$where .= "AND region_id = $region_id";
		}
		
		if ($city_id > 0) {
			$where .= "AND city_id = $city_id";
		}
		
		if ($user_id > 0) {
			$where .= "AND user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), date_add) > 13';
		}
		
		if ((string) $search != null) {
			$match = "AND MATCH(name, content) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
			//$orderr_by = "ORDER BY IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), l.date_add DESC";
			$orderr_by = "ORDER BY IF(sort__id, sort__id, 99999), l.date_add  DESC";
		}
		
		if ($categ_id > 0) {
			$sort_table = "top_to_category";
		}
		else {
			$sort_table = "top_to_section";
		}
		
		$date = DB::now(1);
		
		$query = "SELECT l.lab_id, l.user_id, l.user_name, l.contact_phones, l.region_name, l.address, l.date_add, l.flag, l.flag_moder, l.city_name, l.name, flag_vip_add,
			i.url_full AS image,
			(SELECT GROUP_CONCAT(categ_id) FROM `labs_categs` WHERE lab_id = l.lab_id) AS categs,
			(SELECT name FROM `users_info` WHERE user_id = l.user_id) AS user,
			(SELECT sort_id FROM `$sort_table` WHERE section_id = 7 AND resource_id = l.lab_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 7 AND resource_id = l.lab_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			flag_moder_view,
			liq.color_yellow,
			liq.urgently
			FROM `labs` AS l
			LEFT JOIN liqpay_status liq ON l.lab_id=liq.ads_id AND  liq.section_id=7
			LEFT JOIN `labs_images` AS i ON i.lab_id = l.lab_id AND i.sort_id = 0
			WHERE flag_delete = 0 AND country_id = $country_id $match
			$where 
			$having 
			$orderr_by
			LIMIT $limit, $count";
		
		  
		$labs = DB::DBObject()->query($query);
		$labs->execute();
		
		while ($lab = $labs->fetch(PDO::FETCH_ASSOC)) {
			$lab['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $lab['contact_phones']));
			$lab['categs'] = DB::getAssocKey("SELECT categ_id, name FROM `categories_labs` WHERE categ_id IN({$lab['categs']})");
			$array[] = $lab;
		}
		
		return $array;
	}
	
	public function getLabsCount($country_id = 1, $categ_id = 0, $region_id = 0, $user_id = 0, $search = null, $is_updates = 0, $city_id, $flag = null) {
		if ($categ_id > 0) {
			$where = "AND lab_id IN(SELECT DISTINCT lab_id FROM `labs_categs` WHERE categ_id = $categ_id)";
		}
		
		if ($region_id > 0) {
			$where .= " AND region_id = $region_id";
		}
		
		if ($city_id > 0) {
			$where .= " AND city_id = $city_id";
		}
		
		if ($user_id > 0) {
			$where .= " AND user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), date_add) > 13';
		}
		
		if ((string) $search != null) {
			$match = "AND MATCH(name, content) AGAINST('$search')";
			$orderr_by = '';
		}
		
		$query = "SELECT COUNT(*)
			FROM `labs`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0
			$match
			$where 
			$having ";
		
		return DB::getColumn($query);
	}
	
	public function getLabFull($lab_id) {
		$query = "SELECT l.lab_id, l.user_id, l.user_name, l.contact_phones, l.region_name, l.address, l.date_add, l.content, l.attach, l.city_name, l.city_id, l.link, l.name, l.country_id, l.region_id,
			l.video_link, i.url_full AS image, flag, flag_moder,
			(SELECT GROUP_CONCAT(categ_id) FROM `labs_categs` WHERE lab_id = l.lab_id) AS categs,
			(SELECT COUNT(*) FROM `labs_views` WHERE lab_id = l.lab_id) AS views,
			(SELECT name FROM `users_info` WHERE user_id = l.user_id) AS user,
			liq.urgently,
			liq.color_yellow
			FROM `labs` AS l
			LEFT JOIN liqpay_status liq ON l.lab_id=liq.ads_id AND  liq.section_id=7
			LEFT JOIN `labs_images` AS i ON i.lab_id = l.lab_id AND i.sort_id = 0
			WHERE  flag_delete = 0 AND l.lab_id = $lab_id";
		
		$lab = DB::getAssocArray($query, 1);
		
		$lab['phones'] = @explode(',', $lab['contact_phones']);
		$lab['categs'] = DB::getAssocKey("SELECT categ_id, name FROM `categories_labs` WHERE categ_id IN({$lab['categs']})");
		
		return $lab;
	}
	
	public function getVIP($country_id, $categs, $lab_id) {
		$date = DB::now(1);
		
		$categs = implode(',', array_keys($categs));
		
		/* $query = "SELECT 
			l.lab_id,
			l.name,
			l.city_name,
			l.user_name,
			i.url_full AS image,
			(SELECT name FROM `users_info` WHERE user_id = l.user_id) AS user,
			(SELECT GROUP_CONCAT(categ_id) FROM `labs_categs` WHERE lab_id = l.lab_id AND categ_id IN($categs)) AS categs
			FROM `top_to_main` AS t
			INNER JOIN `labs` AS l  ON l.lab_id = t.resource_id AND l.country_id = $country_id
			LEFT JOIN `labs_images` AS i ON i.lab_id = l.lab_id AND i.sort_id = 0
			WHERE t.section_id = 7 AND resource_id != $lab_id AND DATE_SUB(t.date_start, INTERVAL 1 DAY) < '$date' AND t.date_end > '$date'
			HAVING categs
			ORDER BY RAND()"; */
			$query = "SELECT 
			l.lab_id,
			l.name,
			l.city_name,
			l.user_name,
			l.flag,
			i.url_full AS image,
			(SELECT name FROM `users_info` WHERE user_id = l.user_id) AS user,
			(SELECT GROUP_CONCAT(categ_id) FROM `labs_categs` WHERE lab_id = l.lab_id ) AS categs,
			
			t.color_yellow,
		    t.urgently,
			
			(SELECT date_add from top_to_main WHERE section_id=7 AND resource_id = t.ads_id )as date_add ,
			if((SELECT date_end from top_to_main WHERE section_id=7 AND resource_id = t.ads_id AND 1)>$date ,1,0 )as show_top
			FROM `liqpay_status` AS t
			INNER JOIN `labs` AS l  ON l.lab_id = t.ads_id AND l.country_id = $country_id
			LEFT JOIN `labs_images` AS i ON i.lab_id = l.lab_id AND i.sort_id = 0
			WHERE t.section_id = 7 AND t.ads_id != $lab_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date' AND t.show_competitor>2 AND l.flag_delete <1;
			HAVING categs
			ORDER BY t.start_competitor ";
		
		$lab= DB::getAssocArray($query);
		foreach($lab as $k=>$v){
			$lab[$k]['categs'] = DB::getAssocKey("SELECT categ_id, name FROM `categories_labs` WHERE categ_id IN({$lab[$k]['categs']})");
		}  
		
		
		//Site::d($lab[$k]['categs']);
		return $lab;
	}
	
	public function setViews($user_id, $lab_id) {
		if (Request::getCookie('lab_view_' . $lab_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'lab_id'	=> $lab_id,
				'user_id'	=> $user_id,
				'date_view'	=> DB::now()
			);
			
			DB::insert('labs_views', $write);
			
			Request::setCookie('lab_view_' . $lab_id, 1);
		}
		
		return true;
	}
	
	public function getLabGallery($lab_id) {
		$query = "SELECT url_full, description FROM `labs_images` WHERE lab_id = $lab_id AND sort_id > 0 ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	/* public function getLabsRegionsList($country_id = 1, $categId = 0) {
        if ($categId) {
            $count = '(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND region_id = regions.region_id AND lab_id
				        IN(SELECT lab_id FROM `labs_categs` WHERE categ_id = '. $categId .')) AS count';
        }
        else {
            $count = '(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND region_id = regions.region_id) AS count';
        }

		$query = "SELECT region_id, name,
			$count
			FROM `regions`
			WHERE region_id IN(SELECT DISTINCT region_id FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id)
			HAVING count > 0
			ORDER BY sort_id, name";
		
		return DB::getAssocArray($query);
	} */
	
	
	
	
	public function getLabsRegionsList($country_id = 1, $categId = 0) {
        if ($categId) {
            $count = '(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND region_id = regions.region_id AND lab_id
				        IN(SELECT lab_id FROM `labs_categs` WHERE categ_id = '. $categId .')) AS count';
        }
        else {
            $count = '(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND region_id = regions.region_id) AS count';
        }

		$query = "SELECT region_id, name,
			$count
			FROM `regions`
			WHERE region_id IN(SELECT DISTINCT region_id FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id)
			HAVING count > 0
			ORDER BY sort_id, name";
		
		return DB::getAssocArray($query);
	}
	
	
	
	public function getLabsCities($country_id = 1,$count = false, $categId = 0) {
        if ($categId) {
            $count = '(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id AND lab_id
				        IN(SELECT lab_id FROM `labs_categs` WHERE categ_id = '. $categId .')) AS count';
        }
        else {
            $count = '(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id) AS count';
        }

		$query = "SELECT city_id, name,
			$count
			FROM `cities`
			WHERE city_id IN(SELECT DISTINCT city_id FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id)
			HAVING count > 0
			ORDER BY sort_id, name";
		
		return DB::getAssocArray($query);
	}
	
	
	
	
	/* public function getLabsCities($country_id = 1, $count = false, $categId = 0) {
        if ($count) {
            $query = 'SELECT city_id, name,
                (SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id'. ($categId > 0 ? ' AND lab_id IN(SELECT categ_id FROM labs_categs WHERE categ_id = ' . $categId . ')' : '') .') AS count
                FROM cities
                WHERE city_id IN(SELECT DISTINCT city_id FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = ' . $country_id . ')
                HAVING count > 0
                ORDER BY sort_id, name';
					
            return DB::getAssocArray($query);
        }
			
	
	} */
	public function getCategoriesFromSelect($flag_count = 0) {
		$country_id = Request::get('country'); 
		
		if ($flag_count > 0) {
			$count = ", (SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND lab_id 
				IN(SELECT lab_id FROM `labs_categs` WHERE categ_id = categories_labs.categ_id)) AS count";
		}
		
		$query = "SELECT categ_id, name $count FROM `categories_labs` ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getCategoryMetaTags($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_labs` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function add($user_id, $country_id, $lab_data, $categs, $images, $flag_moder = 0) {
		if (is_array($lab_data)) {
			DB::insert('labs', array(
				'user_id'			=> $user_id,
				'user_name'			=> $lab_data['user_name'],
				'contact_phones'	=> $lab_data['contact_phones'],
				'country_id'		=> $country_id,
				'region_id'			=> $lab_data['region_id'],
				'city_id'			=> $lab_data['city_id'],
				'region_name'		=> DB::getColumn("SELECT name FROM `regions` WHERE region_id = {$lab_data['region_id']}"),
				'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$lab_data['city_id']}"),
				'name'				=> $lab_data['name'],
				'address'			=> $lab_data['address'],
				'content'			=> $lab_data['content'],
				'attach'			=> $lab_data['attach'],
				'link'				=> $lab_data['link'],
				'video_link'		=> $lab_data['video_link'],
				'date_add'			=> DB::now(),
				'flag_moder'		=> $flag_moder,
				'flag_vip_add'		=> $lab_data['flag_vip_add']
			));
			
			$lab_id = DB::lastInsertId();
			
			if (is_array($categs) and $lab_id > 0) {
				for ($i = 0, $c = count($categs); $i < $c; $i++) {
					DB::insert('labs_categs', array(
						'lab_id'	=> $lab_id,
						'categ_id'	=> $categs[$i]
					));
				}
			}
			
			if (is_array($images)) {
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('labs_images', array(
						'lab_id' 	=> $lab_id,
						'sort_id'	=> $i
					), array(
						'image_id'	=> $images[$i]
					));
				}
			}
			
			return $lab_id;
		}
		
		return false;
	}
	
	public function edit($lab_id, $lab_data, $categs, $images, $images_descr) {
		DB::update('labs', array(
			'user_name'			=> $lab_data['user_name'],
			'region_id'			=> $lab_data['region_id'],
			'city_id'			=> $lab_data['city_id'],
			'region_name'		=> DB::getColumn("SELECT name FROM `regions` WHERE region_id = {$lab_data['region_id']}"),
			'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$lab_data['city_id']}"),
			'name'				=> $lab_data['name'],
			'address'			=> $lab_data['address'],
			'content'			=> $lab_data['content'],
			'attach'			=> $lab_data['attach'],
			'link'				=> $lab_data['link'],
			'video_link'		=> $lab_data['video_link'],
			'contact_phones'	=> $lab_data['contact_phones']
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
		
		if(Site::isModerView('labs', 'lab_id', $lab_id)) {
			Site::PublicLink(
                'http://navistom.com/lab/' .
				$lab_id . '-' . Str::get($lab_data['name'])->truncate(60)->translitURL()
			);
		}
	}
	
	public function delete($lab_id) {
		DB::update('labs', array(
			'flag_delete'	=> 1
		), array(
			'lab_id'		=> $lab_id
		));
		
		return true;
	}
	
	public function editFlag($lab_id, $flag = 0) {
		DB::update('labs', array(
			'flag'		=> $flag
		), array(
			'lab_id'	=> $lab_id
		));
		
		return true;
	}
	
	public function editFlagModer($lab_id, $flag_moder = 0) {
		DB::update('labs', array(
			'flag_moder'=> $flag_moder
		), array(
			'lab_id'	=> $lab_id
		));
		
		return true;
	}
	
	public function getLabData($lab_id) {
		$query = "SELECT *,
			(SELECT GROUP_CONCAT(categ_id) FROM `labs_categs` WHERE lab_id = labs.lab_id) AS categ_id 
			FROM `labs` WHERE lab_id = $lab_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getLabImages($lab_id) {
		$images = "SELECT image_id, url_full, description
			FROM `labs_images` WHERE lab_id = $lab_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function getUserId($lab_id) {
		return DB::getColumn("SELECT user_id FROM `labs` WHERE lab_id = $lab_id");
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
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/labs/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/labs/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/labs/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/labs/64x80/'
			),array(
                    'w'		=> 195,
                    'h'		=> 142,
                    'path'	=> UPLOADS . '/images/labs/142x195/'
                ))
		);
		
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
		
		return $result;
	}
	
	
	public static function  remove(){
		$query="SELECT lab_id FROM labs WHERE flag_delete=1";
		$labs= DB::getAssocArray($query);
		if(!count($labs))die('remove ok');
		array_map(function($lab){
			static:: removeImages($lab['lab_id']);
			DB::delete('labs', array('lab_id' => $lab['lab_id']));	
		},$labs);
		 
	}
	public static function  removeImages($lab_id){
	  $query="SELECT url_full FROM labs_images WHERE lab_id=$lab_id";
	  $images= DB::getAssocArray($query);
	  if(!count($images))return 1;
	  
	  array_map(function($image){
		   
		    @unlink(UPLOADS . '/images/labs/full/' . $image['url_full']);
			@unlink(UPLOADS . '/images/labs/160x200/' . $image['url_full']);
			@unlink(UPLOADS . '/images/labs/80x100/' . $image['url_full']);
			@unlink(UPLOADS . '/images/labs/64x80/' . $image['url_full']);
			@unlink(UPLOADS . '/images/labs/142x195/' . $image['url_full']);
			
		   
		   
	  },$images);
      
     DB::delete('labs_images', array('lab_id' => $lab_id));	  
		
	}
	
	
}