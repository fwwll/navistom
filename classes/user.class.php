<?php
class User {
	/**
	 * Get user all info 
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getFullUserInfo($user_id) {
		$query = "SELECT user_id, email, group_id, flag, flag_moder, icq, skype, users_info.region_id, users_info.city_id, users_info.site,
				users_info.name, users_info.date_add, users_info.date_edit, users_info.country_id, 
				users_info.contact_phones, users_info.avatar, INET_NTOA(users_info.ip_address) AS ip_address,
				users_groups.name AS group_name,
				cities.name AS city_name,
				CONCAT('../uploads/users/avatars/full/', users_info.avatar) AS avatar_full
			FROM `users`
			INNER JOIN `users_info` USING(user_id)
			INNER JOIN `users_groups` USING(group_id)
			LEFT JOIN `cities` USING(city_id)
			WHERE user_id = $user_id";
		
		$data 	= DB::getAssocArray($query ,1);
		$geo	= Site::getGeoInfo($data['ip_address']);
		
		return array_merge($data, array(
			'country_name'		=> Registry::get('config')->countries_names[$data['country_id']],
			'geo_country_code'	=> $geo->country_code,
			'geo_city'			=> $geo->city,
			'geo_provider'		=> $geo->provider,
			'phones'			=> explode(',', $data['contact_phones'])
		));
	}
	
	public function setUserInfoToSession($user_id) {
		$user_info = "SELECT u.user_id, u.email, u.group_id, ui.contact_phones, ui.city_id,
			ui.country_id, ui.name, ui.avatar, ui.flag_default_permission
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
	}
	
	/**
	 * Get user info
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getUserInfo($user_id) {
		$query = "SELECT ui.user_id, ui.country_id, ui.name, ui.contact_phones, ui.avatar, INET_NTOA(ip_address) AS ip_address, ui.date_add, ui.date_edit, ui.last_visit, ui.city_id,
			u.email  
			FROM `users_info` AS ui
			INNER JOIN `users` AS u USING(user_id) 
			WHERE user_id = $user_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getUserCountryId($user_id) {
		return DB::getColumn("SELECT country_id FROM `users_info` WHERE user_id = $user_id");
	}
	
	public function getUserName($user_id) {
		$query = "SELECT name FROM `users_info` WHERE user_id = $user_id";
		
		return DB::getColumn($query);
	}
	
	/**
	 * Get user local data IP address (GEOIp)
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getUserGEOInfo($user_id) {
		$query = "SELECT INET_NTOA(ip_address) FROM `users_info` WHERE user_id = $user_id";
		$ip_address = DB::getColumn($query);
		
		return Site::getGeoInfo($ip_address);
	}
	
	/**
	 * Get user permission for site
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getUserPermissions($user_id, $section_id = 0) {
		
		$user_info = "SELECT u.group_id, ui.flag_default_permission
			FROM `users` AS u
			INNER JOIN `users_info` AS ui USING(user_id)
			WHERE u.user_id = $user_id";
		
		$user_info = DB::getAssocArray($user_info, 1);
		
		if ($user_info['flag_default_permission'] == 1) {
			$where = "group_id = " . $user_info['group_id'];
		}
		else {
			$where = "user_id = " . $user_id;
		}
		
		$query = "SELECT users_permissions.section_id, flag_view, flag_add, flag_limit, mod_type, name_sys AS name, sections.link,
			count, time_limit, time_life, flag_date_limit, date_start, date_end
			FROM `users_permissions` 
			INNER JOIN `sections` USING(section_id)
			WHERE $where AND sections.section_id != 1 AND sections.section_id != 17 " . ($section_id > 0 ? " AND section_id = $section_id" : "") .
			" ORDER BY sections.sort_id";
		
		return DB::getAssocArray($query, $section_id);
	}
	
	public function getUserUpdatesCount($user_id) {
        $date = DB::now(1);
        $group=  DB::getColumn("SELECT COUNT(*) FROM `users` WHERE  user_id = $user_id AND  group_id=10");
		return array(
			3 	=> DB::getColumn("SELECT COUNT(*) FROM `products_new` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > 14"),
			4 	=> DB::getColumn("SELECT COUNT(*) FROM `ads` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			5 	=> DB::getColumn("SELECT COUNT(*) FROM `activity` WHERE flag_moder = 1 AND flag_delete = 0 AND IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '$date', date_start > '$date'), 1) AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			6 	=> DB::getColumn("SELECT COUNT(*) FROM `work` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			7 	=> DB::getColumn("SELECT COUNT(*) FROM `labs` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			8 	=> DB::getColumn("SELECT COUNT(*) FROM `realty` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			9 	=> DB::getColumn("SELECT COUNT(*) FROM `services` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			10 	=> DB::getColumn("SELECT COUNT(*) FROM `diagnostic` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			11 	=> DB::getColumn("SELECT COUNT(*) FROM `demand` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)"),
			15 	=> DB::getColumn("SELECT COUNT(*) FROM `vacancies` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND DATEDIFF(NOW(), date_add) > IF($group ,  14,30)")
		);
	}
	
	public function getUserMaterialsCount($user_id) {
		$date = DB::now(1);
		
		return array(
			16 => DB::getTableCount('articles', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			2 => DB::getColumn("SELECT COUNT(*) FROM `stocks` WHERE product_new_id IN(SELECT product_new_id FROM `products_new` WHERE user_id = $user_id) AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date'"),
			
			3 => DB::getTableCount('products_new', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			4 => DB::getTableCount('ads', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			5 => DB::getTableCount('activity', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			6 => DB::getTableCount('work', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			7 => DB::getTableCount('labs', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			8 => DB::getTableCount('realty', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			9 => DB::getTableCount('services', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			10 => DB::getTableCount('diagnostic', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			11 => DB::getTableCount('demand', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
			15 => DB::getTableCount('vacancies', array(
				'user_id'		=> $user_id,
				'flag_delete'	=> 0
			)),
		);
	}

    public static function getUserMaterialsCountDetail($userId = null) {
        $date = DB::now(1);
        $where = '';

        if (isset($userId)) {
            $where .= 'AND user_id = '. (int) $userId;
        }

        $query = "SELECT * FROM (
            SELECT '16',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) AND IF(flag = 1, 1, 0)) AS moderation
            FROM articles
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '4',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) AND IF(flag = 1, 1, 0)) AS moderation
            FROM ads
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '5',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) AND IF(flag = 1, 1, 0) ) AS moderation
            FROM activity
            WHERE flag_delete = 0 {$where}
              AND ((date_start = '0000-00-00' AND date_end = '0000-00-00') OR (date_start != '0000-00-00' AND date_start > '{$date}') OR (date_start > '{$date}' AND date_end != '0000-00-00'))
          UNION ALL
            SELECT '3',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM products_new
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '2',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM stocks
            WHERE DATE_SUB(date_start, INTERVAL 1 DAY) < '{$date}' AND date_end > '{$date}'
            ". ($userId > 0 ? 'AND product_new_id IN(SELECT product_new_id FROM `products_new` WHERE user_id = '. $userId .')' : '') ."
          UNION ALL
            SELECT '6',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM work
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '7',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM labs
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '8',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM realty
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '9',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM services
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '10',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM diagnostic
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '11',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM demand
            WHERE flag_delete = 0 {$where}
          UNION ALL
            SELECT '15',
              count(*) AS count,
              SUM( IF(flag = 0, 1, 0) ) AS hide,
              SUM( IF(flag_moder = 0, 1, 0) ) AS moderation
            FROM vacancies
            WHERE flag_delete = 0 {$where}
        ) AS counts";

        try {
            return DB::fetch($query, PDO::FETCH_ASSOC | PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
        } catch(Exception $e) {
            Debug::log(Debug::getErrors());
            return array();
        }
    }
	
	public static function getUserMaterials($user_id) {
		$query = "";
	}
	
	/**
	 * Get user login data and status
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getUserLoginData($user_id) {
		$query = "SELECT user_id, email, passw, type, flag, flag_moder 
			FROM `users` WHERE user_id = $user_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	/**
	 * Get user statistic 
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getUserStatistic($user_id) {
		
	}
	
	/**
	 * Get all user messages
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getUserMessages($user_id) {
		
	}
	
	/**
	 * Save user permissions for site
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public static function setUserPermissions($user_id = 0, $group_id = 0, $sections = array(), $params = array()) {
		
	}
	
	/**
	 * Save user info
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public static function setUserInfo($user_id, $array) {
		$write = array(
			'user_id'			=> $user_id,
			'country_id'		=> $array['country_id'],
			'region_id'			=> $array['region_id'],
			'city_id'			=> $array['city_id'],
			'name'				=> $array['name'],
			'contact_phones'	=> $array['contact_phones'],
			'icq'				=> $array['icq'],
			'skype'				=> $array['skype'],
			'avatar'			=> $array['avatar'],
			'ip_address'		=> Str::get($array['ip_address'])->ip2mysql(),
			'date_add'			=> DB::now()
		);
		
		if (DB::insert('users_info', $write, 1)) {
			return true;
		}
		
		return false;
	}
	
	/*public static function editUserInfo($user_id, $data) {
		DB::update('users', array(
			'email'		=> $data['email']
		), array(
			'user_id'	=> $user_id
		));
		
		DB::update('users_info', array(
			'region_id'			=> $array['region_id'],
			'city_id'			=> $array['city_id'],
			'name'				=> $array['name'],
			'contact_phones'	=> $array['contact_phones'],
			'icq'				=> $array['icq'],
			'skype'				=> $array['skype'],
			'avatar'			=> $array['avatar']
		), array(
			'user_id'	=> $user_id
		));
	}*/
	
