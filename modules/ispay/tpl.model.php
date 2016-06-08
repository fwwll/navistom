<?php
//User::isAdmin() == 2492

class ModelTpl{ 
	
	public function getArticlesList() {
		$query = "SELECT a.article_id, name, date_public, IFNULL(url_full, 'none.jpg') AS url_full
			FROM `articles` AS a
			LEFT JOIN `articles_images` USING(image_id)
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND date_public <= '". DB::now() ."'
			ORDER BY date_public DESC
			LIMIT 0, 8";
		
		return DB::getAssocArray($query);
	}
	
	public function getJournalsList() {
		$query = "SELECT journal_id, num, year FROM `journals` ORDER BY year DESC, num DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getJournalPages($journal_id) {
		$query = "SELECT image, page, title,
			CONCAT(j.year, '-', j.num) AS path
			FROM `journals_pages` 
			INNER JOIN `journals` AS j USING(journal_id)
			WHERE journal_id = $journal_id 
			ORDER BY page";
		
		return DB::getAssocArray($query);
	}
	
	public function searchInProducts($q) {
		$query = "SELECT pr.product_id, CONCAT(pr.name, ' ', p.name) AS name, pr.description
			FROM `products` AS pr
			INNER JOIN `producers` AS p USING(producer_id)
			WHERE MATCH(pr.name, pr.description) AGAINST('$q')
			OR MATCH(p.name) AGAINST('$q')";
		
		return DB::getAssocArray($query);
	}
	
	public function searchInProductsCategs($q) {
		$query = "SELECT categ_id, name 
			FROM `categories`
			WHERE MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')";
		
		return DB::getAssocArray($query);
	}
	
	public function searchInCategs($q) {
		$query = "(SELECT link AS categ_id, name, 'sections' AS ctrl, '0' AS flag_no_ads
				FROM `sections`
				WHERE flag = 1 AND MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')) 
			UNION (
				SELECT categ_id, name, 'labs' AS ctrl, '0' AS flag_no_ads
				FROM `categories_labs`
				WHERE MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')
			) UNION (
				SELECT categ_id, name, 'realty' AS ctrl, '0' AS flag_no_ads 
				FROM `categories_realty`
				WHERE MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')
			) UNION (
				SELECT categ_id, name, 'services' AS ctrl, '0' AS flag_no_ads 
				FROM `categories_services`
				WHERE MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')
			) UNION (
				SELECT categ_id, name, 'work' AS ctrl, '0' AS flag_no_ads 
				FROM `categories_work`
				WHERE MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')
			) UNION (
				SELECT categ_id, name, 'products' AS ctrl, flag_no_ads 
				FROM `categories`
				WHERE MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')
			) UNION (
				SELECT categ_id, name, 'activity' AS ctrl, '0' AS flag_no_ads 
				FROM `categories_activity`
				WHERE MATCH(meta_title, meta_description, meta_keys) AGAINST('$q')
			)";

        return DB::getAssocArray($query);
	}
	
	public function statistic() {
		
	}

