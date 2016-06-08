<?php

class ModelWork {
	
	public function getResumeList($type = 1, $categ_id = 0, $city_id = 0, $country_id = 1, $min = null, $max = null, $user_id = 0, $page = 1, $search = null, $count = 15, $is_updates = 0, $search = null, $flag = null) {
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND IF(user_id = '" . User::isUser() . "', 1, flag = 1 AND flag_moder = 1)";
		}
		
		if ($categ_id > 0) {
			$having = "HAVING FIND_IN_SET($categ_id, categs) > 0";
		}
		
		if ($city_id > 0) {
			$where .= "AND city_id = $city_id";
		}
		
		if ($min != null or $max != null) {
			if ($min == 0 and $max > 0) {
				$between = "AND work.price BETWEEN 1 AND $max";
			}
			elseif ($min > 0 and $max > 0) {
				$between = "AND work.price BETWEEN $min AND $max";
			}
			elseif ($min > 0 and $max == 0) {
				$where .= " AND work.price > $min";
			}
			elseif($min == 0 and $max == 0) {
				$where .= " AND work.price = 0";
			}
		}
		
		if ($user_id > 0) {
			$where .= " AND user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), date_add) > 13';
		}
		
		if ((string) $search != null) {
			$match = "AND MATCH(name, content) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
			/* $orderr_by = "ORDER BY IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), date_add DESC"; */
			$orderr_by = "ORDER BY IFNULL(sort__id, 99999), date_add DESC";
		}
		
		if ($categ_id > 0) {
			$sort_table = "top_to_category";
		}
		else {
			$sort_table = "top_to_section";
		}
		

		
		$date = DB::now(1);
		
		$resume = "SELECT work_id, user_id, user_name, user_surname, contact_phones, city_id, city_name, currency_name, flag, flag_moder, flag_vip_add,
			work.price, date_add, flag_moder_view,
			(SELECT GROUP_CONCAT(categ_id) FROM `work_categs` WHERE work_id = work.work_id) AS categs,
			(SELECT url_full FROM `work_images` WHERE work_id = work.work_id AND sort_id = 0 AND flag_vac=0) AS image,
			(SELECT sort_id FROM `$sort_table` WHERE section_id = 6 AND resource_id = work.work_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 6 AND resource_id = work.work_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			/* (SELECT avatar FROM `users_info` WHERE user_id = work.user_id) AS avatar, */
			liq.color_yellow,
			liq.urgently
			FROM `work`
			LEFT JOIN liqpay_status liq ON work.work_id=liq.ads_id AND  liq.section_id=6
			WHERE flag_delete = 0 AND type = $type $where $between
			$match
			$having
			$orderr_by
			LIMIT $limit, $count";
			
		
		$resume = DB::DBObject()->query($resume);
		$resume->execute();
	
		while($array = $resume->fetch(PDO::FETCH_ASSOC)) {
			$array['categs'] = DB::getAssocKey("SELECT categ_id, name FROM `categories_work` WHERE categ_id IN({$array['categs']})");
			$array['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $array['contact_phones']));
			
			$result[] = $array;
		}
		
		return $result;
	}
	
	
	

	
	
	public function getUserResume($user_id) {
		$query = "SELECT work_id, flag, flag_moder,
			(SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = work.work_id)) AS name
			FROM `work`
			WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id";
		
		return DB::getAssocArray($query);
	}
	
	public function isUserResume($user_id) {
		return DB::getColumn("SELECT COUNT(*) FROM `work` WHERE flag_moder = 1 AND flag_delete = 0 AND user_id = $user_id");
	}
	
	public function getVacancyList($categ_id = 0, $city_id = 0, $country_id = 1, $min = null, $max = null, $user_id = 0, $page = 1, $search = null, $count = 15, $is_updates = 0, $flag = null) {
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND IF(v.user_id = '" . User::isUser() . "', 1, v.flag = 1 AND v.flag_moder = 1) ";
		}
		
		if ($categ_id > 0) {
			$having = "HAVING FIND_IN_SET($categ_id, categs) > 0";
		}
		
		if ($city_id > 0) {
			$where .= "AND v.city_id = $city_id";
		}
		
		if ($min != null or $max != null) {
			if ($min == 0 and $max > 0) {
				$between = "AND v.price BETWEEN 1 AND $max";
			}
			elseif ($min > 0 and $max > 0) {
				$between = "AND v.price BETWEEN $min AND $max";
			}
			elseif ($min > 0 and $max == 0) {
				$where .= " AND v.price > $min";
			}
			elseif($min == 0 and $max == 0) {
				$where .= " AND v.price = 0";
			}
		}
		
		if ($user_id > 0) {
			$where .= " AND v.user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND v.flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), v.date_add) > 13';
		}
		
		if ((string) $search != null) {
			$match = "AND MATCH(search_name, content) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
		/* 	$orderr_by = "ORDER BY IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), date_add DESC"; */
			$orderr_by = "ORDER BY IFNULL(sort__id, 99999) , date_add DESC";
		}
		
		if ($categ_id > 0) {
			$sort_table = "top_to_category";
		}
		else {
			$sort_table = "top_to_section";
		}
		
		$date = DB::now(1);
		