	/**
	 * Add login data new user
	 *
	 * @param string $email
	 * @param string $passw
	 * @param int $flag_moder
	 * @return int
	 */
	public static function addNewUser($email, $passw, $group_id = 2, $flag = 1, $flag_moder = 0) {
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
	
	/**
	 * Update primary user info
	 *
	 * @param int $user_id
	 * @param string $email
	 * @param int $group_id
	 * @param int $flag
	 * @param int $flag_moder
	 * @return bool
	 */
	public static function editUser($user_id, $email, $group_id, $flag = 1, $flag_moder = 0, $reset_permissions = 0) {
		$write = array(
			'email'			=> $email,
			'group_id'		=> $group_id,
			'flag'			=> $flag,
			'flag_moder'	=> $flag_moder,
            'reset_permissions' => $reset_permissions
		);
		
		if ( DB::update('users', $write, array('user_id' => $user_id)) )
			return true;
		else 
			return false;
	}
	
	public function editUserEmail($user_id, $email) {
		DB::update('users', array(
			'email'		=> $email
		), array(
			'user_id'	=> $user_id
		));
		
		return true;
	}
	
	public function editUserPassw($user_id, $passw) {
		DB::update('users', array(
			'passw'		=> md5(md5($passw))
		), array(
			'user_id'	=> $user_id
		));
	}
	
	public function getUserPassw($user_id) {
		return DB::getColumn("SELECT passw FROM `users` WHERE user_id = $user_id");
	}
	
	public function editUserInfo($user_id, $name, $region_id, $city_id, $icq, $skype, $contact_phones, $avatar, $site) {
		DB::update('users_info', array(
			'region_id'			=> $region_id,
			'city_id'			=> $city_id,
			'name'				=> $name,
			'icq'				=> $icq,
			'skype'				=> $skype,
			'site'				=> $site,
			'contact_phones'	=> $contact_phones,
			'avatar'			=> $avatar
		), array(
			'user_id'	=> $user_id
		));
		
		return true;
	}
	
	public static function isAdmin() {
		if (is_array(Request::getSession('_USER'))) {
			$user = Request::getSession('_USER');
			if ($user['info']['group_id'] == 4) {
				return $user['info']['user_id'];
			}
		}
		
		return false;
	}
	
	public static function authAdmin($email, $passw) {
		if ($email != null and $passw != null) {
			$passwMd5 = md5(md5($passw));
			
			$query 	= "SELECT user_id FROM `users` WHERE email = '$email' AND passw = '$passwMd5' AND flag = 1 AND group_id = 4";
			$user	= DB::getAssocArray($query, 1);
			
			if ($user['user_id'] > 0) {
				User::setUserInfoToSess($user['user_id']);
				
				$hash 	= Str::get()->generate(32, null);
				$ip		= Str::get(Site::getRealIP())->ip2mysql();
				
				$write	= array(
					'aut_ip_address'	=> $ip,
					'hash'				=> $hash
				);
				
				DB::update('users', $write, array('user_id' => $user['user_id']));
				
				Request::setCookie('_aut_key', $hash);
				
				return true;
			}
		}
		
		return false;
	}
	
	public static function isUser() {
		if(is_array(Request::getSession('_USER'))) {
			$user = Request::getSession('_USER');
			return $user['info']['user_id'];
		}
		
		return false;
	}
	
	public static function isUserAuth($user_id = 0) {
		$hash 	= Request::getCookie('_aut_key', 'string');
		$ip		= Str::get(Site::getRealIP())->ip2mysql();

        if ($user_id > 0) {
            if (DB::getColumn('SELECT reset_permissions FROM `users` WHERE user_id = ' . $user_id) > 0) {
                User::setUserInfoToSess($user_id);
                DB::update('users', array('reset_permissions' => 0), array('user_id' => $user_id));
            }
        }

		if ($hash != null and $user_id > 0) {
			return true;
		}
		elseif ($hash != null and $user_id == 0) {
			$user_id = "SELECT user_id FROM `users` WHERE hash = '$hash' AND aut_ip_address = $ip  AND flag = 1 AND flag_moder = 1 AND group_id NOT IN(1, 3)";
			$user_id = DB::getColumn($user_id);
			
			if ($user_id > 0) {
				User::setUserInfoToSess($user_id);
				
				return true;
			}
		}
		
		return false;
	}

    public function getUserMaterialCountBySection($userId, $sectionId) {
        $table = Site::getSectionsTable($sectionId);

        if ($userId > 0 and $table) {
            return DB::getTableCount($table, array(
                'user_id'		=> $userId,
                'flag_delete'	=> 0
            ));
        }
    }
	
	public function isUserAccess($section_id) {
		$user = Request::getSession('_USER');
        $permissions = $user['permissions'][$section_id][0];

        if(!$user) return false;

        // User is admin
        if ($user['info']['group_id'] == 4) return true;

        if ($permissions['flag_add'] == 0 or ($permissions['flag_limit'] > 0 and $permissions['count'] == 0)) return false;

        if ($permissions['flag_date_limit'] == 1) {
            if (strtotime($permissions['date_start']) > strtotime("now") or strtotime($permissions['date_end']) < strtotime("now")) {
                return false;
            }
        }

        if ($permissions['flag_limit'] == 1) {
            if (self::getUserMaterialCountBySection($user['info']['user_id'], $section_id) >= $permissions['count']) {
                return false;
            }
        }
		
		return true;
	}

    public function getUserAccessLimits($sectionId = 0, $key = 'add') {
        $user = Request::getSession('_USER');
        $permissionsLimits = array();

        if ($sectionId == 0) foreach($user['permissions'] as $key => $value) {
            $permissionsLimits[$key] = array(
                'add'       => (boolean) $value[0]['flag_add'],
                'count'     => !($value[0]['flag_limit'] > 0 && $value[0]['count'] == 0),
                'date'      => $value[0]['flag_date_limit'] == 1 && (strtotime($value[0]['date_start']) > strtotime("now") || strtotime($value[0]['date_end']) < strtotime("now")),
                'dateDiff'  => $value[0]['flag_date_limit'] == 1 ?  (string)Str::get($value[0]['date_end'] . ' 23:59:59')->dateDiff() : null,
                'countDiff' => $value[0]['flag_limit'] == 1 ? $value[0]['count'] - self::getUserMaterialCountBySection($user['info']['user_id'], $key) : null
            );
        }
        else {
            $permissions =& $user['permissions'][$sectionId][0];

            switch($key) {
                case 'add':
                    return (boolean) !$permissions['flag_add'];
                case 'count':
                    return (boolean) $permissions['flag_limit'] > 0;
                case 'date':
                    return ($permissions['flag_date_limit'] == 1 && (strtotime($permissions['date_start']) > strtotime("now") || strtotime($permissions['date_end']) < strtotime("now")));
                case 'dateDiff':
                    return $permissions['flag_date_limit'] == 1 ? (string)Str::get($permissions['date_end'] . ' 23:59:59')->dateDiff() : null;
                case 'countDiff':
                    return $permissions['flag_limit'] == 1 ? $permissions['count'] - self::getUserMaterialCountBySection($user['info']['user_id'], $sectionId) : null;
            }
        }

        return $permissionsLimits;
    }

    public function checkUserAccessRequest($userId) {
        return DB::select('date_add')->from('user_access_requests')->where(array('user_id' => $userId))->getColumn();
    }
	
	public function isPostModeration($section_id) {
		$user = Request::getSession('_USER');
		
		if ($user['permissions'][$section_id][0]['mod_type'] > 0 or $user['info']['group_id'] == 4) {
			return true;
		}
		
		return false;
	}

    public function isShowSection($sectionId) {
        $user = Request::getSession('_USER');

        if ($user['permissions'][$sectionId][0]['flag_view'] == 0) {
            return false;
        }

        return true;
    }
	
	public function getUserContacts() {
		$user = Request::getSession('_USER');
		
		return array(
			'name'				=> $user['info']['name'],
			'contact_phones'	=> $user['info']['contact_phones'],
			'email'				=> $user['info']['email']
		);
	}
	
	public function getUserCountry() {
		$user = Request::getSession('_USER');
		
		return $user['info']['country_id'];
	}
	
	public static function checkUserEmail($email) {
		$check = "SELECT COUNT(*) FROM `users` WHERE email = '$email'";
		$check = DB::getColumn($check);
		
		if ($check > 0) {
			return false;
		}
		else {
			return true;
		}
	}

    public function isHideUserAds($permissions) {
        $permissions = is_array($permissions[0]) ? $permissions[0] : $permissions;

        if ($permissions['flag_add'] == 1) {
            if ($permissions['flag_date_limit'] == 1) {
                return (strtotime($permissions['date_start']) <= strtotime("now") and strtotime($permissions['date_end']) >= strtotime("now"));
            }
            else return true;
        }
        else return false;
    }

    /**
     * Hide user ads where not permissions
     * @param $groupId
     */
    public function updateUserAdsPermissionsByGroup($groupId, $userId = 0) {
        if ($userId == 0) {
            $users = 'SELECT u.user_id
                FROM `users` AS u
                INNER JOIN `users_info` AS ui USING(user_id)
                WHERE group_id = ' . $groupId . ' AND flag_default_permission = 1';
            $users = DB::getAssocArray($users);

            $users = array_map(function($item) {
                return $item['user_id'];
            }, $users);

            $users = implode(',', $users);
        }

        $groupPermissions = 'SELECT section_id, flag_view, flag_add, flag_limit, mod_type,
			count, time_limit, time_life, flag_date_limit, date_start, date_end
			FROM `users_permissions`
			WHERE section_id = 3 AND group_id = ' . $groupId;
        $groupPermissions = DB::getAssocArray($groupPermissions);

        if ($groupPermissions[0]['flag_add'] == 1) {
            if ($groupPermissions[0]['flag_date_limit'] == 1) {
                $show = (strtotime($groupPermissions[0]['date_start']) <= strtotime("now") and strtotime($groupPermissions[0]['date_end']) >= strtotime("now")) ? 1 : 0;
            }
            else $show = 1;
        }
        else $show = 0;

        DB::query('UPDATE `products_new` SET flag_show = ' . $show . ' WHERE user_id IN('. ($userId > 0 ? $userId : $users)  .')');

        if ($userId == 0) {
            DB::query('UPDATE `users` SET reset_permissions = 1 WHERE user_id IN('. $users .')');
        }

        return true;
    }

    public function updateUserAdsPermissionsByUser($userId, $permissions) {
        $show = self::isHideUserAds($permissions[3]) ? 1 : 0;

        DB::update('products_new', array(
            'flag_show' => $show
        ), array(
            'user_id' => $userId
        ));
    }
	
	public static function setUserInfoToSess($user_id) {
		$user_info = "SELECT u.user_id, u.email, u.group_id, ui.contact_phones, ui.city_id,
			ui.country_id, ui.name, ui.avatar, ui.flag_default_permission
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
			'info'			=> $user_info,
			'permissions'	=> $user_permission
		));
		
