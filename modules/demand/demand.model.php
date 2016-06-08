<?php

class ModelDemand {
	
	public function getDemandList($country_id = 1, $user_id = 0, $page = 1, $count = 15, $search = null, $is_updates = 0, $flag = null) {
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND IF(d.user_id = '" . User::isUser() . "', 1, d.flag = 1 AND d.flag_moder = 1) ";
		}
		
		if ($user_id > 0) {
			$where .= "AND d.user_id = $user_id";
		}
		
		if ($search != null) {
			$match = "AND MATCH(d.name, d.content) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
			/* $orderr_by = "ORDER BY IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), d.date_add DESC"; */
			$orderr_by = "ORDER BY IFNULL(sort__id, 99999), d.date_add DESC";
		}

        if (isset($flag)) {
            $where .= ' AND d.flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), d.date_add) > 13';
		}
		
		$date = DB::now(1);
		
		$query = "SELECT d.demand_id, d.user_id, d.user_name, d.contact_phones, d.name, d.date_add, d.flag, d.flag_moder, flag_vip_add,
			i.url_full,
			(SELECT sort_id FROM `top_to_section` WHERE section_id = 11 AND resource_id = d.demand_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 11 AND resource_id = d.demand_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			flag_moder_view,
			liq.color_yellow,
			liq.urgently
			FROM `demand` AS d
			LEFT JOIN liqpay_status liq ON d.demand_id=liq.ads_id AND  liq.section_id=11
			LEFT JOIN `demand_images` AS i ON i.demand_id = d.demand_id AND i.sort_id = 0
			WHERE d.flag_delete = 0 AND d.country_id = $country_id
			$where
			$match
			$orderr_by
			LIMIT $limit, $count";
		
		$demand = DB::DBObject()->query($query);
		$demand->execute();
		
		while ($array = $demand->fetch(PDO::FETCH_ASSOC)) {
			$array['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $array['contact_phones']));
			$result[] = $array;
		}
		
		return $result;
	}
	
	public function getDemandCount($country_id = 1, $user_id = 0, $search = null, $is_updates = 0) {
		if (!User::isAdmin()) {
			$where = "AND IF(d.user_id = '" . User::isUser() . "', 1, d.flag = 1 AND d.flag_moder = 1) ";
		}
		
		if ($user_id > 0) {
			$where .= "AND d.user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND d.flag = ' . $flag;
        }
		
		if ($search != null) {
			$match = "AND MATCH(d.name, d.content) AGAINST('$search')";
		}
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), d.date_add) > 13';
		}
		
		$query = "SELECT COUNT(*) 
			FROM `demand` AS d
			WHERE d.flag_delete = 0 AND d.country_id = $country_id $where $match";
		
		return DB::getColumn($query);
	}
	
	public function getDemandFull($demand_id) {
		$query = "SELECT d.demand_id, d.user_id, d.user_name, d.contact_phones, d.name, d.date_add, d.content, d.video_link, d.flag_delete, d.country_id,
			i.url_full, flag, flag_moder,
			(SELECT COUNT(*) FROM `demand_views` WHERE demand_id = d.demand_id) AS views,
			liq.urgently,
			liq.color_yellow
			FROM `demand` AS d
			LEFT JOIN liqpay_status liq ON d.demand_id=liq.ads_id AND  liq.section_id=11
			LEFT JOIN `demand_images` AS i ON i.demand_id = d.demand_id AND i.sort_id = 0
			WHERE ". (User::isAdmin() ? 1 : "IF(d.user_id = '" . User::isUser() . "', 1, d.flag = 1 AND d.flag_moder = 1)") . " AND d.demand_id = $demand_id";
		
		$demand = DB::getAssocArray($query, 1);
		
		$demand['phones'] 		= @explode(',', $demand['contact_phones']);
		$demand['video_link']	= str_replace('watch?v=', '', end(explode('/',  $demand['video_link'])));
		
		return $demand;
	}
	
	/* public function getVIP($country_id, $demand_id) {
		$date = DB::now(1);
		
		$query = "SELECT 
			d.demand_id,
			d.name,
			i.url_full AS image
			FROM `top_to_main` AS t
			INNER JOIN `demand` AS d  ON d.demand_id = t.resource_id AND d.country_id = $country_id
			LEFT JOIN `demand_images` AS i ON i.demand_id = d.demand_id AND i.sort_id = 0
			
			WHERE t.section_id = 11 AND resource_id != $demand_id AND DATE_SUB(t.date_start, INTERVAL 1 DAY) < '$date' AND t.date_end > '$date'
			ORDER BY RAND()";
		
		return DB::getAssocArray($query);
	} */
	
	
	 public function getVIP($country_id,  $ads_id) {
        $date = DB::now(1);


			
			   $query = "SELECT
				p.demand_id, 
				p.name,
				p.user_id,
				p.user_name,
				 i.url_full AS image,
				p.name as description,
				t.color_yellow,
			    t.urgently,
			   (SELECT  date_add  FROM `demand` WHERE demand_id  = t.ads_id )as date_add ,
			   if((SELECT date_end from top_to_main WHERE section_id=11 AND resource_id = t.ads_id AND 1)>$date ,1,0 )as show_top
			
			FROM `liqpay_status` AS t
			INNER JOIN `demand` AS p ON p.demand_id = t.ads_id  AND t.show_competitor>2 
			LEFT JOIN `demand_images` AS i ON i.demand_id = p.demand_id AND i.sort_id = 0

			WHERE t.section_id = 11 AND t.ads_id != $ads_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date'
			AND p.flag=1 AND t.end_competitor > '$date' AND p.flag_delete=0
			ORDER BY t.start_competitor "; 
  
          // 	Site::d($query); 
    return DB::getAssocArray($query);
    }
	
	
	public function getDemandGallery($demand_id) {
		$query = "SELECT url_full, description FROM `demand_images` WHERE demand_id = $demand_id AND sort_id > 0 ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function setViews($user_id, $demand_id) {
		if (Request::getCookie('demand_view_' . $demand_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'demand_id'	=> $demand_id,
				'user_id'	=> $user_id,
				'date_view'	=> DB::now()
			);
			
			DB::insert('demand_views', $write);
			
			Request::setCookie('demand_view_' . $demand_id, 1);
		}
		
		return true;
	}
	
	public function add($user_id, $country_id, $demand_data, $images, $flag_moder) {
		
		if ($user_id > 0 and is_array($demand_data) ) {
			DB::insert('demand', array(
				'user_id'			=> $user_id,
				'user_name'			=> $demand_data['user_name'],
				'contact_phones'	=> $demand_data['contact_phones'],
				'country_id'		=> $country_id,
				'name'				=> $demand_data['name'],
				'content'			=> $demand_data['content'],
				'video_link'		=> $demand_data['video_link'],
				'date_add'			=> DB::now(),
				'flag_moder'		=> $flag_moder,
				'flag_vip_add'		=> $demand_data['flag_vip_add']
			));
			
			$demand_id = DB::lastInsertId();
			
			if (is_array($images)) {
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('demand_images', array(
						'demand_id' 	=> $demand_id,
						'sort_id'		=> $i
					), array(
						'image_id'		=> $images[$i]
					));
				}
			}
			
			return $demand_id;
		}
		
		return false;
	}
	
	public function getDemandData($demand_id) {
		$query = "SELECT * FROM `demand` WHERE demand_id = $demand_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getDemandImages($demand_id) {
		$images = "SELECT image_id, url_full, description
			FROM `demand_images` WHERE demand_id = $demand_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function edit($demand_id, $demand_data, $images, $images_descr) {
		DB::update('demand', array(
			'name'			=> $demand_data['name'],
			'content'		=> $demand_data['content'],
			'video_link'	=> $demand_data['video_link'],
			'contact_phones'=> $demand_data['contact_phones']
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
		
		if(Site::isModerView('demand', 'demand_id', $demand_id)) {
			Site::PublicLink(
                'http://navistom.com/demand/' .
				$demand_id . '-' . Str::get($demand_data['name'])->truncate(60)->translitURL()
			);
		}
		
		return true;
	}
	
	public function delete($demand_id) {
		DB::update('demand', array(
			'flag_delete'	=> 1
		), array(
			'demand_id'		=> $demand_id
		));
		
		return true;
	}
	
	public function editFlag($demand_id, $flag = 0) {
		DB::update('demand', array(
			'flag'		=> $flag
		), array(
			'demand_id'	=> $demand_id
		));
		
		return true;
	}
	
	public function editFlagModer($demand_id, $flag_moder = 0) {
		DB::update('demand', array(
			'flag_moder'=> $flag_moder
		), array(
			'demand_id'	=> $demand_id
		));
		
		return true;
	}
	
	public function getUserId($demand_id) {
		return DB::getColumn("SELECT user_id FROM `demand` WHERE demand_id = $demand_id");
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
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/demand/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/demand/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/demand/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/demand/64x80/'
			),
			array(
                    'w'		=> 195,
                    'h'		=> 142,
                    'path'	=> UPLOADS . '/images/demand/142x195/'
                ))
		);
		
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
		
		return $result;
	}
	
	
	public static  function  removeDemand(){
		 echo count(glob(UPLOADS . '/images/demand/full/*.jpg') ) .'до <br/>';
		$query="SELECT demand_id FROM demand WHERE flag_delete=1";
		$Dem=  DB::getAssocArray($query);
		 if(!count($Dem)){
			// echo'no images'; 
			//  echo  count(glob(UPLOADS . '/images/offers/full/*.jpg') );
		 }else{
	      array_map(function($item){   
	      static::imagesRemove($item['demand_id']);
	      DB::delete('demand', array('demand_id' => $item['demand_id']));
	   },$Dem);
	   
	    
	echo  count(glob(UPLOADS . '/images/demand/full/*.jpg') );
	/* echo"<script> location.href='/ads/remove?r=".rand(10,1000) ."' </script>";    */
	 }
	}

    public static  function  imagesRemove( $demand_id){
	 $query="SELECT url_full FROM demand_images WHERE demand_id=$demand_id";	
	 $images=DB::getAssocArray($query);
	 
	
	  array_map(function($image){ 

			@unlink(UPLOADS . '/images/demand/full/' 	. $image['url_full']);
			@unlink(UPLOADS . '/images/demand/142x195/' . $image['url_full']);
			@unlink(UPLOADS . '/images/demand/160x200/' . $image['url_full']);
			@unlink(UPLOADS . '/images/demand/80x100/' 	. $image['url_full']);
			@unlink(UPLOADS . '/images/demand/64x80/' 	. $image['url_full']);

	 },$images); 

       DB::delete('demand_images', array('demand_id' => $demand_id));	    
	}	

}