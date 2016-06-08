<?php

class ModelAds {
    public function getAdsList($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $page = 1, $count = 15, $country = 1, $search = null, $user_id = 0, $is_updates = 0, $flag = null) {
        $page	= $page > 0 ? $page : 1;
        $limit 	= ($count * $page) - $count;
            $UserID=User::isUser();
			
			if(!$UserID){
				$UserID=0;
			}
		
        if (!User::isAdmin()) {
            $where = "AND IF(ads.user_id = '" . $UserID . "', 1, ads.flag = 1 AND ads.flag_moder = 1)";
			;
			$Admin=  "and ((ads.pay=1 and  DATEDIFF(  NOW( ) ,ads.date_add) <=50 ) or users.group_id=10 or ads.user_id = $UserID )" ;
			
        }else{
			$Admin= ""; 
		}

        if ($categ_id > 0 ) {
            $where .= "AND ads.categ_id = $categ_id";
        }
        elseif ($sub_categ_id > 0) {
            $where .= "AND ads.sub_categ_id = $sub_categ_id";
        }

        if ($producer_id > 0) {
            $where .= " AND ads.producer_id = $producer_id";
        }
        elseif ($product_id > 0) {
            $where .= " AND ads.product_id = $product_id";
        }

        if ($user_id > 0) {
            $where .= " AND ads.user_id = $user_id";
        }

        if ($is_updates > 0) {
            $where .= ' AND DATEDIFF(NOW(), ads.date_add) > 30';
        }

        if (isset($flag)) {
            $where .= ' AND ads.flag = ' . $flag;
        }

      
			
            $topOffers = self::getTopOffers(($categ_id > 0 or $sub_categ_id > 0) ? 'top_to_category' : 'top_to_section', $categ_id, $sub_categ_id);
            if (count($topOffers) > 0) {
                $topOrder = 'FIELD(ads.ads_id, ' . implode(',', $topOffers) . ') DESC, ';
            }
       
              
                              
        if ((string) $search != null) {
            $match = "AND MATCH(ads.product_name, ads.content) AGAINST('$search')";
            $order_by = '';
        }
        else {
          $order_by = 'ORDER BY '. $topOrder .' ads.date_add DESC';
        }
		
		 $orderr_by = 'ORDER BY  IFNULL(top, 9999) DESC, ads.date_add ASC';
        $date = DB::now(1);

        $products = "
		    SELECT SQL_CACHE   DISTINCT
/* 			(SELECT lx.resource_id from top_to_main as lx WHERE lx.resource_id=ads.ads_id   )as top , */
		        ads.ads_id,
                ads.categ_id,
                ads.sub_categ_id,
                ads.product_name,
                ads.user_id,
                ads.user_name,
                ads.contact_phones,
                ads.currency_name,
                ads.currency_id,
                ads.price,
                ads.price_description,
                ads.date_add,
                ads.flag,
                ads.flag_moder,
				ads.pay,
				IF(50 -DATEDIFF(  NOW( ) ,ads.date_add)>0, 50 -DATEDIFF(  NOW( ) ,ads.date_add),0 ) as time_show,
				users.group_id,
                products.description,
                i.url_full,
                products.image,
                categories.name AS categ_name,
                IF(l.resource_id, 1, 0) AS light_flag,
                flag_moder_view,
                flag_vip_add,
				liq.color_yellow,
				liq.urgently
				
			FROM `ads`
			LEFT JOIN liqpay_status liq ON ads.ads_id=liq.ads_id AND  liq.section_id=4  
			INNER JOIN `products`
			    USING(product_id)
			INNER JOIN `categories`
			    ON categories.categ_id = ads.sub_categ_id
			LEFT JOIN `ads_images` AS i
			    ON i.ads_id = ads.ads_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS l
			    ON l.section_id = 4 AND l.resource_id = ads.ads_id AND DATE_SUB(l.date_start, INTERVAL 1 DAY) < '$date' AND l.date_end > '$date'
			LEFT JOIN users  ON ads.user_id  =users.user_id	
			WHERE flag_delete = 0 $Admin   $where $match
			$order_by
			 LIMIT $limit, $count ";
 

   //Site::d(  $products ,1);
		
        $products = DB::DBObject()->query($products);
		//Site::d($products); 
        $products->execute();

        while ($pr = $products->fetch(PDO::FETCH_ASSOC)) {
           $pr['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $pr['contact_phones']));
           $array[] = $pr;
        }
	
//Site::d($array);
		
        return $array;
    }
	public function getAdsListNoPay($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $page = 1, $count = 15, $country = 1, $search = null, $user_id = 0, $is_updates = 0, $flag = null ) {
        $page	= $page > 0 ? $page : 1;
        $limit 	= ($count * $page) - $count;
		 
            $UserID=User::isUser();
			
			if(!$UserID){
				$UserID=0;
			}
			
        if (!User::isAdmin()) {
            $where = "AND IF(ads.user_id = '" . $UserID . "', ads.flag = 1 AND ads.flag_moder = 1, ads.flag = 1 AND ads.flag_moder = 1)";
		
			$Admin=  "and(ads.pay=0  or  DATEDIFF(  NOW( ) ,ads.date_add) >50 ) and users.group_id <>10   " ;
			
        }else{
			$where = "AND IF(ads.user_id = '" . $UserID . "', ads.flag = 1 AND ads.flag_moder = 1, ads.flag = 1 AND ads.flag_moder = 1)";
			$Admin=  "and(ads.pay=0  or  ads.pay IS NULL or  DATEDIFF(  NOW( ) ,ads.date_add) >50 ) and users.group_id <>10  " ;
		}

        if ($categ_id > 0 ) {
            $where .= "AND ads.categ_id = $categ_id";
        }
        elseif ($sub_categ_id > 0) {
            $where .= "AND ads.sub_categ_id = $sub_categ_id";
        }

        if ($producer_id > 0) {
            $where .= " AND ads.producer_id = $producer_id";
        }
        elseif ($product_id > 0) {
            $where .= " AND ads.product_id = $product_id";
        }

        if ($user_id > 0) {
            $where .= " AND ads.user_id = $user_id";
        }

        if ($is_updates > 0) {
            $where .= ' AND DATEDIFF(NOW(), ads.date_add) > 30';
        }

        if (isset($flag)) {
            $where .= ' AND ads.flag = ' . $flag;
        }

      
			
            $topOffers = self::getTopOffers(($categ_id > 0 or $sub_categ_id > 0) ? 'top_to_category' : 'top_to_section', $categ_id, $sub_categ_id);
            if (count($topOffers) > 0) {
                $topOrder = 'FIELD(ads.ads_id, ' . implode(',', $topOffers) . ') DESC, ';
            }
       
              
                              
        if ((string) $search != null) {
            $match = "AND MATCH(ads.product_name, ads.content) AGAINST('$search')";
            $order_by = '';
        }
        else {
          $order_by = 'ORDER BY  ads.ads_id DESC';
        }
		
		 
        $date = DB::now(1);

        $products = "
		    SELECT SQL_CACHE   

		        ads.ads_id,
                ads.categ_id,
                ads.sub_categ_id,
                ads.product_name,
                ads.user_id,
                ads.user_name,
                ads.contact_phones,
                ads.currency_name,
                ads.currency_id,
                ads.price,
                ads.price_description,
                ads.date_add,
                ads.flag,
                ads.flag_moder,
				ads.pay,
				IF(50 -DATEDIFF(  NOW( ) ,ads.date_add)>0, 50 -DATEDIFF(  NOW( ) ,ads.date_add),0 ) as time_show,
				users.group_id,
                products.description,
                i.url_full,
                products.image,
                categories.name AS categ_name,
                IF(l.resource_id, 1, 0) AS light_flag,
                flag_moder_view,
                flag_vip_add,
				liq.color_yellow,
				liq.urgently,
				ads_calls.call_d
				
			FROM `ads`
			LEFT JOIN liqpay_status liq ON ads.ads_id=liq.ads_id AND  liq.section_id=4  
			INNER JOIN `products`
			    USING(product_id)
			INNER JOIN `categories`
			    ON categories.categ_id = ads.sub_categ_id
			LEFT JOIN `ads_images` AS i
			    ON i.ads_id = ads.ads_id AND i.sort_id = 0
			LEFT JOIN `light_content` AS l
			    ON l.section_id = 4 AND l.resource_id = ads.ads_id AND DATE_SUB(l.date_start, INTERVAL 1 DAY) < '$date' AND l.date_end > '$date'
			LEFT JOIN users  ON ads.user_id  =users.user_id	
			 LEFT JOIN ads_calls ON  ads.user_id= ads_calls.user_id AND section_call =4
			WHERE flag_delete = 0 $Admin   $where $match
			$order_by
			LIMIT $limit, $count ";
			//Site::d($products,1);
        $products = DB::DBObject()->query($products); 
        $products->execute();

        while ($pr = $products->fetch(PDO::FETCH_ASSOC)) {
           $pr['phones'] = explode(',', preg_replace("/[^\d+ \,\-]/", '', $pr['contact_phones']));
           $array[] = $pr;
        }
	

		
        return $array;
    }

	
	public static function callsAdd($user_id, $call='1'){
		 $date=DB::now(1);
		$write=array( 'user_id'=>$user_id ,'call_d'=>$call ,'date_call'=>$date ,'section_call'=>4 );

	    return DB::insert('ads_calls', $write );
		
	}
	
	
    public function getTopOffers($table, $categoryId = 0, $subCategoryId = 0) {
        $date = DB::now(1);

/*         $query = 'SELECT a.ads_id
            FROM `'. $table .'` AS t
            INNER JOIN `ads` AS a ON a.ads_id = t.resource_id ' .
            'WHERE section_id = 4 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date . '"
            ORDER BY t.sort_id, RAND()';  */
			
			$query = 'SELECT a.ads_id
            FROM `'. $table .'`AS t
            INNER JOIN `ads` AS a ON a.ads_id = t.resource_id  AND t.section_id = 4' .
            ' WHERE section_id = 4 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date . '"
            ORDER BY  a.date_add,t.sort_id'; 

		 /* $query = 'SELECT a.ads_id
            FROM `'. $table .'` AS t
            INNER JOIN `ads` AS a ON a.ads_id = t.resource_id ' .
            'WHERE section_id = 4 AND date_start< "' . $date . '" AND date_end > "' . $date . '"
            ORDER BY a.date_add,t.sort_id';  */


			
			//Site::d($query);
        $items = DB::getAssocGroup($query);

        return array_keys($items);
    }

    public function getAdsCount($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $country = 1, $user_id = 0, $is_updates = 0, $search = null, $flag = null , $nopay=0) {
        if ($categ_id > 0 ) {
            $where = "AND ads.categ_id = $categ_id";
        }
        elseif ($sub_categ_id > 0) {
            $where = "AND ads.sub_categ_id = $sub_categ_id";
        }

        if ($producer_id > 0) {
            $where .= " AND ads.producer_id = $producer_id";
        }
        elseif ($product_id > 0) {
            $where .= " AND ads.product_id = $product_id";
        }

        if ($user_id > 0) {
            $where .= " AND ads.user_id = $user_id";
        }

        if ($is_updates > 0) {
            $where .= ' AND DATEDIFF(NOW(), ads.date_add) > 13';
        }

        if (isset($flag)) {
            $where .= ' AND ads.flag = ' . $flag;
        }
		$join="inner join users USING(user_id)";
		if ($nopay){
			 
			 
			$where .= " and(ads.pay=0  or  DATEDIFF(  NOW( ) ,ads.date_add) >=51 ) and group_id <>10  and ads.flag_moder_view=1" ;
			$join="inner join users USING(user_id)";
		}else{
			$where .= " and((ads.pay=1  and  DATEDIFF(  NOW( ) ,ads.date_add) <=50 ) or group_id =10)  and ads.flag_moder_view=1" ;
		}
		 
        if ((string) $search != null) {
            $match = "AND MATCH(ads.product_name, ads.content) AGAINST('$search')";
			
        }


        $count = "SELECT COUNT(*) FROM `ads` $join  WHERE ads.flag = 1 AND ads.flag_moder = 1 AND ads.flag_delete = 0 $where $match";

        return DB::getColumn($count);
    }

    public function getAdsFull($ads_id) {
		if(User::isAdmin()){
			$Admin='';
		}else{
			  $user_id= User::isUser();
			  if(!$user_id)$user_id=0;
			
			  $Admin='and ((ads.pay=1 and  DATEDIFF(  NOW( ) ,ads.date_add) <=50 )  or users.group_id =10  or  ads.user_id='.$user_id.' )';
			  $Admin='';
		}
		 //Site::d(User::isUser());
        $ads = "SELECT ads.*,
			products.description,
			i.url_full,
			ads.pay,
			ads.user_id,
			users.group_id,
			    IF(50 -DATEDIFF(  NOW( ) ,ads.date_add)>0, 50 -DATEDIFF(  NOW( ) ,ads.date_add),0 ) as time_show,
			products.image,
			categories.name AS categ_name,
			c.name AS parent_categ,
			cities.name AS city,
			(SELECT COUNT(*) FROM `ads_views` WHERE ads_id = ads.ads_id) AS views,
			liq.urgently,
			liq.color_yellow,
			ads.flag_delete
			FROM `ads`
			LEFT JOIN liqpay_status as liq ON  ads.ads_id=liq.ads_id AND liq.section_id=4
			INNER JOIN `products` USING(product_id)
			LEFT JOIN `ads_images` AS i ON i.sort_id = 0 AND i.ads_id = ads.ads_id
			INNER JOIN `categories` ON categories.categ_id = ads.sub_categ_id
			INNER JOIN `categories` AS c ON c.categ_id = ads.categ_id
			INNER JOIN `users_info` AS ui USING(user_id)
			INNER JOIN  users  ON  users.user_id =ads.user_id 
			
			LEFT JOIN `cities` USING(city_id)
			WHERE  ads.ads_id = $ads_id $Admin";
        
       $ads = DB::getAssocArray($ads, 1);
        if( count($ads)){
        $ads['phones'] 		= explode(',', preg_replace("/[^\d+ \,\-]/", '', $ads['contact_phones']));
        $ads['video_link']	= str_replace('watch?v=', '', end(explode('/',  $ads['video_link'])));
        }
        return $ads;
    }

    public function getVIP($country_id, $sub_categ_id, $ads_id) {
        $date = DB::now(1);

    /*    $query = "SELECT
				p.ads_id, 
				p.product_name,
				p.user_id,
				p.user_name,
				IF(i.url_full != '', i.url_full, products.image) AS image,
				p.currency_name,
				p.currency_id,
				p.price,
				p.price_description,
				products.description
			FROM `top_to_main` AS t
			INNER JOIN `ads` AS p ON p.ads_id = t.resource_id AND p.country_id = $country_id AND p.sub_categ_id = $sub_categ_id
			LEFT JOIN `ads_images` AS i  ON i.ads_id = p.ads_id AND i.sort_id = 0
			LEFT JOIN `products` USING(product_id)

			WHERE t.section_id = 4 AND resource_id != $ads_id AND DATE_SUB(t.date_start, INTERVAL 1 DAY) < '$date' AND t.date_end > '$date'
			ORDER BY RAND()";  */
			
			   $query = "SELECT
				p.ads_id,
				p.sub_categ_id	,
				p.product_name,
				p.user_id,
				p.user_name,
				IF(i.url_full != '', i.url_full, products.image) AS image,
				p.currency_name,
				p.currency_id,
				p.price,
				p.price_description,
				products.description,
				t.color_yellow,
				t.urgently,
				 ( SELECT name FROM categories WHERE categ_id =p.sub_categ_id )as categ_name, 
				
				/* (SELECT date_add from top_to_main WHERE section_id=4 AND resource_id = t.ads_id )as date_add , */
				(SELECT date_add from ads WHERE ads_id = t.ads_id )as date_add ,
				if((SELECT date_end from top_to_main WHERE section_id=4 AND resource_id = t.ads_id AND 1)>$date ,1,0 )as show_top
			FROM `liqpay_status` AS t
			INNER JOIN `ads` AS p ON p.ads_id = t.ads_id AND p.country_id = $country_id  AND p.sub_categ_id = $sub_categ_id  AND t.show_competitor>2 AND p.flag_delete=0
			LEFT JOIN `ads_images` AS i  ON i.ads_id = p.ads_id AND i.sort_id = 0
			LEFT JOIN `products`  ON p.product_id =products.product_id

			WHERE t.section_id = 4 AND t.ads_id != $ads_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date'
			AND p.flag_show=1 AND t.end_competitor > '$date'
			ORDER BY t.start_competitor "; 
  
           	//Site::d($query,1); 
    return DB::getAssocArray($query);
    }

    public function getAdsGallery($ads_id) {
        $images = "SELECT url_full, description FROM `ads_images` WHERE ads_id = $ads_id AND sort_id != 0 ORDER BY sort_id";

        return DB::getAssocArray($images);
    }

    public function addAds($product_data, $images, $producer_new_name = null, $product_new_name = null, $product_new_description = null) {
        if (is_array($product_data)) {

            if ($producer_new_name != null) {
                DB::insert('producers', array(
                    'name'			=> $producer_new_name,
                    'description'	=> '',
                    'date_add'		=> DB::now(),
                    'flag_moder'	=> 0
                ));

                $producer_id = DB::lastInsertId();
            }
            else {
                $producer_id = $product_data['producer_id'];
            }

            if ($product_new_name != null) {
                DB::insert('products', array(
                    'producer_id'	=> $producer_id,
                    'name'			=> $product_new_name,
                    'description'	=> $product_new_description,
                    'image'			=> '',
                    'date_add'		=> DB::now(),
                    'flag_moder'	=> 0
                ));

                $product_id = DB::lastInsertId();
            }
            else {
                $product_id = $product_data['product_id'];
            }

            $productName = DB::getColumn("SELECT CONCAT((SELECT name FROM `producers` WHERE producer_id = products.producer_id), ' ', name) FROM `products` WHERE product_id = $product_id");
            $currencyName = DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$product_data['currency_id']}");

            DB::insert('ads', array(
                'product_id'		=> $product_id,
                'producer_id'		=> $producer_id,
                'categ_id'			=> $product_data['categ_id'],
                'sub_categ_id'		=> $product_data['sub_categ_id'],
                'product_name'		=> $productName,
                'user_id'			=> $product_data['user_id'],
                'user_name'			=> $product_data['user_name'],
                'contact_phones'	=> $product_data['contact_phones'],
                'currency_id'		=> $product_data['currency_id'],
                'currency_name'		=> $currencyName,
                'country_id'		=> $product_data['country_id'],
                'price'				=> $product_data['price'],
                'price_description'	=> $product_data['price_description'],
                'content'			=> $product_data['content'],
                'video_link'		=> $product_data['video_link'],
                'flag'				=> 1,
                'flag_moder'		=> $product_data['flag_moder'],
                'flag_vip_add'		=> $product_data['flag_vip_add'],
                'date_add'			=> DB::now()
            ));

            $ads_id = DB::lastInsertId();

            if (is_array($images)) {
                for ($i = 0, $c = count($images); $i < $c; $i++) {
                    DB::update('ads_images', array(
                        'ads_id'	=> $ads_id,
                        'sort_id'	=> $i
                    ), array(
                        'ads_id'	=> 0,
                        'image_id'	=> $images[$i]
                    ));
                }
            }

            News::addOfferToNews(4, $ads_id, array(
                'name'              => $productName,
                'image'             => self::getImageToNews($product_id, $images[0]),
                'description'       => DB::getColumn('SELECT description FROM `products` WHERE product_id = ' . $product_id),
                'currency_id'		=> $product_data['currency_id'],
                'currency_name'		=> $currencyName,
                'price'				=> $product_data['price'],
                'price_description'	=> $product_data['price_description'],
                'flag'				=> 1,
                'flag_moder'		=> $product_data['flag_moder'],
                'flag_vip_add'		=> $product_data['flag_vip_add'],
                'date_add'			=> DB::now()
            ));

            return $ads_id;
        }

        return false;
    }