		return true;
	}
	
	public function getUserVip($user_id, $section_id = 0) {
		if ($section_id > 0) {
			$where = " AND section_id = $section_id";
		}
		
		$query = "SELECT section_id, count, date_start, date_end 
			FROM `users_vip`
			WHERE user_id = $user_id $where
			AND date_start < DATE_SUB('" . DB::now(1) . "', INTERVAL -1 DAY) AND date_end > " . DB::now(1) . "";
		
		return DB::getAssocArray($query);
	}
	
	public function getUserMaterialsToSelect($section_id, $user_id, $q) {
		switch ($section_id) {
			case 3:
				return DB::getAssocArray("SELECT product_new_id AS id, product_name AS name 
					FROM `products_new` 
					WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id AND product_name LIKE '$q%'");
			break;
		}
	}
	
	public function addVipMaterial($section_id, $resource_id) {
		switch ($section_id) {
			case 3:
				DB::update('products_new', array(
					'flag_vip' => 1
				), array(
					'product_new_id' => $resource_id
				));
			break;
		}
	}
	
	public function deleteVipMaterial($user_id, $section_id) {
		switch ($section_id) {
			case 3:
				DB::update('products_new', array(
					'flag_vip' => 0
				), array(
					'user_id' => $user_id
				));
			break;
		}
	}
	
	public function getExchanges($country_id) {
		$query 	= "SELECT * FROM `user_exchange_rates`";
		$data 	= DB::getAssocArray($query);
		
		for ($i = 0, $c = count($data); $i < $c; $i++) {
			$result[$data[$i]['user_id']][$data[$i]['currency_id']] = $data[$i]['currency_rates'];
		}
		
		$query 	= "SELECT currency_id, currency_rates FROM `exchange_rates_default` WHERE country_id = $country_id";
		$data 	= DB::getAssocArray($query);
		
		for ($i = 0, $c = count($data); $i < $c; $i++) {
			$result[0][$data[$i]['currency_id']] = $data[$i]['currency_rates'];
		}
		
		return $result;
	}
	
	public function getExchangesUser($country_id, $user_id) {
		$query = "SELECT d.currency_id, c.name_min, 
			/* IFNULL(u.currency_rates, d.currency_rates) AS rate */
			IF(u.currency_rates, u.currency_rates ,d.currency_rates) AS rate
			FROM `exchange_rates_default` AS d
			INNER JOIN `currency` AS c ON c.currency_id = d.currency_id
			LEFT JOIN `user_exchange_rates` AS u ON u.user_id = $user_id AND u.currency_id = d.currency_id
			WHERE d.country_id = $country_id";
		
		return DB::getAssocGroup($query);
	}
	
	/**
	 * Delete user avatar
	 *
	 * @param string $avatar_name
	 * @return bool
	 */
	public static function deleteUserAvatar($avatar_name) {
		if ($avatar_name != null) {
			if (@unlink(UPLOADS . '/users/avatars/full/' . $avatar_name) and unlink(UPLOADS . '/users/avatars/tumb1/' . $avatar_name) and unlink(UPLOADS . '/users/avatars/tumb2/' . $avatar_name))
				return true;
			else 
				return false;
		}
	}
	
	public function saveSubscribeStatus($user_id, $status = 0, $news_status) {
		DB::update('users_info', array(
			'subscribe_status' 	=> $status,
			'news_status'		=> $news_status
		), array(
			'user_id' => $user_id
		));
	}
	
	public static function getSubscribeStatus($user_id) {
		return DB::getAssocArray("SELECT subscribe_status AS subscribe, news_status AS news FROM `users_info` WHERE user_id = $user_id", 1);
	}
	
	public static function getNewsStatus($user_id) {
		return DB::getColumn("SELECT news_status FROM users_info WHERE user_id = $user_id");
	}
	
	public static function getSubscribeUsers($limit = 10000) {
        $date = DB::now(1);

		return DB::getAssocArray("SELECT u.user_id, u.email, ui.name
			FROM users AS u
			INNER JOIN users_info AS ui USING(user_id)
			WHERE subscribe_status = 1 AND
			  u.user_id NOT IN(SELECT user_id FROM subscribe_logs)
			LIMIT $limit"
		);
	}

    public static function setUserSubscribeDate($userId) {
        return DB::query("UPDATE users_info SET subscribe_send_date = NOW() WHERE user_id = $userId");
    }
	
	/**
	 * Save user avatar to dir
	 *
	 * @param string $_file_name
	 * @return string
	 */
	public function addUserAvatar($_file_name, $avatar_name) {
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

    public static function isDeveloper() {
        if (self::isAdmin() and self::isAdmin() == Registry::get('config')->developerId) {
            return true;
        }

        return false;
    }

    public static function setDefaultSubscribeData( $userId ) {
        if (empty($userId)) {
            return false;
        }

        $categories = DB::select('*')->from('users_subscribe_categs')->where(array('user_id' => 0))->getAssoc();
        $cities = DB::select('*')->from('users_subscribe_cities')->where(array( 'user_id' => 0 ))->getAssoc();

        foreach ($categories as $category) {
            DB::insert('users_subscribe_categs', array(
                'user_id'       => $userId,
                'section_id'    => $category['section_id'],
                'categ_id'      => $category['categ_id'],
                'parent_id'     => $category['parent_id']
            ), 1);
        }

        foreach ($cities as $city) {
            DB::insert('users_subscribe_cities', array(
                'user_id'       => $userId,
                'section_id'    => $city['section_id'],
                'city_id'       => $city['city_id']
            ), 1);
        }

        return true;
    }
}
?>