		$vacancies = "SELECT v.vacancy_id, v.user_id, v.city_id, v.city_name, v.contact_phones, v.flag, v.flag_moder, flag_vip_add,
			v.price, v.currency_name, v.date_add, flag_moder_view,
			(SELECT url_full FROM `work_images` WHERE work_id = v.vacancy_id AND sort_id = 0 AND flag_vac=1) AS image,
			c.name AS company_name, c.logotype,
			(SELECT sort_id FROM `$sort_table` WHERE section_id = 15 AND resource_id = v.vacancy_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort__id,
			(SELECT COUNT(*) FROM `light_content` WHERE section_id = 15 AND resource_id = v.vacancy_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS light_flag,
			(SELECT GROUP_CONCAT(categ_id) FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id) AS categs,
			liq.color_yellow,
			liq.urgently
			FROM `vacancies` AS v
			LEFT JOIN liqpay_status liq ON v.vacancy_id=liq.ads_id AND  liq.section_id=15
			INNER JOIN `vacancy_company_info` AS c USING(company_id)
			WHERE  v.flag_delete = 0 AND v.country_id = $country_id $match
			$where $between $having
			$orderr_by
			LIMIT $limit, $count";
		
		$vacancies = DB::DBObject()->query($vacancies);
		$vacancies->execute();
	
		while ($array = $vacancies->fetch(PDO::FETCH_ASSOC)) {
			$array['categs'] = DB::getAssocKey("SELECT categ_id, name FROM `categories_work` WHERE categ_id IN({$array['categs']})");
			$array['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $array['contact_phones']));
			
			$result[] = $array;
		}
		
		return $result;
	}
	
	public function getVacancyCount($categ_id = 0, $city_id = 0, $country_id = 1, $min = null, $max = null, $user_id = 0, $search = null, $is_updates = 0, $flag = null) {
		
		 
		if ($categ_id > 0) {
			$where = "AND vacancy_id IN(SELECT vacancy_id FROM `vacancies_categs` WHERE categ_id = $categ_id)";
			
		
		}
		
		if ($city_id > 0) {
			$where .= " AND city_id = $city_id";
		}
		
		if ($min != null or $max != null) {
			if ($min == 0 and $max > 0) {
				$between = "AND price BETWEEN 1 AND $max";
			}
			elseif ($min > 0 and $max > 0) {
				$between = "AND price BETWEEN $min AND $max";
			}
			elseif ($min > 0 and $max == 0) {
				$where .= " AND price > $min";
			}
			elseif($min == 0 and $max == 0) {
				$where .= " AND price = 0";
			}
		}
		
		if ($user_id > 0) {
			$where .= " AND user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), date_add) > 13';
		}
		
		if ((string) $search != null) {
			$match = "AND MATCH(search_name, content) AGAINST('$search')";
		}
		
		

		$count = "SELECT COUNT(*) 
			FROM `vacancies` 
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $match $where $between";
		
		return DB::getColumn($count);
	}
	
	public function getWorkFull($work_id) {
		

		 
		$work = "SELECT work_id, user_id, user_name, user_surname, user_firstname, contact_phones, user_brith, city_id, city_name, currency_name, employment_type, leave_type, country_id,
			price, currency_name, work.date_add, content, video_link,
			(SELECT url_full FROM `work_images` WHERE work_id = work.work_id AND sort_id = 0 AND flag_vac=0 ) AS image,
			 /* (SELECT avatar FROM `users_info` WHERE user_id = work.user_id) AS avatar, */ 
			(SELECT name FROM `cities` WHERE city_id = (SELECT city_id FROM `users_info` WHERE user_id = work.user_id)) AS user_city,
			TIMESTAMPDIFF(YEAR, user_brith, '" . DB::now(1) ."') AS years,
			(SELECT GROUP_CONCAT(categ_id) FROM `work_categs` WHERE work_id = work.work_id) AS categs,
			(SELECT COUNT(*) FROM `work_views` WHERE work_id = work.work_id) AS views, flag, flag_moder,
	( SELECT `liqpay_status`.urgently FROM `liqpay_status`  WHERE `liqpay_status`.ads_id = work.work_id  AND  `liqpay_status`.section_id=6) as urgently
			FROM `work` 
			WHERE work.work_id = $work_id";
			
		// Site::d($work);
		$work = DB::getAssocArray($work, 1);
		
		$work['phones'] = @explode(',', $work['contact_phones']);
		$work['categs'] = DB::getAssocKey("SELECT categ_id, name FROM `categories_work` WHERE categ_id IN({$work['categs']})");
		$work['video_link']	= str_replace('watch?v=', '', end(explode('/',  $work['video_link'])));
		
		return $work;
	}
	
	public function getResumeVIP($country_id, $categs, $work_id) {
		$date = DB::now(1);
		
		$categs = implode(',', array_keys($categs));
		
		$query = "SELECT 
			w.work_id,
			w.city_name,
			w.user_name,
			w.date_add,
			w.price,
			(SELECT GROUP_CONCAT(categ_id) FROM `work_categs` WHERE work_id = w.work_id AND categ_id IN($categs)) AS categs_find,
			(SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id)) AS categs,
			(SELECT url_full FROM `work_images` WHERE work_id = w.work_id AND sort_id = 0 AND flag_vac=0) AS image,
			/* (SELECT avatar FROM `users_info` WHERE user_id = w.user_id) AS avatar, */
			
			t.color_yellow,
			t.urgently
			FROM `liqpay_status` AS t
			INNER JOIN `work` AS w  ON w.work_id = t.ads_id AND w.country_id = $country_id
			
			WHERE t.section_id = 6 AND t.ads_id  != $work_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date' AND show_competitor >2
			HAVING categs_find
			ORDER BY RAND()";
			$result =DB::getAssocArray($query);
		 
			$result['test'] = DB::getAssocKey(
			'SELECT name,categ_id 
			FROM `categories_work` 
				WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id ='.($result[0]["work_id"]).')'
				);

		   // Site::d($result['test']);
		return DB::getAssocArray($query);
	}
	
	
	public function getVacancyFull($vacancy_id) {
		$vacancy = "SELECT v.vacancy_id, v.user_id, v.contact_phones, v.city_id, v.city_name, v.price, v.country_id,
			v.currency_name, v.type_id, v.experience_type, v.education_type, v.content, v.video_link, v.date_add,
			c.name AS company_name, c.site, c.logotype, c.description, c.user_name,
			(SELECT url_full FROM `work_images` WHERE work_id = v.vacancy_id AND sort_id = 0 AND flag_vac=1) AS image,
			(SELECT GROUP_CONCAT(categ_id) FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id) AS categs,
			(SELECT COUNT(*) FROM `vacancy_views` WHERE vacancy_id = v.vacancy_id) AS views, flag, flag_moder,
			( SELECT `liqpay_status`.urgently FROM `liqpay_status`  WHERE `liqpay_status`.ads_id = v.vacancy_id  AND  `liqpay_status`.section_id=15) as urgently
			FROM `vacancies` AS v
			INNER JOIN `vacancy_company_info` AS c USING(company_id)
			
			WHERE vacancy_id = $vacancy_id";
		//  Site::d($vacancy);
		$vacancy = DB::getAssocArray($vacancy, 1);
		
		$vacancy['phones'] 		= @explode(',', $vacancy['contact_phones']);
		$vacancy['categs'] 		= DB::getAssocKey("SELECT categ_id, name FROM `categories_work` WHERE categ_id IN({$vacancy['categs']})");
		$vacancy['video_link']	= str_replace('watch?v=', '', end(explode('/',  $vacancy['video_link'])));
		
		return $vacancy;
	}
	