    public function getImageToNews($productId, $imageId) {
        $query = 'SELECT IFNULL(url_full, image) AS image
            FROM `products`
            LEFT JOIN `ads_images` AS a ON image_id = '. $imageId .'
            WHERE product_id = ' . $productId;

        $result = DB::getColumn($query);

        return ($result ? $result : '');
    }

    public function getAdsData($ads_id) {
        $ads = "SELECT * FROM `ads` WHERE ads_id = $ads_id";

        return DB::getAssocArray($ads, 1);
    }

    public function getAdsImages($ads_id) {
        $images = "SELECT image_id, url_full AS url_full, description
			FROM `ads_images` WHERE ads_id = $ads_id ORDER BY sort_id";

        return DB::getAssocArray($images);
    }

    public function editAds($ads_id, $ads_data, $images, $images_descr, $producer_new_name = null, $product_new_name = null, $product_new_description = null) {
        if ($ads_id > 0 and is_array($ads_data)) {

            if ($producer_new_name != null) {
                DB::insert('producers', array(
                    'name'			=> $producer_new_name,
                    'description'	=> '',
                    'date_add'		=> DB::now(),
                    'flag_moder'	=> 0
                ));

                $producer_id = DB::lastInsertId();
            }
            else {
                $producer_id = $ads_data['producer_id'];
            }

            if ($product_new_name != null) {
                DB::insert('products', array(
                    'producer_id'	=> $producer_id,
                    'name'			=> $product_new_name,
                    'description'	=> $product_new_description,
                    'image'			=> '',
                    'date_add'		=> DB::now(),
                    'flag_moder'	=> 0
                ));

                $product_id = DB::lastInsertId();
            }
            else {
                $product_id = $ads_data['product_id'];
            }

            $product_name = DB::getColumn("SELECT CONCAT(pr.name, ' ', p.name) FROM `products` AS p INNER JOIN `producers` AS pr USING(producer_id) WHERE product_id = {$product_id}");
            $currencyName = DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$ads_data['currency_id']}");

            DB::update('ads', array(
                'product_id'		=> $product_id,
                'producer_id'		=> $producer_id,
                'categ_id'			=> $ads_data['categ_id'],
                'sub_categ_id'		=> $ads_data['sub_categ_id'],
                'product_name'		=> $product_name,
                'currency_id'		=> $ads_data['currency_id'],
                'contact_phones'	=> $ads_data['contact_phones'],
                'currency_name'		=> $currencyName,
                'price'				=> $ads_data['price'],
                'price_description'	=> $ads_data['price_description'],
                'content'			=> $ads_data['content'],
                'video_link'		=> $ads_data['video_link']
            ), array(
                'ads_id' 			=> $ads_id
            ));

            if (is_array($images)) {
                for ($i = 0, $c = count($images); $i < $c; $i++) {
                    DB::update('ads_images', array(
                        'ads_id' 		=> $ads_id,
                        'description'	=> $images_descr[$images[$i]],
                        'sort_id'		=> $i
                    ), array(
                        'image_id' => $images[$i]
                    ));
                }
            }

            if(Site::isModerView('ads', 'ads_id', $ads_id)) {
                Site::PublicLink(
                    'http://navistom.com/ads/' .
                    $ads_id . '-' . Str::get($product_name)->truncate(60)->translitURL()
                );
            }

            News::updateOfferOnNews(4, $ads_id, array(
                'name' => $product_name,
                'image'             => self::getImageToNews($product_id, $images[0]),
                'description'       => DB::getColumn('SELECT description FROM `products` WHERE product_id = ' . $product_id),
                'currency_id'		=> $ads_data['currency_id'],
                'currency_name'		=> $currencyName,
                'price'				=> $ads_data['price'],
                'price_description'	=> $ads_data['price_description']
            ));

            return true;
        }

