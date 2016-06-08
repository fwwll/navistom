<?php

class ModelRealty {
	
	public function getRealtyList($country_id = 1, $categ_id = 0, $city_id = 0, $user_id = 0, $page = 1, $count = 15, $search = null, $is_updates = 0, $flag = null) {
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND IF(r.user_id = '" . User::isUser() . "', 1, r.flag = 1 AND r.flag_moder = 1)";
		}
		
		if ($categ_id > 0) {
			$where .= "AND r.categ_id = $categ_id";
		}
		
		if ($city_id > 0) {
			$where .= " AND r.city_id = $city_id";
		}
		
		if ($user_id > 0) {
			$where .= " AND r.user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND r.flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), r.date_add) > 13';
		}
		
		if ($search != null) {
			$match = "AND MATCH(r.name, r.content) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
			/* $orderr_by = "ORDER BY IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), r.date_add DESC"; */
			$orderr_by = "ORDER BY IFNULL(sort__id, 99999),  r.date_add DESC";
		}
		
		if ($categ_id > 0) {
			$sort_table = "top_to_category";
		}
		else {
			$sort_table = "top_to_section";
		}
		
		$date = DB::now(1);
		
		$query = "SELECT r.realty_id, r.user_id, r.user_name, r.contact_phones, r.city_id, r.city_name, r.categ_id, r.flag, r.flag_moder, flag_vip_add,
			r.address, r.currency_name, r.currency_id, r.price, r.price_description, r.name, r.date_add, i.url_full, c.name AS categ_name, 
			(SELECT sort_id FROM `$sort_table` WHERE section_id = 8 AND resource_id = r.realty_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 8 AND resource_id = r.realty_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			flag_moder_view,
			liq.color_yellow,
			liq.urgently
			FROM `realty` AS r
			LEFT JOIN liqpay_status liq ON r.realty_id=liq.ads_id AND  liq.section_id=8
			LEFT JOIN `realty_images` AS i ON i.realty_id = r.realty_id AND i.sort_id = 0
			INNER JOIN `categories_realty` AS c USING(categ_id)
			WHERE r.flag_delete = 0 AND r.country_id = $country_id
			$where $match
			$orderr_by
			LIMIT $limit, $count";
		
		$realty = DB::DBObject()->query($query);
		$realty->execute();
		
		while ($array = $realty->fetch(PDO::FETCH_ASSOC)) {
			$array['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $array['contact_phones']));
			$result[] = $array;
		}
		
		return $result;
	}
	
	public function getRealtyCount($country_id = 1, $categ_id = 0, $city_id = 0, $user_id = 0, $search = null, $is_updates = 0, $flag = null) {
		if (!User::isAdmin()) {
			$where = "AND IF(r.user_id = '" . User::isUser() . "', 1, r.flag = 1 AND r.flag_moder = 1)";
		}
		
		if ($categ_id > 0) {
			$where .= "AND r.categ_id = $categ_id";
		}
		
		if ($city_id > 0) {
			$where .= " AND r.city_id = $city_id";
		}
		
		if ($user_id > 0) {
			$where .= " AND r.user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND r.flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), r.date_add) > 13';
		}
		
		if ($search != null) {
			$match = "AND MATCH(r.name, r.content) AGAINST('$search')";
		}
		
		$query = "SELECT COUNT(*)
			FROM `realty` AS r
			WHERE r.flag_delete = 0 AND r.country_id = $country_id $where $match";
		
		return DB::getColumn($query);
	}
	
	public function getCategoriesFromSelect($flag_count = 0) {
		$country_id = Request::get('country'); 
		
		if ($flag_count > 0) {
			$count = ", (SELECT COUNT(*) FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND categ_id = categories_realty.categ_id) AS count";
		}
		
		$query = "SELECT categ_id, name $count FROM `categories_realty` ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getCategoryMetaTags($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_realty` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getRealtyData($realty_id) {
		$query = "SELECT *, (SELECT region_id FROM `cities` WHERE city_id = realty.city_id) AS region_id FROM `realty` WHERE realty_id = $realty_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getRealtyImages($realty_id) {
		$images = "SELECT image_id, url_full, description
			FROM `realty_images` WHERE realty_id = $realty_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function getUserId($realty_id) {
		return DB::getColumn("SELECT user_id FROM `realty` WHERE realty_id = $realty_id");
	}
	
	public function add($user_id, $country_id, $realty_data, $images, $flag_moder = 0) {
		if (is_array($realty_data)) {
			DB::insert('realty', array(
				'user_id'			=> $user_id,
				'user_name'			=> $realty_data['user_name'],
				'contact_phones'	=> $realty_data['contact_phones'],
				'country_id'		=> $country_id,
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
				'date_add'			=> DB::now(),
				'flag_moder'		=> $flag_moder,
				'flag_vip_add'		=> $realty_data['flag_vip_add']
			));
			
			$realty_id = DB::lastInsertId();
			
			if (is_array($images) and $realty_id > 0) {
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('realty_images', array(
						'realty_id' 	=> $realty_id,
						'sort_id'		=> $i
					), array(
						'image_id'		=> $images[$i]
					));
				}
			}
			
			return $realty_id;
		}
		
		return false;
	}
	
	public function edit($realty_id, $realty_data, $images, $images_descr) {
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
				'contact_phones'	=> $realty_data['contact_phones']
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
			
			if(Site::isModerView('realty', 'realty_id', $realty_id)) {
				Site::PublicLink(
                    'http://navistom.com/realty/' .
					$realty_id . '-' . Str::get($realty_data['name'])->truncate(60)->translitURL()
				);
			}
			
			return true;
		}
		
		return false;
	}
	
