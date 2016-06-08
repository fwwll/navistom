<?php

class Site {
	public static $banners = array();
    public static $bannersViews = array();

	public function getUserFullInfo($user_id) {
		$query = 'SELECT user_id, email, type, flag, flag_moder, name, date_add, date_edit, country_id, phone, avatar, ip_address
			FROM `users`
			INNER JOIN `users_info` USING(user_id)
			WHERE user_id = $user_id';
		
		$user = DB::getAssocArray($query, 1);
		
		$user['country'] 	= Registry::get('config')->countryes_names[$user['country_id']];
		$user['real_ip'] 	= long2ip(sprintf("%d", $user['ip_address']));
		$user['geo']		= Site::getGeoInfo($user['real_ip']);
		
		return $user;
	}
	
	public function pagination($limit, $count, $page = 1 ,$n=0) {
		$pages 		= ceil($count / $limit);
		 // Site::d($pages,1);
		if($n){
		  $name='no-pay-page-';	
		}else{
		  $name='page-';
		}
		
		$real_page	= $page > 0 ? $page : 1;
		$page_range = 3;
		
		$start_page = $real_page - $page_range;
		$start_page = $start_page > 0 ? $start_page : 1;
		
		$end_page	= $real_page + $page_range;
		$end_page	= $end_page < $pages ? $end_page : $pages - 1;
		
		$pagination['next_page'] 	= ($real_page == $pages ? 0 : $real_page + 1);
		$pagination['prev_page'] 	= $real_page == 1 ? 1 : $real_page - 1;
        $pagination['last']         = array(
            'name'  => '»|',
            'url'   => $pages
        );
        $pagination['first']         = array(
            'name'  => '|«',
            'url'   => 1
        );
		
		for ($i = $start_page; $i <= $end_page; $i++) {
			$pagination['pages'][] = array(
				'name'	=> $i + 1,
				'url'	=> $name . ($i + 1)
			);
		}

        if ($pagination['pages'][0]['name'] == 2) {
            array_unshift($pagination['pages'], array(
                'name'	=> 1,
                'url'	=> $name.'1'
            ));
        }
		
		return $pages > 1 ? $pagination : false;
	}
	
	public function setSectionView($section_id, $user_id) {
		if (Request::getCookie('section_view_' . $section_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'section_id'	=> $section_id,
				'user_id'		=> $user_id,
				'sess_id'		=> session_id(),
				'date_view'		=> DB::now()
			);
			
			DB::insert('sections_views', $write);
			
			Request::setCookie('section_view_' . $section_id, 1);
		}
		
		return true;
	}
	
	public static function getRealIP(){
	    if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) { 
	        $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] :(( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );

	         $entries = explode('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
	        reset($entries);
	        while (list(, $entry) = each($entries)){
	            $entry = trim($entry);
	            if ( preg_match("/^([0-9]+.[0-9]+.[0-9]+.[0-9]+)/", $entry, $ip_list) ){
	                // http://www.faqs.org/rfcs/rfc1918.html
	                $private_ip = array(
	                    '/^0./',
	                    '/^127.0.0.1/',
	                    '/^192.168..*/',
	                    '/^172.((1[6-9])|(2[0-9])|(3[0-1]))..*/',
	                    '/^10..*/');
	                $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
	                if ($client_ip != $found_ip){
	                    $client_ip = $found_ip;
	                    break;
	                }
	            }
	        }
	    } else {
	        $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
	        if ($client_ip == 'unknown') {
	            if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	            {
	                $ip=$_SERVER['HTTP_CLIENT_IP'];}
	                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	                {
	                    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	                }
	                else { 
	                    $ip=$_SERVER['REMOTE_ADDR'];
	                }
	                $client_ip = $ip;
	            }
	        }
	    return $client_ip;
	}
	
	public static function getGeoCuuntry($ip = 0) {
		
		$GEOcountry = LIBS.'geoip/database/GeoIP.dat';
		
		include_once(LIBS.'geoip/geoip.php');
		
		$ip = $ip > 0 ? $ip : self::getRealIP();
		
		if (is_file($GEOcountry)) {
			if ($geoip = geoip_open($GEOcountry, GEOIP_STANDARD)) {
				@$result->name = geoip_country_name_by_addr($geoip, $ip);
				$result->code = strtolower(geoip_country_code_by_addr($geoip, $ip));
				$result->id = geoip_country_id_by_addr($geoip, $ip);
				geoip_close($geoip);
				//Site::d($result);
				return $result;
			}
		}
		
		return false;
	}
	
	public static function getUserCountryDefault() {
		$geo_info = Site::getGeoCuuntry();
		if ($geo_info->id == 222) {
			return 1;
		}
		elseif ($geo_info->id == 185) {
			return 2;
		}
		elseif ($geo_info->id == 36) {
			return 3;
		}
		else {
			return Registry::get('config')->default_country_id;
		}
	}
	
	public static function getRegionsFromSelect($country_id) {
		$regions = "SELECT region_id, name FROM `regions` WHERE country_id = $country_id ORDER BY sort_id, name";
		
		return DB::getAssocKey($regions);
	}
	
	public static function getCitiesFromSelect($region_id, $country_id  = 0) {
		if ($region_id == 0) {
			$where = "country_id = $country_id";
		}
		else {
			$where = "region_id = $region_id";
		}
		
		$cities = "SELECT city_id, name FROM `cities` WHERE $where ORDER BY sort_id, name";
		
		return DB::getAssocKey($cities);
	}
	
	public static function getGeoInfo($ip = 0){
		$GEOcity 	= LIBS.'geoip/database/GeoIPCity.dat';
		
		include_once(LIBS.'geoip/geoip.php');
		include_once(LIBS.'geoip/geoipcity.php');
		
		$ip = $ip > 0 ? $ip : self::getRealIP();
		
		if (is_file($GEOcity)){
			if ($geoip = geoip_open($GEOcity, GEOIP_STANDARD)){
				if ($result = GeoIP_record_by_addr($geoip, $ip)){
					include_once(LIBS.'geoip/geoipregionvars.php');
					
					$result->region_name = $GEOIP_REGION_NAME[$result->country_code][$result->region];
					geoip_close($geoip);
					
					$giisp = geoip_open(LIBS."geoip/database/GeoIPISP.dat",GEOIP_STANDARD);
					$isp = geoip_org_by_addr($giisp,$ip);
					$result->provider = $isp;
					geoip_close($giisp);
					
					return $result;
				}
			}
		}
	}
	
	public function getUserBrowserInfo() {
        include_once(LIBS . 'UdgerParser/UdgerParser.php');

        $parser = new Udger\Parser(false);
        $parser->SetDataDir( Registry::get('config')->uaParserCache );
        //$parser->SetAccessKey( Registry::get('config')->uaParserAccessKey );
        $data = $parser->parse($_SERVER['HTTP_USER_AGENT']);

        return $data['info'];
	}
	