	public function getVacancyVIP($country_id, $categs, $vacancy_id) {
		$date = DB::now(1);
		
		$categs = implode(',', array_keys($categs));
		
		$query = "SELECT 
			v.vacancy_id,
			v.city_name,
			v.date_add,
			c.name as company_name,
			v.price,
			c.logotype,
			(SELECT url_full FROM `work_images` WHERE work_id = v.vacancy_id AND sort_id = 0 AND flag_vac=1) AS image,
			
			(SELECT GROUP_CONCAT(categ_id) FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id AND categ_id IN($categs)) AS categs_find,
			(SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)) AS categs,
			t.color_yellow,
			t.urgently,
			/* (SELECT date_add from top_to_main WHERE section_id=15 AND resource_id = t.ads_id )as date_add , */
			if((SELECT date_end from top_to_main WHERE section_id=15 AND resource_id = t.ads_id AND 1)>$date ,1,0 )as show_top
			FROM `liqpay_status` AS t
			INNER JOIN `vacancies` AS v  ON v.vacancy_id = t.ads_id AND v.country_id = $country_id
			INNER JOIN `vacancy_company_info` AS c USING(company_id)
			
			
			WHERE t.section_id = 15 AND t.ads_id != $vacancy_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date' AND t.show_competitor>2
			HAVING categs_find
			ORDER BY RAND()";
		