    public function getNews($page = 1, $count = 10, $userId = 0, $q = null, $flagModerView = 0, $flagModer = 1, $flagVipAdd = 0, $flagShow = 1)
    {
        $now = DB::now(1);
        $limit = ($count * $page) - $count;
        $where = '';

        if ($flagModerView > 0) {
            $where .= ' AND flag_moder_view = 0';
        }
        if ($flagVipAdd > 0) {
            $where .= ' AND flag_vip_add = 1';
        }

        $query = "
        SELECT SQL_CACHE  DISTINCT a.*,
		   
            IF(b.resource_id, 1, 0) AS light_flag,
            IFNULL(c.sort_id, 9999) AS sort
            ". (User::isAdmin() ? ", IF(s.sort_id, 1, 0) AS top_section_flag, IF(c.sort_id, 1, 0) AS top_main_flag, IF(tc.sort_id, 1, 0) AS top_category_flag" : '') ."
        FROM (
          (SELECT
               activity.activity_id AS content_id,
               5 AS section_id,
               'activity' As type,
               activity.user_id,
               activity.user_name,
               activity.name,
               IFNULL(CONCAT('lectors/', l.image), CONCAT('80x100/', activity.image)) AS image,
               activity.date_start AS description,
               activity.date_end AS price,
               city_name AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               activity.date_add,
               flag_moder_view,
               activity.flag_moder,
               activity.flag,
               " . ($q ? "MATCH(activity.name) AGAINST('$q') AS rel," : '') . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor
           FROM `activity`
		   LEFT JOIN liqpay_status AS liq ON  activity.activity_id =liq.ads_id and liq.section_id=5
           LEFT JOIN `activity_lectors` AS l
               ON l.activity_id = activity.activity_id AND l.sort_id = 0
           WHERE
               activity.flag = {$flagShow} AND activity.flag_moder = $flagModer AND flag_delete = 0 AND
               " . ($userId ? 'activity.user_id = ' . $userId . ' AND' : '') . "
               ((date_start = '0000-00-00' AND date_end = '0000-00-00') OR (date_start != '0000-00-00' AND date_start > '$now') OR (date_start > '$now' AND date_end != '0000-00-00'))
               $where
               " . ($q ? "AND MATCH(activity.name) AGAINST('$q')" : '') . "
        ) UNION ALL (
           SELECT
               articles.article_id AS content_id,
               16 AS section_id,
               'articles' AS type,
               user_id,
               '' AS user_name,
               name,
               IFNULL(url_full, 'none.jpg') AS image,
               content_min AS description,
               '' AS price,
               '' AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               date_public AS date_add,
               '1' AS flag_moder_view,
               articles.flag_moder,
               articles.flag,
               " . ($q ? "MATCH(articles.name, content_min, meta_title, meta_keys, meta_description) AGAINST('$q') AS rel, " : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
			   
           FROM `articles`
		   LEFT JOIN liqpay_status AS liq ON  articles.article_id =liq.ads_id and liq.section_id=16
           LEFT JOIN `articles_images`
               USING(image_id)
           WHERE
               " . (($flagModerView or $flagVipAdd) ? '0 AND ' : '') . "
               articles.flag = {$flagShow} AND articles.flag_moder = $flagModer AND flag_delete = 0 AND date_public < CAST('$now 23:59:59' AS DATETIME)
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               " . ($q ? "AND MATCH(articles.name, content_min, meta_title, meta_keys, meta_description) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               ads.ads_id AS content_id,
               4 AS section_id,
               'ads' AS type,
               user_id,
               user_name,
               product_name AS name,
               IFNULL(CONCAT('offers/80x100/', i.url_full), CONCAT('products/80x100/', products.image)) AS image,
               products.description,
               ads.price,
               ads.price_description,
               ads.currency_name,
               ads.currency_id,
               '' AS flag_stock,
               ads.date_add,
               flag_moder_view,
               ads.flag_moder,
               ads.flag,
               " . ($q ? "MATCH(product_name, content) AGAINST('$q') AS rel, " : "") . "
               1 AS flag_show,
			      liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
           FROM `ads`
		   LEFT JOIN liqpay_status AS liq ON  ads.ads_id =liq.ads_id and liq.section_id=4
           INNER JOIN `products`
               USING(product_id)
           LEFT JOIN `ads_images` AS i
               ON i.ads_id = ads.ads_id AND i.sort_id = 0
           WHERE
               ads.flag = {$flagShow} AND ads.flag_moder = $flagModer AND ads.flag_delete = 0
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(product_name, content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               p.product_new_id AS content_id,
               3 AS section_id,
               'products_new' As type,
               user_id,
               user_name,
               product_name AS name,
               IFNULL(i.url_full, products.image) AS image,
               products.description,
               IF(s.flag = 1, s.price, p.price) AS price,
               p.price_description,
               p.currency_name,
               IF(s.flag = 1, s.currency_id, p.currency_id) AS currency_id,
               s.flag AS flag_stock,
               IF(s.flag = 1, s.date_add, p.date_add) AS date_add,
               flag_moder_view,
               p.flag_moder,
               p.flag,
               " . ($q ? " MATCH(product_name, p.content) AGAINST('$q') AS rel," : "") . "
               p.flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor    
           FROM `products_new` AS p
		   LEFT JOIN liqpay_status AS liq ON  p.product_new_id =liq.ads_id and liq.section_id=3
           INNER JOIN `products`
               USING(product_id)
           LEFT JOIN `products_new_images` AS i
               ON i.product_new_id = p.product_new_id AND i.sort_id = 0
           LEFT JOIN `stocks` AS s
               ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND s.date_start <= '$now' AND s.date_end > '$now'
           WHERE
               p.flag = {$flagShow} AND p.flag_moder = $flagModer AND p.flag_delete = 0 AND p.flag_show = 1
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(product_name, p.content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               s.service_id AS content_id,
               9 AS section_id,
               'services' AS type,
               user_id,
               user_name,
               name,
               i.url_full AS image,
               '' AS description,
               s.price,
               '' AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               s.date_add,
               flag_moder_view,
               s.flag_moder,
               s.flag,
               " . ($q ? " MATCH(name, content) AGAINST('$q') AS rel," : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
           FROM `services` AS s
		   LEFT JOIN liqpay_status AS liq ON  s.service_id=liq.ads_id and liq.section_id=9
           LEFT JOIN `services_images` AS i
               ON i.service_id = s.service_id AND i.sort_id = 0
           WHERE
               s.flag = {$flagShow} AND s.flag_moder = $flagModer AND s.flag_delete = 0
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(name, content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               d.demand_id,
               11 AS section_id,
               'demand' AS type,
               user_id,
               user_name,
               name,
               i.url_full AS image,
               '' AS description,
               '' AS price,
               '' AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               d.date_add,
               flag_moder_view,
               d.flag_moder,
               d.flag,
               " . ($q ? " MATCH(name, content) AGAINST('$q') AS rel," : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor
           FROM `demand` AS d
		   LEFT JOIN liqpay_status AS liq ON  d.demand_id=liq.ads_id and liq.section_id=11
           LEFT JOIN `demand_images` AS i
               ON i.demand_id = d.demand_id AND i.sort_id = 0
           WHERE
               d.flag = {$flagShow} AND d.flag_moder = $flagModer AND d.flag_delete = 0
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(name, content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               l.lab_id AS content_id,
               7 AS section_id,
               'labs' AS type,
               l.user_id,
               ui.name AS user_name,
               l.name,
               i.url_full AS image,
               '' AS description,
               '' AS price,
               '' AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               l.date_add,
               l.flag_moder_view,
               l.flag_moder,
               l.flag,
               " . ($q ? " MATCH(l.name, content) AGAINST('$q') AS rel," : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
           FROM `labs` AS l
		   LEFT JOIN liqpay_status AS liq ON  l.lab_id=liq.ads_id and liq.section_id=7
           LEFT JOIN `users_info` AS ui
               USING(user_id)
           LEFT JOIN `labs_images` AS i
               ON i.lab_id = l.lab_id AND i.sort_id = 0
           WHERE
               l.flag = {$flagShow} AND l.flag_moder = $flagModer AND l.flag_delete = 0
               " . ($userId ? 'AND l.user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(l.name, content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               r.realty_id AS content_id,
               8 AS section_id,
               'realty' AS type,
               user_id,
               user_name,
               CONCAT(name, ', г. ', city_name) AS name,
               i.url_full AS image,
               '' AS description,
               r.price AS price,
               price_description,
               currency_name AS currency_name,
               currency_id,
               '' AS flag_stock,
               r.date_add,
               flag_moder_view,
               r.flag_moder,
               r.flag,
               " . ($q ? " MATCH(name, content) AGAINST('$q') AS rel," : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
           FROM `realty` AS r
		   LEFT JOIN liqpay_status AS liq ON  r.realty_id=liq.ads_id and liq.section_id=8
           LEFT JOIN `realty_images` AS i
               ON i.realty_id = r.realty_id AND i.sort_id = 0
           WHERE
               r.flag = {$flagShow} AND r.flag_moder = $flagModer AND r.flag_delete = 0
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(name, content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               d.diagnostic_id AS content_id,
               10 AS section_id,
               'diagnostic' AS type,
               user_id,
               user_name,
               name,
               i.url_full AS image,
               '' AS description,
               '' AS price,
               city_name AS price_description,
               '' AS currency_name,
               '' AS currency_id,
               '' AS flag_stock,
               d.date_add,
               flag_moder_view,
               d.flag_moder,
               d.flag,
               " . ($q ? " MATCH(name, content) AGAINST('$q') AS rel," : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
           FROM `diagnostic` AS d
		   LEFT JOIN liqpay_status AS liq ON   d.diagnostic_id=liq.ads_id and liq.section_id=10
           LEFT JOIN `diagnostic_images` AS i
               ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
           WHERE
               d.flag = {$flagShow} AND d.flag_moder = $flagModer AND d.flag_delete = 0
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(name, content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               w.work_id AS content_id,
               6 AS section_id,
               'resume' AS type,
               user_id,
               user_name,
               (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id)) AS name,
               i.url_full AS image,
               ui.avatar AS description,
               w.price,
               city_name AS price_description,
               currency_name,
               currency_id,
               '' AS flag_stock,
               w.date_add,
               flag_moder_view,
               w.flag_moder,
               w.flag,
               " . ($q ? " MATCH(w.name, content) AGAINST('$q') AS rel," : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
           FROM `work` AS w
		   LEFT JOIN liqpay_status AS liq ON   w.work_id =liq.ads_id and liq.section_id=6
           LEFT JOIN `users_info` AS ui
               USING(user_id)
           LEFT JOIN `work_images` AS i
               ON i.work_id = w.work_id AND i.sort_id = 0
           WHERE
               w.flag = {$flagShow} AND w.flag_moder = $flagModer AND w.flag_delete = 0
               " . ($userId ? 'AND user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(w.name, content) AGAINST('$q')" : "") . "
        ) UNION ALL (
           SELECT
               v.vacancy_id AS content_id,
               15 AS section_id,
               'vacancies'	AS type,
               v.user_id,
               c.name AS user_name,
               CONCAT((SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)), ', г. ', city_name) AS name,
               c.logotype AS image,
               '' AS description,
               v.price,
               v.currency_name,
               v.currency_id,
               '' AS price_description,
               '' AS flag_stock,
               v.date_add,
               flag_moder_view,
               v.flag_moder,
               v.flag,
               " . ($q ? " MATCH(search_name, content) AGAINST('$q') AS rel," : "") . "
               1 AS flag_show,
			   liq.color_yellow,
			   liq.urgently,
			   liq.show_competitor 
           FROM `vacancies` AS v
		   LEFT JOIN liqpay_status AS liq ON   v.vacancy_id =liq.ads_id and liq.section_id=15
           INNER JOIN `vacancy_company_info` AS c
               USING(company_id)
           WHERE 
               v.flag = {$flagShow} AND v.flag_moder = $flagModer AND v.flag_delete = 0
               " . ($userId ? 'AND v.user_id = ' . $userId : '') . "
               $where
               " . ($q ? "AND MATCH(search_name, content) AGAINST('$q')" : "") . "
           )
        ) AS a

        LEFT JOIN light_content AS b ON b.section_id = a.section_id AND b.resource_id = a.content_id AND b.date_start <= '$now' AND b.date_end > '$now'
       ".(User::isAdmin() ?'LEFT JOIN' :'INNER JOIN') ." top_to_main AS c ON c.section_id = a.section_id AND c.resource_id = a.content_id AND c.date_start <= '$now' AND c.date_end > '$now'
        ". (User::isAdmin() ? "
            LEFT JOIN top_to_section AS s ON s.section_id = a.section_id AND s.resource_id = a.content_id AND s.date_start <= '$now' AND s.date_end > '$now'
            LEFT JOIN top_to_category AS tc ON tc.section_id = a.section_id AND tc.resource_id = a.content_id AND tc.date_start <= '$now' AND tc.date_end > '$now'" : '') ."
     
        ORDER BY
            " . (!$q ? 'sort, a.date_add DESC' : 'a.rel DESC') . "
        LIMIT $limit, $count ";
		//Site::d($query);
        $news = DB::getAssocArray($query);
		//Site::d($news );
		
		 /* foreach($news as $k=>$v){
				$current = new DateTime("now");
                $public = new DateTime($v["date_end"]);

                 if ($current < $public && $news[$i]['sort']=='999' ) {
                    unset($news[$i]);
                } 	
		}  */
		
        for ($i = 0, $c = count($news); $i < $c; $i++) {
			/* if($news[$i]["date_end"]<$now){
				  unset($news[$i]);
			}  */
            if ($news[$i]['section_id'] == 16 and $news[$i]['date_add']) {
                $current = new DateTime("now");
                $public = new DateTime($news[$i]['date_add']);

                if ($current < $public) {
                    unset($news[$i]);
                }
            }

            if ($news[$i]['sort'] == '999') {
              //  $tmp[$i] = $news[$i];
            }
        }

        if (count($tmp) > 1) {
            $keys = array_keys($tmp);
            shuffle($tmp);

            for ($i = 0, $c = count($keys); $i < $c; $i++) {
                $news[$keys[$i]] = $tmp[$i];
            }
        }

        return $news;
    }
	
	public function getMessTpl($mess_id) {
		$query = "SELECT message FROM `feedback_mess_tpls` WHERE mess_id = $mess_id";
		
		return DB::getColumn($query);
	}
	
	public function updateFlagVipAdd($table, $primary_key, $resource_id, $flag_vip_add = 1) {
		DB::update($table, array(
			'flag_vip_add' => $flag_vip_add
		), array(
			$primary_key => $resource_id)
		);
	}
	
	public function updateVipRequest($section_id, $resource_id, $type = 1, $flag_delete = 0) {
		if ($flag_delete > 0) {
			DB::delete('vip_requests', array(
				'section_id'	=> $section_id,
				'resource_id'	=> $resource_id
			));
		}
		else {
			DB::insert('vip_requests', array(
				'section_id'	=> $section_id,
				'resource_id'	=> $resource_id,
				'type'			=> $type
			));
		}
	}
	
	public function getVipRequests() {
		$requests = DB::getAssocArray("SELECT * FROM `vip_requests`");
		
		for ($i = 0, $c = count($requests); $i < $c; $i++) {
			$result[$requests[$i]['section_id']][$requests[$i]['resource_id']] = array(
				'type'	=> $requests[$i]['type'],
				'date'	=> $requests[$i]['date_add']
			);
		}
		
		return $result;
	}

    public function getTopProviders() {
        $date = DB::now(1);

        $query = '
            SELECT
                provider_id,
                name,
                link,
                target,
                image
            FROM
                top_providers
            WHERE
                flag = 1 AND
                date_start  <= "'. $date .'" AND
                date_end    >= "'. $date .'"
            ORDER BY position';

        return DB::getAssocArray($query);
    }

    public function setProviderTransition($providerId) {
        if ($providerId > 0) {
            DB::insert('top_provider_transitions', array(
                'provider_id' => $providerId,
                'user_id' => User::isUser()
            ));
        }

        return true;
    }
}