	public function sendSubscribeMessage($title, $email, $message) {
		$mailer = new PHPMailer(true);
		
		$mailer->From 		= $title;
		$mailer->FromName 	= 'NaviStom.com';
		
		$mailer->SetFrom('navistom@navistom.com', 'NaviStom.com');
		
		$mailer->AddAddress($email);
		$mailer->Subject = $title;
		
		$mailer->MsgHTML($message);
		$mailer->IsHTML(true);
		
		$mailer->CharSet	= 'UTF-8';
		
		$mailer->Send();
		
		$mailer->ClearAddresses();
		$mailer->ClearAttachments();
		$mailer->IsHTML(false);
		
		unset($mailer);
	}
	
	public function sendMessageToMail($title, $email, $message, $tpl = null, $copy = null, $from = null, $attach = null, $attach_file = null) {
		include_once(LIBS.'phpmailer/class.phpmailer.php');
		$mailer = new PHPMailer(true);
             
		if ($tpl != null) {
			$message = Registry::get('twig')->render($tpl, $message);
		}	
		
		$mailer->From 		= $title;
		$mailer->FromName 	= 'NaviStom.com';
		
		if ($from == '') {
			$mailer->SetFrom('navistom@navistom.com', 'NaviStom.com');
		}
		else {
			$mailer->SetFrom($from['email'], $from['name']);
		}
		
		if (is_array($attach)) {
			$mailer->AddStringAttachment($attach['file'], $attach['name'], 'base64', 'application/pdf');
		}
		
		if (is_array($attach_file)) {
			$mailer->AddAttachment($attach_file['file'], $attach_file['name']);
		}
		
		$mailer->AddAddress($email);
		
		$mailer->Subject	= $title;
		
		$mailer->MsgHTML($message);
		$mailer->IsHTML(true);
		
		$mailer->CharSet	= 'UTF-8';
		
		if ($mailer->Send()) {
		}
		else {
			
		}
		
		$mailer->ClearAddresses();
		$mailer->ClearAttachments();
		$mailer->IsHTML(false);
		
		/***/
		
		$mailer->From 		= $title;
		$mailer->FromName 	= 'NaviStom.com';
		
		if ($from == '') {
			$mailer->SetFrom('navistom@navistom.com', 'NaviStom.com');
		}
		else {
			$mailer->SetFrom($from['email'], $from['name']);
		}
		
		$mailer->AddAddress('navistom@gmail.com');
		
		$mailer->Subject	= $title;
		
		$mailer->MsgHTML($message);
		$mailer->IsHTML(true);
		
		$mailer->CharSet	= 'UTF-8';
		
		if ($mailer->Send()) {
			return true;
		}
		else {
			return false;
		}
		
		$mailer->ClearAddresses();
		$mailer->ClearAttachments();
		$mailer->IsHTML(false);
	}
	
	public function isModerView($table, $column, $id) {
		if (User::isAdmin()) {
			$is_view = "SELECT flag_moder_view FROM `$table` WHERE $column = $id";
			$is_view = DB::getColumn($is_view);
			
			if ($is_view == 0) {
				DB::update($table, array(
					'flag_moder_view'	=> 1
				), array(
					$column => $id
				));

                \Mailer\Storage::set(Site::getSectionByTable($table), $id);

                /*News::updateOfferOnNews(self::getSectionByTable($table), $id, array(
                    'flag_moder_view' => 1
                ));*/
				
				return true;
			}
		}
		
		return false;
	}
	
	public function PublicLink($link) {
		$vk = new vk();
		$vk->post('', '', $link);
		
		return true;
	}
	
	public function getSectionUrlById($section_id) {
		$sections = array(
		    2	=> 'products/filter-stocks', 
			3 	=> 'products',
			4 	=> 'ads',
			5 	=> 'activity',
			6 	=> 'work/resume',
			7 	=> 'lab',
			8 	=> 'realty',
			9 	=> 'service',
			10 	=> 'diagnostic',
			11	=> 'demand',
			15	=> 'work/vacancy',
			16	=> 'article'
		);
		
		return $sections[$section_id];
	}
	
	public function getSectionsUrlByType($type) {
		switch ($section_id) {
			case 'articles':
				return '/articles';
			break;
			case 'products_new':
				return '/products';
			break;
			case 'ads':
				return '/ads';
			break;
			case 'activity':
				return '/activity';
			break;
			case 'resume':
				return '/work/resume';
			break;
			case 'vacancies':
				return '/work/vacancy';
			break;
			case 'labs':
				return '/labs';
			break;
			case 'realty':
				return '/realty';
			break;
			case 'services':
				return '/services';
			break;
			case 'diagnostic':
				return '/diagnostic';
			break;
			case 'demand':
				return '/demand';
			break;
		}
	}
	
