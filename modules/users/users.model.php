<?php
class ModelUsers {
	
	public function authUser($email, $passw) {
		$passwMd5 = md5(md5($passw));
		
		$query 		= "SELECT user_id FROM `users` WHERE email = '$email' AND passw = '$passwMd5' AND flag = 1 AND flag_moder = 1 AND group_id NOT IN(1, 3)";
		$user_id	= DB::getColumn($query);
		
		if ($user_id > 0) {
			$user_info = "SELECT u.user_id, u.email, u.group_id, ui.contact_phones, ui.site,
				ui.country_id, ui.name, ui.avatar, ui.flag_default_permission, ui.city_id
				FROM `users` AS u
				INNER JOIN `users_info` AS ui USING(user_id)
				WHERE u.user_id = $user_id";
			
			$user_info = DB::getAssocArray($user_info, 1);
			
			if ($user_info['flag_default_permission'] == 1) {
				$where = "group_id = " . $user_info['group_id'];
			}
			else {
				$where = "user_id = " . $user_info['user_id'];
			}
			
			$user_permission = "SELECT section_id, flag_view, flag_add, flag_limit, mod_type, 
				count, time_limit, time_life, flag_date_limit, date_start, date_end
				FROM `users_permissions`
				WHERE $where";
			
			$user_permission = DB::DBObject()
				->query($user_permission)
				->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
			
			Request::setSession('_USER', array(
				'user_id'		=> $user_id,
				'info'			=> $user_info,
				'permissions'	=> $user_permission
			));
			
			$hash 	= Str::get()->generate(32, null);
			$ip		= Str::get(Site::getRealIP())->ip2mysql();
			
			$write	= array(
				'aut_ip_address'	=> $ip,
				'hash'				=> $hash
			);
			
			DB::update('users', $write, array('user_id' => $user_id));
			
			Request::setCookie('_aut_key', $hash);
			
			return true;
		}
		
		return false;
	}

    public function isNotConfirmed($email, $password) {
        $passwordMd5 = md5(md5($password));

        $query = '
            SELECT
                u.user_id
            FROM `users_confirms` AS c
            INNER JOIN `users` AS u
                USING(user_id)
            WHERE
                u.email = "'. $email .'" AND
                u.passw = "'. $passwordMd5 .'"';

        return DB::getColumn($query);
    }

    public function isNotConfirmedByUserId($userId) {
        return DB::select('user_key')->from('users_confirms')->where(array( 'user_id' => $userId ))->getColumn();
    }
	
	public function addUser($email, $passw, $group_id = 2, $flag = 0, $flag_moder = 0) {
		$write = array(
			'email'			=> $email,
			'passw'			=> $passw,
			'group_id'		=> $group_id,
			'flag'			=> $flag,
			'flag_moder'	=> $flag_moder
		);
		
		DB::insert('users', $write);
		
		return DB::lastInsertId();
	}
	
	public function addUserInfo($user_id, $country_id, $region_id, $city_id, $name, $icq, $skype, $contact_phones, $avatar, $site) {
		$write = array(
			'user_id'		=> $user_id,
			'country_id'	=> $country_id,
			'region_id'		=> $region_id,
			'city_id'		=> $city_id,
			'name'			=> $name,
			'icq'			=> $icq,
			'skype'			=> $skype,
			'site'			=> $site,
			'contact_phones'=> $contact_phones,
			'avatar'		=> $avatar,
			'ip_address'	=> Str::get(Site::getRealIP())->ip2mysql(),
			'date_add'		=> DB::now()
		);
		
		DB::insert('users_info', $write);
		
		return $user_id;
	}
	
	public function addConfirm($user_id) {
		$key = Str::get()->generate(20, null);

        $write = array(
            'user_id'	=> $user_id,
            'user_key'	=> $key,
            'date_last_send' => DB::now()
        );
		
		DB::insert('users_confirms', $write);
		
		return $key;
	}
	
	public function userConfirm($key) {
		$isConfirm = "SELECT user_id FROM `users_confirms` WHERE user_key = '$key'";
		$isConfirm = DB::getColumn($isConfirm);
		
		if ($isConfirm > 0) {
			DB::update('users', array('flag' => 1, 'flag_moder' => 1), array('user_id' => $isConfirm));
			DB::delete('users_confirms', array('user_id' => $isConfirm));
			
			return $isConfirm;
		}
		
		return false;
	}
	
