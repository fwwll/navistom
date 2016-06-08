<?php

class ModelServices {
	
	public function getServicesList($country_id = 1, $categ_id = 0, $city_id = 0, $user_id = 0, $page = 1, $count = 15, $search = null, $is_updates = 0, $flag = null) {
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND IF(s.user_id = '" . User::isUser() . "', 1, s.flag = 1 AND s.flag_moder = 1) ";
		}
		
		if ($categ_id > 0) {
			$having = "HAVING FIND_IN_SET($categ_id, categs) > 0";
		}
		
		if ($city_id > 0) {
			$where .= "AND s.city_id = $city_id";
		}
		
		if ($user_id > 0) {
			$where .= "AND s.user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND s.flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), s.date_add) > 13';
		}
		
		if ($search != null) {
			$match = "AND MATCH(s.name, s.content) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
			/* $orderr_by = "ORDER BY  IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), s.date_add DESC"; */
			$orderr_by = "ORDER BY  IFNULL(sort__id, 99999), s.date_add DESC";
		}
		
		if ($categ_id > 0) {
			$sort_table = "top_to_category";
		}
		else {
			$sort_table = "top_to_section";
		}
		
		$date = DB::now(1);
		
		 $query = "SELECT s.service_id, s.user_id, s.user_name, s.contact_phones, s.city_id, s.city_name, s.address, s.flag, s.flag_moder, flag_vip_add,users.group_id,
			s.name, s.date_add, i.url_full, flag_moder_view,s.price,s.currency_id,s.currency_name,
			(SELECT sort_id FROM `$sort_table` WHERE section_id = 9 AND resource_id = s.service_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 9 AND resource_id = s.service_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			(SELECT GROUP_CONCAT(categ_id) FROM `services_categs` WHERE service_id = s.service_id) AS categs,
			liq.color_yellow,
			liq.urgently
			FROM `services` AS s
			LEFT JOIN liqpay_status liq ON s.service_id=liq.ads_id AND  liq.section_id=9
			LEFT JOIN `services_images` AS i ON i.service_id = s.service_id AND i.sort_id = 0
			LEFT JOIN users ON  s.user_id=users.user_id
			WHERE s.flag_delete = 0 AND s.country_id = $country_id
			$where 
			$having 
			$match
			$orderr_by
			LIMIT $limit, $count"; 
			
			
			
		
		$services = DB::DBObject()->query($query);
		$services->execute();
		
		while ($service = $services->fetch(PDO::FETCH_ASSOC)) {
			$service['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $service['contact_phones']));
			$service['categs'] = DB::getAssocKey("SELECT categ_id, name FROM `categories_services` WHERE categ_id IN({$service['categs']})");
			$array[] = $service;
		}
		  
		 
		  
		return $array;
	}
	
	public function getServicesCount($country_id = 1, $categ_id = 0, $city_id = 0, $user_id = 0, $search = null, $is_updates = 0, $flag = null) {
		if ($categ_id > 0) {
			//$having = "HAVING FIND_IN_SET($categ_id, categs) > 0";
			$having="AND services_categs.categ_id=$categ_id";
		}
		
		if ($city_id > 0) {
			$where = "AND s.city_id = $city_id";
		}
		
		if ($user_id > 0) {
			$where = "AND s.user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND s.flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), s.date_add) > 13';
		}
		
		if ($search != null) {
			$match = "AND MATCH(name, content) AGAINST('$search')";
		}
		
		/* $query = "SELECT COUNT(*) 
			FROM `services`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $having $match"; */
			 $query = "select   count(*) from  services INNER JOIN   services_categs ON  services.service_id =services_categs.service_id  WHERE  flag = 1 AND flag_moder = 1 AND flag_delete = 0  $having  $match";
		
		return DB::getColumn($query);
	}
	
	public function getServiceFull($service_id) {
		$query = "SELECT s.service_id, s.user_id, s.user_name, s.contact_phones, s.city_id, s.city_name, s.address, s.attach, s.country_id, s.price,s.currency_id,s.currency_name,
			s.name, s.content, s.video_link, s.date_add, i.url_full, ui.site, ui.skype, ui.icq,
			(SELECT GROUP_CONCAT(categ_id) FROM `services_categs` WHERE service_id = s.service_id) AS categs,
			(SELECT name FROM `regions` WHERE region_id = s.region_id) AS region_name,
			(SELECT COUNT(*) FROM `services_views` WHERE service_id = s.service_id) AS views, flag, flag_moder,
			liq.urgently,
			liq.color_yellow
			FROM `services` AS s
			LEFT JOIN liqpay_status as liq ON  s.service_id=liq.ads_id AND  liq.section_id=9
			INNER JOIN `users_info` AS ui USING(user_id)
			LEFT JOIN `services_images` AS i ON i.service_id = s.service_id AND i.sort_id = 0
			WHERE 
				" . (User::isAdmin() ? " 1 " : "IF(s.user_id = '" . User::isUser() . "', 1, s.flag = 1 AND s.flag_moder = 1) " ) . "
				AND s.flag_delete = 0 AND s.service_id = $service_id";
		
		$service = DB::getAssocArray($query, 1);
		
		$service['phones'] 		= @explode(',', $service['contact_phones']);
		$service['categs'] 		= DB::getAssocKey("SELECT categ_id, name FROM `categories_services` WHERE categ_id IN({$service['categs']})");
		$service['video_link']	= str_replace('watch?v=', '', end(explode('/',  $service['video_link'])));
		return $service;
	}
	
	public function getVIP($country_id, $categs, $service_id) {
		$date = DB::now(1);
		
		$categs = implode(',', array_keys($categs));
		
		
		/*  $query = "SELECT 
			s.service_id,
			s.name,

			s.city_name,
			s.user_name,
			i.url_full AS image,
			(SELECT GROUP_CONCAT(categ_id) FROM `services_categs` WHERE service_id = s.service_id AND categ_id IN($categs)) AS categs
			FROM `top_to_main` AS t
			INNER JOIN `services` AS s  ON s.service_id = t.resource_id AND s.country_id = $country_id
			LEFT JOIN `services_images` AS i ON i.service_id = s.service_id AND i.sort_id = 0
			
			WHERE t.section_id = 9 AND resource_id != $service_id AND DATE_SUB(t.date_start, INTERVAL 1 DAY) < '$date' AND t.date_end > '$date'
			HAVING categs
			ORDER s.add_date";  */
		 $query = "SELECT 
			s.service_id,
			s.name,
			s.city_name,
			s.price,
			s.user_name,
			i.url_full AS image,
			(SELECT GROUP_CONCAT(categ_id) FROM `services_categs` WHERE service_id = s.service_id AND categ_id IN($categs)) AS categs,
			t.color_yellow,
			t.urgently,
			(SELECT  date_add  FROM `services` WHERE service_id  = t.ads_id )as date_add ,
			if((SELECT date_end from top_to_main WHERE section_id=9 AND resource_id = t.ads_id AND 1)>$date ,1,0 )as show_top
			
			FROM `liqpay_status` AS t
			INNER JOIN `services` AS s  ON s.service_id = t.ads_id  AND s.country_id = $country_id AND  t.show_competitor>2
			LEFT JOIN `services_images` AS i ON i.service_id = s.service_id AND i.sort_id = 0
			WHERE t.section_id = 9 AND t.ads_id != $service_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date'
			HAVING categs
			ORDER BY t.start_competitor"; 

		$service= DB::getAssocArray($query);
		  
		$service[0]['categs']= DB::getAssocKey("SELECT categ_id, name FROM `categories_services` WHERE categ_id IN({$service[0]['categs']})");
		//Site::d($service,1);
		return $service;
	}
	
	public function getServicesCities($country_id = 1, $count = false, $categId = 0) {
        if ($count) {
            $query = 'SELECT city_id, name,
                (SELECT COUNT(*) FROM services WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id'. ($categId > 0 ? ' AND service_id IN(SELECT service_id FROM services_categs WHERE categ_id = ' . $categId . ')' : '') .') AS count
                FROM cities
                WHERE city_id IN(SELECT DISTINCT city_id FROM services WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = ' . $country_id . ')
                HAVING count > 0
                ORDER BY sort_id, name';

            return DB::getAssocArray($query);
        }

		$query = "SELECT city_id, name 
			FROM `cities`
			WHERE city_id IN(SELECT DISTINCT city_id FROM `services` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id)
			ORDER BY sort_id, name";
		
		return DB::getAssocKey($query);
	}
	
	public function setViews($user_id, $service_id) {
		if (Request::getCookie('service_view_' . $service_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'service_id'	=> $service_id,
				'user_id'		=> $user_id,
				'date_view'		=> DB::now()
			);
			
			DB::insert('services_views', $write);
			
			Request::setCookie('service_view_' . $service_id, 1);
		}
		
		return true;
	}
	
	public function getServiceGallery($service_id) {
		$query = "SELECT url_full, description FROM `services_images` WHERE service_id = $service_id AND sort_id > 0 ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function add($user_id, $country_id, $service_data, $categs, $images, $flag_moder = 0) {
	
	 $currencyName = DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$service_data['currency_id']}");
	
		if (is_array($service_data)) {
			DB::insert('services', array(
				'user_id'			=> $user_id,
				'user_name'			=> $service_data['user_name'],
				'contact_phones'	=> $service_data['contact_phones'],
				'country_id'		=> $country_id,
				'region_id'			=> $service_data['region_id'],
				'city_id'			=> $service_data['city_id'],
				'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$service_data['city_id']}"),
				'address'			=> $service_data['address'],
				'name'				=> $service_data['name'],
				'content'			=> $service_data['content'],
				'attach'			=> $service_data['attach'],
				'video_link'		=> $service_data['video_link'],
				'date_add'			=> DB::now(),
				'flag_moder'		=> $flag_moder,
				'flag_vip_add'		=> $service_data['flag_vip_add'],
				'currency_id'		=> $service_data['currency_id'],
				'price'				=> $service_data['price'],
				'currency_name'		=> $currencyName
				
			));
			
			$service_id = DB::lastInsertId();
			
			if (is_array($categs) and $service_id > 0) {
				for ($i = 0, $c = count($categs); $i < $c; $i++) {
					DB::insert('services_categs', array(
						'service_id'	=> $service_id,
						'categ_id'		=> $categs[$i]
					));
				}
			}
			
			if (is_array($images)) {
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('services_images', array(
						'service_id' 	=> $service_id,
						'sort_id'		=> $i
					), array(
						'image_id'	=> $images[$i]
					));
				}
			}
			
			return $service_id;
		}
		
