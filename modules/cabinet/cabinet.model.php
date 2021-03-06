<?php

class ModelCabinet {
	public function getUserDialogs($user_id) {
		$query = "SELECT m.*, s.name_sys AS section_name, 
			u.name, u.avatar
			FROM `users_messages` AS m
			INNER JOIN users_info AS u ON u.user_id = m.from_id
			INNER JOIN `sections` AS s USING(section_id)
			WHERE m.to_id = $user_id
			GROUP BY m.from_id, m.section_id, m.resource_id, m.status
			ORDER BY m.status, m.date_add DESC";
		
		$dialogs = DB::DBObject()->query($query);
		$dialogs->execute();
	
		while ($array = $dialogs->fetch(PDO::FETCH_ASSOC)) {
			switch ($array['section_id']) {
				case 3:
					$array['info'] = ModelCabinet::getProductNewMessInfo($array['resource_id']);
				break;
				case 4:
					$array['info'] = ModelCabinet::getAdsMessInfo($array['resource_id']);
				break;
				case 5:
					$array['info'] = ModelCabinet::getActivityMessInfo($array['resource_id']);
				break;
				case 6:
					$array['info'] = ModelCabinet::getResumeMessInfo($array['resource_id']);
				break;
				case 15:
					$array['info'] = ModelCabinet::getVacancyMessInfo($array['resource_id']);
				break;
				case 7:
					$array['info'] = ModelCabinet::getLabMessInfo($array['resource_id']);
				break;
				case 8:
					$array['info'] = ModelCabinet::getRealtyMessInfo($array['resource_id']);
				break;
				case 9:
					$array['info'] = ModelCabinet::getServiceMessInfo($array['resource_id']);
				break;
				case 10:
					$array['info'] = ModelCabinet::getDiagnosticMessInfo($array['resource_id']);
				break;
				case 11:
					$array['info'] = ModelCabinet::getDemandMessInfo($array['resource_id']);
				break;
			}
			
			$result[] = $array;
		}
		
		return $result;
	}
	
	public function getDialogFull($from_id, $resource_id, $section_id) {
		$query = "SELECT m.*, u.avatar, u.name
			FROM `users_messages` AS m
			INNER JOIN `users_info` AS u ON u.user_id = m.from_id
			WHERE (m.from_id = $from_id OR m.to_id = $from_id) AND m.resource_id = $resource_id
			ORDER BY date_add";
		
		switch ($section_id) {
			case 3:
				$info = ModelCabinet::getProductNewMessInfo($resource_id);
			break;
			case 4:
				$info = ModelCabinet::getAdsMessInfo($resource_id);
			break;
			case 5:
				$info = ModelCabinet::getActivityMessInfo($resource_id);
			break;
			case 6:
				$info = ModelCabinet::getResumeMessInfo($resource_id);
			break;
			case 15:
				$info = ModelCabinet::getVacancyMessInfo($resource_id);
			break;
			case 7:
				$info = ModelCabinet::getLabMessInfo($resource_id);
			break;
			case 8:
				$info = ModelCabinet::getRealtyMessInfo($resource_id);
			break;
			case 9:
				$info = ModelCabinet::getServiceMessInfo($resource_id);
			break;
			case 10:
				$info = ModelCabinet::getDiagnosticMessInfo($resource_id);
			break;
			case 11:
				$info = ModelCabinet::getDemandMessInfo($resource_id);
			break;
		}
		
		return array(
			'dialog'	=> DB::getAssocArray($query),
			'info'		=> $info
		);
	}
	
	public function getMessCount($user_id) {
		$query = "SELECT COUNT(*) 
			FROM `users_messages` 
			WHERE to_id = $user_id AND status = 0";
		
		return DB::getColumn($query);
	}
	
