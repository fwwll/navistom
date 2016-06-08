<?php

class ModelActivity {
	
	public function getActivityList($categ_id = 0, $city_id = 0, $page = 1, $user_id, $sort_by = null, $count = 15, $is_updates = 0, $search = null, $flag = null) {
		$limit 	= ($count * $page) - $count;
        $date = DB::now(1);
		   
		     //$SQL='SELECT url_full, description FROM `activity_images` WHERE activity_id = $activity_id AND sort_id = 0 ';
		   
		   
		if (!User::isAdmin()) {
			$where = "AND IF(user_id = '" . User::isUser() . "', 1, flag = 1 AND flag_moder = 1) ";
		}
		
		if ($categ_id > 0) {
			$having = "HAVING FIND_IN_SET($categ_id, categs) > 0";
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
		
		switch ($sort_by) {
			case 'popular':
				/* $order_by = "IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), views"; */
				$order_by = "IFNULL(sort__id, 99999), views";
			break;
			case 'coming':
				//$order_by = "IFNULL(sort__id, 99999), IF(date_start != '000-00-00' AND flag_agreed = 0, date_start, 99999)";
				$order_by = "IFNULL(sort__id, 99999), date_add DESC";
			break;
			default:
				/* $order_by = "IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), date_add DESC"; */
				$order_by = "IFNULL(sort__id, 99999), date_add  DESC";
			   //	$order_by = ",date_add  DESC";
			break;
		}
		
		if ($search != null) {
			$match = "AND MATCH(activity.name) AGAINST('$search')";
			$orderr_by = '';
		}
		
		if ($categ_id > 0) {
			$sort_table = "top_to_category";
		}
		else {
			$sort_table = "top_to_section";
		}
		

			$query = "SELECT activity.activity_id, user_id, user_name, contact_phones, city_name, city_id, date_start, date_end, flag_agreed, activity.name, activity.image, date_add, views, flag,
			flag_moder, flag_moder_view, flag_vip_add,
			l.image AS lector_image,
			im.url_full AS img_l,
			(SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = activity.activity_id) AS categs,
			(SELECT sort_id FROM `top_to_main` WHERE section_id = 5 AND resource_id = activity.activity_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date' LIMIT 1) AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 5 AND resource_id = activity.activity_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			liq.color_yellow,
			liq.urgently
			FROM `activity`
			LEFT JOIN liqpay_status liq ON activity.activity_id=liq.ads_id AND  liq.section_id=5
			LEFT JOIN `activity_lectors` AS l ON l.activity_id = activity.activity_id AND l.sort_id = 0
			LEFT JOIN `activity_images` AS im ON im.activity_id = activity.activity_id AND im.sort_id = 0
			WHERE flag_delete = 0 $where AND
			IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '$date', date_start > '$date'), 1)
			$match
			$having
			ORDER BY $order_by
			LIMIT $limit, $count";

		return DB::getAssocArray($query);
	}

    public function getTopOffers($table) {
        $date = DB::now(1);

       
			
			$query = 'SELECT a.activity_id
            FROM `'. $table .'` AS t
            INNER JOIN `activity` AS a ON a.activity_id = t.resource_id ' .
            'WHERE section_id = 5 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date . '"
            ORDER BY a.date_add,t.sort_id'; 
			
			
			
			
		

        $items = DB::getAssocGroup($query);

        return array_keys($items);
    }
	
	public function getActivityFull($activity_id) {
		$activity = "SELECT activity_id, user_id, user_name, contact_phones, city_name, city_id, date_start, date_end, flag_agreed, name, video_link, address, country_id,
			content, image, date_add, views, link, attachment, flag_delete,
			(SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = activity.activity_id) AS categs,
			(SELECT COUNT(*) FROM `activity_views` WHERE activity_id = $activity_id) + views AS views, flag, flag_moder,
			(SELECT url_full FROM `activity_images` WHERE  activity_id= $activity_id AND sort_id = 0 ) AS img_l,
			liq.urgently,
			liq.color_yellow
			FROM `activity`
			LEFT JOIN liqpay_status liq ON activity.activity_id=liq.ads_id AND  liq.section_id=5
			
			WHERE activity_id = $activity_id";
		
		$activity = DB::getAssocArray($activity, 1);
		
		$activity['phones'] 	= @explode(',', $activity['contact_phones']);
		$activity['categs'] 	= DB::getAssocKey("SELECT categ_id, name FROM `categories_activity` WHERE categ_id IN({$activity['categs']})");
		$activity['video_link']	= str_replace('watch?v=', '', end(explode('/',  $activity['video_link'])));
		
		return $activity;
	}
	