	public function LoginUserConfirm($user_id) {
		if ($user_id > 0) {
			$user_info = "SELECT u.user_id, u.email, u.group_id, ui.contact_phones, ui.site,
				ui.country_id, ui.name, ui.avatar, ui.flag_default_permission, ui.city_id
				FROM `users` AS u
				INNER JOIN `users_info` AS ui USING(user_id)
				WHERE u.user_id = $user_id";
			
			$user_info = DB::getAssocArray($user_info, 1);
			
			if ($user_info['flag_default_permission'] == 1) {
				$where = "group_id = " . $user_info['group_id'];
			}
			else {
				$where = "user_id = " . $user_info['user_id'];
			}
			
			$user_permission = "SELECT section_id, flag_view, flag_add, flag_limit, mod_type, 
				count, time_limit, time_life, flag_date_limit, date_start, date_end
				FROM `users_permissions`
				WHERE $where";
			
			$user_permission = DB::DBObject()
				->query($user_permission)
				->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
			
			Request::setSession('_USER', array(
				'user_id'		=> $user_id,
				'info'			=> $user_info,
				'permissions'	=> $user_permission
			));
			
			$hash 	= Str::get()->generate(32, null);
			$ip		= Str::get(Site::getRealIP())->ip2mysql();
			
			$write	= array(
				'aut_ip_address'	=> $ip,
				'hash'				=> $hash
			);
			
			DB::update('users', $write, array('user_id' => $user_id));
			
			Request::setCookie('_aut_key', $hash);
			
			return true;
		}
		else {
			return false;
		}
	}
	
	public function passwRecovery() {
		
	}
	
	public function saveUserErrorMess($user_id, $user_email, $user_phone, $url, $message, $ip_addres, $browser_name, $browser_ver, $os_name) {
		DB::insert('users_errors_mess', array(
			'user_id'		=> $user_id,
			'user_email'	=> $user_email,
			'user_phone'	=> $user_phone,
			'url'			=> $url,
			'message'		=> $message,
			'ip_address'	=> Str::get($ip_addres)->ip2mysql(),
			'browser_name'	=> $browser_name,
			'browser_ver'	=> $browser_ver,
			'os_name'		=> $os_name,
			'date_add'		=> DB::now()
		));
		
		return DB::lastInsertId();
	}
	
	public function saveUserFeedbackMess($user_id, $user_name, $user_email, $user_phone, $message, $ip_addres, $browser_name, $browser_ver, $os_name) {
		DB::insert('users_feedback_mess', array(
			'user_id'		=> $user_id,
			'user_email'	=> $user_email,
			'user_phone'	=> $user_phone,
			'user_name'		=> $user_name,
			'message'		=> $message,
			'ip_address'	=> Str::get($ip_addres)->ip2mysql(),
			'browser_name'	=> $browser_name,
			'browser_ver'	=> $browser_ver,
			'os_name'		=> $os_name,
			'date_add'		=> DB::now()
		));
		
		return DB::lastInsertId();
	}

    public function saveUserAccessRequest($user_id, $link, $type = 0) {
        DB::insert('user_access_requests', array(
            'user_id'   => $user_id,
            'link'      => $link,
            'type'      => $type,
            'date_add'	=> DB::now()
        ));

        return DB::lastInsertId();
    }
	