	public function addMessage($from_id, $to_id, $resource_id, $section_id, $message) {
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
	
	public function getMessage($message_id) {
		$query = "SELECT m.message, m.date_add, u.avatar, u.name
			FROM `users_messages` AS m
			INNER JOIN `users_info` AS u ON u.user_id = m.from_id
			WHERE m.message_id = $message_id
			ORDER BY date_add";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function setSattusDialog($from_id, $to_id, $resource_id, $section_id) {
		DB::update('users_messages', array(
			'status'	=> 1,
			'date_view'	=> DB::now()
		), array(
			'from_id'		=> $from_id,
			'to_id'			=> $to_id,
			'resource_id'	=> $resource_id,
			'section_id'	=> $section_id
		));
		
		return true;
	}
	
	public function getProductNewMessInfo($product_new_id) {
		$query = "SELECT product_name AS name, pr.description AS description,
			CONCAT('products/80x100/', IF(i.url_full != '', i.url_full, pr.image)) AS image
			FROM `products_new` 
			INNER JOIN `products` AS pr USING(product_id)
			LEFT JOIN `products_new_images` AS i  ON i.product_new_id = products_new.product_new_id AND i.sort_id = 0
			WHERE products_new.product_new_id = $product_new_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getAdsMessInfo($ads_id) {
		$query = "SELECT product_name AS name, pr.description AS description,
			CONCAT('ads/80x100/', IF(i.url_full != '', i.url_full, pr.image)) AS image
			FROM `ads`
			INNER JOIN `products` AS pr USING(product_id)
			LEFT JOIN `ads_images` AS i  ON i.ads_id = ads.ads_id AND i.sort_id = 0
			WHERE ads.ads_id = $ads_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getActivityMessInfo($activity_id) {
		$query = "SELECT name, 
			CONCAT('activity/80x100/', image) AS image
			FROM `activity`
			WHERE activity_id = $activity_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getResumeMessInfo($work_id) {
		$query = "SELECT (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = work.work_id)) AS name,
			CONCAT('work/80x100/', i.url_full) AS image
			FROM `work`
			LEFT JOIN `work_images` AS i ON i.work_id = work.work_id AND i.sort_id = 0
			WHERE work.work_id = $work_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getVacancyMessInfo($vacancy_id) {
		$query = "SELECT (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)) AS name,
			CONCAT('work/80x100/', c.logotype) AS image
			FROM `vacancies` AS v
			INNER JOIN `vacancy_company_info` AS c USING(company_id)
			WHERE v.vacancy_id = $vacancy_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getLabMessInfo($lab_id) {
		$query = "SELECT l.user_name AS name,
			CONCAT('labs/80x100/', i.url_full) AS image
			FROM `labs` AS l
			LEFT JOIN `labs_images` AS i ON i.lab_id = l.lab_id AND i.sort_id = 0
			WHERE l.lab_id = $lab_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getRealtyMessInfo($realty_id) {
		$query = "SELECT name, 
			CONCAT('realty/80x100/', i.url_full) AS images
			FROM `realty` AS r
			LEFT JOIN `realty_images` AS i ON i.realty_id = r.realty_id AND i.sort_id = 0
			WHERE r.realty_id = $realty_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getServiceMessInfo($service_id) {
		$query = "SELECT s.name, 
			CONCAT('services/80x100/', i.url_full) AS images
			FROM `services` AS s
			LEFT JOIN `services_images` AS i ON i.service_id = s.service_id AND i.sort_id = 0
			WHERE s.service_id = $service_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getDiagnosticMessInfo($diagnostic_id) {
		$query = "SELECT d.name, 
			CONCAT('diagnostic/80x100/', i.url_full) AS images
			FROM `diagnostic` AS d
			LEFT JOIN `diagnostic_images` AS i ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
			WHERE d.diagnostic_id = $diagnostic_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getDemandMessInfo($demand_id) {
		$query = "SELECT d.name, 
			CONCAT('demand/80x100/', i.url_full) AS images
			FROM `demand` AS d
			LEFT JOIN `demand_images` AS i ON i.demand_id = d.demand_id AND i.sort_id = 0
			WHERE d.demand_id = $demand_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getCurrensyList($country_id) {
		$query = "SELECT currency_id, name FROM `currency` WHERE country_id = $country_id AND is_default = 0";
		
		return DB::getAssocArray($query);
	}
	
	public function getCurrensyDefault($country_id) {
		$query = "SELECT currency_id, name_min FROM `currency` WHERE country_id = $country_id AND is_default = 1";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getExchanges($user_id, $country_id) {
		$query = "SELECT d.currency_id,
			IFNULL(u.currency_rates, d.currency_rates) AS rate
			FROM `exchange_rates_default` AS d
			LEFT JOIN `user_exchange_rates` AS u ON u.user_id = $user_id AND u.currency_id = d.currency_id
			WHERE d.country_id = $country_id";
		
		return DB::getAssocKey($query);
	}

    public function isUserExchanges($userId) {
        return DB::getTableCount('user_exchange_rates', array('user_id' => $userId));
    }
	
	public function saveUserExchange($user_id, $rates, $default = false) {
		if (!$default and is_array($rates)) {
            $deleted = 0;
			foreach ($rates as $key => $val) {
				if ($val > 0) {
					DB::insert('user_exchange_rates', array(
						'user_id'			=> $user_id,
						'currency_id'		=> $key,
						'currency_rates'	=> $val
					), 1);
				}
                else {
                    $deleted++;
                    DB::delete('user_exchange_rates', array(
                        'user_id'       => $user_id,
                        'currency_id'   => $key
                    ));
                }
			}

            if ($deleted == count($rates)) {
                return 2;
            }
			
			return 1;
		}
		else {
            if ($default) {
                DB::delete('user_exchange_rates', array(
                    'user_id' => $user_id
                ));

                return 2;
            }

			return false;
		}
	}
	
	public function saveUserSubscribeCategs($user_id, $section_id, $categs, $parent_id = 0) {
		if ($user_id > 0 and $section_id > 0 and is_array($categs)) {
			for ($i = 0, $c = count($categs); $i < $c; $i++) {
				DB::insert('users_subscribe_categs', array(
					'user_id'		=> $user_id,
					'section_id' 	=> $section_id,
					'categ_id'		=> $categs[$i],
					'parent_id'		=> $parent_id
				), 1);
			}
			
			return true;
		}
		
		return false;
	}
	
	public function saveUserSubscribeCities($user_id, $section_id, $cities) {
		if ($user_id > 0 and $section_id > 0 and is_array($cities)) {
			for ($i = 0, $c = count($cities); $i < $c; $i++) {
				DB::insert('users_subscribe_cities', array(
					'user_id'		=> $user_id,
					'section_id' 	=> $section_id,
					'city_id'		=> $cities[$i]
				), 1);
			}
			
			return true;
		}
		
		return false;
	}
	
	public function deleteSubscribeData($user_id) {
		DB::delete('users_subscribe_categs', array(
			'user_id'		=> $user_id
		));
		
		DB::delete('users_subscribe_cities', array(
			'user_id'		=> $user_id
		));
	}
	
	public function deleteSubscribeCategs($user_id, $section_id, $categ_id = 0) {
		if (is_array($categ_id)) {
			DB::query("DELETE FROM `users_subscribe_categs` WHERE user_id = $user_id AND section_id = $section_id AND categ_id IN(" . implode(',', $categ_id) . ")");
		}
		else {
			DB::delete('users_subscribe_categs', 
				$categ_id > 0 ?  array(
					'user_id'		=> $user_id,
					'section_id'	=> $section_id,
					'categ_id'		=> $categ_id
				) : array(
					'user_id'		=> $user_id,
					'section_id'	=> $section_id
				)
			);
		}
	}
	
	public static function getUserSubscribeCategs($user_id, $parent_id = 0, $section_id = 0) {
		if ($user_id > 0) {
			if ($section_id > 0) {
				$categs = DB::getAssocArray("SELECT categ_id FROM `users_subscribe_categs` WHERE user_id = $user_id ". ($parent_id != -1 ? 'AND parent_id = ' . $parent_id : '') ." AND section_id = $section_id");
				
				for ($i = 0, $c = count($categs); $i < $c; $i++) {
					$result[] = $categs[$i]['categ_id'];
				}
			}
			else {
				$categs = DB::getAssocArray("SELECT section_id, categ_id FROM `users_subscribe_categs` WHERE user_id = $user_id AND parent_id = $parent_id");
			
				for ($i = 0, $c = count($categs); $i < $c; $i++) {
					$result[$categs[$i]['section_id']][] = $categs[$i]['categ_id'];
				}
			}

            return (isset($result) ? $result : array());
		}
	}
	
	public function deleteSubscribeCities($user_id, $section_id, $city_id = 0) {
		DB::delete('users_subscribe_cities', 
			$city_id > 0 ?  array(
				'user_id'		=> $user_id,
				'section_id'	=> $section_id,
				'city_id'		=> $city_id
			) : array(
				'user_id'		=> $user_id,
				'section_id'	=> $section_id
			)
		);
	}
	
	public static function getUserSubscribeCities($user_id, $section_id = 0) {
		if ($user_id > 0) {
			$cities = DB::getAssocArray("SELECT section_id, city_id FROM `users_subscribe_cities` WHERE user_id = $user_id " . ($section_id > 0 ? " AND section_id = $section_id" : ""));
			
			for ($i = 0, $c = count($cities); $i < $c; $i++) {
				$result[$cities[$i]['section_id']][] = $cities[$i]['city_id'];
			}
			
			return (isset($result) ? $result : array());
		}
	}
	
	public static function getSubscribeItems($storage) {
        if (count($storage) <= 0) return false;

        $date = DB::now(1);

		$query = "SELECT SQL_CACHE a.* FROM (
            (SELECT
				5 AS section_id,
				activity.activity_id AS content_id,
				'activity' AS type,
				activity.user_name,
				activity.name,
				IFNULL(CONCAT('activity/lectors/', l.image), CONCAT('activity/80x100/', activity.image)) AS image,
				date_start AS description,
				date_end AS price,
				city_name AS currency_name,
				activity.date_add,
				(SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = activity.activity_id) AS categs,
				activity.city_id
			FROM `activity`
			LEFT JOIN `activity_lectors` AS l ON l.activity_id = activity.activity_id AND l.sort_id = 0
			WHERE ". ($storage->has(5) ? ('activity.activity_id IN('. $storage->join(5) .') ') : 0) ." AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1 AND
			IF(date_start = '000-00-00', 1, 
				IF(date_end != '000-00-00', date_end > '{$date}', date_start > '{$date}')
			)
		) UNION (
			SELECT
				16 AS section_id,
				articles.article_id AS content_id,
				'article' As type,
				'' AS user_name,
				name,
				IFNULL(url_full, 'none.jpg') AS image,
				content_min AS description,
				'' AS price,
				'' AS currency_name,
				date_public AS date_add,
				(SELECT GROUP_CONCAT(categ_id) FROM `articles_categs` WHERE article_id = articles.article_id) AS categs,
				'' AS city_id
			FROM `articles`
			LEFT JOIN `articles_images` USING(image_id)
			WHERE ". ($storage->has(16) ? ('articles.article_id IN('. $storage->join(16) .') ') : 0) ." AND flag = 1 AND flag_moder = 1 AND flag_delete = 0
		) UNION (
			SELECT 
				4 AS section_id,
				ads.ads_id AS content_id,
				'ads' As type,
				user_name,
				CONCAT(product_name, ', Б/У') AS name,
				IFNULL(CONCAT('offers/80x100/', i.url_full), CONCAT('products/80x100/', products.image)) AS image,
				products.description,
				ads.price,
				ads.currency_name,
				ads.date_add,
				sub_categ_id AS categs,
				'' AS city_id
			FROM `ads`
			INNER JOIN `products` USING(product_id)
			LEFT JOIN `ads_images` AS i  ON i.ads_id = ads.ads_id AND i.sort_id = 0
			WHERE ". ($storage->has(4) ? ('ads.ads_id IN('. $storage->join(4) .') ') : 0) ." AND
			    ads.flag = 1 AND ads.flag_moder = 1 AND ads.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1 AND ads.flag_show = 1
		) UNION (
			SELECT 
				3 AS section_id,
				p.product_new_id AS content_id,
				'product' As type,
				user_name,
				product_name AS name,
				CONCAT('products/80x100/', IF(i.url_full,i.url_full, products.image)) AS image,
				products.description,
				p.price AS price,
				p.currency_name,
				p.date_add AS date_add,
				sub_categ_id AS categs,
				'' AS city_id
			FROM `products_new` AS p
			INNER JOIN `products` USING(product_id)
			LEFT JOIN `products_new_images` AS i  ON i.product_new_id = p.product_new_id AND i.sort_id = 0
			LEFT JOIN `stocks` AS s ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '$date' AND s.date_end > '$date'
			WHERE ". ($storage->has(3) ? ('p.product_new_id IN('. $storage->join(3) .') ') : 0) ." AND
			    p.flag = 1 AND p.flag_moder = 1 AND p.flag_delete = 0  AND is_update = 0 AND flag_moder_view = 1 AND p.flag_show = 1
		) UNION (
			SELECT 
				2 AS section_id,
				p.product_new_id AS content_id,
				'product' As type,
				user_name,
				product_name AS name,
				CONCAT('products/80x100/', IFNULL(i.url_full, products.image)) AS image,
				products.description,
				s.price AS price,
				p.currency_name,
				s.date_add AS date_add,
				sub_categ_id AS categs,
				'' AS city_id
			FROM `products_new` AS p
			INNER JOIN `products` USING(product_id)
			LEFT JOIN `products_new_images` AS i  ON i.product_new_id = p.product_new_id AND i.sort_id = 0
			INNER JOIN `stocks` AS s ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '$date' AND s.date_end > '$date'
			WHERE ". ($storage->has(2) ? ('p.product_new_id IN('. $storage->join(2) .') ') : 0) ." AND
			    p.flag = 1 AND p.flag_moder = 1 AND p.flag_delete = 0 AND s.flag = 1 AND is_update = 0 AND flag_moder_view = 1
		) UNION (
			SELECT
				9 AS section_id,
				s.service_id AS content_id,
				'service' AS type,
				user_name,
				name,
				CONCAT('services/80x100/', i.url_full) AS image,
				'' AS description,
				'' AS price,
				'' AS currency_name,
				s.date_add,
				(SELECT GROUP_CONCAT(categ_id) FROM `services_categs` WHERE service_id = s.service_id) AS categs,
				s.city_id
			FROM `services` AS s
			LEFT JOIN `services_images` AS i ON i.service_id = s.service_id AND i.sort_id = 0
			WHERE ". ($storage->has(9) ? ('s.service_id IN('. $storage->join(9) .') ') : 0) ." AND
			    s.flag = 1 AND s.flag_moder = 1 AND s.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1
		) UNION (
			SELECT
				11 AS section_id,
				d.demand_id,
				'demand' AS type,
				user_name,
				name,
				CONCAT('demand/80x100/', i.url_full) AS image,
				'' AS description,
				'' AS price,
				'' AS currency_name,
				d.date_add,
				'' AS categs,
				'' AS city_id
			FROM `demand` AS d
			LEFT JOIN `demand_images` AS i ON i.demand_id = d.demand_id AND i.sort_id = 0
			WHERE ". ($storage->has(11) ? ('d.demand_id IN('. $storage->join(11) .') ') : 0) ." AND
			    d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1
		) UNION (
			SELECT 
				7 AS section_id,
				l.lab_id AS content_id,
				'lab' AS type,
				(SELECT name FROM `users_info` WHERE user_id = l.user_id) AS user_name,
				name,
				CONCAT('labs/80x100/', i.url_full) AS image,
				'' AS description,
				'' AS price,
				'' AS currency_name,
				l.date_add,
				(SELECT GROUP_CONCAT(categ_id) FROM `labs_categs` WHERE lab_id = l.lab_id) AS categs,
				l.region_id AS city_id
			FROM `labs` AS l
			LEFT JOIN `labs_images` AS i ON i.lab_id = l.lab_id AND i.sort_id = 0
			WHERE  ". ($storage->has(7) ? ('l.lab_id IN('. $storage->join(7) .') ') : 0) ." AND
			    l.flag = 1 AND l.flag_moder = 1 AND l.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1
		) UNION (
			SELECT
				8 AS section_id,
				r.realty_id AS content_id,
				'realty' AS type,
				user_name,
				CONCAT(name, ', г. ', city_name) AS name,
				CONCAT('realty/80x100/', i.url_full) AS image,
				'' AS description,
				price AS price,
				currency_name AS currency_name,
				r.date_add,
				r.categ_id AS categs,
				r.city_id
			FROM `realty` AS r
			LEFT JOIN `realty_images` AS i ON i.realty_id = r.realty_id AND i.sort_id = 0
			WHERE ". ($storage->has(8) ? ('r.realty_id IN('. $storage->join(8) .') ') : 0) ." AND
			    r.flag = 1 AND r.flag_moder = 1 AND r.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1
		) UNION (
			SELECT 
				10 AS section_id,
				d.diagnostic_id AS content_id,
				'diagnostic' AS type,
				user_name,
				name,
				CONCAT('diagnostic/80x100/', i.url_full) AS image,
				'' AS description,
				'' AS price,
				'' AS currency_name,
				d.date_add,
				'' AS categs,
				d.city_id
			FROM `diagnostic` AS d
			LEFT JOIN `diagnostic_images` AS i ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
			WHERE ". ($storage->has(10) ? ('d.diagnostic_id IN('. $storage->join(10) .') ') : 0) ." AND
			    d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1
		) UNION (
			SELECT
				6 AS section_id,
				w.work_id AS content_id,
				'work/resume' AS type,
				user_name,
				CONCAT('Резюме ', (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id))) AS name,
				IFNULL(CONCAT('realty/80x100/', i.url_full), '') AS image,
				'' AS description,
				price,
				currency_name,
				w.date_add,
				(SELECT GROUP_CONCAT(categ_id) FROM `work_categs` WHERE work_id = w.work_id) AS categs,
				w.city_id
			FROM `work` AS w
			LEFT JOIN `work_images` AS i ON i.work_id = w.work_id AND i.sort_id = 0
			LEFT JOIN `users_info` USING(user_id)
			WHERE ". ($storage->has(6) ? ('w.work_id IN('. $storage->join(6) .') ') : 0) ." AND
			    w.flag = 1 AND w.flag_moder = 1 AND w.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1
		) UNION (
			SELECT
				15 AS section_id,
				v.vacancy_id AS content_id,
				'work/vacancy'	AS type,
				c.name AS user_name,
				CONCAT('Требуется ', (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)), ', г. ', city_name) AS name,
				c.logotype AS image,
				'' AS description,
				v.price,
				v.currency_name,
				v.date_add,
				(SELECT GROUP_CONCAT(categ_id) FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id) AS categs,
				v.city_id
			FROM `vacancies` AS v
			INNER JOIN `vacancy_company_info` AS c USING(company_id)
			WHERE ". ($storage->has(15) ? ('v.vacancy_id IN('. $storage->join(15) .') ') : 0) ." AND
			    v.flag = 1 AND v.flag_moder = 1 AND v.flag_delete = 0 AND is_update = 0 AND flag_moder_view = 1
		) ) AS a ORDER BY a.date_add DESC";
	
		return DB::getAssocGroup($query);
	}
	
	public static function zayavka($user_id){
		$query="SELECT user_dalete FROM  users WHERE user_id =$user_id";
		$res=  DB::getAssocArray($query, 1);	
		if(count($res)){
			 $update=  ($res['user_dalete'])? 0:1 ; 
			 DB::update('users',  array('user_dalete' =>$update ),  array('user_id' =>$user_id)); 
			 
			return  json_encode(  array('user_dalete' =>$update ,'success'=>true ) ); 
		}	
		
	return  json_encode(  array( 'success'=>false) ); 
	 
	}
	
	public  static function  flagZayavka($user_id){
		$query="SELECT user_dalete FROM  users WHERE user_id =$user_id";
		$res=  DB::getAssocArray($query, 1);
		return $res['user_dalete'];
	}
}