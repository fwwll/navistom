<?php

class ModelUsers {
	public function getCountriesListFromSelect() {
		return Registry::get('config')->countries_names;
	}
	
	public function getRegionsFromSelect($country_id) {
		$regions = "SELECT region_id, name FROM `regions` WHERE country_id = $country_id ORDER BY sort_id, name";
		
		return DB::getAssocKey($regions);
	}
	
	public function getCitiesFromSelect($region_id) {
		$cities = "SELECT city_id, name FROM `cities` WHERE region_id = $region_id ORDER BY sort_id, name";
		
		return DB::getAssocKey($cities);
	}
	
	public function getUserGroupsFromSelect() {
		$groups = "SELECT group_id, name FROM `users_groups` ORDER BY sort_id";
		
		return DB::getAssocKey($groups);
	}
	
	public function addUser($email, $passw, $group_id, $flag, $flag_moder) {
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
	
	public function getSectionsList() {
		$query = "SELECT section_id, name_sys 
			FROM `sections`
			WHERE section_id NOT IN(1, 2)";
		
		return DB::getAssocKey($query);
	}
	
	public function getUserVip($user_id) {
		$query = "SELECT section_id, count, date_start, date_end 
			FROM `users_vip`
			WHERE user_id = $user_id";
		
		return DB::getAssocArray($query);
	}
	
	public function addUserVip($user_id, $section_id, $count, $date_start, $date_end) {
		if ($date_start == null and $date_end == null) {
			DB::delete('users_vip', array(
				'user_id'		=> $user_id,
				'section_id'	=> $section_id
			));
		}
		else {
			DB::insert('users_vip', array(
				'user_id'		=> $user_id,
				'section_id'	=> $section_id,
				'count'			=> $count,
				'date_start'	=> $date_start,
				'date_end'		=> $date_end,
				'date_add'		=> DB::now()
			), 1);
			
			return DB::lastInsertId();
		}
	}
	
	public function groupDelete($group_id) {
		DB::delete('users_groups', array(
			'group_id'	=> $group_id
		));
	}
	
	public function delete($user_id) {
		$image = "SELECT avatar FROM `users_info` WHERE user_id = $user_id";
		$image = DB::getColumn($image);
		
		if ($image != '') {
			User::deleteUserAvatar($image);
		}
		
		DB::delete('users', array(
			'user_id'	=> $user_id
		));
		
		return true;
	}

    static public function getUsersAccessWarnings() {
        $query = '
                SELECT
                  u.user_id,
                  u.email,
                  ui.name,
                  ui.date_add,
                  ui.date_edit,
                  ui.contact_phones
                FROM `users` AS `u`
                INNER JOIN `users_info` AS `ui`
                  USING(user_id)
                WHERE
                  flag_default_permission = 0';
        $users =& DB::getAssocArray($query);

        if ($users && is_array($users)) for ($i =0, $c = count($users); $i < $c; $i++) {
            $query = '
                    SELECT
                        up.section_id,
                        up.flag_view,
                        up.flag_add,
                        up.flag_limit,
                        up.count,
                        up.flag_date_limit,
                        up.date_start,
                        up.date_end,
                        s.name
                    FROM `users_permissions` AS up
                    INNER JOIN `sections` AS s USING(section_id)
                    WHERE user_id = ' . $users[$i]['user_id'];
            $permissions =& DB::getAssocGroup($query);

            foreach($permissions as $sectionId => $permission) {
                if ($permission[0]['flag_date_limit'] == 1 && (string) Str::get($permission[0]['date_end'] . ' 23:59:59')->dateDiff() <= 14) {
                    $users[$i]['warnings'][] = array(
                        'diff' => (string) Str::get($permission[0]['date_end'] . ' 23:59:59')->dateDiff(),
                        'name' => $permission[0]['name']
                    );
                }
            }

            if (!$users[$i]['warnings']) unset($users[$i]);
        }

        return count($users) > 0 ? $users : array();
    }
	
	
	public static  function  zavavkaCount(){
		$query="SELECT  count(*)as cou FROM  users WHERE user_dalete =1";
		$res=  DB::getAssocArray($query, 1);
		return $res['cou'];
	}
	
}