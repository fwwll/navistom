<?

$query = "(
			SELECT
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
				" . ($is_search ? " MATCH(activity.name) AGAINST('$q') As rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 5 AND resource_id = activity.activity_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				activity.flag,
				1 AS flag_show
			FROM `activity`
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 5 AND b.resource_id = activity.activity_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			LEFT JOIN `activity_lectors` AS l
			    ON l.activity_id = activity.activity_id AND l.sort_id = 0
			WHERE " . (!User::isAdmin() ? ' flag = 1 AND flag_moder = 1 AND ' : '') . " flag_delete = 0 AND country_id = $country_id AND
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($flag_no_moder ? " flag_moder = 0 AND " : '') . "
                " . ($is_user ? " activity.user_id = $user_id AND " : '') . "
                " . ($is_search ? " MATCH(activity.name) AGAINST('$q') AND " : "") . "
                    (activity.date_start = '0000-00-00' AND activity.date_end = '0000-00-00') OR
                   (activity.date_start != '0000-00-00' AND activity.date_end > '$date') OR
                   (activity.date_start > '$date' AND activity.date_end != '0000-00-00')
		) UNION ALL (
			SELECT
				articles.article_id AS content_id,
				16 AS section_id,
				'articles' As type,
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
				" . ($is_search ? " MATCH(name, content_min, meta_title, meta_keys, meta_description) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 16 AND resource_id = articles.article_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				0 AS light_flag,
				articles.flag,
				1 AS flag_show
			FROM `articles`
			LEFT JOIN `articles_images`
			    USING(image_id)
			WHERE " . (!User::isAdmin() ? ' flag = 1 AND flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(name, content_min, meta_title, meta_keys, meta_description) AGAINST('$q') AND " : "") . "
			flag_delete = 0 AND date_public < CAST('$date 23:59:59' AS DATETIME)
		) UNION ALL (
			SELECT
				ads.ads_id AS content_id,
				4 AS section_id,
				'ads' As type,
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
				" . ($is_search ? " MATCH(product_name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 4 AND resource_id = ads.ads_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				ads.flag,
				1 AS flag_show
			FROM `ads`
			INNER JOIN `products`
			    USING(product_id)
			LEFT JOIN `ads_images` AS i
			    ON i.ads_id = ads.ads_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 4 AND b.resource_id = ads.ads_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' ads.flag = 1 AND ads.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " ads.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(product_name, content) AGAINST('$q') AND " : "") . "
			ads.flag_delete = 0 AND ads.country_id = $country_id
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
				" . ($is_search ? " MATCH(product_name, p.content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 3 AND resource_id = p.product_new_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				p.flag,
				p.flag_show
			FROM `products_new` AS p
			INNER JOIN `products`
			    USING(product_id)
			LEFT JOIN `products_new_images` AS i
			    ON i.product_new_id = p.product_new_id AND i.sort_id = 0
			LEFT JOIN `stocks` AS s
			    ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '$date' AND s.date_end > '$date'
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 3 AND b.resource_id = p.product_new_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' p.flag = 1 AND p.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " p.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(product_name, p.content) AGAINST('$q') AND " : "") . "
			p.flag_delete = 0 AND p.flag_show = 1 AND p.country_id = $country_id
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
				'' AS price,
				'' AS price_description,
				'' AS currency_name,
				'' AS currency_id,
				'' AS flag_stock,
				s.date_add,
				flag_moder_view,
				s.flag_moder,
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 9 AND resource_id = s.service_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				s.flag,
				1 AS flag_show
			FROM `services` AS s
			LEFT JOIN `services_images` AS i
			    ON i.service_id = s.service_id AND i.sort_id = 0
            LEFT JOIN `light_content` AS b
			    ON b.section_id = 9 AND b.resource_id = s.service_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' s.flag = 1 AND s.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " s.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
			s.flag_delete = 0 AND s.country_id = $country_id
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
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 11 AND resource_id = d.demand_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				d.flag,
				1 AS flag_show
			FROM `demand` AS d
			LEFT JOIN `demand_images` AS i
			    ON i.demand_id = d.demand_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 11 AND b.resource_id = d.demand_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' d.flag = 1 AND d.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " d.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
			d.flag_delete = 0 AND d.country_id = $country_id
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
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 7 AND resource_id = l.lab_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				l.flag,
				1 AS flag_show
			FROM `labs` AS l
			LEFT JOIN `users_info` AS ui
			    USING(user_id)
			LEFT JOIN `labs_images` AS i
			    ON i.lab_id = l.lab_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 7 AND b.resource_id = l.lab_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' l.flag = 1 AND l.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " l.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
			l.flag_delete = 0 AND l.country_id = $country_id
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
				price AS price,
				price_description,
				currency_name AS currency_name,
				currency_id,
				'' AS flag_stock,
				r.date_add,
				flag_moder_view,
				r.flag_moder,
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 8 AND resource_id = r.realty_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				r.flag,
				1 AS flag_show
			FROM `realty` AS r
			LEFT JOIN `realty_images` AS i
			    ON i.realty_id = r.realty_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 8 AND b.resource_id = r.realty_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' r.flag = 1 AND r.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " r.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
			r.flag_delete = 0 AND r.country_id = $country_id
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
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 10 AND resource_id = d.diagnostic_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				d.flag,
				1 AS flag_show
			FROM `diagnostic` AS d
			LEFT JOIN `diagnostic_images` AS i
			    ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 10 AND b.resource_id = d.diagnostic_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' d.flag = 1 AND d.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " d.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
			d.flag_delete = 0 AND d.country_id = $country_id
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
				price,
				city_name AS price_description,
				currency_name,
				currency_id,
				'' AS flag_stock,
				w.date_add,
				flag_moder_view,
				w.flag_moder,
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 6 AND resource_id = w.work_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				w.flag,
				1 AS flag_show
			FROM `work` AS w
			LEFT JOIN `users_info` AS ui
			    USING(user_id)
			LEFT JOIN `work_images` AS i
			    ON i.work_id = w.work_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 6 AND b.resource_id = w.work_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' w.flag = 1 AND w.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " w.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
			w.flag_delete = 0 AND w.country_id = $country_id
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
				" . ($is_search ? " MATCH(search_name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 15 AND resource_id = v.vacancy_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				v.flag,
				1 AS flag_show
			FROM `vacancies` AS v
			INNER JOIN `vacancy_company_info` AS c
			    USING(company_id)
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 15 AND b.resource_id = v.vacancy_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' v.flag = 1 AND v.flag_moder = 1 AND ' : '') . "
			" . ($is_user ? " v.user_id = $user_id AND " : '') . "
			" . ($flag_no_moder ? " v.flag_moder = 0 AND " : '') . "
			" . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
			" . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
			" . ($is_search ? " MATCH(search_name, content) AGAINST('$q') AND " : "") . "
			v.flag_delete = 0 AND v.country_id = $country_id
		)
		" . (!$is_search ? "ORDER BY IFNULL(sort_id, 99999), IF(sort_id = 999, RAND(), 1), date_add DESC" : "ORDER BY rel DESC") ."
		LIMIT $limit, $count";

/*
$query = "(
			SELECT
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
				" . ($is_search ? " MATCH(activity.name) AGAINST('$q') As rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 5 AND resource_id = activity.activity_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				activity.flag,
				1 AS flag_show
			FROM `activity`
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 5 AND b.resource_id = activity.activity_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			LEFT JOIN `activity_lectors` AS l
			    ON l.activity_id = activity.activity_id AND l.sort_id = 0
			WHERE " . (!User::isAdmin() ? ' flag = 1 AND flag_moder = 1 AND ' : '') . " flag_delete = 0 AND country_id = $country_id AND
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($flag_no_moder ? " flag_moder = 0 AND " : '') . "
                " . ($is_user ? " activity.user_id = $user_id AND " : '') . "
                " . ($is_search ? " MATCH(activity.name) AGAINST('$q') AND " : "") . "
                IF(activity.date_start = '000-00-00', 1,
                    IF(activity.date_end != '000-00-00', activity.date_end > '" . $date . "', activity.date_start > '" . $date . "')
                )
            LIMIT $count
		) UNION ALL (
			SELECT
				articles.article_id AS content_id,
				16 AS section_id,
				'articles' As type,
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
				" . ($is_search ? " MATCH(name, content_min, meta_title, meta_keys, meta_description) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 16 AND resource_id = articles.article_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				0 AS light_flag,
				articles.flag,
				1 AS flag_show
			FROM `articles`
			LEFT JOIN `articles_images`
			    USING(image_id)
			WHERE " . (!User::isAdmin() ? ' flag = 1 AND flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(name, content_min, meta_title, meta_keys, meta_description) AGAINST('$q') AND " : "") . "
                flag_delete = 0 AND date_public <= '". DB::now() ."'
            LIMIT $count
		) UNION ALL (
			SELECT
				ads.ads_id AS content_id,
				4 AS section_id,
				'ads' As type,
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
				" . ($is_search ? " MATCH(product_name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 4 AND resource_id = ads.ads_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				ads.flag,
				1 AS flag_show
			FROM `ads`
			INNER JOIN `products`
			    USING(product_id)
			LEFT JOIN `ads_images` AS i
			    ON i.ads_id = ads.ads_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 4 AND b.resource_id = ads.ads_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' ads.flag = 1 AND ads.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " ads.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(product_name, content) AGAINST('$q') AND " : "") . "
                ads.flag_delete = 0 AND ads.country_id = $country_id
			LIMIT $count
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
				" . ($is_search ? " MATCH(product_name, p.content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 3 AND resource_id = p.product_new_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				p.flag,
				p.flag_show
			FROM `products_new` AS p
			INNER JOIN `products`
			    USING(product_id)
			LEFT JOIN `products_new_images` AS i
			    ON i.product_new_id = p.product_new_id AND i.sort_id = 0
			LEFT JOIN `stocks` AS s
			    ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '$date' AND s.date_end > '$date'
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 3 AND b.resource_id = p.product_new_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' p.flag = 1 AND p.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " p.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(product_name, p.content) AGAINST('$q') AND " : "") . "
                p.flag_delete = 0 AND p.flag_show = 1 AND p.country_id = $country_id
			LIMIT $count
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
				'' AS price,
				'' AS price_description,
				'' AS currency_name,
				'' AS currency_id,
				'' AS flag_stock,
				s.date_add,
				flag_moder_view,
				s.flag_moder,
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 9 AND resource_id = s.service_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				s.flag,
				1 AS flag_show
			FROM `services` AS s
			LEFT JOIN `services_images` AS i
			    ON i.service_id = s.service_id AND i.sort_id = 0
            LEFT JOIN `light_content` AS b
			    ON b.section_id = 9 AND b.resource_id = s.service_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' s.flag = 1 AND s.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " s.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
                s.flag_delete = 0 AND s.country_id = $country_id
			LIMIT $count
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
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 11 AND resource_id = d.demand_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				d.flag,
				1 AS flag_show
			FROM `demand` AS d
			LEFT JOIN `demand_images` AS i
			    ON i.demand_id = d.demand_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 11 AND b.resource_id = d.demand_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' d.flag = 1 AND d.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " d.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
                d.flag_delete = 0 AND d.country_id = $country_id
			LIMIT $count
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
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 7 AND resource_id = l.lab_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				l.flag,
				1 AS flag_show
			FROM `labs` AS l
			LEFT JOIN `users_info` AS ui
			    USING(user_id)
			LEFT JOIN `labs_images` AS i
			    ON i.lab_id = l.lab_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 7 AND b.resource_id = l.lab_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' l.flag = 1 AND l.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " l.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
                l.flag_delete = 0 AND l.country_id = $country_id
			LIMIT $count
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
				price AS price,
				price_description,
				currency_name AS currency_name,
				currency_id,
				'' AS flag_stock,
				r.date_add,
				flag_moder_view,
				r.flag_moder,
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 8 AND resource_id = r.realty_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				r.flag,
				1 AS flag_show
			FROM `realty` AS r
			LEFT JOIN `realty_images` AS i
			    ON i.realty_id = r.realty_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 8 AND b.resource_id = r.realty_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' r.flag = 1 AND r.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " r.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
			    r.flag_delete = 0 AND r.country_id = $country_id
			LIMIT $count
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
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 10 AND resource_id = d.diagnostic_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				d.flag,
				1 AS flag_show
			FROM `diagnostic` AS d
			LEFT JOIN `diagnostic_images` AS i
			    ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 10 AND b.resource_id = d.diagnostic_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' d.flag = 1 AND d.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " d.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
                d.flag_delete = 0 AND d.country_id = $country_id
			LIMIT $count
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
				price,
				city_name AS price_description,
				currency_name,
				currency_id,
				'' AS flag_stock,
				w.date_add,
				flag_moder_view,
				w.flag_moder,
				" . ($is_search ? " MATCH(name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 6 AND resource_id = w.work_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				w.flag,
				1 AS flag_show
			FROM `work` AS w
			LEFT JOIN `users_info` AS ui
			    USING(user_id)
			LEFT JOIN `work_images` AS i
			    ON i.work_id = w.work_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 6 AND b.resource_id = w.work_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' w.flag = 1 AND w.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " w.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(name, content) AGAINST('$q') AND " : "") . "
                w.flag_delete = 0 AND w.country_id = $country_id
			LIMIT $count
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
				" . ($is_search ? " MATCH(search_name, content) AGAINST('$q') AS rel, " : "") . "
				(SELECT sort_id FROM `top_to_main` WHERE section_id = 15 AND resource_id = v.vacancy_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '$date' AND date_end > '$date') AS sort_id,
				IF(b.resource_id, 1, 0) AS light_flag,
				v.flag,
				1 AS flag_show
			FROM `vacancies` AS v
			INNER JOIN `vacancy_company_info` AS c
			    USING(company_id)
			LEFT JOIN `light_content` AS b
			    ON b.section_id = 15 AND b.resource_id = v.vacancy_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
			WHERE " . (!User::isAdmin() ? ' v.flag = 1 AND v.flag_moder = 1 AND ' : '') . "
                " . ($is_user ? " v.user_id = $user_id AND " : '') . "
                " . ($flag_no_moder ? " v.flag_moder = 0 AND " : '') . "
                " . ($flag_no_view > 0 ? " flag_moder_view = 0 AND " : "") . "
                " . ($flag_vip_add > 0 ? " flag_vip_add = 1 AND " : "") . "
                " . ($is_search ? " MATCH(search_name, content) AGAINST('$q') AND " : "") . "
                v.flag_delete = 0 AND v.country_id = $country_id
			LIMIT $count
		)
		" . (!$is_search ? "ORDER BY IFNULL(sort_id, 99999), IF(sort_id = 999, RAND(), 1), date_add DESC" : "ORDER BY rel DESC") ."
		LIMIT $limit, $count";
 */




/*$q3 = "

SELECT a.*,
    IF(b.resource_id, 1, 0) AS light_flag,
    IFNULL(c.sort_id, 9999) AS sort
FROM (
        (
            SELECT
                activity.activity_id AS content_id,
                5 AS section_id,
                'activity' As type,
                activity.user_id,
                activity.user_name,
                activity.name,
                IFNULL(CONCAT('lectors/', l.image), CONCAT('80x100/', activity.image)) AS image,
                date_start AS description,
                date_end AS price,
                city_name AS price_description,
                '' AS currency_name,
                '' AS currency_id,
                '' AS flag_stock,
                activity.date_add,
                flag_moder_view,
                activity.flag_moder,
                activity.flag,
                1 AS flag_show
            FROM `activity`
            LEFT JOIN `activity_lectors` AS l ON l.activity_id = activity.activity_id AND l.sort_id = 0
            WHERE flag_delete = 0 AND
            IF(date_start = '000-00-00', 1,
                IF(date_end != '000-00-00', date_end > '$date', date_start > '$date')
            )
        ) UNION ALL (
            SELECT
                articles.article_id AS content_id,
                16 AS section_id,
                'articles' As type,
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
                1 AS flag_show
            FROM `articles`
            LEFT JOIN `articles_images` USING(image_id)
            WHERE flag_delete = 0 AND date_public <= '". DB::now() ."'
        ) UNION ALL (
            SELECT
                ads.ads_id AS content_id,
                4 AS section_id,
                'ads' As type,
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
                1 AS flag_show
            FROM `ads`
            INNER JOIN `products` USING(product_id)
            LEFT JOIN `ads_images` AS i  ON i.ads_id = ads.ads_id AND i.sort_id = 0
            WHERE ads.flag_delete = 0
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
                p.flag_show
            FROM `products_new` AS p
            INNER JOIN `products` USING(product_id)
            LEFT JOIN `products_new_images` AS i  ON i.product_new_id = p.product_new_id AND i.sort_id = 0
            LEFT JOIN `stocks` AS s ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '2014-10-26' AND s.date_end > '2014-10-26'
            WHERE  p.flag_delete = 0 AND p.flag_show = 1
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
                '' AS price,
                '' AS price_description,
                '' AS currency_name,
                '' AS currency_id,
                '' AS flag_stock,
                s.date_add,
                flag_moder_view,
                s.flag_moder,
                s.flag,
                1 AS flag_show
            FROM `services` AS s
            LEFT JOIN `services_images` AS i ON i.service_id = s.service_id AND i.sort_id = 0
            WHERE  s.flag_delete = 0
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
                1 AS flag_show
            FROM `demand` AS d
            LEFT JOIN `demand_images` AS i ON i.demand_id = d.demand_id AND i.sort_id = 0
            WHERE d.flag_delete = 0
        ) UNION ALL (
            SELECT
                l.lab_id AS content_id,
                7 AS section_id,
                'labs' AS type,
                user_id,
                u.name AS user_name,
                l.name,
                i.url_full AS image,
                '' AS description,
                '' AS price,
                '' AS price_description,
                '' AS currency_name,
                '' AS currency_id,
                '' AS flag_stock,
                l.date_add,
                flag_moder_view,
                l.flag_moder,
                l.flag,
                1 AS flag_show
            FROM `labs` AS l
            INNER JOIN `users_info` AS u USING(user_id)
            LEFT JOIN `labs_images` AS i ON i.lab_id = l.lab_id AND i.sort_id = 0
            WHERE  l.flag_delete = 0
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
                price AS price,
                price_description,
                currency_name AS currency_name,
                currency_id,
                '' AS flag_stock,
                r.date_add,
                flag_moder_view,
                r.flag_moder,
                r.flag,
                1 AS flag_show
            FROM `realty` AS r
            LEFT JOIN `realty_images` AS i ON i.realty_id = r.realty_id AND i.sort_id = 0
            WHERE r.flag_delete = 0
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
                1 AS flag_show
            FROM `diagnostic` AS d
            LEFT JOIN `diagnostic_images` AS i ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
            WHERE d.flag_delete = 0
        ) UNION ALL (
            SELECT
                w.work_id AS content_id,
                6 AS section_id,
                'resume' AS type,
                user_id,
                user_name,
                GROUP_CONCAT(c.name SEPARATOR ', ') AS name,
                i.url_full AS image,
                u.avatar AS description,
                price,
                city_name AS price_description,
                currency_name,
                currency_id,
                '' AS flag_stock,
                w.date_add,
                flag_moder_view,
                w.flag_moder,
                w.flag,
                1 AS flag_show
            FROM `work` AS w
            INNER JOIN `users_info` AS u USING(user_id)
            LEFT JOIN `work_categs` AS wc USING(work_id)
            LEFT JOIN `categories_work` AS c USING(categ_id)
            LEFT JOIN `work_images` AS i ON i.work_id = w.work_id AND i.sort_id = 0
            WHERE w.flag_delete = 0
            GROUP BY w.work_id
        ) UNION ALL (
            SELECT
                v.vacancy_id AS content_id,
                15 AS section_id,
                'vacancies'	AS type,
                v.user_id,
                c.name AS user_name,
                CONCAT(GROUP_CONCAT(ca.name SEPARATOR ', '), ', г. ', city_name) AS name,
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
                1 AS flag_show
            FROM `vacancies` AS v
            INNER JOIN `vacancy_company_info` AS c USING(company_id)
            LEFT JOIN `vacancies_categs` AS vc USING(vacancy_id)
            LEFT JOIN `categories_work` AS ca USING(categ_id)
            WHERE v.flag_delete = 0
            GROUP BY content_id
        )
    ) AS a

    LEFT JOIN `light_content` AS b ON b.section_id = a.section_id AND b.resource_id = a.content_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '$date' AND b.date_end > '$date'
    LEFT JOIN `top_to_main` AS c ON c.section_id = a.section_id AND c.resource_id = a.content_id AND DATE_SUB(c.date_start, INTERVAL 1 DAY) < '$date' AND c.date_end > '$date'

    WHERE a.flag = 1 AND a.flag_moder = 1

    ORDER BY sort, a.date_add DESC

    LIMIT $limit, $count
";*/