	public function getVIP($country_id, $categs, $activity_id) {
		$date = DB::now(1);
		
		$categs = implode(',', array_keys($categs));
		
		/* $query = "SELECT 
			a.activity_id,
			a.name,
			a.date_start,
			a.date_end,
			a.user_name,
			a.city_name,
			a.flag_agreed,
			l.image,
			(SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = a.activity_id AND categ_id IN($categs)) AS categs
			FROM `top_to_main` AS t
			INNER JOIN `activity` AS a  ON a.activity_id = t.resource_id AND a.country_id = $country_id
			LEFT JOIN `activity_lectors` AS l ON l.activity_id = a.activity_id AND l.sort_id = 0
			
			WHERE a.flag = 1 AND a.flag_moder = 1 AND a.flag_delete = 0 AND t.section_id = 5 AND resource_id != $activity_id AND DATE_SUB(t.date_start, INTERVAL 1 DAY) < '$date' AND t.date_end > '$date'
			HAVING categs
			ORDER BY RAND()"; */
			
			 $query = "SELECT 
			a.activity_id,
			a.name,
			a.date_start,
			a.date_end,
			a.user_name,
			a.city_name,
			a.flag_agreed,
			l.image,
			(SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = a.activity_id AND categ_id IN($categs)) AS categs,
			t.color_yellow,
			t.urgently,
			(SELECT date_add from activity WHERE  activity_id = t.ads_id )as date_add ,
			
			if((SELECT date_end from top_to_main WHERE section_id=5 AND resource_id = t.ads_id AND 1)>$date ,1,0 )as show_top
			FROM `liqpay_status` AS t
			INNER JOIN `activity` AS a  ON a.activity_id = t.ads_id AND a.country_id = $country_id AND a.flag_delete=0
			LEFT JOIN `activity_lectors` AS l ON l.activity_id = a.activity_id AND l.sort_id = 0 
			
			WHERE a.flag = 1 AND a.flag_moder = 1 AND a.flag_delete = 0 AND t.section_id = 5 AND t.ads_id != $activity_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor  > '$date' AND t.show_competitor>2
			HAVING categs
			ORDER BY t.start_competitor";
			
		
		return DB::getAssocArray($query);
	}
	
	public function getActivityLectors($activity_id) {
		$lectors = "SELECT name, image, description FROM `activity_lectors` WHERE activity_id = $activity_id ORDER BY sort_id, name";
		
		return DB::getAssocArray($lectors);
	}
	
	public function getActivityCount($categ_id = 0, $city_id = 0, $user_id = 0, $country_id = 1, $is_updates = 0, $search = null, $flag = null) {
		if ($categ_id > 0) {
			$where = "AND FIND_IN_SET($categ_id, (SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = activity.activity_id)) > 0";
			
			$where ="AND  activity_categs.categ_id=$categ_id" ;
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
		
		if ($search != null) {
			$match = "AND MATCH(activity.name) AGAINST('$search')";
		}
		$date = DB::now(1);

		$count  = "SELECT COUNT(*) FROM `activity`  
		INNER JOIN  activity_categs  ON   activity.activity_id= activity_categs.activity_id
		WHERE flag = 1 AND flag_moder = 1 AND country_id = 1  $where  AND flag_delete = 0 
		AND IF(date_end = '000-00-00', date_start > '$date', 
				IF(date_end != '000-00-00', date_end > '$date', date_start < '$date')
			) $match
		 ";
		

		/* $count  = "SELECT COUNT(*) FROM `activity` WHERE flag = 1 AND flag_moder = 1 AND country_id = $country_id AND 
		IF(date_end = '000-00-00', date_start > '$date', 
				IF(date_end != '000-00-00', date_end > '$date', date_start < '$date')
			)
		$where $match"; */
		
		
		return DB::getColumn($count);
	}
	
	public function getAllUsers() {
        $date = DB::now(1);
		$query = "SELECT user_id, user_name, COUNT(*) AS count
			FROM activity
			WHERE
			  flag = 1 AND
			  flag_moder = 1 AND
			  flag_delete = 0 AND
			  user_name != '' AND
			  IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '$date', date_start > '$date'), 1)
			GROUP BY user_id
			ORDER BY count DESC";
		
