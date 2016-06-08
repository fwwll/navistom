<?php

class ModelActivity {
	public function getActivityList($flag = 1, $flag_moder = 1, $flag_delete = 0) {
		$activity = "SELECT a.activity_id, a.name, a.flag, a.date_add, u.name AS user_name, a.user_id, a.flag_moder
			FROM `activity` AS a
			INNER JOIN `users_info` AS u USING(user_id)
			WHERE a.flag = $flag AND flag_moder = $flag_moder AND a.flag_delete = $flag_delete";
		
		return DB::getAssocArray($activity);
	}
	
	public function getActivityData($activity_id) {
		$activity = "SELECT *,  
			(SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = activity.activity_id) AS categs
			FROM `activity` WHERE activity_id = $activity_id";
		
		$activity = DB::getAssocArray($activity, 1);
		
		$activity['categ_id[]']	= @explode(',', $activity['categs']);
		
		return $activity;
	}
	
	public function getActivityImages($activity_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/activity/80x100/', url_full) AS url_full FROM `activity_images` WHERE activity_id = $activity_id";
		
		return DB::getAssocKey($images);
	}
	
	public function editActivity($activity_id, $data, $categs, $images = null) {
		if (DB::update('activity', $data, array('activity_id' => $activity_id))) {
			
			DB::delete('activity_categs', array('activity_id' => $activity_id));
			
			for ($i = 0, $c = count($categs); $i < $c; $i++) {
				DB::insert('activity_categs', array(
					'activity_id'	=> $activity_id,
					'categ_id'		=> $categs[$i]
				));
			}
			
			if (is_array($images)) {
				$image_id = implode(',', $images);
				DB::query("UPDATE `activity_images` SET activity_id = $activity_id WHERE activity_id = 0 AND image_id IN($image_id)");
			}
			
			return true;
		}
		
		return false;
	}
	
	public function getCategoriesList() {
		$categs = "SELECT categ_id, name, date_add, date_edit FROM `categories_activity` ORDER BY sort_id, name";
		
		return DB::getAssocArray($categs);
	}
	
	public function getCategoriesFromSelect() {
		$categs = "SELECT categ_id, name FROM `categories_activity` ORDER BY sort_id, name";
		
		return DB::getAssocKey($categs);
	}
	
	public function getCategData($categ_id) {
		$categ = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_activity` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($categ, 1);
	}
	
	public function editCategory($categ_id,  $name, $title, $meta_title, $meta_description, $meta_keys) {
		$update = array(
			'name'				=> $name,
			'title'				=> $title,
			'meta_title'		=> $meta_title,
			'meta_description'	=> $meta_description,
			'meta_keys'			=> $meta_keys
		);
		
		DB::update('categories_activity', $update, array('categ_id' => $categ_id));
		
		return true;
	}
	
	public function addCategory($name, $title, $meta_title, $meta_description, $meta_keys) {
		$write = array(
			'name'				=> $name,
			'title'				=> $title,
			'meta_title'		=> $meta_title,
			'meta_description'	=> $meta_description,
			'meta_keys'			=> $meta_keys,
			'date_add'			=> DB::now()
		);
		
		DB::insert('categories_activity', $write);
		
		return DB::lastInsertId();
	}
	
	public function deleteCategory($categ_id) {
		DB::delete('categories_activity', array('categ_id' => $categ_id));
		
		return true;
	}
	
	public function getRegionsFromSelect($country_id) {
		$regions = "SELECT region_id, name FROM `regions` WHERE country_id = $country_id ORDER BY sort_id, name";
		
		return DB::getAssocKey($regions);
	}
	
	public function getCitiesFromSelect($region_id) {
		$cities = "SELECT city_id, name FROM `cities` WHERE region_id = $region_id ORDER BY sort_id, name";
		
		return DB::getAssocKey($cities);
	}
	
	public function sortedCategs($sort) {
		$query = "UPDATE `categories_activity` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['categ']); $i < $c; $i++) { 
			$query .= " WHEN categ_id = " . (int)$sort['categ'][$i] . " THEN $i ";
		}
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
	}
}