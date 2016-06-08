<?php

class Admin {
	public static function displayFormTPL($form, $title = null, $meta_title = null, $prepend = null, $append = null) {
		return Registry::get('twig')->render('form.tpl', array(
			'title' => $meta_title,
			'form'	=> array(
				'title'		=> $title,
				'content'	=> $form
			)
		));
	}
	
	public static function getDefaultPermissionToForm() {
		$data = DB::DBObject()->prepare("SELECT * FROM `users_permissions_default`");
		$data->execute();
		
		$values = array();
		
		while ($array = $data->fetch(PDO::FETCH_OBJ)) {
			$values = array_merge($values, array(
				'flag_add['.$array->section_id.']' 			=> $array->flag_add,
				'count['.$array->section_id.']' 			=> $array->count,
				'flag_date_limit['.$array->section_id.']' 	=> $array->flag_date_limit,
				'time_limit['.$array->section_id.']' 		=> $array->time_limit,
				'life_time['.$array->section_id.']' 		=> $array->life_time
			));
		}
		
		return $values;
	}
	
	public function getArticlesModerCount() {
		$query = "SELECT COUNT(*) FROM `articles` WHERE flag = 0 AND date_public = '0000-00-00'";
		
		return DB::getColumn($query);
	}
	
	public function getUsersModerCount() {
		return DB::getTableCount('users', array(
			'flag_moder'	=> 0
		));
	}
	
	public function getActivityModerCount() {
		$query = "SELECT COUNT(*) FROM `activity` WHERE flag_moder = 0";
		
		return DB::getColumn($query);
	}
	
	public function getProductsNewModerCount() {
		$query = "SELECT COUNT(*) FROM `products_new` WHERE flag_moder = 0";
		
		return DB::getColumn($query);
	}
	
	public function getProducersModerCount() {
		$query = "SELECT COUNT(*) FROM `producers` WHERE flag_moder = 0";
		
		return DB::getColumn($query);
	}
	
	public function getModerationCount() {
		$query = "SELECT COUNT(*) AS 'articles',
			(SELECT COUNT(*) FROM `products_new` WHERE flag_moder = 0 AND flag_delete = 0) AS 'products_new',
			(SELECT COUNT(*) FROM `users_errors_mess` WHERE flag_view = 0) AS user_errors,
			(SELECT COUNT(*) FROM `users_feedback_mess` WHERE flag_view = 0) AS user_feedback_mess,
			(SELECT COUNT(*) FROM `user_access_requests`) AS user_access_mess,
			(SELECT COUNT(*) FROM `stocks` WHERE flag_moder = 0 AND flag_delete = 0) AS 'stocks',
			(SELECT COUNT(*) FROM `ads` WHERE flag_moder = 0 AND flag_delete = 0) AS 'ads',
			(SELECT COUNT(*) FROM `activity` WHERE flag_moder = 0 AND flag_delete = 0) AS 'activity',
			(SELECT COUNT(*) FROM `work` WHERE flag_moder = 0 AND flag_delete = 0) AS 'resume',
			(SELECT COUNT(*) FROM `vacancies` WHERE flag_moder = 0 AND flag_delete = 0) AS 'vacancies',
			(SELECT COUNT(*) FROM `labs` WHERE flag_moder = 0 AND flag_delete = 0) AS 'labs',
			(SELECT COUNT(*) FROM `realty` WHERE flag_moder = 0 AND flag_delete = 0) AS 'realty',
			(SELECT COUNT(*) FROM `services` WHERE flag_moder = 0 AND flag_delete = 0) AS 'services',
			(SELECT COUNT(*) FROM `diagnostic` WHERE flag_moder = 0 AND flag_delete = 0) AS 'diagnostic',
			(SELECT COUNT(*) FROM `demand` WHERE flag_moder = 0 AND flag_delete = 0) AS 'demand',
			(SELECT COUNT(*) FROM `producers` WHERE flag_moder = 0) AS 'producers',
			(SELECT COUNT(*) FROM `products` WHERE flag_moder = 0) AS 'products'
			FROM `articles`
			WHERE flag = 0 AND flag_delete = 0";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getEndedBannersCount() {
		$query = "SELECT COUNT(*) FROM `banners` WHERE DATE_SUB(date_end, INTERVAL 7 DAY) < NOW() AND date_end > NOW() AND flag = 1 AND flag_default = 0";
		
		return DB::getColumn($query);
	}
}

?>