	public function sendUserMail($email, $message) {
		include_once(LIBS.'phpmailer/class.phpmailer.php');
		
		$mailer = new PHPMailer(true);
					
		$mailer->From 		= 'Подтверждение регистрации на NaviStom';
		$mailer->FromName 	= 'navistom.com';
		
		$mailer->AddAddress($email);
		
		$mailer->Subject	= 'Подтверждение регистрации на NaviStom';
		
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
	
	public function isCaptcha() {
		if (Request::getSession('qaptcha_key') != null) {
			$key = (string) Request::getSession('qaptcha_key');

			if (isset($_POST[$key])) {
				return true;
			}
		}
		
		return false;
	}
	
	public function getRegionsFromSelect($country_id) {
		$regions = "SELECT region_id, name FROM `regions` WHERE country_id = $country_id ORDER BY sort_id, name";
		
		return DB::getAssocKey($regions);
	}
	
	public function getCitiesFromSelect($region_id, $regions = null) {
		if ($region_id == 0 and  is_array($regions)) {
			$cities = "SELECT city_id, name FROM `cities` WHERE region_id IN(" . implode(',', $regions) . ") ORDER BY sort_id, name";
		}
		else {
			$cities = "SELECT city_id, name FROM `cities` WHERE region_id = $region_id ORDER BY sort_id, name";
		}
		
		return DB::getAssocKey($cities);
	}
	
	public function addUserAvatar($_file_name, $avatar_name) {
			
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$images = Site::resizeImage($_FILES[$_file_name]['tmp_name'], $avatar_name, array(
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/users/avatars/full/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/users/avatars/tumb1/'
			),
			array(
				'w'		=> 70,
				'h'		=> 70,
				'crop'	=> -1,
				'path'	=> UPLOADS . '/users/avatars/tumb2/'
			),
			array(
				 'w'    => 195,
                 'h'    => 142,
				'path'	=> UPLOADS . '/users/avatars/full/'
			))
		);
		
		return $avatar_name . '.jpg';	
		
		die();
			
			include_once(LIBS.'upload/upload.class.php');
			
			$image = new upload($_FILES[$_file_name]);
			
			if ($image->uploaded) {
				$image->file_new_name_body 		= $avatar_name;
				$image->image_resize       		= true;
				$image->image_ratio_fill   		= true;
				$image->image_convert 			= 'jpg';
				$image->image_y            		= 160;
				$image->image_x            		= 200;
				$image->image_background_color 	= '#FFFFFF';
				
				$image->Process(UPLOADS . '/users/avatars/full/');
				
				if (!$image->processed) {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
				
				$image->file_new_name_body 		= $avatar_name;
				$image->image_resize        	= true;
				$image->image_ratio_fill    	= true;
				$image->image_convert 			= 'jpg';
				$image->image_y             	= 80;
				$image->image_x             	= 100;
				$image->image_background_color 	= '#FFFFFF';
				
				$image->Process(UPLOADS . '/users/avatars/tumb1/');
				
				if (!$image->processed) {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
				
				$image->file_new_name_body 		= $avatar_name;
				$image->image_resize        	= true;
				$image->image_ratio_crop    	= true;
				$image->image_convert 			= 'jpg';
				$image->image_y             	= 70;
				$image->image_x             	= 70;
				
				$image->Process(UPLOADS . '/users/avatars/tumb2/');
				
				if ($image->processed) {
					$image->Clean();
					
					$avatar_name = $avatar_name . '.jpg';
				}
				else {
					Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
					
					return false;
				}
			}
			else {
				return false;
			}
		
		return $avatar_name;
	}
	
	
	public function lightContent($section_id, $content_id, $date_start, $date_end) {
		
		
		$where= array(
			'section_id'	=> $section_id,
			'resource_id'	=> $content_id,
		);
		
		$insert=array(
			'section_id'	=> $section_id,
			'resource_id'	=> $content_id,
			'date_start'	=> $date_start,
			'date_end'		=> $date_end,
			'date_add'		=> DB::now()
		);
		
		$count=DB::getTableCount("light_content",$where);
		
	    if($count){	
			DB::update('light_content', $insert,$where);
		}else{
			DB::insert('light_content', $insert, 1);
	    } 
		return true;
	}
	
	public function getLightContentData($section_id, $resource_id) {
		$query = "SELECT * FROM `light_content` WHERE section_id = $section_id AND resource_id = $resource_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getTopData($table, $section_id, $resource_id) {
		$query = "SELECT * FROM `$table` WHERE section_id = $section_id AND resource_id = $resource_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	
	
	
	

	public static function statusPortmone($data){
	    $id_data =explode('~',$data);
	    $tab_colum = Registry::get('config')->cms_table_colum;
		$where= array(
			$tab_colum[trim($id_data[1])] => trim($id_data[0]),		
		);
		if(!empty($data))
		{ 
			
			
           			  

			$status = self::parser_status(array(
				'method' 	=> 'result',
				'payee_id' 	=> Registry::get('config')->portmone_id,
				'login'  	=> Registry::get('config')->portmone_login,
				'password'	=> Registry::get('config')->portmone_pass,
				'lang'		=>'ru',
				'server_output' => 'UTF-8',
				'shop_order_number' => trim($data)

			));
			  
			if('REJECTED'==trim($status->status))die('REJECTED');
			 $cms_table=Registry::get('config')->cms_table; 

			 
			$ob= DB::update($cms_table[$id_data[1]],  array('status' => 'success','service_payment'=>'portmone'),$where);
			
			

			$curr_time= time();
			$time_index=Registry::get('config')->time_index;
			$time_end =  $curr_time+ $time_index[trim($status->bill_amount)];
			$curr_time= date('Y-m-d',$curr_time);
			$time_end= date('Y-m-d',$time_end);
			
			
			DB::insert('top_to_main', array(
						'section_id'	=> $id_data[1],
						'resource_id'	=> $id_data[0],
						'date_start'	=> $curr_time,
						'date_end'		=> date('Y-m-d',$time_end),
						'sort_id'		=> '999',
						'date_add'		=> DB::now()
					), 1); 
		}
		Site::removeFlagVipAdd($id_data[1], $id_data[0]);
		
					
		Users::lightContent($id_data[1], $id_data[0], $curr_time , $time_end);
		
		DB::insert('top_to_section', array(
							'section_id'	=> $id_data[1],
							'resource_id'	=> $id_data[0],
							'date_start'	=> $curr_time,
							'date_end'		=> $time_end, 
							'sort_id'		=> 9999,
							'date_add'		=> DB::now()
						), 1);
		//Site::removeFlagVipAdd($id_data[1], $id_data[0]);
             
						
		file_put_contents('etap3.text',$id_data[0].'||'.$id_data[1]."||".$id_data[2] , FILE_APPEND);
		$url=DB::getAssocArray('SELECT link FROM `sections` WHERE  section_id='.$id_data[1],1);
	    return $url["link"];
	}
	
	
	public static function remove($user_id){
		
	
		///$user_id =User::isUser();
		//$user_id=5178;
		$query='SELECT content_id ,section_id ,type  from(
		   (
		    SELECT article_id as content_id ,16 AS section_id ,user_id ,"articles" as type FROM articles 
		   )UNION(
		    SELECT activity_id ,5 AS section_id , user_id , "activity" as type FROM  activity
		   )UNION(
		    SELECT ads_id  , 4 AS section_id , user_id , "ads" as type FROM  ads
		   )UNION(
			SELECT product_new_id ,  3 AS section_id , user_id , "products_new" as type FROM  products_new 
		   )UNION(
			SELECT service_id ,  9 AS section_id , user_id , "services" as type  FROM  services 
		   )UNION(
			SELECT demand_id , 11 AS section_id , user_id , "demand" as type FROM  demand
		   )UNION(
			SELECT lab_id	 ,  7 AS section_id , user_id , "labs" as type  FROM  labs
		   )UNION(
			SELECT realty_id ,  8 AS section_id , user_id ,"realty" as type   FROM  realty
		   )UNION(
			SELECT work_id ,  6 AS section_id , user_id  ,"work" as type  FROM  work 
		   )UNION(
			SELECT vacancy_id ,  15 AS section_id , user_id ,"vacancies" as type   FROM  vacancies 
		
		   )    
		)as use_data WHERE use_data.user_id='.$user_id;
		  
		$content_user= DB::getAssocArray($query);
	
       if( count($content_user)){
			foreach($content_user as $content ){
				static::removeCategImage($content['section_id'],$content['content_id']);	
              DB::delete( $content['type'] , array( Site::getSectionsTableIdName($content['section_id']) => $content['content_id']));
				
			}
	   }
		 static::removeAvatar($user_id);
		 DB::delete('users', array('user_id' => $user_id)); 
		
		
	}


public static function removeCategImage($section_id,$content_id ){
	  
	switch($section_id){
	case 16:ModelArticles::removeImages($content_id); 
          break;
	case 5: ModelActivity::deleteLectorImage($content_id);
          break;
	case 4: ModelAds::imagesRemove($content_id);
          break;
	case 3: ModelProducts::removeImages($content_id);
		  break;
	case 9: ModelServices::removeImages($content_id);
		  break;
	case 11: ModelDemand::imagesRemove($content_id);
		  break;
	case 7: ModelLabs::removeImages($content_id);
		  break;
	case 8: ModelRealty::removeImages($content_id); 
		  break;
	case 6:ModelWork::removeImages($content_id); 
		  break;		  			  
	case 15: ModelWork::removeImages($content_id);
		  break;		  
		  
   }	
	
	
}

public static function removeAvatar($user_id){
		$query="SELECT avatar FROM `users_info` WHERE user_id = $user_id";
		$images=DB::getAssocArray($query);
		$path = UPLOADS.'/images/articles';
		
		if(!count($images))return 1;	
		
		array_map(function($image){
			@unlink( UPLOADS .'/users/avatars/full/'.$image['url_full']);
			@unlink(UPLOADS . '/users/avatars/tumb1/'. $image['url_full']);
			@unlink(UPLOADS . '/users/avatars/tumb2/'. $image['url_full']);
			@unlink(UPLOADS . '/users/avatars/tumb2/'. $image['url_full']);
			@unlink(UPLOADS . '/users/avatars/tumb2/'. $image['url_full']);
		},$images);
	   DB::delete('users_info', array('user_id' => $user_id));
	}


	
}