		return false;
	}
	
	public function edit($service_id, $service_data, $categs, $images, $images_descr) {
		$currencyName = DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$service_data['currency_id']}");
		DB::update('services', array(
			'user_name'			=> $service_data['user_name'],
			'region_id'			=> $service_data['region_id'],
			'city_id'			=> $service_data['city_id'],
			'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$service_data['city_id']}"),
			'address'			=> $service_data['address'],
			'contact_phones'	=> $service_data['contact_phones'],
			'name'				=> $service_data['name'],
			'content'			=> $service_data['content'],
			'attach'			=> $service_data['attach'],
			'currency_name'		=> $currencyName,
			'currency_id'      	=>$service_data['currency_id'],
			'price'		    	=> $service_data['price'],
			'video_link'		=> $service_data['video_link']
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
		
		if(Site::isModerView('services', 'service_id', $service_id)) {
			Site::PublicLink(
                'http://navistom.com/service/' .
				$service_id . '-' . Str::get($service_data['name'])->truncate(60)->translitURL()
			);
		}
	}
	
	public function delete($service_id) {
		DB::update('services', array(
			'flag_delete'	=> 1
		), array(
			'service_id'	=> $service_id
		));
		
		return true;
	}
	
	public function editFlag($service_id, $flag = 0) {
		DB::update('services', array(
			'flag'			=> $flag
		), array(
			'service_id'	=> $service_id
		));
		
		return true;
	}
	
	public function editFlagModer($service_id, $flag_moder = 0) {
		DB::update('services', array(
			'flag_moder'	=> $flag_moder
		), array(
			'service_id'	=> $service_id
		));
		
		return true;
	}
	
	public function getUserId($service_id) {
		return DB::getColumn("SELECT user_id FROM `services` WHERE service_id = $service_id");
	}
	
	public function getServiceData($service_id) {
		$query = "SELECT *,
			(SELECT GROUP_CONCAT(categ_id) FROM `services_categs` WHERE service_id = services.service_id) AS categ_id 
			FROM `services` WHERE service_id = $service_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getServiceImages($service_id) {
		$images = "SELECT image_id, url_full, description
			FROM `services_images` WHERE service_id = $service_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function getCategoriesFromSelect($count = false) {
        if ($count) {
            $query = 'SELECT categ_id, name,
                      (SELECT COUNT(*) FROM services AS s WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND categ_id IN(SELECT categ_id FROM services_categs WHERE service_id = s.service_id)) AS count
                      FROM categories_services AS c
                      ORDER BY sort_id';

            return DB::getAssocArray($query);
        }

		$query = "SELECT categ_id, name FROM `categories_services` ORDER BY sort_id";
		
		return DB::getAssocKey($query);
	}
	
	public function getCategoryMetaTags($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_services` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
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
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/services/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/services/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/services/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/services/64x80/'
			),
			array(
                    'w'		=> 195,
                    'h'		=> 142,
                    'path'	=> UPLOADS . '/images/services/142x195/'
                ))
		);
		
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
		
		return $result;
	}
	
	public static function servicesRemove(){
		$query="SELECT service_id  FROM services WHERE flag_delete=1 ";
		$services= DB::getAssocArray($query);
		if(count($services)){
			array_map(function($service){
				static::removeImages($service['service_id']);
				DB::delete('services', array('service_id'=>$service['service_id'])); 	
			},$services);
			   $rand=rand(10,1000);
			   Header::Location('/services/remove?='.$rand);
			
		}else{
			echo 'ok';
			
		}
		
	}
	
	public static function removeImages($service_id){
		$query="SELECT url_full FROM `services_images` WHERE service_id=$service_id";
		$images=DB::getAssocArray($query);
		
		if(!count($images))return 1;
		 
		array_map(function($image){
			@unlink(UPLOADS . '/images/services/full/' 		. $image['url_full']);
			@unlink(UPLOADS . '/images/services/160x200/' 	. $image['url_full']);
			@unlink(UPLOADS . '/images/services/80x100/' 	. $image['url_full']);
			@unlink(UPLOADS . '/images/services/64x80/' 	. $image['url_full']);
			@unlink(UPLOADS . '/images/services/142x195/' . $image['url_full']);
		},$images);
		 
		DB::delete('services_images', array('service_id'=>$service_id)); 
	}
}