			return DB::getAssocArray($query);
		
		
	
	}
	
	public function getWorkEmployment($work_id) {
		$employment = "SELECT company_name, position, activity, 
			DATE_FORMAT(date_start, '%Y.%m') AS date_start, 
			DATE_FORMAT(date_end, '%Y.%m') AS date_end
			FROM `work_employment` 
			WHERE work_id = $work_id 
			ORDER BY sort_id";
		
		return DB::getAssocArray($employment);
	}
	
	public function getWorkEducation($work_id) {
		$education = "SELECT type, institution, faculty, location,
			DATE_FORMAT(date_start, '%Y.%m') AS date_start, 
			DATE_FORMAT(date_end, '%Y.%m') AS date_end 
			FROM `work_education`
			WHERE work_id = $work_id
			ORDER BY sort_id";
		
		return DB::getAssocArray($education);
	}
	
	public function getWorkTraning($work_id) {
		$traning = "SELECT name, description FROM `work_traning` WHERE work_id = $work_id";
		
		return DB::getAssocArray($traning);
	}
	
	public function getWorkLangs($work_id) {
		$langs = "SELECT name, level FROM `work_langs` WHERE work_id = $work_id";
		
		return DB::getAssocArray($langs);
	}
	
	public function getWorkImages($work_id ,$vac=0) {
		$query = "SELECT image_id, url_full, description FROM `work_images` WHERE work_id = $work_id  AND flag_vac = $vac ORDER BY sort_id";
		
		return DB::getAssocArray($query);
	}
	
	public function getWorkGallery($work_id,$vac=0) {
		$gallery = "SELECT url_full, description FROM `work_images` WHERE work_id = $work_id AND sort_id > 0 AND flag_vac = $vac ORDER BY sort_id";
		
		return DB::getAssocArray($gallery);
	}
	
	public function getResuneUserId($work_id) {
		return DB::getColumn("SELECT user_id FROM `work` WHERE work_id = $work_id");
	}
	
	public function getCategoryMetaTags($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_work` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getVacancyCategoryMetaTags($categ_id) {
		$query = "SELECT name, title_vacancy, meta_title_vacancy, meta_description_vacancy, meta_keys_vacancy FROM `categories_work` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getWorkCities($country_id, $categ_id = 0, $type = 1, $count = false)
    {
        if ($categ_id > 0) {
            $where = " AND work_id IN(SELECT work_id FROM `work_categs` WHERE categ_id = $categ_id)";
        }

        if ($count) {
            $query = "SELECT city_id, name,
                (SELECT COUNT(*) FROM `work` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND type = $type AND country_id = $country_id AND city_id = cities.city_id $where) AS count
                FROM `cities`
                HAVING count > 0";

            return DB::getAssocArray($query);
        }

        $cities = "SELECT city_id, name
			FROM `cities` 
			WHERE city_id IN(SELECT city_id FROM `work` WHERE flag = 1 AND flag_moder = 1 AND type = $type AND country_id = $country_id $where) GROUP BY city_id";

        return DB::getAssocKey($cities);
    }
	
	public function getVacancyCities($country_id, $categ_id = 0, $count = false) {
		if ($categ_id > 0) {
			$where = " AND vacancy_id IN(SELECT vacancy_id FROM `vacancies_categs` WHERE categ_id = $categ_id)";
		}

        if ($count) {
            $query = "SELECT city_id, name,
                (SELECT COUNT(*) FROM `vacancies` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id AND city_id = cities.city_id $where) AS count
                FROM `cities`
                HAVING count > 0";

            return DB::getAssocArray($query);
        }
		
		$cities = "SELECT city_id, name 
			FROM `cities` 
			WHERE city_id IN(SELECT city_id FROM `vacancies` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = $country_id $where) GROUP BY city_id";
		
		return DB::getAssocKey($cities);
	}
	
	public function getWorkCount($type = 1, $categ_id = 0, $city_id = 0, $country_id = 1, $min = null, $max = null, $user_id = 0, $search = null, $is_updates = 0, $flag = null) {
		if ($categ_id > 0) {
			$having = "HAVING FIND_IN_SET($categ_id, categs) > 0";
		}
		
		if ($city_id > 0) {
			$where = "AND city_id = $city_id";
		}
		
		if ($min != null or $max != null) {
			if ($min == 0 and $max > 0) {
				$between = "AND price BETWEEN 1 AND $max";
			}
			elseif ($min > 0 and $max > 0) {
				$between = "AND price BETWEEN $min AND $max";
			}
			elseif ($min > 0 and $max == 0) {
				$where .= " AND price > $min";
			}
			elseif($min == 0 and $max == 0) {
				$where .= " AND price = 0";
			}
		}
		
		if ($user_id > 0) {
			$where .= " AND user_id = $user_id";
		}

        if (isset($flag)) {
            $where .= ' AND flag = ' . $flag;
        }
		
		if ($is_updates > 0) {
			$where .= ' AND DATEDIFF(NOW(), date_add) > 13';
		}
		
		$count = "SELECT COUNT(*) 
			FROM `work` 
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND type = $type
			$where $between
			$having";
		
		
		// die($count);
		
		return DB::getColumn($count);
	}
	
	public function addVacancy($user_id, $data_company, $data_vacancy, $categs, $country_id, $flag_moder, $company_id = 0, $images=null) {
		if ($user_id > 0) {
			
			if ($company_id == 0 ){
				DB::insert('vacancy_company_info', array(
					'user_id'		=> $user_id,
					'name'			=> $data_company['name'],
					'site'			=> $data_company['site'],
					'logotype'		=> $data_company['logotype'],
					'description'	=> $data_company['description'],
					'user_name'		=> $data_company['user_name']
				));
				
				$company_id = DB::lastInsertId();
			}
			else {
				DB::update('vacancy_company_info', array(
					'name'			=> $data_company['name'],
					'site'			=> $data_company['site'],
					'logotype'		=> $data_company['logotype'],
					'description'	=> $data_company['description'],
					'user_name'		=> $data_company['user_name']
				), array(
					'company_id'	=> $company_id
				));
			}
			
			if ($company_id > 0) {
				DB::insert('vacancies', array(
					'company_id'		=> $company_id,
					'user_id'			=> $user_id,
					'contact_phones'	=> $data_vacancy['contact_phones'],
					'country_id'		=> $country_id,
					'region_id'			=> $data_vacancy['region_id'],
					'city_id'			=> $data_vacancy['city_id'],
					'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$data_vacancy['city_id']}"),
					'price'				=> $data_vacancy['price'],
					'currency_id'		=> $data_vacancy['currency_id'],
					'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$data_vacancy['currency_id']}"),
					'search_name'		=> DB::getColumn("SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(" . implode(',', $categs) . ")"),
					'type_id'			=> $data_vacancy['type_id'],
					'experience_type'	=> $data_vacancy['experience_type'],
					'education_type'	=> $data_vacancy['education_type'],
					'content'			=> $data_vacancy['content'],
					'video_link'		=> $data_vacancy['video_link'],
					'flag_vip_add'		=> $data_vacancy['flag_vip_add'],
					'flag_moder'		=> $flag_moder,
					'date_add'			=> DB::now()
				));
				
				$vacancy_id = DB::lastInsertId();
				
				if(is_array($images)){
					for ($i = 0, $c = count($images); $i < $c; $i++) {
						DB::update('work_images', array(
							'work_id'	=> $vacancy_id,
							'sort_id'	=> $i,
							'flag_vac'	=>1
						), array(
							'image_id'	=> $images[$i]
						));
					}
			   }
				
				
				
				
				
				
				for ($i = 0, $c = count($categs); $i < $c; $i++) {
					DB::insert('vacancies_categs', array(
						'vacancy_id'	=> $vacancy_id,
						'categ_id'		=> $categs[$i]
					));
				}
				
				return $vacancy_id;
			}
			
			 
			
		}
		
		return false;
	}
	
	public function editVacancy($vacancy_id, $data_company, $data_vacancy, $categs, $company_id ,$images=null) {
		if ($company_id > 0) {
			DB::update('vacancy_company_info', array(
				'name'			=> $data_company['name'],
				'site'			=> $data_company['site'],
				'logotype'		=> $data_company['logotype'],
				'description'	=> $data_company['description'],
				'user_name'		=> $data_company['user_name']
			), array(
				'company_id'	=> $company_id
			));
		}
		
		DB::update('vacancies', array(
			'contact_phones'	=> $data_vacancy['contact_phones'],
			'region_id'			=> $data_vacancy['region_id'],
			'city_id'			=> $data_vacancy['city_id'],
			'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$data_vacancy['city_id']}"),
			'price'				=> $data_vacancy['price'],
			'currency_id'		=> $data_vacancy['currency_id'],
			'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$data_vacancy['currency_id']}"),
			'search_name'		=> DB::getColumn("SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(" . implode(',', $categs) . ")"),
			'type_id'			=> $data_vacancy['type_id'],
			'experience_type'	=> $data_vacancy['experience_type'],
			'education_type'	=> $data_vacancy['education_type'],
			'content'			=> $data_vacancy['content'],
			'video_link'		=> $data_vacancy['video_link']
		), array(
			'vacancy_id'	=> $vacancy_id
		));
		
		DB::delete('vacancies_categs', array('vacancy_id' => $vacancy_id));
		
		for ($i = 0, $c = count($categs); $i < $c; $i++) {
			DB::insert('vacancies_categs', array(
				'vacancy_id'	=> $vacancy_id,
				'categ_id'		=> $categs[$i]
			));
		}
		
		$name = DB::getColumn("SELECT GROUP_CONCAT(name) FROM `categories_work` WHERE categ_id IN(" . implode(',', $categs) . ")");
		
		if(Site::isModerView('vacancies', 'vacancy_id', $vacancy_id)) {
			Site::PublicLink(
                'http://navistom.com/work/vacancy/' .
				$vacancy_id . '-' . Str::get($name)->truncate(60)->translitURL()
			);
		}
		
		
		if(is_array($images)){
		
			for ($i = 0, $c = count($images); $i < $c; $i++) {
				DB::update('work_images', array(
					'work_id'	=> $vacancy_id,
					'sort_id'	=> $i,
					'flag_vac' =>1
				), array(
					'image_id'	=> $images[$i]
				));
			}
		
		}
		
		
		return true;
	}
	
	public function getVacancyData($vacancy_id) {
		$query = "SELECT vacancies.*,
			(SELECT GROUP_CONCAT(categ_id) FROM `vacancies_categs` WHERE vacancy_id = vacancies.vacancy_id) AS categ_id
			FROM `vacancies` 
			WHERE vacancy_id = $vacancy_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function isUserCompanyInfo($user_id) {
		$company_id = DB::getColumn("SELECT company_id FROM `vacancy_company_info` WHERE user_id = $user_id");
		
		if ($company_id > 0) {
			return $company_id;
		}
		
		return false;
	}
	
	public function getVacancyUserId($vacancy_id) {
		return DB::getColumn("SELECT user_id FROM `vacancies` WHERE vacancy_id = $vacancy_id");
	}
	
	public function getCompanyInfo($user_id) {
		$info = "SELECT company_id, name, site, logotype, description, user_name
			FROM `vacancy_company_info`
			WHERE user_id = $user_id";
		
		return DB::getAssocArray($info, 1);
	}
	
	public function setVacancyViews($vacancy_id, $user_id = 0) {
		if (Request::getCookie('vacancy_view_' . $vacancy_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'vacancy_id'	=> $vacancy_id,
				'user_id'		=> $user_id,
				'date_view'		=> DB::now()
			);
			
			DB::insert('vacancy_views', $write);
			
			Request::setCookie('vacancy_view_' . $vacancy_id, 1);
		}
		
		return true;
	}
	
	public function setViews($work_id, $user_id = 0) {
		if (Request::getCookie('work_view_' . $work_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'work_id'	=> $work_id,
				'user_id'	=> $user_id,
				'date_view'	=> DB::now()
			);
			
			DB::insert('work_views', $write);
			
			Request::setCookie('work_view_' . $work_id, 1);
		}
		
		return true;
	}
	
	public function add($user_id, $type, $data, $work_data = array(), $education_data = array(), $traning_data = array(), $langs_data = array(),  $categs = array(), $images = array(), $flag_moder = 0) {
		if ($user_id > 0 and is_array($data)) {
			
			DB::insert('work', array(
				'type'				=> 1,
				'user_id'			=> $user_id,
				'user_name'			=> $data['user_name'],
				'user_surname'		=> $data['user_surname'],
				'contact_phones'	=> $data['contact_phones'],
				'user_firstname'	=> $data['user_firstname'],
				'employment_type'	=> $data['employment_type'],
				'leave_type'		=> $data['leave'],
				'user_brith'		=> $data['user_brith'],
				'country_id'		=> $data['country_id'],
				'city_id'			=> $data['city_id'],
				'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$data['city_id']}"),
				'currency_id'		=> $data['currency_id'],
				'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$data['currency_id']}"),
				'name'				=> DB::getColumn("SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(" . implode(',', $categs) . ")"),
				'price'				=> $data['price'],
				'content'			=> $data['content'],
				'video_link'		=> $data['video_link'],
				'flag'				=> 1,
				'flag_moder'		=> $flag_moder,
				'flag_vip_add'		=> $data['flag_vip_add'],
				'date_add'			=> DB::now()
			));
			
			$work_id = DB::lastInsertId();
			
			for ($i = 0, $c = count($categs); $i < $c; $i++) {
				DB::insert('work_categs', array(
					'work_id'	=> $work_id,
					'categ_id'	=> $categs[$i]
				));
			}
			
			for ($i = 0, $c = count($images); $i < $c; $i++) {
				DB::update('work_images', array(
					'work_id'	=> $work_id,
					'sort_id'	=> $i
				), array(
					'image_id'	=> $images[$i]
				));
			}
			
			if (count($work_data) > 0) {
				for ($i = 0, $c = count($work_data['company_name']); $i < $c; $i++) {
					DB::insert('work_employment', array(
						'work_id'		=> $work_id,
						'company_name'	=> $work_data['company_name'][$i],
						'position'		=> $work_data['position'][$i],
						'activity'		=> $work_data['activity'][$i],
						'date_start'	=> $work_data['date_start'][$i] . '-00',
						'date_end'		=> $work_data['date_end'][$i] . '-00',
						'sort_id'		=> $i
					));
				}
			}
			
			if (count($education_data) > 0) {
				for ($i = 0, $c = count($education_data['type']); $i < $c; $i++) {
					DB::insert('work_education', array(
						'work_id'		=> $work_id,
						'type'			=> $education_data['type'][$i],
						'institution'	=> $education_data['institution'][$i],
						'faculty'		=> $education_data['faculty'][$i],
						'location'		=> $education_data['location'][$i],
						'date_start'	=> $education_data['date_start'][$i] . '-00',
						'date_end'		=> $education_data['date_end'][$i] . '-00',
						'sort_id'		=> $i
					));
				}
			}
			
			if (count($traning_data) > 0) {
				for ($i = 0, $c = count($traning_data['name']); $i < $c; $i++) {
					DB::insert('work_traning', array(
						'work_id'		=> $work_id,
						'name'			=> $traning_data['name'][$i],
						'description'	=> $traning_data['description'][$i]
					));
				}
			}
			
			if (count($langs_data) > 0) {
				for ($i = 0, $c = count($langs_data['name']); $i < $c; $i++) {
					DB::insert('work_langs', array(
						'work_id'	=> $work_id,
						'name'		=> $langs_data['name'][$i],
						'level'		=> $langs_data['level'][$i]
					));
				}
			}
			
			return $work_id;
		}
		
		return false;
	}
	
	public function resumeEdit($work_id, $data, $work_data, $education_data, $traning_data, $langs_data,  $categs, $images) {
		DB::update('work', array(
			'type'				=> 1,
			'user_name'			=> $data['user_name'],
			'user_surname'		=> $data['user_surname'],
			'user_firstname'	=> $data['user_firstname'],
			'employment_type'	=> $data['employment_type'],
			'leave_type'		=> $data['leave'],
			'user_brith'		=> $data['user_brith'],
			'city_id'			=> $data['city_id'],
			'city_name'			=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = {$data['city_id']}"),
			'currency_id'		=> $data['currency_id'],
			'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$data['currency_id']}"),
			'name'				=> DB::getColumn("SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(" . implode(',', $categs) . ")"),
			'price'				=> $data['price'],
			'content'			=> $data['content'],
			'video_link'		=> $data['video_link'],
			'contact_phones'	=> $data['contact_phones']
		), array(
			'work_id'			=> $work_id
		));
		
		DB::delete('work_categs', array('work_id' => $work_id));
			
		for ($i = 0, $c = count($categs); $i < $c; $i++) {
			DB::insert('work_categs', array(
				'work_id'	=> $work_id,
				'categ_id'	=> $categs[$i]
			));
		}
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			DB::update('work_images', array(
				'work_id'	=> $work_id,
				'sort_id'	=> $i
			), array(
				'image_id'	=> $images[$i]
			));
		}
		
		if (count($work_data) > 0) {
			DB::delete('work_employment', array('work_id' => $work_id));
			
			for ($i = 0, $c = count($work_data); $i < $c; $i++) {
				DB::insert('work_employment', array(
					'work_id'		=> $work_id,
					'company_name'	=> $work_data['company_name'][$i],
					'position'		=> $work_data['position'][$i],
					'activity'		=> $work_data['activity'][$i],
					'date_start'	=> $work_data['date_start'][$i] . '-00',
					'date_end'		=> $work_data['date_end'][$i] . '-00',
					'sort_id'		=> $i
				));
			}
		}
		
		if (count($education_data) > 0) {
			DB::delete('work_education', array('work_id' => $work_id));
			
			for ($i = 0, $c = count($education_data); $i < $c; $i++) {
				DB::insert('work_education', array(
					'work_id'		=> $work_id,
					'type'			=> $education_data['type'][$i],
					'institution'	=> $education_data['institution'][$i],
					'faculty'		=> $education_data['faculty'][$i],
					'location'		=> $education_data['location'][$i],
					'date_start'	=> $education_data['date_start'][$i] . '-00',
					'date_end'		=> $education_data['date_end'][$i] . '-00',
					'sort_id'		=> $i
				));
			}
		}
		
		if (count($traning_data) > 0) {
			DB::delete('work_traning', array('work_id' => $work_id));
			
			for ($i = 0, $c = count($traning_data); $i < $c; $i++) {
				DB::insert('work_traning', array(
					'work_id'		=> $work_id,
					'name'			=> $traning_data['name'][$i],
					'description'	=> $traning_data['description'][$i]
				));
			}
		}
		
		if (count($langs_data) > 0) {
			DB::delete('work_langs', array('work_id' => $work_id));
			
			for ($i = 0, $c = count($langs_data['name']); $i < $c; $i++) {
				DB::insert('work_langs', array(
					'work_id'	=> $work_id,
					'name'		=> $langs_data['name'][$i],
					'level'		=> $langs_data['level'][$i]
				));
			}
		}
		
		$name = DB::getColumn("SELECT GROUP_CONCAT(name) FROM `categories_work` WHERE categ_id IN(" . implode(',', $categs) . ")");
		
		if(Site::isModerView('work', 'work_id', $work_id)) {
			Site::PublicLink(
                'http://navistom.com/work/resume/' .
				$work_id . '-' . Str::get($name)->truncate(60)->translitURL()
			);
		}
		
		return true;
	}
	
	public function getResumeData($work_id) {
		$query = "SELECT work.*, cities.region_id,
			(SELECT GROUP_CONCAT(categ_id) FROM `work_categs` WHERE work_id = work.work_id) AS categ_id 
			FROM `work` 
			INNER JOIN `cities` USING(city_id)
			WHERE work_id = $work_id";
		
		$data = DB::getAssocArray($query, 1);
		$data['categs_id']	= explode(',', $data['categ_id']);
		$data['brith']		= explode('-', $data['user_brith']);
		
		return $data;
	}
	
	public function resumeDelete($work_id) {
		DB::update('work', array(
			'flag_delete'	=> 1
		), array(
			'work_id'		=> $work_id
		));
		
		return true;
	}
	
	public function editResumeFlag($work_id, $flag = 0) {
		DB::update('work', array(
			'flag'		=> $flag
		), array(
			'work_id'	=> $work_id
		));
		
		return true;
	}
	
	public function editResumeFlagModer($work_id, $flag_moder = 0) {
		DB::update('work', array(
			'flag_moder'	=> $flag_moder
		), array(
			'work_id'		=> $work_id
		));
		
		return true;
	}
	
	public function vacancyDelete($vacancy_id) {
		DB::update('vacancies', array(
			'flag_delete'	=> 1
		), array(
			'vacancy_id'	=> $vacancy_id
		));
		
		return true;
	}
	
	public function editVacancyFlag($vacancy_id, $flag = 0) {
		DB::update('vacancies', array(
			'flag'			=> $flag
		), array(
			'vacancy_id'	=> $vacancy_id
		));
		
		return true;
	}
	
	public function editVacancyFlagModer($vacancy_id, $flag_moder = 0) {
		DB::update('vacancies', array(
			'flag_moder'	=> $flag_moder
		), array(
			'vacancy_id'	=> $vacancy_id
		));
		
		return true;
	}
	
	public function saveUserMessage($work_id, $section_id, $from_id, $to_id, $message) {
		DB::insert('users_messages', array(
			'to_id'			=> $to_id,
			'from_id'		=> $from_id,
			'message'		=> $message,
			'section_id'	=> 6,
			'resource_id'	=> $work_id,
			'date_add'		=> DB::now()
		));
		
		return DB::lastInsertId();
	}
	
	public function uploadAttach($file) {
		include_once(LIBS.'upload/upload.class.php');
		
		$attach = new upload($file);
		$attach->allowed = array('application/pdf','application/msword');
		
		$file_name = Str::get()->generate(20);
		
		if ($attach->uploaded) {
			$attach->file_new_name_body = $file_name;
			$attach->Process(UPLOADS . '/docs/');
			
			if (!$attach->processed) {
				Debug::setError('UPLOAD', $attach->error, 0, __FILE__, __LINE__);
				
				return false;
			}
			
			return $file_name . '.' . $attach->file_src_name_ext;
		}
		
		return false;
	}
	
	public function uploadLogotype($file) {
		include_once(LIBS.'upload/upload.class.php');
		
		$image = new upload($file);
		
		$image_name = Str::get()->generate(20);
		
		if ($image->uploaded) {
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 160;
			$image->image_x             	= 200;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/160x200/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 145;
			$image->image_x             	= 195;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/142x195/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 80;
			$image->image_x             	= 100;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/80x100/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 64;
			$image->image_x             	= 80;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/64x80/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			if ($image->processed) {
				$image->Clean();
				
				return $image_name . '.jpg';
			}
			else {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
		}
		else {
			$result = array(
				'uploadName' 	=> '',
				'success'		=> false,
				'image_id'		=> 0
			);
		}
		
		return false;
	}
	
	/* public function uploadImage() {
		include_once(LIBS.'upload/upload.class.php');
		
		$image = new upload($_FILES['qqfile']);
		
		$image_name = Str::get()->generate(20);
		
		if ($image->uploaded) {
			$image->file_new_name_body 		= $image_name;
			$image->image_resize       		= true;
			$image->image_ratio_fill   		= true;
			$image->image_convert 			= 'jpg';
			$image->image_y            		= 560;
			$image->image_x            		= 700;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/full/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 160;
			$image->image_x             	= 200;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/160x200/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 145;
			$image->image_x             	= 195;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/142x195/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 80;
			$image->image_x             	= 100;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/80x100/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			$image->file_new_name_body 		= $image_name;
			$image->image_resize        	= true;
			$image->image_ratio_fill    	= true;
			$image->image_convert 			= 'jpg';
			$image->image_y             	= 64;
			$image->image_x             	= 80;
			$image->image_background_color 	= '#FFFFFF';
			
			$image->Process(UPLOADS . '/images/work/64x80/');
			
			if (!$image->processed) {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
			
			if ($image->processed) {
				$image->Clean();
				
				$write = array(
					'url_full'	=> $image_name . '.jpg'
				);
				
				DB::insert('work_images', $write);
				
				$image_id = DB::lastInsertId();
				
				$result = array(
					'uploadName' 	=> '/uploads/images/work/80x100/' . $image_name . '.jpg',
					'success'		=> true,
					'image_id'		=> $image_id
				);
			}
			else {
				Debug::setError('UPLOAD', $image->error, 0, __FILE__, __LINE__);
				
				$result = array(
					'uploadName' 	=> '',
					'success'		=> false,
					'image_id'		=> 0,
					'error'			=> $image->error
				);
			}
		}
		else {
			$result = array(
				'uploadName' 	=> '',
				'success'		=> false,
				'image_id'		=> 0
			);
		}
		
		return $result;
	} */
	public function uploadImage() {
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/work/full/'
			),
			array(
				'w'		=> 200,
				'h'		=> 160,
				'path'	=> UPLOADS . '/images/work/160x200/'
			),
			array(
				'w'		=> 100,
				'h'		=> 80,
				'path'	=> UPLOADS . '/images/work/80x100/'
			),
			array(
				'w'		=> 80,
				'h'		=> 64,
				'path'	=> UPLOADS . '/images/work/64x80/'
			),array(
                    'w'		=> 195,
                    'h'		=> 142,
                    'path'	=> UPLOADS . '/images/work/142x195/'
                ))
		);
		
		$write = array(
			'url_full'	=> $image_name . '.jpg'
		);
		
		DB::insert('work_images', $write);
		
		$image_id = DB::lastInsertId();
		
		$result = array(
			'uploadName' 	=> '/uploads/images/work/80x100/' . $image_name . '.jpg',
			'success'		=> true,
			'image_id'		=> $image_id
		);
		
		return $result;
	}
	
	
	
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `work_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/work/full/' . $image);
			unlink(UPLOADS . '/images/work/160x200/' . $image);
			unlink(UPLOADS . '/images/work/80x100/' . $image);
			unlink(UPLOADS . '/images/work/64x80/' . $image);
			@unlink(UPLOADS . '/images/work/142x195/' . $image);
			DB::delete('work_images', array('image_id' => $image_id));
			
			return true;
		}
		
		return false;
	}
	
	public function getCategoriesFromSelect($count = false, $vacancy = false) {
        if ($count) {
            if ($vacancy) {
                $count = '(SELECT COUNT(*) FROM `vacancies` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND vacancy_id IN(SELECT vacancy_id FROM `vacancies_categs` WHERE categ_id = c.categ_id)) AS count';
            }
            else {
                $count = '(SELECT COUNT(*) FROM `work` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND work_id IN(SELECT work_id FROM `work_categs` WHERE categ_id = c.categ_id)) AS count';
            }

            $query = 'SELECT categ_id, name,
            '. $count .'
            FROM `categories_work` AS c
            HAVING count > 0
            ORDER BY sort_id';

            return DB::getAssocArray($query);
        }

		$categs = "SELECT categ_id, name FROM `categories_work` ORDER BY sort_id";
		
		return DB::getAssocKey($categs);
	}
	
	
	public static function  remove(){
		$query="SELECT work_id FROM work WHERE flag_delete=1";
		$works= DB::getAssocArray($query);
		if(!count($works))die('remove ok');
		array_map(function($work){
			static:: removeImages($work['work_id']);
			DB::delete('work', array('work_id' => $work['work_id']));	
		},$works);
		 
	}
	public static function  removeImages($work_id){
	  $query="SELECT url_full FROM work_images WHERE work_id=$work_id";
	  $images= DB::getAssocArray($query);
	  if(!count($images))return 1;
	  
	  array_map(function($image){
		   
		    @unlink(UPLOADS . '/images/work/full/' . $image['url_full']);
			@unlink(UPLOADS . '/images/work/160x200/' . $image['url_full']);
			@unlink(UPLOADS . '/images/work/80x100/' . $image['url_full']);
			@unlink(UPLOADS . '/images/work/64x80/' . $image['url_full']);
			@unlink(UPLOADS . '/images/work/142x195/' . $image['url_full']);
			
		   
		   
	  },$images);
      
     DB::delete('work_images', array('work_id' => $work_id));	  
		
	}
	
	
	
	
	
}