	public function delete($realty_id) {
		DB::update('realty', array(
			'flag_delete'	=> 1
		), array(
			'realty_id'		=> $realty_id
		));
		
		return true;
	}
	
	public function editFlag($realty_id, $flag = 0) {
		DB::update('realty', array(
			'flag'		=> $flag
		), array(
			'realty_id'	=> $realty_id
		));
		
		return true;
	}
	
	public function editFlagModer($realty_id, $flag_moder = 0) {
		DB::update('realty', array(
			'flag_moder'=> $flag_moder
		), array(
			'realty_id'	=> $realty_id
		));
		
		return true;
	}
	
	public function getRealtyFull($realty_id) {
		$query = "SELECT r.realty_id, r.user_id, r.user_name, r.contact_phones, r.city_id, r.city_name, r.categ_id, r.content, r.video_link, r.country_id,
			r.address, r.currency_name, r.currency_id, r.price, r.price_description, r.name, r.date_add, i.url_full, c.name AS categ_name,
			(SELECT COUNT(*) FROM `realty_views` WHERE realty_id = r.realty_id) AS views, flag, flag_moder,
			liq.urgently,
			liq.color_yellow
			FROM `realty` AS r
			
			LEFT JOIN liqpay_status liq ON r.realty_id=liq.ads_id AND  liq.section_id=8
			LEFT JOIN `realty_images` AS i ON i.realty_id = r.realty_id AND i.sort_id = 0
			INNER JOIN `categories_realty` AS c USING(categ_id)
			WHERE " . (User::isAdmin() ? 1 : "IF(r.user_id = '" . User::isUser() . "', 1, r.flag = 1 AND r.flag_moder = 1)") . " AND r.flag_delete = 0 AND r.realty_id = $realty_id";
		
		$realty = DB::getAssocArray($query, 1);
		
		$realty['phones'] = @explode(',', $realty['contact_phones']);
		$realty['video_link']	= str_replace('watch?v=', '', end(explode('/',  $realty['video_link'])));
		
		return $realty;
	}
	