        return false;
    }

    public function delete($ads_id) {
        DB::update('ads', array(
            'flag_delete'	=> 1
        ), array(
            'ads_id'		=> $ads_id
        ));

        return true;
    }

    public function editFlag($ads_id, $flag = 0) {
        DB::update('ads', array(
            'flag'		=> $flag
        ), array(
            'ads_id'	=> $ads_id
        ));

        return true;
    }

    public function editFlagModer($ads_id, $flag_moder = 0) {
        DB::update('ads', array(
            'flag_moder'	=> $flag_moder
        ), array(
            'ads_id'		=> $ads_id
        ));

        return true;
    }

    public function transferToProducts($ads_id) {
        if ($ads_id > 0) {
            /*
                Get product data
            */
            $data = "SELECT * FROM `ads` WHERE ads_id = $ads_id";
            $data = DB::getAssocArray($data, 1);

            unset($data['ads_id']);

            $images = "SELECT * FROM `ads_images` WHERE ads_id = $ads_id";
            $images = DB::getAssocArray($images);

            $views = "SELECT * FROM `ads_views` WHERE ads_id = $ads_id";
            $views = DB::getAssocArray($views);

            /*
                Insert new Ads
            */
            DB::insert('products_new', $data);
            $product_new_id = DB::lastInsertId();

            if ($product_new_id > 0) {
                /*
                    Transfer images
                */
                for ($i = 0, $c = count($images); $i < $c; $i++) {
                    rename(UPLOADS . '/images/offers/full/' 	. $images[$i]['url_full'], UPLOADS . '/images/products/full/' 		. $images[$i]['url_full']);
                    rename(UPLOADS . '/images/offers/160x200/' 	. $images[$i]['url_full'], UPLOADS . '/images/products/160x200/' 	. $images[$i]['url_full']);
                    rename(UPLOADS . '/images/offers/80x100/' 	. $images[$i]['url_full'], UPLOADS . '/images/products/80x100/' 	. $images[$i]['url_full']);
                    rename(UPLOADS . '/images/offers/64x80/' 	. $images[$i]['url_full'], UPLOADS . '/images/products/64x80/' 		. $images[$i]['url_full']);
					@rename(UPLOADS . '/images/offers/142x195/' 	. $images[$i]['url_full'], UPLOADS . '/images/products/142x195/' 		. $images[$i]['url_full']);

                    DB::insert('products_new_images', array(
                        'product_new_id'	=> $product_new_id,
                        'sort_id'			=> $images[$i]['sort_id'],
                        'description'		=> $images[$i]['description'],
                        'url_full'			=> $images[$i]['url_full']
                    ));
                }

                /*
                    Transfer views
                */

                for ($i = 0, $c = count($views); $i < $c; $i++) {
                    DB::insert('products_new_views', array(
                        'product_new_id'	=> $product_new_id,
                        'user_id'			=> $views[$i]['user_id'],
                        'date_view'			=> $views[$i]['date_view']
                    ));
                }

                /*
                    Remove product data
                */

                DB::delete('ads', array(
                    'ads_id'	=> $ads_id
                ));

                DB::delete('ads_images', array(
                    'ads_id'	=> $ads_id
                ));

                DB::delete('ads_views', array(
                    'ads_id'	=> $ads_id
                ));

                return $product_new_id;
            }
            else {
                return false;
            }
        }
    }

    public function getUserId($ads_id) {
        return DB::getColumn("SELECT user_id FROM `ads` WHERE ads_id = $ads_id");
    }

    public function setViews($ads_id, $user_id = 0) {
        if (Request::getCookie('ads_view_' . $ads_id, 'int') > 0) {
            return true;
        }
        else {
            $write = array(
                'ads_id'	=> $ads_id,
                'user_id'	=> $user_id,
                'date_view'	=> DB::now()
            );

            DB::insert('ads_views', $write);

            Request::setCookie('ads_view_' . $ads_id, 1);
        }

        return true;
    }

    public function getCategoriesFromSelectOnly($parentId = 0, $userId = 0) {
        $query = 'SELECT categ_id, name, name_min,
                    (SELECT COUNT(*) FROM ads WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND '.($parentId > 0 ? 'sub_categ_id' : 'categ_id').' = c.categ_id '. ($userId > 0 ? 'AND user_id = ' . $userId : '') .') AS count
                    FROM categories AS c
                    WHERE parent_id = ' . $parentId . ' AND flag_no_ads = 1
                    HAVING count > 0
                    ORDER BY sort_id';

        return DB::getAssocArray($query);
    }

    public function getProducersFromSelectOnly($categoryId = 0, $subCategoryId = 0, $userId = 0) {
        $where = '';

        if ($categoryId > 0) {
            $where = 'AND categ_id = ' . $categoryId;
        }

        if($subCategoryId > 0) {
            $where = 'AND sub_categ_id = ' . $subCategoryId;
        }

        if ( $userId > 0 ) $where .= ' AND user_id = ' . $userId;

        $query = 'SELECT producer_id, name,
                    (SELECT COUNT(*) FROM ads WHERE p.producer_id = producer_id AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 '. $where .') AS count
                    FROM producers AS p
                    WHERE flag_moder = 1
                    HAVING count > 0
                    ORDER BY sort_id, name';
           //Site::d($query);
        return DB::getAssocArray($query);
    }

    public function getProductsFromSelectOnly($producerId, $categoryId = 0, $subCategoryId = 0, $userId = 0) {
        $where = '';

        if ($categoryId > 0) $where = 'AND categ_id = ' . $categoryId;

        if($subCategoryId > 0) $where = 'AND sub_categ_id = ' . $subCategoryId;

        if ( $userId > 0 ) $where .= ' AND user_id = ' . $userId;

        $query = 'SELECT product_id, name,
                    (SELECT COUNT(*) FROM ads WHERE p.product_id = product_id AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 '. $where .') AS count
                    FROM products AS p
                    WHERE producer_id = ' . $producerId . ' AND flag_moder = 1
                    HAVING count > 0
                    ORDER BY name';
					
				
        return DB::getAssocArray($query);
    }

    public function getCategoriesFromSelect($parent_id = 0, $flag_min = 0, $flag_ads = 0) {
        $country_id = Request::get('country');

        if ($flag_min > 0) {
            $name = "IF(name_min != '', name_min, name) AS name";
        }
        else {
            $name = "name";
        }

        $query = "SELECT categ_id, $name,
			(SELECT COUNT(*) FROM `ads` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND country_id = $country_id AND
				IF($parent_id > 0, sub_categ_id = categories.categ_id, categ_id = categories.categ_id)) AS count 
			FROM `categories` 
			WHERE parent_id = $parent_id AND flag_no_ads = 1 ORDER BY sort_id";

        return DB::getAssocArray($query);
    }

    public function getProducersFromSelect($categ_id = 0, $sub_categ_id = 0) {
        $country_id = Request::get('country');

        if ($categ_id > 0 and $sub_categ_id == 0) {
            $where = "AND producer_id IN(SELECT producer_id FROM `ads` WHERE categ_id = $categ_id)";
        }
        elseif ($categ_id > 0 and $sub_categ_id > 0) {
            $where = "AND producer_id IN(SELECT producer_id FROM `ads` WHERE categ_id = $categ_id AND sub_categ_id = $sub_categ_id)";
        }
        elseif ($categ_id == 0 and $sub_categ_id > 0) {
            $where = "AND producer_id IN(SELECT producer_id FROM `ads` WHERE sub_categ_id = $sub_categ_id)";
        }

        $query = "SELECT producer_id, name,
			(SELECT COUNT(*) FROM `ads` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND country_id = $country_id AND producer_id = producers.producer_id) AS count
			FROM `producers` WHERE flag_moder = 1 $where ORDER BY sort_id, name";

        return DB::getAssocArray($query);
    }

    public function getProductsFromSelect($producer_id) {
        $country_id = Request::get('country');

        $query = "SELECT product_id, name,
			(SELECT COUNT(*) FROM `ads` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND country_id = $country_id AND product_id = products.product_id) AS count
			FROM `products` WHERE producer_id = $producer_id ORDER BY name";

        return DB::getAssocArray($query);
    }

    public function getProducersListOrderByName() {
        $query = "SELECT producer_id, name,
			(SELECT COUNT(*) FROM `ads` WHERE producer_id = producers.producer_id AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1) AS count
			FROM `producers` WHERE flag_moder = 1 HAVING count > 0 ORDER BY name";

        return DB::getAssocArray($query);
    }

    public function getSalespeople() {
        $query = "SELECT user_id, user_name, COUNT(*) AS count
			FROM `ads`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND user_name != ''
			GROUP BY user_id
			ORDER BY count DESC";

        return DB::getAssocArray($query);
    }

    public function getParentProducerId($product_id) {
        $query = "SELECT producer_id FROM `products` WHERE product_id = $product_id";

        return DB::getColumn($query);
    }

    public function getParentIdCategory($categ_id) {
        $query = "SELECT parent_id FROM `categories` WHERE categ_id = $categ_id";
        $array = DB::getAssocArray($query, 1);

        return $array['parent_id'];
    }

    public function getAdsUserInfo($ads_id) {

        $query = "SELECT a.user_id, ui.name, ui.avatar, ui.contact_phones,
			a.ads_id AS resource_id, a.product_name, products.description, 
			a.price,
			a.currency_name
			FROM `ads` AS a
			INNER JOIN `users_info` AS ui USING(user_id)
			INNER JOIN `products` USING(product_id)
			WHERE a.ads_id = $ads_id";

        $array = DB::getAssocArray($query, 1);

        $array['contact_phones'] = explode(',', $array['contact_phones']);

        return $array;
    }

    public function getUserMessages($ads_id, $user_id) {
        $messages = "SELECT m.message_id, m.message, m.status, m.date_add,
			u.name, u.avatar
			FROM `users_messages` AS m 
			INNER JOIN `users_info` AS u ON u.user_id = m.from_id
			WHERE m.section_id = 4 AND m.resource_id = $ads_id AND (m.from_id = $user_id OR m.to_id = $user_id)
			ORDER BY m.date_add";

        return DB::getAssocArray($messages);
    }

    public function saveUserMessage($ads_id, $from_id, $to_id, $message) {
        DB::insert('users_messages', array(
            'to_id'			=> $to_id,
            'from_id'		=> $from_id,
            'message'		=> $message,
            'section_id'	=> 4,
            'resource_id'	=> $ads_id,
            'date_add'		=> DB::now()
        ));

        return DB::lastInsertId();
    }

    public function deleteImage($image_id) {
        $image = "SELECT url_full FROM `ads_images` WHERE image_id = $image_id";
        $image = DB::getColumn($image);

        if ($image != null) {
            @unlink(UPLOADS . '/images/offers/full/' . $image);
            @unlink(UPLOADS . '/images/offers/160x200/' . $image);
            @unlink(UPLOADS . '/images/offers/80x100/' . $image);
            @unlink(UPLOADS . '/images/offers/64x80/' . $image);
			@unlink(UPLOADS . '/images/offers/142x195/' . $image);
            DB::delete('ads_images', array('image_id' => $image_id));

            return true;
        }

        return false;
    }

    public function uploadImage() {

        require_once(LIBS . 'AcImage/AcImage.php');

        $image_name = Str::get()->generate(20);

        $images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
                array(
                    'w'		=> 700,
                    'h'		=> 560,
                    'path'	=> UPLOADS . '/images/offers/full/'
                ),
                array(
                    'w'		=> 200,
                    'h'		=> 160,
                    'path'	=> UPLOADS . '/images/offers/160x200/'
                ),
                array(
                    'w'		=> 100,
                    'h'		=> 80,
                    'path'	=> UPLOADS . '/images/offers/80x100/'
                ),
                array(
                    'w'		=> 80,
                    'h'		=> 64,
                    'path'	=> UPLOADS . '/images/offers/64x80/'
                ),
				array(
                    'w'		=> 195,
                    'h'		=> 142,
                    'path'	=> UPLOADS . '/images/offers/142x195/'
                ),
				
				
				)
        );

        $write = array(
            'url_full'	=> $image_name . '.jpg'
        );

        DB::insert('ads_images', $write);

        $image_id = DB::lastInsertId();

        $result = array(
            'uploadName' 	=> '/uploads/images/offers/80x100/' . $image_name . '.jpg',
            'success'		=> true,
            'image_id'		=> $image_id
        );

        return $result;


    }

    public function updateFlagVipAdd($ads_id, $flag_vip_add = 1) {
        DB::update('ads', array(
                'flag_vip_add' => $flag_vip_add
            ), array(
                'ads_id' => $ads_id)
        );
    }
	
	
	public static function   removeAds(){
		$query="SELECT ads_id FROM ads WHERE flag_delete=1 limit 100";
		$ads=  DB::getAssocArray($query);
		 if(!count($ads)){
			 echo'no images'; 
			  echo  count(glob(UPLOADS . '/images/offers/full/*.jpg') );
		 }else{
	      array_map(function($item){   
	      static::imagesRemove($item['ads_id']);
	      DB::delete('ads', array('ads_id' => $item['ads_id']));
	   },$ads);
	   
	    
	 echo  count(glob(UPLOADS . '/images/offers/full/*.jpg') );
	 echo"<script> location.href='/ads/remove?r=".rand(10,1000) ."' </script>";   
	   
	  }
	}
	
	public static  function  imagesRemove( $ads_id){
	 $query="SELECT url_full FROM ads_images WHERE ads_id=$ads_id";	
	 $images=DB::getAssocArray($query);
	 
	
	  array_map(function($image){ 

		    @unlink(UPLOADS . '/images/offers/full/' . $image['url_full']);
            @unlink(UPLOADS . '/images/offers/160x200/' . $image['url_full']);
            @unlink(UPLOADS . '/images/offers/80x100/' . $image['url_full']);
            @unlink(UPLOADS . '/images/offers/64x80/' . $image['url_full']);
			@unlink(UPLOADS . '/images/offers/142x195/' . $image['url_full']);

	 },$images); 

       DB::delete('ads_images', array('ads_id' => $ads_id));	    
	}
	
}