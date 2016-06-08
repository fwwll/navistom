<?php

class ModelCron {
	
	public function getArticlesToSitemap() {
		$query = "SELECT article_id, name, DATE(date_edit) AS date
			FROM `articles`
			WHERE flag = 1 AND flag_delete = 0 AND date_public <= '". DB::now() ."'
			ORDER BY date_public DESC";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/article/" . $array['article_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8",
				'changefreq' =>'never'
			);
		}
		
		return $result;
	}
	
	public function getProductsToSitemap() {
		$query = "SELECT p.product_new_id, p.product_name, p.country_id, DATE(p.date_edit) AS date
			FROM `products_new`as p
			inner JOIN users USING( user_id)
			WHERE p.flag = 1 AND 
			p.flag_moder = 1 AND 
			p.flag_delete = 0 AND  
			((p.pay=1 AND  DATEDIFF(NOW(), p.date_add)<=50  ) or users.group_id=10 )
			ORDER BY p.date_add DESC";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/product/" . $array['product_new_id'] . "-" . Str::get($array['product_name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.9"
			);
		}
		
		return $result;
	}
	
	public function getAdsToSitemap() {
		$query = "SELECT ads_id, product_name, country_id, DATE(date_edit) AS date
			FROM `ads` AS a
			INNER JOIN users USING(user_id)
			WHERE a.flag = 1 AND 
			a.flag_moder = 1 AND
			a.flag_delete = 0 AND
		   ((a.pay=1 and DATEDIFF( NOW(),a.date_add )<=50 )or users.group_id=10 )
			ORDER BY a.date_add DESC";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/ads/" . $array['ads_id'] . "-" . Str::get($array['product_name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.9"
			);
		}
		
		return $result;
	}
	
	public function getActivityToSitemap() {
		$query = "SELECT activity_id, name, country_id, DATE(date_edit) AS date
			FROM `activity`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND
			IF(flag_agreed > 0, 1, 
				IF(date_end != '000-00-00', date_end > '" . DB::now(1) . "', date_start > '" . DB::now(1) . "')
			)
			ORDER BY date_add DESC";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/activity/" . $array['activity_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getResumeToSitemap() {
		$query = "SELECT work_id, country_id, DATE(date_edit) AS date,
			(SELECT GROUP_CONCAT(name) FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = work.work_id)) AS categs
			FROM `work`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/work/resume/" . $array['work_id'] . "-" . Str::get($array['categs'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getVacancyToSitemap() {
		$query = "SELECT vacancy_id, country_id, DATE(date_edit) AS date,
			(SELECT GROUP_CONCAT(name) FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = vacancies.vacancy_id)) AS name
			FROM `vacancies`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/work/vacancy/" . $array['vacancy_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getLabsToSitemap() {
		$query = "SELECT lab_id, country_id, DATE(date_edit) AS date,
			(SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_labs` WHERE categ_id IN(SELECT categ_id FROM `labs_categs` WHERE lab_id = labs.lab_id)) AS name
			FROM `labs`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/lab/" . $array['lab_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getRealtyToSitemap() {
		$query = "SELECT realty_id, country_id, DATE(date_edit) AS date, name
			FROM `realty`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/realty/" . $array['realty_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getServicesToSitemap() {
		$query = "SELECT service_id, country_id, DATE(date_edit) AS date, name
			FROM `services`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/service/" . $array['service_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getDiagnosticToSitemap() {
		$query = "SELECT diagnostic_id, country_id, DATE(date_edit) AS date, name
			FROM `diagnostic`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0";
		
		$data = DB::DBObject()->query($query);
		$data->execute();

        while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/diagnostic/" . $array['diagnostic_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getDemandToSitemap() {
		$query = "SELECT demand_id, country_id, DATE(date_edit) AS date, name
			FROM `demand`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0";
		
		$data = DB::DBObject()->query($query);
		$data->execute();
		
		while ($array = $data->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'loc' 		=> "http://navistom.com/demand/" . $array['demand_id'] . "-" . Str::get($array['name'])->truncate(60)->translitURL(),
				'lastmod'	=> $array['date'],
				'priority'	=> "0.8"
			);
		}
		
		return $result;
	}
	
	public function getSectionsToSitemap() {
		return array(
			array(
                'loc' 		=> "http://navistom.com/articles",
                'lastmod'	=> DB::now(1),
                'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/products/filter-stocks",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/products",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/ads",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/activity",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/work/resume",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/labs",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/realty",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/services",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/demand",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			),
			array(
				'loc' 		=> "http://navistom.com/work/vacancy",
				'lastmod'	=> DB::now(1),
				'priority'	=> "0.8"
			)
		);
	}
	
	public function createSitemap() {
		$args 	= func_get_args();
		$array 	= array();
		
		for ($i = 0, $c = count($args); $i < $c; $i++) {
			$array = array_merge($array, $args[$i]);
		}
		//Site::d($array)
		$xml = new DOMDocument('1.0', 'UTF-8');
		$xml->formatOutput = true;
		
		$urlset = $xml->createElement('urlset');
		$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		
		for ($a = 0, $c = count($array); $a<$c; $a++) {
			$url = $xml->createElement('url');
			
			$loc = $xml->createElement('loc', $array[$a]['loc']);
			$lastmod = $xml->createElement('lastmod', $array[$a]['lastmod']);
			$priority = $xml->createElement('priority', $array[$a]['priority']);
			$chan =(isset($array[$a]['changefreq']))?($array[$a]['changefreq']) : 'monthly';
			$changefreq = $xml->createElement('changefreq',  $chan );
			
			$url->appendChild($loc);
			$url->appendChild($lastmod);
			$url->appendChild($changefreq);
			$url->appendChild($priority);
			
			$urlset->appendChild($url);
		}
		$xml->appendChild($urlset);
		
		return $xml->save(FULLPATH.'/sitemap.xml');
	}
	
	public function getExchangeRatesUA() {
		$xml = simplexml_load_file('http://bank-ua.com/export/currrate.xml');
		
		foreach ($xml->item as $key => $val) {
			if ($val->char3 == 'USD') {
				$usd = $val->rate / $val->size;
			}
			
			if ($val->char3 == 'EUR') {
				$eur = $val->rate / $val->size;
			}
		}
		
		DB::insert('exchange_rates_default', array(
			'country_id'		=> 1,
			'currency_id'		=> 8,
			'currency_rates'	=> $usd	
		), 1);
		
		DB::insert('exchange_rates_default', array(
			'country_id'		=> 1,
			'currency_id'		=> 9,
			'currency_rates'	=> $eur	
		), 1);
	}
	
	public function getExchangeRatesRU() {
		$xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp');
		
		$eur = (float) str_replace(',', '.', $xml->Valute[10]->Value);
		$usd = (float) str_replace(',', '.', $xml->Valute[9]->Value);
		
		DB::insert('exchange_rates_default', array(
			'country_id'		=> 2,
			'currency_id'		=> 4,
			'currency_rates'	=> $usd	
		), 1);
		
		DB::insert('exchange_rates_default', array(
			'country_id'		=> 2,
			'currency_id'		=> 5,
			'currency_rates'	=> $eur	
		), 1);
	}
	
	public function getExchangeRatesBY() {
		$xml = simplexml_load_file('http://www.nbrb.by/Services/XmlExRates.aspx');
		
		$eur = (float) $xml->Currency[5]->Rate;
		$usd = (float) $xml->Currency[4]->Rate;
		
		DB::insert('xchange_rates_default', array(
			'country_id'		=> 3,
			'currency_id'		=> 10,
			'currency_rates'	=> $usd	
		), 1);
		
		DB::insert('xchange_rates_default', array(
			'country_id'		=> 3,
			'currency_id'		=> 11,
			'currency_rates'	=> $eur	
		), 1);
	}

    public function deleteNotConfirmedUsers() {
        $query = 'DELETE FROM `users` WHERE user_id IN(SELECT user_id FROM `users_confirms` WHERE date_register <= DATE_SUB(NOW(), INTERVAL 24 HOUR))';
        DB::query($query);

        return true;
    }
	
	
	public static function  getEndPay($d ,$id_user){
	 $day =$d;	
	$query="
		SELECT * from(
(
		SELECT 
				 4 AS section_id,  
				ads.ads_id AS content_id,
				'ads' As type,
				users.email,
				user_name,
				CONCAT(product_name, ', Б/У') AS name,
				IFNULL(CONCAT('offers/80x100/', i.url_full), CONCAT('products/80x100/', products.image)) AS image,
				products.description,
				ads.price,
				ads.currency_name,
				ads.date_add,
				sub_categ_id AS categs,
				DATEDIFF(NOW(), ads.date_add) as day_count,
				ads.user_id
			FROM `ads`
			INNER JOIN `products` USING(product_id)
			LEFT JOIN `ads_images` AS i  ON i.ads_id = ads.ads_id AND i.sort_id = 0
			INNER JOIN users ON users.user_id=ads.user_id
			WHERE 
			 ads.flag = 1 AND ads.flag_moder = 1 AND ads.flag_delete = 0 AND  flag_moder_view = 1 AND ads.flag_show = 1 AND ads.pay=1 AND users.group_id<>10
			 AND DATEDIFF(NOW(), ads.date_add)=$day AND ads.user_id =$id_user
			 
)UNION(
		SELECT 
				 3 AS section_id, 
				pro.product_new_id AS content_id,
				'products_new' As type,
				users.email,
				user_name,
				product_name AS name,
				 CONCAT('products/80x100/',IFNULL( i.url_full,  products.image)) AS image,
				products.description,
				pro.price,
				pro.currency_name,
				pro.date_add,
				sub_categ_id AS categs,
				DATEDIFF(NOW(), pro.date_add) as day_count,
				pro.user_id
			FROM `products_new` as pro
			INNER JOIN `products` USING(product_id)
			LEFT JOIN `products_new_images` AS i
			    ON i.product_new_id = pro.product_new_id AND i.sort_id = 0
			INNER JOIN users ON users.user_id=pro.user_id
			WHERE 
			 pro.flag = 1 AND pro.flag_moder = 1 AND pro.flag_delete = 0 AND  flag_moder_view = 1 AND pro.flag_show = 1 AND pro.pay=1 AND users.group_id<>10
			 AND DATEDIFF(NOW(), pro.date_add)=$day AND pro.user_id =$id_user

)) as ads
	
	";
	//Site::d($query);
	  return DB::getAssocArray($query) ;
		//return DB::getAssocGroup($query);
		
		
	}
	
	
	public static function user_groub_end($d){
		
		$query="SELECT  A.user_id from (
   (
    SELECT user_id, flag,flag_moder,flag_delete ,flag_moder_view  ,flag_show ,pay,date_add 
	FROM ads  
   )UNION(
    SELECT user_id,flag ,flag_moder,flag_delete ,flag_moder_view  ,flag_show ,pay ,date_add
	FROM products_new
   )) as A
	inner join   users ON A.user_id=users.user_id
	WHERE  A.flag = 1 AND
		A.flag_moder = 1 AND
		A.flag_delete = 0 AND 
		flag_moder_view = 1 AND
		A.flag_show = 1 AND
		A.pay=1 AND
		users.group_id<>10
		AND DATEDIFF(NOW(), A.date_add)=$d   
		group by A.user_id";
		
		return DB::getAssocArray($query) ;
		
		
		
	}
	
}