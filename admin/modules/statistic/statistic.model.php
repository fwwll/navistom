<?php

class ModelStatistic {
	
	public function getSessinsCount($date_start = null, $date_end  = null, $interval = null) {
		if ($date_start != null and $date_end == null) {
			$where = "DATE(date_visit) = '$date_start'";
		}
		elseif ($date_start != null and $date_end != null) {
			
		}
		elseif ($date_start == null and $date_end == null and $interval > 0) {
			$where = "date_visit >= DATE_SUB(NOW(), INTERVAL $interval DAY)";
		}
		else {
			$where = 1;
		}
		
		$query = "SELECT COUNT(*) 
			FROM `statistic_sessions`
			WHERE $where";
		
		return DB::getColumn($query);
	}
	
	public function getUsersCount() {
		$query = "SELECT COUNT(*) FROM `users` WHERE flag = 1 AND flag_moder = 1";
		
		return DB::getColumn($query);
	}
	
	public function getSectionsStatistic() {
		$query = "SELECT name_sys AS name,
			(SELECT COUNT(*) FROM `sections_views` WHERE section_id = s.section_id) AS views_section
			FROM `sections` AS s
			WHERE section_id != 2 
			ORDER BY s.sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getRegistrationCount($date_start = null, $date_end  = null, $interval = null) {
		
		if ($date_start != null and $date_end == null) {
			$where = "DATE(date_add) = '$date_start'";
		}
		elseif ($date_start != null and $date_end != null) {
			
		}
		elseif ($date_start == null and $date_end == null and $interval > 0) {
			$where = "date_add >= DATE_SUB(NOW(), INTERVAL $interval DAY)";
		}
		else {
			$where = 1;
		}
		
		$query = "SELECT COUNT(*) FROM `users_info` WHERE $where";
		
		return DB::getColumn($query);
	}
	
	public function getContentsCount($date_start = null, $date_end  = null, $interval = null) {
		if ($date_start != null and $date_end == null) {
			$where = "AND DATE(date_add) = '$date_start'";
		}
		elseif ($date_start != null and $date_end != null) {
			
		}
		elseif ($date_start == null and $date_end == null and $interval > 0) {
			$where = "AND date_add >= DATE_SUB(NOW(), INTERVAL $interval DAY)";
		}
		
		$query = "SELECT COUNT(*) AS articles_count,
			(SELECT COUNT(*) FROM `products_new` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS products_new_count,
			(SELECT COUNT(*) FROM `stocks` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS stocks_count,
			(SELECT COUNT(*) FROM `ads` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS ads_count,
			(SELECT COUNT(*) FROM `activity` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS activity_count,
			(SELECT COUNT(*) FROM `work` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS resume_count,
			(SELECT COUNT(*) FROM `vacancies` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS vacansies_count,
			(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS labs_count,
			(SELECT COUNT(*) FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS realty_count,
			(SELECT COUNT(*) FROM `services` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS services_count,
			(SELECT COUNT(*) FROM `diagnostic` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS diagnostic_count,
			(SELECT COUNT(*) FROM `demand` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where) AS demand_count
			FROM `articles`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getSectionsContentViews() {
		$query = "SELECT 
			COUNT(*) AS articles_views,
			(SELECT COUNT(*) FROM `products_new_views`) AS products_new_views,
			(SELECT COUNT(*) FROM `ads_views`) AS ads_views,
			(SELECT COUNT(*) FROM `activity_views`) AS activity_views,
			(SELECT COUNT(*) FROM `work_views`) AS resume_views,
			(SELECT COUNT(*) FROM `vacancy_views`) AS vacancy_views,
			(SELECT COUNT(*) FROM `labs_views`) AS labs_views,
			(SELECT COUNT(*) FROM `realty_views`) AS realty_views,
			(SELECT COUNT(*) FROM `services_views`) AS services_views,
			(SELECT COUNT(*) FROM `diagnostic_views`) AS diagnostic_views,
			(SELECT COUNT(*) FROM `demand_views`) AS demand_views
			FROM `articles_views`";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getSectionsViewsByMonth() {
		$query = "SELECT COUNT(DISTINCT ip_address, browser_name, browser_version, os_name) AS views,
			DATE(s.date_visit) AS date
			FROM `statistic_sessions` AS s
			WHERE os_name != 'unknown' AND browser_name != 'Apache-HttpClient'
			GROUP BY DATE(date_visit)
			ORDER BY date DESC
			LIMIT 30";
					
		$array = DB::getAssocArray($query);
		
		krsort($array);
		
		return $array;
	}
	
	public function getUsersBrowsers() {
		$query = "SELECT COUNT(*) AS count, browser_name
			FROM `statistic_sessions` 
			GROUP BY browser_name
			ORDER BY count DESC
			LIMIT 15";
		
		$data 	= DB::getAssocArray($query);
		
		return $data;
	}
	
	public function getUsersPlatform() {
		$query = "SELECT COUNT(*) AS count, os_name
			FROM `statistic_sessions` 
			GROUP BY os_name
			ORDER BY count DESC
			LIMIT 15";
		
		$data 	= DB::getAssocArray($query);
		
		return $data;
	}
	
	public function getSubscribeUsersCount() {
		$query = "SELECT COUNT(DISTINCT user_id) FROM `users_subscribe_categs`";
		
		return DB::getColumn($query);
	}
	
	public function getSubscribeActiveUsersCount() {
		$query = "SELECT COUNT(*) FROM `users_info` WHERE subscribe_status = 1";
		
		return DB::getColumn($query);
	}
	
	public function getSubscribeUsersCountBySections() {
		$query = "SELECT subscribe.section_id, 
			sections.name,
			COUNT(DISTINCT subscribe.user_id) AS users_count
			FROM `users_subscribe_categs` AS subscribe
			INNER JOIN `sections` USING(section_id)
			GROUP BY section_id
			ORDER BY sections.sort_id";
		
		return DB::getAssocArray($query);
	}
}