	public function getDefaultMetaTags($controller) {
		$query = "SELECT title, meta_title, meta_description, meta_keys FROM `sections` WHERE controller = '$controller'";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function saveUserMessage($resource_id, $section_id, $from_id, $to_id, $message) {
		DB::insert('users_messages', array(
			'to_id'			=> $to_id,
			'from_id'		=> $from_id,
			'message'		=> $message,
			'section_id'	=> $section_id,
			'resource_id'	=> $resource_id,
			'date_add'		=> DB::now()
		));
		
		return DB::lastInsertId();
	}
	
	public static function getSectionsTable($section_id) {
		switch ($section_id) {
			case 1:
            case 16:
				return 'articles';
			break;
			case 3:
				return 'products_new';
			break;
			case 4:
				return 'ads';
			break;
			case 5:
				return 'activity';
			break;
			case 6:
				return 'work';
			break;
			case 15:
				return 'vacancies';
			break;
			case 7:
				return 'labs';
			break;
			case 8:
				return 'realty';
			break;
			case 9:
				return 'services';
			break;
			case 10:
				return 'diagnostic';
			break;
			case 11:
				return 'demand';
			break;
		}
	}

    public  function getSectionByTable($table) {
        $sections = array(
            'products_new' 	=> 3,
            'ads' 	=> 4,
            'activity' 	=> 5,
            'work' 	=> 6,
            'labs' 	=> 7,
            'realty' 	=> 8,
            'services'	=> 9,
            'diagnostic' 	=> 10,
            'demand'	=> 11,
            'vacancies'	=> 15,
            'articles'	=> 16
        );

        return $sections[$table];
    }
	
	
	
	
	public static function getSectionsTableIdName($section_id) {
		switch ($section_id) {
			case 1:
				return 'article_id';
			break;
			case 3:
				return 'product_new_id';
			break;
			case 4:
				return 'ads_id';
			break;
			case 5:
				return 'activity_id';
			break;
			case 6:
				return 'work_id';
			break;
			case 15:
				return 'vacancy_id';
			break;
			case 7:
				return 'lab_id';
			break;
			case 8:
				return 'realty_id';
			break;
			case 9:
				return 'service_id';
			break;
			case 10:
				return 'diagnostic_id';
			break;
			case 11:
				return 'demand_id';
			break;
		}
	}
	
	public function removeFlagVipAdd($section_id, $resource_id) {
		$table 	= Site::getSectionsTable($section_id);
		$column = Site::getSectionsTableIdName($section_id);
		
		if ($table != null and $column != null) {
			DB::update($table, array('flag_vip_add' => 0), array(
				$column => $resource_id
			));
		}
		
		return true;
	}

	
	public static function removeFlagVipAdd2($section_id, $resource_id) {
		$table 	= Site::getSectionsTable($section_id);
		$column = Site::getSectionsTableIdName($section_id);
		
		if ($table != null and $column != null) {
			DB::update($table, array('flag_vip_add' => 0,'flag_moder_view'=>1), array(
				$column => $resource_id
			));
		}
		
		return true;
	}
	
    public function loadBanners() {
        $date = DB::now(1);

        if (User::isAdmin() and Request::getSession('adv_country') > 1) {
            $country = 0;
			$country=1;
        }
        else {
            $country = Site::getGeoCuuntry()->id == 222 ? 1 : 0;
			$country=1;
        }

        $query = 'SELECT type, flag_default, banner_id, image, link, target, percent, code
            FROM `banners`
            WHERE flag = 1 AND country_id = '. $country .' AND ((date_start < DATE_SUB("' . $date . '", INTERVAL -1 DAY) AND date_end > DATE_SUB("' . $date . '", INTERVAL 1 DAY)) OR flag_default = 1)';
		//Site::d($query);
        $banners = DB::getAssocArray($query);

        for ($i = 0, $c = count($banners); $i < $c; $i++) {
            if ($banners[$i]['flag_default']) {
                self::$banners['default'][$banners[$i]['type']][] = $banners[$i];
            }
            else {
                self::$banners['active'][$banners[$i]['type']][] = $banners[$i];
            }
        }

        return true;
    }

    public function getBanner($type = 1) {
        $banners = self::$banners;
        $tmp = array();

        if (count($banners) == 0) {
            return false;
        }

        if (count($banners['active'][$type]) == 0) {
            $tmp = $banners['default'][$type];
        }
        else {
            $tmp = $banners['active'][$type];
        }

        if (count($tmp) > 0) {
            $banners_percent 	= array();
            $percent_count		= 0;

            for ($i = 0, $c = count($tmp); $i < $c; $i++) {
                $banners_percent = array_merge(
                    $banners_percent,
                    array_fill(
                        count($banners_percent),
                        $tmp[$i]['percent'],
                        $tmp[$i]
                    )
                );

                $percent_count = $percent_count + $tmp[$i]['percent'];
            }

            $banner = $banners_percent[rand(0, $percent_count - 1)];
            $banner['link']	= '/banner-' . $banner['banner_id'];

            self::$bannersViews[] = $banner['banner_id'];

            return $banner;
        }

        return false;
    }

    public function updateBannersViews() {
        if (count(self::$bannersViews) > 0) {
            DB::query('UPDATE `banners` SET views = views + 1 WHERE banner_id IN('. (implode(',', self::$bannersViews)) .')');

            self::$bannersViews = array();
            return true;
        }

        return false;
    }
	
	public function getSectionsList($is_articles = 0,$icon_min=0) {
		
		$where = $is_articles ? "OR section_id = 16 OR section_id = 15 " : "";
		
		$query = "SELECT section_id, name, link, target, class, icon
			FROM `sections`
			WHERE flag = 1 $where
			ORDER BY sort_id";
		if(!$icon_min){  
		     return DB::getAssocArray($query);
		}else{
			  $result=DB::getAssocArray($query);
			  foreach($result as $k=>$v){
				$result[$k]['icon']=str_replace('navi-icon','min-navi-icon', $result[$k]['icon']); 
			  }
			  return $result;
		}
	}
	
	public function resizeImageBg($image, $image_name, $sizes) {
		include_once(LIBS.'upload/upload.class.php');
		
		$image = new upload($image);
		
		if ($image != null) {
			if ($image->uploaded) {
				for ($i = 0, $c = count($sizes); $i < $c; $i++) {
					
					$image->file_new_name_body 		= $image_name;
					$image->image_resize       		= true;
					$image->image_ratio_fill   		= true;
					$image->image_convert 			= 'jpg';
					$image->image_y            		= $sizes[$i]['h'];
					$image->image_x            		= $sizes[$i]['w'];
					$image->image_background_color 	= '#FFFFFF';
					
					$image->Process($sizes[$i]['path']);
					
					$result[] = $sizes[$i]['path'] . $image_name . '.jpg';
				}
				
				$image->Clean();
				
				return $result;
			}
		}
		
		return false;
	}
	
	public function resizeImage($image, $image_name, $sizes) {
		if ($image != null) {
			for ($i = 0, $c = count($sizes); $i < $c; $i++) {
				$img = AcImage::createImage($image);
				
				if ($sizes[$i]['crop'] != -1) {
					
					
					
					
					if( $sizes[$i]['w']==700  ){
						if($img->getWidth() >700) $img->resizeByWidth( 700);
					  }else{
						  /* $img->cropCenter('4pr', '3pr');
							$img->thumbnail(
								$sizes[$i]['w'],
								$sizes[$i]['h']
							); */
							$img->resizeByWidth( $sizes[$i]['w']);
					  }
					
					
					
					
					
				}
				elseif ($sizes[$i]['crop'] != -2) {
					$img->thumbnail(
						$sizes[$i]['w'],
						$sizes[$i]['h']
					);
				}
				else {
					$img->cropCenter($sizes[$i]['w'], $sizes[$i]['h']);
				}
				
				$img->saveAsJPG($sizes[$i]['path'] . $image_name . '.jpg');
				
				$result[] = $sizes[$i]['path'] . $image_name . '.jpg';
			}
			
			return $result;
		}
	}
	
	public function getCountryCurrency($country_id) {
		return DB::getAssocArray("SELECT currency_id, name, name_min FROM `currency` WHERE country_id = $country_id");
	}
	
	public function getContentsCount($flag_no_view = 0, $user_id = 0, $q = null, $flag_moder = 0, $flag_vip_add = 0, $flag_no_show = 0) {
		$date 		= DB::now(1);
		
		if ($flag_no_view > 0) {
			$where 			= " AND flag_moder_view = 0";
			$where_articles = "AND 0";
		}
		else {
			$where_articles = "AND flag_moder = 1";
		}
		
		if ($user_id > 0) {
			$where .= " AND user_id = $user_id";
			$where_articles .= " AND user_id = $user_id";
			$where_stocks = "AND 0";
		}
		
		if ($flag_moder > 0) {
			$where .= " AND flag_moder = 0";
			$where_articles .= " AND flag_moder = 0";
			$where_stocks .= " AND flag_moder = 0";
		}
		else {
			$where .= " AND flag_moder = 1";
			$where_articles .= " AND flag_moder = 1";
			$where_stocks .= " AND flag_moder = 1";
		}
		
		if ($flag_vip_add > 0) {
			$where = " AND flag_vip_add = 1";
			$where_articles = " AND flag_vip_add = 1";
			$where_stocks = ' AND 0';
		}

        if ($flag_no_show > 0) {
            $flag = 0;
        }
        else {
            $flag = 1;
        }
		
		$is_search = $q != null ? true : false;
		
		$query = "SELECT COUNT(*) AS articles_count,
			(SELECT COUNT(*) 
				FROM `products_new` 
				WHERE " . ($is_search ? " MATCH(product_name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 AND flag_show = 1 $where) AS '3',
			(SELECT COUNT(*) 
				FROM `stocks` 
				WHERE flag = {$flag} AND flag_delete = 0 AND flag_show = 1 AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date' $where_stocks) AS '2',
			(SELECT COUNT(*) 
				FROM `ads` 
				WHERE " . ($is_search ? " MATCH(product_name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 AND flag_show = 1 $where) AS '4',
			(SELECT COUNT(*) 
				FROM `activity` 
				WHERE " . ($is_search ? " MATCH(name) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_moder = 1 AND flag_delete = 0 AND
				IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '$date', date_start > '$date'), 1)
				$where) AS '5',
			(SELECT COUNT(*) 
				FROM `work` 
				WHERE " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 $where) AS '6',
			(SELECT COUNT(*) 
				FROM `vacancies` 
				WHERE " . ($is_search ? " MATCH(search_name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 $where) AS '15',
			(SELECT COUNT(*) 
				FROM `labs` 
				WHERE " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 $where) AS '7',
			(SELECT COUNT(*) 
				FROM `realty` 
				WHERE " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 $where) AS '8',
			(SELECT COUNT(*) 
				FROM `services` 
				WHERE " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 $where) AS '9',
			(SELECT COUNT(*) 
				FROM `diagnostic` 
				WHERE " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 $where) AS '10',
			(SELECT COUNT(*) 
				FROM `demand` 
				WHERE " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . " flag = {$flag} AND flag_delete = 0 $where) AS '11'
			FROM `articles`
			WHERE " . ($is_search ? " MATCH(name, content_min, meta_title, meta_keys, meta_description) AGAINST('$q') AND " : "") . " flag = {$flag} $where_articles AND flag_delete = 0";
		
		return DB::getAssocArray($query, 1);
	}
	public function all_count(){
		$date = DB::now(1);
		   
		$query="SELECT COUNT(*) AS  articles_count ,
			(
			SELECT count(*)
				FROM `products_new`
				LEFT JOIN liqpay_status liq ON products_new.product_new_id=liq.ads_id AND  liq.section_id=3
				INNER JOIN `categories`
					ON categories.categ_id = products_new.sub_categ_id
				LEFT JOIN `products`
					USING(product_id)
				
				LEFT JOIN `stocks` AS s
					ON s.product_new_id = products_new.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '$date' AND s.date_end > DATE_SUB('$date', INTERVAL 1 DAY)
				LEFT JOIN `light_content` AS l
					ON l.section_id = 3 AND l.resource_id = products_new.product_new_id AND DATE_SUB(l.date_start, INTERVAL 1 DAY) < '$date' AND l.date_end > DATE_SUB('$date', INTERVAL 1 DAY)
				WHERE products_new.flag_delete = 0 AND IF(products_new.user_id = '', 1, products_new.flag = 1 AND products_new.flag_moder = 1 AND products_new.flag_show = 1)  AND s.flag > 0 
			
			) AS '2',	
			(
				SELECT count(*) as count
				FROM `products_new`
				INNER JOIN `categories`
				ON categories.categ_id = products_new.sub_categ_id
				LEFT JOIN `products`
				USING(product_id)
				LEFT JOIN `light_content` AS l
				ON l.section_id = 3 
				AND l.resource_id = products_new.product_new_id 
				AND DATE_SUB(l.date_start, INTERVAL 1 DAY) < '$date' 
				AND l.date_end > DATE_SUB(now() , INTERVAL 1 DAY)
				WHERE products_new.flag_delete = 0 
				AND  products_new.flag = 1 
				AND products_new.flag_moder = 1 
				AND products_new.flag_show = 1
			)AS '3' ,
			(
				SELECT count(*) as count
				FROM `ads`
				LEFT JOIN liqpay_status liq ON ads.ads_id=liq.ads_id AND  liq.section_id=4  
				INNER JOIN `products`
					USING(product_id)
				INNER JOIN `categories`
					ON categories.categ_id = ads.sub_categ_id
				LEFT JOIN users  ON ads.user_id  =users.user_id	
				WHERE flag_delete = 0 and ((ads.pay=1 and  DATEDIFF(  NOW( ) ,ads.date_add) <=50 ) or users.group_id=10 or ads.user_id = 0 )   AND IF(ads.user_id = '0', 1, ads.flag = 1 AND ads.flag_moder = 1) 
			) AS '4',
			(
				SELECT COUNT(*) 
				FROM `activity` 
				WHERE  flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND
				IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '$date', date_start > '$date'), 1)
				AND flag_moder = 1
			) AS '5',
			(
				SELECT count(*)
				FROM `work`
				WHERE flag_delete = 0 AND type = 1 AND  flag = 1 AND flag_moder = 1 

			)AS '6',
			(
				SELECT count(*)
				FROM `labs` AS l
				WHERE flag_delete = 0 AND country_id = 1 
				AND  flag = 1 AND flag_moder = 1
			)AS '7',
			(
				SELECT COUNT(*) 
				FROM `realty` 
				WHERE  flag = 1 AND flag_delete = 0  AND flag_moder = 1
			) AS '8',
			(
				SELECT COUNT(*) 
				FROM `services` 
				WHERE  flag = 1 AND flag_delete = 0  AND flag_moder = 1
			)AS '9',
			(
				SELECT COUNT(*) 
				FROM `demand` 
				WHERE  flag = 1 AND flag_delete = 0  AND flag_moder = 1
			) AS '11',
			(
				SELECT COUNT(*) 
				FROM `vacancies` 
				WHERE  flag = 1 AND flag_delete = 0  AND flag_moder = 1
			) AS '15',
            
			(
			
			 SELECT COUNT(*) FROM `articles`  WHERE  flag = 1 AND flag_delete = 0  AND flag_moder = 1
			) AS '16'

		FROM `articles`";
        
		return DB::getAssocArray($query)[0];
	}
	
	public static function no_pay_count(){
		$query="SELECT count(*) as count
				
			FROM `ads`
			 
			INNER JOIN `products`
			    USING(product_id)
			INNER JOIN `categories`
			    ON categories.categ_id = ads.sub_categ_id
			LEFT JOIN users  ON ads.user_id  =users.user_id	
			WHERE flag_delete = 0 and(ads.pay=0  or  DATEDIFF(  NOW( ) ,ads.date_add) >50 ) and users.group_id <>10     
			AND  ads.flag = 1 AND ads.flag_moder = 1";
			
			
			return DB::getAssocArray($query)[0]['count'];
			 
	}
	
	
	public static function no_pay_new_count(){
		$query="SELECT count(*) as count
				
			FROM `products_new` as ads
			 
			INNER JOIN `products`
			    USING(product_id)
			INNER JOIN `categories`
			    ON categories.categ_id = ads.sub_categ_id
			INNER JOIN users  ON ads.user_id  =users.user_id	
			WHERE flag_delete = 0 and(ads.pay=0  or  DATEDIFF(  NOW( ) ,ads.date_add) >50 ) and users.group_id <>10     
			AND  ads.flag = 1 AND ads.flag_moder = 1";
			
			
			return DB::getAssocArray($query)[0]['count'];
			 
	}
	
	
	
	public static function product_count(){
		
		
		$query="SELECT count(*) as count
			FROM `products_new`
			INNER JOIN `categories`
			    ON categories.categ_id = products_new.sub_categ_id
			LEFT JOIN `products`
			    USING(product_id)
			LEFT JOIN `light_content` AS l
			    ON l.section_id = 3 AND l.resource_id = products_new.product_new_id AND DATE_SUB(l.date_start, INTERVAL 1 DAY) < '2016-04-01' AND l.date_end > DATE_SUB(now() , INTERVAL 1 DAY)
			WHERE products_new.flag_delete = 0 AND  products_new.flag = 1 AND products_new.flag_moder = 1 AND products_new.flag_show = 1";
		  
		return DB::getAssocArray($query)[0]['count'];
	}
	
	
	
	public static function  product_ads_count(){
		$query="SELECT count(*)			
			FROM `ads`
			INNER JOIN `products`
			    USING(product_id)
			INNER JOIN `categories`
			    ON categories.categ_id = ads.sub_categ_id
			LEFT JOIN users  ON ads.user_id  =users.user_id	
			WHERE flag_delete = 0 and ((ads.pay=1 and  DATEDIFF(  NOW( ) ,ads.date_add) <=50 ) or users.group_id=10 or ads.user_id = 0 )   AND ads.flag = 1 AND ads.flag_moder = 1";
			
			return DB::getAssocArray($query)[0]['count'];
	}
	
	
	
	
	public function getMessTplsToSelect($section_id) {
		$query = "SELECT mess_id, title FROM `feedback_mess_tpls` WHERE section_id = $section_id";
		
		return DB::getAssocArray($query);
	}

    public function getCategoriesFromSelect($parentId = 0, $sectionId = 3, $allSubCategories = false, $ignoredZero = false) {
        $date = DB::now(1);
        $flagIgnore = ($sectionId == 4 ? 'flag_no_ads = 1' : 'flag_no_products = 0');
        $categoryWhere = $allSubCategories ? 'sub_categ_id = c.categ_id' : ($parentId > 0 ? 'sub_categ_id = c.categ_id' : 'categ_id = c.categ_id');

        $countRules = array(
            2 => '(SELECT COUNT(*) FROM products_new WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND '. $categoryWhere .' AND
                        (SELECT stock_id FROM stocks WHERE product_new_id = products_new.product_new_id AND flag = 1 AND flag_moder = 1 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date .'") > 0) AS count',

            3 => '(SELECT COUNT(*) FROM products_new
                    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND '. $categoryWhere .') AS count',

            4 => '(SELECT COUNT(*) FROM ads WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND '. $categoryWhere .') AS count'
        );

        $query = 'SELECT parent_id, categ_id, name, name_min, '. $countRules[$sectionId] .'
              FROM categories AS c
              WHERE '. ($allSubCategories ? 'parent_id > 0' : ('parent_id = ' . $parentId)) .' AND '. $flagIgnore .'
              '. ($ignoredZero ? 'HAVING count > 0' : '') .'
              ORDER BY sort_id';

        return $allSubCategories ? DB::getAssocGroup($query) : DB::getAssocArray($query);
    }

	public static function getLastOffers() {
		$now = DB::now(1);

		$query = "
        SELECT SQL_CACHE a.*,
        	sections.name_sys AS section_name,
        	sections.icon,
        	sections.link
        FROM (
          (SELECT
               activity.activity_id AS content_id,
               5 AS section_id,
               'activity' As type,
               activity.user_id,
               activity.user_name,
               activity.name,
               IFNULL(IF(l.image, CONCAT('/uploads/images/activity/lectors/', l.image), NULL), IF(activity.image, CONCAT('/uploads/images/activity/142x195/', activity.image), NULL)) AS image,
               activity.date_start AS description,
               activity.date_end AS price,
               city_name AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               activity.date_add
           FROM `activity`
           LEFT JOIN `activity_lectors` AS l
               ON l.activity_id = activity.activity_id AND l.sort_id = 0
           WHERE
               activity.flag = 1 AND activity.flag_moder = 1 AND flag_delete = 0 AND
               ((date_start = '0000-00-00' AND date_end = '0000-00-00') OR (date_start != '0000-00-00' AND date_start > '$now') OR (date_start > '$now' AND date_end != '0000-00-00'))
		   ORDER BY date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               ads.ads_id AS content_id,
               4 AS section_id,
               'ads' AS type,
               user_id,
               user_name,
               CONCAT(product_name, ', Б/У') AS name,
               IFNULL(IF(i.url_full, CONCAT('/uploads/images/offers/142x195/', i.url_full), NULL), IF(products.image, CONCAT('/uploads/images/products/142x195/', products.image), NULL)) AS image,
               products.description,
               ads.price,
               ads.price_description,
               ads.currency_name,
               ads.currency_id,
               '' AS flag_stock,
               ads.date_add
           FROM `ads`
           INNER JOIN `products`
               USING(product_id)
           LEFT JOIN `ads_images` AS i
               ON i.ads_id = ads.ads_id AND i.sort_id = 0
           WHERE
               ads.flag = 1 AND ads.flag_moder = 1 AND ads.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               p.product_new_id AS content_id,
               3 AS section_id,
               'products_new' As type,
               user_id,
               user_name,
               product_name AS name,
               IFNULL(CONCAT('/uploads/images/products/142x195/', i.url_full), CONCAT('/uploads/images/products/142x195/', products.image)) AS image,
               products.description,
               p.price,
               p.price_description,
               p.currency_name,
               p.currency_id,
               0 AS flag_stock,
               p.date_add
           FROM `products_new` AS p
           INNER JOIN `products`
               USING(product_id)
           LEFT JOIN `products_new_images` AS i
               ON i.product_new_id = p.product_new_id AND i.sort_id = 0
           WHERE
               p.flag = 1 AND p.flag_moder = 1 AND p.flag_delete = 0 AND p.flag_show = 1
		   ORDER BY date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               p.product_new_id AS content_id,
               2 AS section_id,
               'products_new' As type,
               user_id,
               user_name,
               product_name AS name,
               IFNULL(CONCAT('/uploads/images/products/142x195/', i.url_full), CONCAT('/uploads/images/products/142x195/', products.image)) AS image,
               products.description,
               s.price,
               p.price_description,
               p.currency_name,
               p.currency_id,
               s.flag AS flag_stock,
               s.date_add
           FROM `products_new` AS p
           INNER JOIN `products`
               USING(product_id)
           LEFT JOIN `products_new_images` AS i
               ON i.product_new_id = p.product_new_id AND i.sort_id = 0
           INNER JOIN `stocks` AS s
               ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND s.date_start <= '$now' AND s.date_end > '$now'
           WHERE
               p.flag = 1 AND p.flag_moder = 1 AND p.flag_delete = 0 AND p.flag_show = 1
		   ORDER BY s.date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               s.service_id AS content_id,
               9 AS section_id,
               'services' AS type,
               user_id,
               user_name,
               name,
               CONCAT('/uploads/images/services/142x195/', i.url_full) AS image,
               '' AS description,
               '' AS price,
               '' AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               s.date_add
           FROM `services` AS s
           LEFT JOIN `services_images` AS i
               ON i.service_id = s.service_id AND i.sort_id = 0
           WHERE
               s.flag = 1 AND s.flag_moder = 1 AND s.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               d.demand_id,
               11 AS section_id,
               'demand' AS type,
               user_id,
               user_name,
               name,
               CONCAT('/uploads/images/demand/142x195/', i.url_full) AS image,
               '' AS description,
               '' AS price,
               '' AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               d.date_add
           FROM `demand` AS d
           LEFT JOIN `demand_images` AS i
               ON i.demand_id = d.demand_id AND i.sort_id = 0
           WHERE
               d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               l.lab_id AS content_id,
               7 AS section_id,
               'labs' AS type,
               l.user_id,
               ui.name AS user_name,
               l.name,
               CONCAT('/uploads/images/labs/142x195/', i.url_full) AS image,
               '' AS description,
               '' AS price,
               '' AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               l.date_add
           FROM `labs` AS l
           LEFT JOIN `users_info` AS ui
               USING(user_id)
           LEFT JOIN `labs_images` AS i
               ON i.lab_id = l.lab_id AND i.sort_id = 0
           WHERE
               l.flag = 1 AND l.flag_moder = 1 AND l.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               r.realty_id AS content_id,
               8 AS section_id,
               'realty' AS type,
               user_id,
               user_name,
               CONCAT(name, ', г. ', city_name) AS name,
               CONCAT('/uploads/images/realty/142x195/', i.url_full) AS image,
               '' AS description,
               price AS price,
               price_description,
               currency_name AS currency_name,
               currency_id,
               '' AS flag_stock,
               r.date_add
           FROM `realty` AS r
           LEFT JOIN `realty_images` AS i
               ON i.realty_id = r.realty_id AND i.sort_id = 0
           WHERE
               r.flag = 1 AND r.flag_moder = 1 AND r.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
        ) /* UNION ALL (
           SELECT
               d.diagnostic_id AS content_id,
               10 AS section_id,
               'diagnostic' AS type,
               user_id,
               user_name,
               name,
               CONCAT('/uploads/images/diagnostic/full/', i.url_full) AS image,
               '' AS description,
               '' AS price,
               city_name AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               d.date_add
           FROM `diagnostic` AS d
           LEFT JOIN `diagnostic_images` AS i
               ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
           WHERE
               d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
        ) */ UNION ALL (
           SELECT
               w.work_id AS content_id,
               6 AS section_id,
               'resume' AS type,
               user_id,
               user_name,
               (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id)) AS name,
               CONCAT('/uploads/images/work/142x195/', i.url_full) AS image,
               ui.avatar AS description,
               price,
               city_name AS price_description,
               currency_name,
               currency_id,
               '' AS flag_stock,
               w.date_add
           FROM `work` AS w
           LEFT JOIN `users_info` AS ui
               USING(user_id)
           LEFT JOIN `work_images` AS i
               ON i.work_id = w.work_id AND i.sort_id = 0
           WHERE
               w.flag = 1 AND w.flag_moder = 1 AND w.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
        ) UNION ALL (
           SELECT
               v.vacancy_id AS content_id,
               15 AS section_id,
               'vacancies'	AS type,
               v.user_id,
               c.name AS user_name,
               CONCAT((SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)), ', г. ', city_name) AS name,
               CONCAT('/uploads/images/work/142x195/', c.logotype) AS image,
               '' AS description,
               v.price,
               v.currency_name,
               v.currency_id,
               '' AS price_description,
               '' AS flag_stock,
               v.date_add
           FROM `vacancies` AS v
           INNER JOIN `vacancy_company_info` AS c
               USING(company_id)
           WHERE
               v.flag = 1 AND v.flag_moder = 1 AND v.flag_delete = 0
		   ORDER BY date_add DESC
		   LIMIT 1
           )
        ) AS a

		LEFT JOIN `sections` ON  sections.section_id = a.section_id

        ORDER BY sections.sort_id";
		//Site::d($query)	;
		return DB::getAssocArray($query);
	}
	
	 public static function getPriceCategoriy($section_id ,$s='*'){
		$query='select '.$s.' from price_cat where section_id='.$section_id;
		  $result=DB::getAssocArray($query);
		 if( empty($result)){
			 // $result =Registry::get('config')->price;
		   }else{
             return  $result[0];
	       }	  
		return  $result;
	}
	public static function getPriceCategoriyCheked($section_id ,$s='*'){
		$query='select '.$s.' from price_cat_checkbox  where section_id='.$section_id;
		  $result=DB::getAssocArray($query);
		 	  
		return  $result[0];
	}
	
	public static function dataJsoneString($arr){
		$result ="{";
		
			foreach($arr as $k =>$v){
					$result .='"'.$k.'":"'.$v.'",' ;	
			}
		 return trim($result,",")."}"; 
	}
	
	
	
	public static function  setTimeOut($i,$update=0)
	{  $i or die('TIME ERROR'); 
	 	   $curr_time=  time();
			$date=0;
	      if($update){
		  
			$res=DB::getAssocArray('SELECT date_end  FROM `light_content` WHERE  resource_id='.$update[0].' AND section_id='.$update[1],1);
            if(!empty($res['date_end'])){
				$date=strtotime($res['date_end']);
			}
			$date=$date - $curr_time;
			if($date<0)$date=0;
			 
		  }
	   	
			$time=array();	
			$time_index= Registry::get('config')->time_index;
			$time_end =  $curr_time+ ($time_index[$i]) +$date;
			$time['curr_time']=  date('Y-m-d',$curr_time);
			$time['time_end']=   date('Y-m-d',$time_end);
		return $time;
	}
	
	
	
	public static function  setTimePayment($i,$update=0)
	{  $i or die('TIME ERROR'); 
	 	   $curr_time=  time('Y-m-d');
			$date=0;
	      if($update){

			$res=DB::getAssocArray('SELECT end_competitor  FROM `liqpay_status` WHERE  ads_id='.$update[0].' AND section_id='.$update[1],1);
			
			
            if(!empty($res['end_competitor'])){
				
				$date_s=strtotime($res['end_competitor']);
				$datetime1 = date_create(date('Y-m-d',$curr_time));
				$datetime2 = date_create(date('Y-m-d',$date_s));
				$interval = date_diff($datetime1, $datetime2);
				$r= $interval->format('%R%a');
			
				if($r>0){
					$r=(int)$r;
					$date =($r*24*60*60);
				}
			
		  }
		}
			$time=array();	
			$time_index= Registry::get('config')->time_index;
			$time_end =  $curr_time+ ($time_index[$i]) +$date;
			$time['start_competitor']=  date('Y-m-d',$curr_time);
			$time['end_competitor']=   date('Y-m-d',$time_end);
		    return $time;
	 
	}
	public static function month(){
		return [		
		'1'  => 'Январь',
		'2'  => 'Февраль',
		'3'  => 'Март',
		'4'  => 'Апрель',
		'5'  => 'Май',
		'6'  => 'Июнь',
		'7'  => 'Июль',
		'8'  => 'Август',
		'9'  => 'Сентябрь',
		'10' => 'Октябрь',
		'11' => 'Ноябрь',
		'12' => 'Декабрь'
		];
		
	}
	
	public static function  table_categ( $cat){
		switch($cat){
			case 'products': return 'categories';
			case 'ads':return 'categories';
			case 'services':return 'categories_services';
			case 'demand':return 'demand';
			case 'activity':return 'categories_activity';
			case 'work':return 'categories_work';
			case 'labs':return 'categories_labs';
			case 'realty':return 'categories_realty';
			case 'articles':return 'categories_articles';
		}
		return false;
	}
	
	
	public static function  table_images( $cat){
		switch($cat){
			case 'products': return 'products_new_images';
			case 'ads':return 'ads_images';
			case 'services':return 'services_images';
			case 'demand':return 'demand_images';
			case 'activity':return 'activity_images';
			case 'work':return 'work_images';
			case 'labs':return ' labs_images';
			case 'realty':return 'realty_images';
			case 'articles':return 'articles_images';
			case 'vacancies':return 'vacancy_images';
		}
		return false;
	}
	
	
	public static function getName($controller ){
		switch($controller){
			case 'products': return 'Продам новое';
			case 'ads':return 'Продам Б/У';
			case 'services':return 'Сервис/Запчасти';
			case 'demand':return 'Спрос';
			case 'activity':return 'Анонсы мероприятий';
			case 'resume':return 'Резюме';
			case 'vacancy':return 'Вакансии';
			case 'labs':return 'З/Т Лаборатории';
			case 'realty':return 'Аренда/Продажа';
			case 'articles':return 'Статьи';
			case 'work':return ' ';
			case 'maps': return'Карта Navistom';
		}
		return false;
		
	}
	
	
	public static function getNameID($id ){
		switch($id){
			case 3: return 'Продам новое';
			case 4:return 'Продам Б/У';
			case 9:return 'Сервис/Запчасти';
			case 11:return 'Спрос';
			case 5:return 'Мероприятия';
			case 6:return 'Резюме';
			case 15:return 'Вакансии';
			case 7:return 'З/Т Лаборатории';
			case 8:return 'Недвижимость';
			case 1:return 'Статьи';
			
		}
		return false;
		
	}
	
	
	
	public static function remaining_time( $time)
	{
		$curr= date( 'Y-m-d', time());
		$datetime1 = date_create($curr);
		$datetime2 = date_create($time);
		$interval = date_diff($datetime1, $datetime2);
		if($interval->format('%R%a')>0){
			return $interval->format('%a');
		}else{
			return 0;
		}
	}
	public  static function date_format($data){
		 $d= strtotime($data);
		 $d -=(1*24*60*60);
		 return date( 'Y-m-d',$d);
	}
	
	public static function is_ajax(){
		 // Site::d($_SERVER['HTTP_X_REQUESTED_WITH'],1);
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;	
	    }

			return false;
		
	}

	public static function is_image($link){
		$fd = @ fopen($link, "r");
		
		if (!$fd){ 
			 return false; 
		}else{ 
			return true;
		}
		
		fclose ($fd); 
	}
	
	 public static function cityName($return=false){
		$url=Request::get('route') ;
		$r='#city-(\d+)#';
		
		if(preg_match($r,$url,$sub_id)){
			$parentId=$sub_id[1]; 
			$query='select name from  cities where city_id='.$parentId;
		    $result=DB::getAssocArray($query);
			return $result[0]['name'];
		}

		return $return;
	} 
	
	public static  function categoryName($cat_name='articles' ){
		$url=Request::get('route') ;
		$r='#categ-(\d+)#';
		
		if(preg_match($r,$url,$sub_id)){
			$parentId=$sub_id[1];
			$query='select name from  categories_'.$cat_name.'  where categ_id='.$parentId;
		    $result=DB::getAssocArray($query);
			
			return $result[0]['name'];	
		}
	}
	
	public static function get_meta($page ,$return=0){
		$query="SELECT * FROM meta_tegs WHERE  pages= '$page'";
		$M= DB::getAssocArray($query)[0];
		
		 if($return) return $M;
		 if($M['title'])Header::SetTitle($M['title']);
		 if($M['h1'])Header::SetH1Tag($M['h1']);
		 if($M['description']) Header::SetMetaTag('description',$M['description']);
		 if($M['keywords'])Header::SetMetaTag('keywords',$M['keywords']);
	}
	
	public static function set_meta( $arr_meta,$pages){
		  
		if(count(self::get_meta($pages,1))){
			DB::update('meta_tegs', $arr_meta, array('pages'=> $pages));
			
		}else{
			
			DB::insert('meta_tegs',$arr_meta );
		}	
		
		
		
	}
	
	public static function  pay($id=0,$tab_number){
		if($tab_number==4){ 
			$query="select  pay,  group_id , DATEDIFF( NOW( ),  date_add  ) as date_e  from ads
			inner join users on users.user_id=ads.user_id  
			where    ads_id=$id" ;
		}else{
			
			$query="select  pay,  group_id , DATEDIFF( NOW( ),  date_add  ) as date_e  from products_new
			inner join users on users.user_id=products_new.user_id 
			where   products_new.product_new_id =$id"; 	
			
		}
		//Site::d($query);
		$res= DB::getAssocArray($query);
		if($res[0]['group_id'] ==10) return true;
		 
		 
		return  ($res[0]['pay'] and  $res[0]['date_e']<50  )?true:false;	
	}
	
	
	public static function isPayAlert( $user_id ){
		
		$query="SELECT   count(*)as counts   FROM ads as a
			inner join users as u on  u.user_id =a.user_id 
			INNER JOIN `categories`
			 ON categories.categ_id = a.sub_categ_id
			WHERE  u.group_id <> 10 AND
			a.flag = 1 AND
			a.flag_moder = 1 AND
			a.flag_delete = 0 AND 
			a.flag_moder_view = 1 AND
			a.flag_show = 1  AND 
			(a.pay=0 or DATEDIFF(now() ,a.date_add )>50) AND
			a.user_id=$user_id";

			//Site::d($query,1);	
        return DB::getAssocArray($query)[0]['counts'];
			
	}
	
	public static function print_page(){
		if(Request::post('print','int'))   
		echo'<script> 
	        var stor =localStorage.getItem("print");
	
		   window.onload=function(){
			  if( parseInt(stor)){
				 localStorage.setItem("print","0") ;
			     $("body").addClass("print");
		         window.print();
			   }
		   }
		</script>';
	   
	    return ;
	}
	
	
	
	
	public static function d($ob ,$co=0){
		 if($co){
			 if($_COOKIE["xxx"] and  $_COOKIE["volo"]){
				$flag=1; 
			 }else{
				$flag=0; 
			 }
		 }else{
			 $flag=1;
		 }
		
		if(($_SERVER['REMOTE_ADDR']=='93.188.36.16' or $_SERVER['REMOTE_ADDR']=='134.249.173.243')and $flag==1){
			echo '<pre>';
			var_dump($ob);
			die;
		}	
	}
	
	
	public static function get_jurnal_image($section_id,$resource_id){
		$query="
		SELECT section_id ,resource_id ,  image, path ,name , phones,price,user_id FROM(
		 (
			 SELECT 
			 '4'As section_id, 
			 ads.ads_id as resource_id ,
			 IFNULL( img.url_full, 0) as image,
			 '/uploads/images/offers/142x195/' as path,
			 ads.product_name  as name,
			 ads.contact_phones as phones ,
			 ads.price,
			 ads.currency_id,
			 ads.user_id
			 FROM `ads` 
			LEFT JOIN   ads_images  AS img  ON ads.ads_id = img.ads_id  and img.sort_id=0
		 )UNION(
			 SELECT  
			 '3'As section_id,
			  pro.product_new_id as resource_id ,
			  IFNULL(img.url_full, 0) as image,
			  '/uploads/images/products/142x195/' as path,
			  pro.product_name  as name,
			  pro.contact_phones as phones, 
			  pro.price,
			  pro.currency_id,
			  pro.user_id
			 FROM `products_new` as pro
			LEFT JOIN  products_new_images AS img  ON pro.product_new_id=img.product_new_id AND  img.sort_id=0
		 
		 ) UNION(
			SELECT 
			  '9'As section_id,
			  ser.service_id as resource_id,
			  IFNULL(img.url_full, 0) as image,
			  '/uploads/images/services/142x195/' as path,
			  ser.name,
			  ser.contact_phones as phones,
			  '' AS price,
			  ''AS currency_id,
			  ser.user_id			  
			 FROM `services` AS ser
			LEFT JOIN  services_images AS img  ON  ser.service_id=img.service_id AND  img.sort_id=0
		 
		 )UNION(
			SELECT 
			  '11'As section_id,
			  d.demand_id as resource_id,
			  IFNULL(img.url_full, 0) as image,
			  '/uploads/images/demand/142x195/' as path,
			  d.name,
			  d.contact_phones as phones,
			  '' AS price,
			  ''AS currency_id,
			  d.user_id
			 FROM `demand` AS d
			LEFT JOIN  demand_images AS img  ON  d.demand_id=img.demand_id AND  img.sort_id=0
		 
		 )UNION(
			SELECT 
			  '5'As section_id,
			  d.activity_id as resource_id,
			  IFNULL(l.image, 0) as image,
			  '/uploads/images/activity/lectors/' as path,
			  
			  d.name,
			  d.contact_phones as phones,
			  '' AS price,
			  ''AS currency_id,
			  d.user_id
			 FROM `activity` AS d
			LEFT JOIN  activity_images AS img  ON  d.activity_id=img.activity_id AND  img.sort_id=0
			LEFT JOIN `activity_lectors` AS l
               ON l.activity_id = d.activity_id AND l.sort_id = 0
		 
		 )UNION(
			SELECT 
			  '6'As section_id,
			  d.work_id as resource_id,
			  IFNULL(img.url_full, 0) as image,
			  '/uploads/images/work/142x195/' as path,
			 CONCAT( d.user_surname,' ',d.user_name,' ', d.user_firstname,' ', d.name, ' ,г.',d.city_name) AS name,
			  d.contact_phones as phones,
			  d.price,
			  d.currency_id,
			  d.user_id
			 FROM `work` AS d
			LEFT JOIN  work_images AS img  ON  d.work_id=img.work_id AND  img.sort_id=0
		 
		 )UNION(
			SELECT 
			  '15'As section_id,
			  d.vacancy_id as resource_id,
			  (SELECT url_full FROM `work_images` WHERE work_id = d.vacancy_id AND sort_id = 0 AND flag_vac=1) AS image,
			/*   IFNULL(c.logotype, 0) as image, */
			  '/uploads/images/work/142x195/' as path,
			 CONCAT('Требуется ',LOWER(d.search_name),', г.',d.city_name ) as name,
			  d.contact_phones as phones,
			  d.price,
			  d.currency_id,
			  d.user_id
			 FROM `vacancies` AS d
			LEFT JOIN  vacancy_images AS img  ON  d.vacancy_id=img.vacancy_id AND  img.sort_id=0
			INNER JOIN `vacancy_company_info` AS c
               USING(company_id)
		 
		 )UNION(
			SELECT 
			  '7'As section_id,
			  d.lab_id as resource_id,
			  IFNULL(img.url_full, 0) as image,
			  '/uploads/images/labs/142x195/' as path,
			  CONCAT(d.name ,',г.',d.city_name) as name,
			  d.contact_phones as phones,
			  '' AS price,
			  '' AS currency_id,
			   d.user_id
			 FROM `labs` AS d
			LEFT JOIN  labs_images AS img  ON  d.lab_id=img.lab_id AND  img.sort_id=0
		 
		 )UNION(
			SELECT 
			  '8'As section_id,
			  d.realty_id as resource_id,
			  IFNULL(img.url_full, 0) as image,
			  '/uploads/images/realty/142x195/' as path,
			   CONCAT( d.name,',г.', city_name) as name,
			  d.contact_phones as phones,
			  d.price AS price,
			  d.currency_id,
			  d.user_id
			 FROM `realty` AS d
			LEFT JOIN  realty_images AS img  ON  d.realty_id=img.realty_id AND  img.sort_id=0
		 
		 )

		) AS prod WHERE  section_id=$section_id AND resource_id = $resource_id";
		
		return DB::getAssocArray($query)[0];
	}
	
	
	public  static function redirect301( $bred ,$route){
		  if($route['action']=='noPay' or $route['action']=='calls') return ;
		
		 $arr=explode('/', trim($bred,'/')); 
		  
		  if($route['values']['page']){
			  
			  $bred='';
			 foreach ($arr as $k=>$v){
				 
				if($k===1) $bred .='/page-'.$route['values']['page']; 
				$bred .="/$v";
			 }
			
		  }
		
		//static::d( $_SERVER['REQUEST_URI'] );
		//static::d( $bred ,1);
		
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return;
		// static::d( $_SERVER['REQUEST_URI'] ,1);
		
		if( count( $arr) <2  or $arr[1]=='cabinet')return ;
		
	   if('/users'==$bred or '/main'==$bred ) return;
	  
		 if( $bred !==$_SERVER['REQUEST_URI']){
		   header("HTTP/1.1 301 Moved Permanently"); 
           header("Location:http://navistom.com$bred"); 
           exit();     
		  }      
		
		 
	}


	public static function add_jurnal_public( $resurs_id,$section_id)
	{
	  return  DB::insert('jurnal_public', array('section_id'=>$section_id,'resurs_id'=>$resurs_id));		   
	}
	
	
	
}