	public function getVIP($country_id, $categ_id, $realty_id) {
		$date = DB::now(1);
		
		$query = "SELECT 
				r.realty_id, 
				r.user_id,
				r.name,
				c.name AS categ_name,
				r.city_name,
				r.user_name,
				r.date_add,
				i.url_full AS image,
				r.currency_name,
				r.currency_id,
				r.price,
				r.price_description,
				t.color_yellow,
		        t.urgently,
				/* (SELECT date_add from top_to_main WHERE section_id=8 AND resource_id = t.ads_id )as date_add , */
				if((SELECT date_end from top_to_main WHERE section_id=8 AND resource_id = t.ads_id AND 1)>$date ,1,0 )as show_top
			FROM `liqpay_status` AS t
			INNER JOIN `realty` AS r ON r.realty_id = t.ads_id AND r.country_id = $country_id AND r.categ_id = $categ_id
			LEFT JOIN `realty_images` AS i ON i.realty_id = r.realty_id AND i.sort_id = 0
			INNER JOIN `categories_realty` AS c  ON r.categ_id= c.categ_id

			WHERE t.section_id = 8 AND t.ads_id != $realty_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date' AND t.show_competitor >2 AND r.flag_delete<1
			ORDER BY RAND()";
		   // Site::d($query);
		return DB::getAssocArray($query);
	}
	
	public function setViews($user_id, $realty_id) {
		if (Request::getCookie('realty_view_' . $realty_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'realty_id'	=> $realty_id,
				'user_id'	=> $user_id,
				'date_view'	=> DB::now()
			);
			
			DB::insert('realty_views', $write);
			
			Request::setCookie('realty_view_' . $realty_id, 1);
		}
		
		return true;
	}
	
	public function getRealtyGallery($realty_id) {
		$query = "SELECT url_full, description FROM `realty_images` WHERE realty_id = $realty_id AND sort_id > 0 ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getRealtyCities($country_id, $categ_id = 0) {
		if ($categ_id > 0) {
			$where = " AND categ_id = $categ_id";
		}
		
		$query = "SELECT city_id, name,
			(SELECT COUNT(*) FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id $where) AS count
			FROM `cities` 
			WHERE city_id IN(SELECT DISTINCT city_id FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id $where)
			ORDER BY sort_id, name";
		
		return DB::getAssocArray($query);
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `realty_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/realty/full/' 	. $image);
			unlink(UPLOADS . '/images/realty/160x200/' 	. $image);
			unlink(UPLOADS . '/images/realty/80x100/' 	. $image);
			unlink(UPLOADS . '/images/realty/64x80/' 	. $image);
			@unlink(UPLOADS . '/images/realty/142x195/' 	. $image);
			DB::delete('realty_images', array('image_id' => $image_id));
			
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
				'path'	=> UPLOADS . '/images/realty/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/realty/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/realty/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/realty/64x80/'
			),
			array(
                'w'    => 195,
				'h'	   => 142,
				'path' => UPLOADS . '/images/realty/142x195/'
                ))
		);
		
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
		
		return $result;
	}
	
	
	public static function  remove(){
		$query="SELECT realty_id FROM realty WHERE flag_delete=1";
		$realtys= DB::getAssocArray($query);
		
		if(!count($realtys))die('remove ok');
		array_map(function($realty){
			static:: removeImages($realty['realty_id']);
		   DB::delete('realty', array('realty_id' => $realty['realty_id']));	
		},$realtys);
		 
	}
	public static function  removeImages($realty_id){
	  $query="SELECT url_full FROM realty_images WHERE realty_id=$realty_id";
	  $images= DB::getAssocArray($query);
	  if(!count($images))return 1;
	  
	  array_map(function($image){
		   
		    @unlink(UPLOADS . '/images/realty/full/' . $image['url_full']);
			@unlink(UPLOADS . '/images/realty/160x200/' . $image['url_full']);
			@unlink(UPLOADS . '/images/realty/80x100/' . $image['url_full']);
			@unlink(UPLOADS . '/images/realty/64x80/' . $image['url_full']);
			@unlink(UPLOADS . '/images/realty/142x195/' . $image['url_full']);
			
		   
		   
	  },$images);
      
     DB::delete('realty_images', array('realty_id' => $realty_id));	  
		
	}
	
	
}