<?php

class Statistic {
	public static $ga = null;
	
	public function getSectionViews($section_id) {
		$query = "SELECT COUNT(*) FROM `sections_views` WHERE section_id = $section_id";
		
		return DB::getColumn($query);
	}
	
	public function getContentsCount($date = null, $period = null, $country_id = 0, $no_articles = 0) {
		if ($date != null) {
			$where = "AND DATE(date_add) = '$date'";
		}
		
		if ($period > 0) {
			$where = 'AND date_add >= NOW() - INTERVAL ' . $period .' DAY';
		}
		
		if ($country_id > 0) {
			$where_country = ' AND country_id = ' . $country_id;
		}
		
		$query = "SELECT COUNT(*) AS articles_count,
			(SELECT COUNT(*) FROM `products_new` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 $where $where_country) AS products_new_count,
			(SELECT COUNT(*) FROM `stocks` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 $where $where_country) AS stocks_count,
			(SELECT COUNT(*) FROM `ads` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 $where $where_country) AS ads_count,
			(SELECT COUNT(*) FROM `activity` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS activity_count,
			(SELECT COUNT(*) FROM `work` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS resume_count,
			(SELECT COUNT(*) FROM `vacancies` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS vacansies_count,
			(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS labs_count,
			(SELECT COUNT(*) FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS realty_count,
			(SELECT COUNT(*) FROM `services` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS services_count,
			(SELECT COUNT(*) FROM `diagnostic` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS diagnostic_count,
			(SELECT COUNT(*) FROM `demand` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where $where_country) AS demand_count
			FROM `articles`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where";
		
		$result = DB::getAssocArray($query, 1);
		
		if ($no_articles > 0) {
			$result['articles_count'] = 0;
		}
		
		return $result;
	}
	
	public function getSectionsStatistic($no_journal = 0) {
		
		$query = "SELECT section_id, name, views AS views_section FROM `cache_statistic_sections` WHERE date_cached = '" . DB::now(1) . "'";
		
		$result = DB::getAssocArray($query);
		
		if (count($result) == 0) {
			$query = "SELECT section_id, name_sys AS name,
				(SELECT COUNT(*) FROM `sections_views` WHERE section_id = s.section_id) AS views_section
				FROM `sections` AS s
				WHERE section_id != 2 " . ($no_journal > 0 ? 'AND section_id != 17' : '') . "
				ORDER BY s.sort_id";
			
			$result = DB::getAssocArray($query);
			
			DB::query("TRUNCATE TABLE `cache_statistic_sections`");
			
			for ($i = 0, $c = count($result); $i < $c; $i++) {
				DB::insert('cache_statistic_sections', array(
					'section_id'	=> $result[$i]['section_id'],
					'name'			=> $result[$i]['name'],
					'views'			=> $result[$i]['views_section'],
					'date_cached'	=> DB::now(1)
				));
			}
		}
		
		return $result;
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
	
	public function getSectionsViewsByPeriod($period) {
		$query = "SELECT COUNT(*) AS views, DATE(date_visit) AS date
			FROM `statistic_sessions`
			GROUP BY DATE(date_visit)  
			ORDER BY date DESC
			LIMIT 30";
		
		$array = DB::getAssocArray($query);
		
		krsort($array);
		
		return $array;
	}
	
	public function getTopArticles($count, $date_start = null) {
		
		$where = $date_start != null ? ' AND date_public BETWEEN "' . $date_start .'" AND "' . DB::now(1) . '"' : '';
		
		$query = "SELECT article_id, name, views, views / DATEDIFF(NOW(), date_public) AS q, DATEDIFF(NOW(), date_public) AS period, date_public
			FROM articles 
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where
			ORDER BY q DESC 
			LIMIT $count";
		
		$query = "SELECT articles.article_id, name, COUNT(v.user_id) AS views, COUNT(v.user_id) / DATEDIFF(NOW(), date_public) AS q, DATEDIFF(NOW(), date_public) AS period, date_public
				FROM articles
				INNER JOIN `articles_views` AS v ON v.article_id = articles.article_id
				WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where
				GROUP BY articles.article_id
				ORDER BY q DESC 
				LIMIT $count";
		
		return DB::getAssocArray($query);
	}
	
	public function getTopMaterials($date_start, $date_end, $country_id = 1) {
		$query = "
		(SELECT 
		    'activity' AS link,
		    c.activity_id AS id,
		    name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `activity` AS c
		    INNER JOIN `activity_views` AS v USING(activity_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.activity_id
		    ORDER BY q DESC
		) UNION (SELECT 
		    'ads' AS link,
		    c.ads_id AS id,
		    product_name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `ads` AS c
		    INNER JOIN `ads_views` AS v USING(ads_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.ads_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		) UNION (SELECT 
		    'product' AS link,
		    c.product_new_id AS id,
		    product_name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `products_new` AS c
		    INNER JOIN `products_new_views` AS v USING(product_new_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.product_new_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		) UNION (SELECT 
		    'service' AS link,
		    c.service_id AS id,
		    name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `services` AS c
		    INNER JOIN `services_views` AS v USING(service_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.service_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		) UNION (SELECT 
		    'demand' AS link,
		    c.demand_id AS id,
		    name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `demand` AS c
		    INNER JOIN `demand_views` AS v USING(demand_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.demand_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		) UNION (SELECT 
		    'lab' AS link,
		    c.lab_id AS id,
		    name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `labs` AS c
		    INNER JOIN `labs_views` AS v USING(lab_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.lab_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		) UNION (SELECT 
		    'lab' AS link,
		    c.lab_id AS id,
		    name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `labs` AS c
		    INNER JOIN `labs_views` AS v USING(lab_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.lab_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		) UNION (SELECT 
		    'realty' AS link,
		    c.realty_id AS id,
		    name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `realty` AS c
		    INNER JOIN `realty_views` AS v USING(realty_id) 
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.realty_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		) UNION (SELECT 
		    'diagnostic' AS link,
		    c.diagnostic_id AS id,
		    name AS name,
		    COUNT(v.user_id) AS views,
		    DATEDIFF(NOW(), date_add) AS period,
		    COUNT(v.user_id) / DATEDIFF(NOW(), date_add) AS q
		    FROM `diagnostic` AS c
		    INNER JOIN `diagnostic_views` AS v USING(diagnostic_id)
		    WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND DATE(date_add) BETWEEN '$date_start' AND '$date_end'
		    GROUP BY c.diagnostic_id
		    ORDER BY COUNT(v.user_id) / DATEDIFF(NOW(), date_add) DESC
		)
		ORDER BY q DESC
		LIMIT 5";
		
		return DB::getAssocArray($query);
	}
	
	public function getUsersCount() {
		$query = "SELECT COUNT(*) FROM `users` WHERE flag = 1 AND flag_moder = 1";
		
		return DB::getColumn($query);
	}
	
	public function getArticlesCount() {
		$query = "SELECT COUNT(*) FROM `articles` WHERE flag = 1 AND flag_moder = 1";
		
		return DB::getColumn($query);
	}
	
	public function googleAnalyticsConnect() {
		if (self::$ga) {
			return self::$ga;
		}
		else {
			include_once(CLASSES . 'gapi.class.php');
			$ga = new GoogleAnalyticsAPI('service');
			
			$ga->auth->setClientId(Registry::get('config')->ga_client_id);
			$ga->auth->setEmail(Registry::get('config')->ga_email);
			$ga->auth->setPrivateKey(Registry::get('config')->ga_private_key);
			
			$auth = $ga->auth->getAccessToken();

			
			if ($auth['http_code'] == 200) {
			    $accessToken = $auth['access_token'];
			    $tokenExpires = $auth['expires_in'];
			    $tokenCreated = time();
			} else {
			    // error...
			}
			
			$ga->setAccessToken($accessToken);
		    $ga->setAccountId('ga:81142471');
		    
		    self::$ga = $ga;
		    
		    return $ga;
		}
	}
	
	public function gaGetStatisticByDate($param, $date_start, $date_end) {
		$ga = Statistic::googleAnalyticsConnect();
		
	    $ga->setDefaultQueryParams(array(
	        'start-date' => $date_start,
	        'end-date'   => $date_end,
	    ));

	    $visits = $ga->query(array(
	        'metrics'    => 'ga:' . $param,
	        'dimensions' => 'ga:date',
	    ));
	    
	    return array(
	    	'total'	=> $visits['totalsForAllResults']['ga:' . $param],
	    	'data'	=> $visits['rows']
	    );
	}
	
	public static function createStatisticCache() {
		$fileName = CACHE . 'statistic/' . DB::now(1) . '.html';
		
		if (!is_file($fileName)) {
			$users = Statistic::gaGetStatisticByDate(
				'users',
				date('Y-m-d', strtotime('-1 month')),
				date('Y-m-d', strtotime('-1 day'))
			);
	
			$contentCount 	= Statistic::getContentsCount(null, null, Request::get('country'));
			$articlesCount	= $contentCount['articles_count'];
			
			$contentCount['articles_count'] = 0;
			
			for ($i = 0, $c = count($users['data']); $i < $c; $i++) {
				$users['data'][$i][0] = date('Y-m-d', strtotime($users['data'][$i][0]));
			}
			
			$tpl = Registry::get('twig')->render('statistic.tpl', array(
				'globalAccounts'	=> Statistic::getUsersCount(),
				'globalArticles'	=> $articlesCount,
				'globalAds'			=> array_sum($contentCount),
				'globalUsers'		=> $users['total'],
				'usersByWeek'		=> array_slice($users['data'], -7, 7, true),
				'sectionsViews'		=> Statistic::getSectionsStatistic(1),
				'topArticles'		=> Statistic::getTopArticles(5, date('Y-m-d', strtotime('-30 day'))),
				'topMaterials3'		=> Statistic::getTopMaterials(date('Y-m-d', strtotime('-3 day')), date('Y-m-d'), Request::get('country')),
				'topMaterials7'		=> Statistic::getTopMaterials(date('Y-m-d', strtotime('-7 day')), date('Y-m-d'), Request::get('country'))
			));
			
			@array_map('unlink', glob(CACHE . 'statistic/*'));
			
			file_put_contents($fileName, $tpl);
			
			return $tpl;
		}
	}
}