		return DB::getAssocArray($query); 
	}
	
	public function getCategoriesFromSelect($flag_count = 0) {
		$country_id = Request::get('country'); 
		
		if ($flag_count > 0) {
            $date = DB::now(1);
			$count = ", (SELECT COUNT(*) FROM `activity` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '$date', date_start > '$date'), 1) AND activity_id
				IN(SELECT activity_id FROM `activity_categs` WHERE categ_id = categories_activity.categ_id)) AS count";
		}
		
		$categs = "SELECT categ_id, name $count
			FROM `categories_activity` ORDER BY sort_id, name";
		
		return DB::getAssocArray($categs);
	}
	
	public function getCitiesFromFilterSelect($country_id = 1, $categ_id = 0) {
		$date = DB::now(1);
		
		if ($categ_id > 0) {
			$where = "AND activity_id IN(SELECT activity_id FROM `activity_categs` WHERE categ_id = $categ_id)";
		}
		
		$cities = "SELECT cities.city_id, cities.name, COUNT(activity_id) AS count
			FROM `activity`
			INNER JOIN `cities` USING(city_id)
			WHERE activity.flag = 1 AND activity.flag_moder = 1 AND activity.flag_delete = 0 AND activity.country_id = $country_id AND
			IF(activity.date_start != '000-00-00', IF(activity.date_end != '000-00-00', activity.date_end > '$date', activity.date_start > '$date'), 1)
			$where
			GROUP BY cities.city_id";
		
		return DB::getAssocArray($cities);
	}
	
	public function getCategoryMetaTags($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_activity` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function add($user_id, $activity_data, $lectors_data, $categs ,$images=0 ) {
		$user_info = User::getUserContacts();
        $city_name = DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$activity_data['city_id']}");
		
		DB::insert('activity', array(
			'user_id'		=> $user_id,
			'user_name'		=> $user_info['name'],
			'contact_phones'=> $activity_data['contact_phones'],
			'country_id'	=> $activity_data['country_id'],
			'city_id'		=> $activity_data['city_id'],
			'city_name'		=> $city_name,
			'region_id'		=> $activity_data['region_id'],
			'address'		=> $activity_data['address'],
			'date_start'	=> $activity_data['date_start'],
			'date_end'		=> $activity_data['date_end'],
			'flag_agreed'	=> $activity_data['flag_agreed'],
			'name'			=> $activity_data['name'],
			'content'		=> $activity_data['content'],
			'link'			=> $activity_data['link'],
			'image'			=> $activity_data['image'],
			'attachment'	=> $activity_data['attachment'],
			'video_link'	=> $activity_data['video_link'],
			'flag'			=> $activity_data['flag'],
			'flag_moder'	=> $activity_data['flag_moder'],
			'flag_vip_add'	=> $activity_data['flag_vip_add'],
			'date_add'		=> DB::now()
		));
		
		$activity_id = DB::lastInsertId();
		
		 // Site::d($images);
		   
		if(is_array($images)){
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('activity_images', array(
						'activity_id' 	=> $activity_id,
						'sort_id'		=> $i
					), array(
						'id'		=> $images[$i]
					));
				}
			}
		
		

        // Add offer to news
        $img = $lectors_data[0]['lector_image'] ? ('lectors/' . $lectors_data[0]['lector_image']) : ($activity_data['image'] ? ('80x100/' . $activity_data['image']) : '');

        News::addOfferToNews(5, $activity_id, array(
            'name' => $activity_data['name'],
            'image' => $img,
            'description' => $activity_data['date_start'],
            'price' => $activity_data['date_end'],
            'price_description' => $city_name
        ));
        // End add
		
		for ($i = 0, $c = count($lectors_data); $i < $c; $i++) {
			DB::insert('activity_lectors', array(
				'activity_id'	=> $activity_id,
				'sort_id'		=> $i,
				'name'			=> $lectors_data[$i]['lector_name'],
				'image'			=> $lectors_data[$i]['lector_image'],
				'description'	=> $lectors_data[$i]['lector_description']
			));
		}
		
		for ($i = 0, $c = count($categs); $i < $c; $i++) {
			DB::insert('activity_categs', array(
				'activity_id'	=> $activity_id,
				'categ_id'		=> $categs[$i]
			));
		}
		
		return $activity_id;
	}
	
	public function getActivityData($activity_id) {
		$activity = "SELECT *,  
			(SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = activity.activity_id) AS categs
			FROM `activity` WHERE activity_id = $activity_id";
		
		$activity = DB::getAssocArray($activity, 1);
		
		$activity['categ_id']	= @explode(',', $activity['categs']);
		
		return $activity;
	}
	
	public function editActivity($activity_id, $data, $categs, $lectors, $images = null) {
		if (DB::update('activity', $data, array('activity_id' => $activity_id))) {
			
			DB::delete('activity_categs', array('activity_id' => $activity_id));
			
			for ($i = 0, $c = count($categs); $i < $c; $i++) {
				DB::insert('activity_categs', array(
					'activity_id'	=> $activity_id,
					'categ_id'		=> $categs[$i]
				));
			}
			
			DB::delete('activity_lectors', array('activity_id' => $activity_id));
			
			if (count($categs) == 1 and $categs[0] == 19) {
				
			}
			else {
				for ($i = 0, $c = count($lectors); $i < $c; $i++) {
					DB::insert('activity_lectors', array(
						'activity_id'	=> $activity_id,
						'sort_id'		=> $i,
						'name'			=> $lectors[$i]['name'],
						'image'			=> $lectors[$i]['image'],
						'description'	=> $lectors[$i]['description']
					));
				}
			}
			 // Site::d($images);
			if (is_array($images)) {
				/* $image_id = implode(',', $images);
				DB::query("UPDATE `activity_images` SET activity_id = $activity_id WHERE  id IN($image_id)"); */
				
				
				
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('activity_images', array(
						'activity_id' 	=> $activity_id,
						'sort_id'		=> $i
					), array(
						'id'		=> $images[$i]
					));
				}
		
				
				
				
			}
			
			if (Site::isModerView('activity', 'activity_id', $activity_id)) {
				Site::PublicLink(
                    'http://navistom.com/activity/' .
					$activity_id . '-' . Str::get($data['name'])->truncate(60)->translitURL()
				);
			}

            // Add offer to news
            $img = $lectors[0]['image'] ? ('lectors/' . $lectors[0]['image']) : ($data['image'] ? ('80x100/' . $data['image']) : '');

            News::addOfferToNews(5, $activity_id, array(
                'name' => $data['name'],
                'image' => $img,
                'description' => $data['date_start'],
                'price' => $data['date_end'],
                'price_description' => $data['city_name']
            ));
            // End add
			
			return true;
		}
		
		return false;
	}
	
	public function delete($activity_id) {
		DB::update('activity', array(
			'flag_delete'	=> 1
		), array(
			'activity_id'	=> $activity_id
		));

        News::deleteOfferOnNews(5, $activity_id);
		
		return true;
	}
	
	public function editFlag($activity_id, $flag = 0) {
		DB::update('activity', array(
			'flag'			=> $flag
		), array(
			'activity_id'	=> $activity_id
		));

        News::updateOfferOnNews(5, $activity_id, array(
            'flag' => $flag
        ));
		
		return true;
	}
	
	public function editFlagModer($activity_id, $flag_moder = 0) {
		DB::update('activity', array(
			'flag_moder'	=> $flag_moder
		), array(
			'activity_id'		=> $activity_id
		));

        News::updateOfferOnNews(5, $activity_id, array(
            'flag_moder' => $flag_moder
        ));
		
		return true;
	}
	
	public function getUserId($activity_id) {
		return DB::getColumn("SELECT user_id FROM `activity` WHERE activity_id = $activity_id");
	}
	
	public function setViews($activity_id, $user_id = 0) {
		if (Request::getCookie('activity_view_' . $activity_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'activity_id'	=> $activity_id,
				'user_id'		=> $user_id,
				'date_view'		=> DB::now()
			);
			
			DB::insert('activity_views', $write);
			
			Request::setCookie('activity_view_' . $activity_id, 1);
		}
		
		return true;
	}
	
	public function saveUserMessage($activity_id, $from_id, $to_id, $message) {
		DB::insert('users_messages', array(
			'to_id'			=> $to_id,
			'from_id'		=> $from_id,
			'message'		=> $message,
			'section_id'	=> 5,
			'resource_id'	=> $activity_id,
			'date_add'		=> DB::now()
		));
		
		return DB::lastInsertId();
	}
	
	public function uploadLectorImage($file) {
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($file, $image_name, array(
			array(
				'w'		=> 195,
				'h'		=> 142,
				'path'	=> UPLOADS . '/images/activity/lectors/',
				'crop'	=> -1
			)
		));
		
		/*include_once(LIBS.'upload/upload.class.php');
		
		$image = new upload($file);
		
		$image_name = Str::get()->generate(20);
		
		if ($image->uploaded) {
			$image->file_new_name_body 		= $image_name;
			$image->image_resize       		= true;
			$image->image_ratio_fill   		= true;
			$image->image_convert 			= 'jpg';
			$image->image_y            		= 125;
			$image->image_x            		= 100;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/activity/lectors/');
			
			if (!$image->processed) {
				return false;
			}
		}*/
		
		return $image_name . '.jpg';
	}
	
	public function uploadActivityAttach($file) {
		include_once(LIBS.'upload/upload.class.php');
		
		$attach = new upload($file);
		$attach->allowed = array('application/pdf','application/msword', 'application/zip');
		
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
	
	public function uploadActivityImage($file) {
		
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES[$file]['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/activity/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/activity/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/activity/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/activity/64x80/'
			))
		);
		
		return $image_name . '.jpg';
	}
	
	
	public function uploadImage() {
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/activity/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/activity/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/activity/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/activity/64x80/'
			),array(
                    'w'		=> 195,
                    'h'		=> 142,
                    'path'	=> UPLOADS . '/images/activity/142x195/'
                ))
		);
		
		$write = array(
			'url_full'	=> $image_name . '.jpg'
		);
		
		DB::insert('activity_images', $write);
		
		$image_id = DB::lastInsertId();
		
		$result = array(
			'uploadName' 	=> '/uploads/images/activity/80x100/' . $image_name . '.jpg',
			'success'		=> true,
			'image_id'		=> $image_id
		);
		
		return $result;
	}
	public function getActivityGallery($activity_id){
		
		$query = "SELECT url_full, description FROM `activity_images` WHERE activity_id = $activity_id AND sort_id > 0 ORDER BY sort_id";
		//Site::d($query);
		//Site::d(DB::getAssocArray($query));
		return DB::getAssocArray($query);
	}
	
	
	
	
	public function getActivityImages($activity_id) {
		$images = "SELECT id, url_full 
			FROM `activity_images` WHERE  activity_id = $activity_id ORDER BY sort_id";
	
		return DB::getAssocArray($images);
	}
	
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `activity_images` WHERE id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
		 if(file_exists(UPLOADS . '/images/activity/full/' 	. $image)) 
		 { 
			unlink(UPLOADS . '/images/activity/full/' 	. $image);
			unlink(UPLOADS . '/images/activity/160x200/'. $image);
			unlink(UPLOADS . '/images/activity/80x100/' . $image);
			unlink(UPLOADS . '/images/activity/64x80/' . $image);
		 }
			DB::delete('activity_images', array('id' => $image_id));
			
			return true;
		}
		
		return false;
	}
	
	public static function  deleteLectorImage( $activity_id){
	
		$query="SELECT image FROM activity_lectors WHERE activity_id= $activity_id";
        $lectors= DB::getAssocArray($query);
		array_map(function($lector){ 

	        if(file_exists(UPLOADS . '/images/activity/lectors/'.$lector['image'])){	
				 @unlink(UPLOADS . '/images/activity/lectors/'.$lector['image']);  
		    }
		
		}, $lectors);
		DB::delete('activity_lectors', array('activity_id' => $activity_id));  
		
		
		$query="SELECT url_full  FROM `activity_images` WHERE activity_id= $activity_id";
		$images =DB::getAssocArray($query);
	
		array_map(function($image){
			if(file_exists(UPLOADS . '/images/activity/full/' 	. $image['url_full'])) 
			 { 
				@unlink(UPLOADS . '/images/activity/full/' 	. $image['url_full']);
				@unlink(UPLOADS . '/images/activity/160x200/'. $image['url_full']);
				@unlink(UPLOADS . '/images/activity/80x100/' . $image['url_full']);
				@unlink(UPLOADS . '/images/activity/64x80/' . $image['url_full']);
			 }

		}, $images); 
		
		DB::delete('activity_images', array('activity_id' => $activity_id));
		
	}
	

	public static function  deleteActivity(){
		$query="SELECT activity_id FROM `activity` WHERE date_end is not null and date_end <> 0000-00-00 and date_end < now()";
		$activitys =DB::getAssocArray($query);
		if(!count($activitys)) Header::Location('/activity'); 
	    
		array_map(function($activity){ 
			 self::deleteLectorImage( $activity['activity_id']); 
			  DB::delete('activity', array('activity_id' => $activity['activity_id']));		
			},$activitys);
			
       Header::Location('/activity');
	}
	
	
	
}