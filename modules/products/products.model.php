<?php

class ModelProducts {
    public static function getProductsList($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $page = 1, $count = 15, $country = 1, $filter = null, $search = null, $user_id = 0, $is_updates = 0, $flag = null,$nopay=0) {
        $page	= $page > 0 ? $page : 1;
        $limit 	= ($count * $page) - $count;
		
		$UserID=User::isUser();
		
		if(!$UserID){
			$UserID=0;
		}
		
		if (!User::isAdmin()) {
            $where = "AND IF(products_new.user_id = '" . $UserID . "', 1, products_new.flag = 1 AND products_new.flag_moder = 1)";
			;
			$Admin=  "and ((products_new.pay=1 and  DATEDIFF(  NOW( ) ,products_new.date_add) <=50 ) or users.group_id=10 or products_new.user_id = $UserID )" ;
			
        }else{
			$Admin= "";
			/* --------------- */
			if($nopay){
			    $where = "AND IF(products_new.user_id = '" . $UserID . "', products_new.flag = 1 AND products_new.flag_moder = 1, products_new.flag = 1 AND products_new.flag_moder = 1)";
				
				$Admin=  "and(products_new.pay=0  or  products_new.pay IS NULL or  DATEDIFF(  NOW( ) ,products_new.date_add) >50 ) and users.group_id <>10  " ;	
				
			}
		   /* --------------- */
		}
		
		
		

       /*  if (!User::isAdmin()) {
            $where = "AND IF(products_new.user_id = '" . User::isUser() . "', 1, products_new.flag = 1 AND products_new.flag_moder = 1 AND products_new.flag_show = 1) ";
        } */

        if ($categ_id > 0 ) {
            $where .= "AND products_new.categ_id = $categ_id";
        }
        elseif ($sub_categ_id > 0) {
            $where .= "AND products_new.sub_categ_id = $sub_categ_id";
        }

        if ($producer_id > 0) {
            $where .= " AND products_new.producer_id = $producer_id";
        }
        elseif ($product_id > 0) {
			  
            $where .= " AND products_new.product_id = $product_id";
        }

        if ($user_id > 0) {
            $where .= " AND products_new.user_id = $user_id";
        }

        if ($is_updates > 0) {
            $where .= ' AND DATEDIFF(NOW(), products_new.date_add) > 13';
        }

        if (isset($flag)) {
            $where .= ' AND products_new.flag = ' . $flag;
        }

        if ($filter == 'stocks') {
            $where .= " AND s.flag > 0";
        }

        if ($page == 1) {
            $topOffers = self::getTopOffers(($categ_id > 0 or $sub_categ_id > 0) ? 'top_to_category' : 'top_to_section', $categ_id, $sub_categ_id);

            if (count($topOffers) > 0) {
                $topOrder = 'FIELD(products_new.product_new_id, ' . implode(',', $topOffers) . ') DESC, ';
            }
        }

        if ((string) $search != null) {
            $match = "AND MATCH(products_new.product_name, products_new.content) AGAINST('$search')";
            $orderr_by = '';
        }
        else {
            if ($filter == 'stocks') {
                $orderr_by = 'ORDER BY '. $topOrder .' s.date_add DESC';
            }
            else {
                $orderr_by = 'ORDER BY '. $topOrder .' date_add DESC';
            }
        }

        $date = DB::now(1);

        $products = "SELECT SQL_NO_CACHE DISTINCT products_new.product_new_id,
			products_new.categ_id,
			products_new.sub_categ_id,
			products_new.product_name,
			products_new.user_id,
			products_new.user_name,
			products_new.contact_phones,
			products_new.currency_name,
			products_new.currency_id,
			products_new.price,
			products_new.price_description,
			products_new.date_add,
			products_new.pay,
			IF(50 -DATEDIFF(  NOW( ) ,products_new.date_add)>0, 50 -DATEDIFF(  NOW( ) ,products_new.date_add),0 ) as time_show,
			users.group_id,
			products.description,
			IF(i.url_full != '', i.url_full, products.image) AS image,
			categories.name AS categ_name,
			s.flag AS stock_flag,
			s.date_end,
			s.price AS stock_price,
			s.currency_id AS stock_currency_id,
			products_new.flag,
			products_new.flag_moder,
			IF(l.resource_id, 1, 0) AS light_flag,
			flag_vip_add,
			flag_moder_view,
			products_new.flag_show,
			flag_vip_add,
			liq.color_yellow,
			liq.urgently,
			ads_calls.call_d
	
			FROM `products_new`
			LEFT JOIN liqpay_status liq ON products_new.product_new_id=liq.ads_id AND  liq.section_id=3
			INNER JOIN `categories`
			    ON categories.categ_id = products_new.sub_categ_id
			LEFT JOIN `products`
			    USING(product_id)
			LEFT JOIN `products_new_images` AS i
			    ON i.product_new_id = products_new.product_new_id AND i.sort_id = 0
			LEFT JOIN `stocks` AS s
			    ON s.product_new_id = products_new.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '$date' AND s.date_end > DATE_SUB('$date', INTERVAL 1 DAY)
			LEFT JOIN `light_content` AS l
			    ON l.section_id = 3 AND l.resource_id = products_new.product_new_id AND DATE_SUB(l.date_start, INTERVAL 1 DAY) < '$date' AND l.date_end > DATE_SUB('$date', INTERVAL 1 DAY)
				LEFT JOIN ads_calls ON  products_new.user_id= ads_calls.user_id AND section_call =3
				LEFT JOIN users  ON products_new.user_id  =users.user_id
				
			WHERE products_new.flag_delete = 0 $Admin  $where  $match
			$orderr_by
			LIMIT $limit, $count";
			
	    //  Site::d($products ,1);
        $products = DB::DBObject()->query($products);
        $products->execute();
        while ($pr = $products->fetch(PDO::FETCH_ASSOC)) {
          // $pr['phones'] = explode(',', preg_replace('/[^\d+ \,\-]/', '', $pr['contact_phones']));
			$pr['phones'] = explode(',', $pr['contact_phones']);
            $array[] = $pr;
        }
       return $array;
    }

    public static function getTopOffers($table, $categoryId = 0, $subCategoryId = 0) {
        $date = DB::now(1);

			  $query = 'SELECT p.product_new_id
            FROM `'. $table .'` AS t
            INNER JOIN `products_new` AS p ON p.product_new_id = t.resource_id ' .
            'WHERE section_id = 3 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > DATE_SUB("' . $date . '", INTERVAL 1 DAY)
            ORDER BY   p.date_add ,t.sort_id'; 
			
			

        $items = DB::getAssocGroup($query);

        return array_keys($items);
    }

    public static function getSubscribeStocks($categs, $date) {
        if (is_array($categs)) {
            $query = "SELECT
				products_new.product_new_id AS id,
				product_name AS name,
				products.description AS description,
				CONCAT('products/80x100/', IF(i.url_full != '', i.url_full, products.image)) AS image,
				products_new.date_add AS date
			FROM `products_new`
			LEFT JOIN `products` USING(product_id)
			LEFT JOIN `products_new_images` AS i  ON i.product_new_id = products_new.product_new_id AND i.sort_id = 0
			INNER JOIN `stocks` AS s ON s.product_new_id = products_new.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '" . DB::now(1) . "' AND s.date_end > '" . DB::now(1) . "'
			WHERE flag = 1 AND products_new.flag_moder = 1 AND flag_delete = 0 AND DATE(products_new.date_add) = '". $date ."'
				AND products_new.sub_categ_id IN(" . implode(',', $categs) . ")
			ORDER BY products_new.date_add DESC";

            return DB::getAssocArray($query);
        }
    }

    public static function getSubscribeItems($categs, $date) {
        if (is_array($categs)) {
            $query = "SELECT
				products_new.product_new_id AS id,
				product_name AS name,
				products.description AS description,
				CONCAT('products/80x100/', IF(i.url_full != '', i.url_full, products.image)) AS image,
				products_new.date_add AS date
			FROM `products_new`
			LEFT JOIN `products` USING(product_id)
			LEFT JOIN `products_new_images` AS i  ON i.product_new_id = products_new.product_new_id AND i.sort_id = 0
			WHERE flag = 1 AND products_new.flag_moder = 1 AND flag_delete = 0 AND DATE(products_new.date_add) = '". $date ."'
				AND products_new.sub_categ_id IN(" . implode(',', $categs) . ")
			ORDER BY products_new.date_add DESC";

           return DB::getAssocArray($query);
        }
    }

    public static function getProductGallery($product_new_id) {
        $images = "SELECT url_full, description FROM `products_new_images` WHERE product_new_id = $product_new_id AND sort_id != 0 ORDER BY sort_id";

        return DB::getAssocArray($images);
    }

    public static function getProroductNewUserInfo($product_new_id) {
        $date = DB::now(1);

        $query = "SELECT p.user_id, ui.name, ui.avatar, ui.contact_phones,
			p.product_new_id AS resource_id, p.product_name,
			IFNULL(s.price_description, p.price_description) AS price_description,
			IFNULL(s.price, p.price) AS price,
			p.currency_name
			FROM `products_new` AS p
			INNER JOIN `users_info` AS ui USING(user_id)
			INNER JOIN `products` USING(product_id)
			LEFT JOIN `stocks` AS s ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.date_start < '$date' AND s.date_end > '$date'
			WHERE p.product_new_id = $product_new_id";

        $array = DB::getAssocArray($query, 1);

        $array['contact_phones'] = explode(',', $array['contact_phones']);

        return $array;
    }

    public static function getProductsNewCount($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $country = 1, $user_id = 0, $filter = null, $is_updates = 0, $search = null, $flag = null,$nopay=0) {
        $date = DB::now(1);
		$UserID=User::isUser();
         $Admin='';
        if (!User::isAdmin()) {
            $where = "AND IF(products_new.user_id = '" . User::isUser() . "', 1, products_new.flag = 1 AND products_new.flag_moder = 1 AND products_new.flag_show = 1) ";
        }else{
			
			if($nopay){
				
			    $where = "AND IF(products_new.user_id = '" . $UserID . "', products_new.flag = 1 AND products_new.flag_moder = 1, products_new.flag = 1 AND products_new.flag_moder = 1)";
				
				$Admin=  "and(products_new.pay=0  or  products_new.pay IS NULL or  DATEDIFF(  NOW( ) ,products_new.date_add) >50 ) and users.group_id <>10  " ;	
				
			}
		
		}

        if ($categ_id > 0 ) {
            $where .= " AND categ_id = $categ_id";
        }
        elseif ($sub_categ_id > 0) {
            $where .= " AND sub_categ_id = $sub_categ_id";
        }

        if ($producer_id > 0) {
            $where .= " AND producer_id = $producer_id";
        }
        elseif ($product_id > 0) {
            $where .= " AND product_id = $product_id";
        }

        if ($user_id > 0) {
            $where .= " AND users.user_id = $user_id";
        }

        if ($is_updates > 0) {
            $where .= ' AND DATEDIFF(NOW(), date_add) > 13';
        }

        if (isset($flag)) {
            $where .= ' AND flag = ' . $flag;
        }

        if ($filter == 'stocks') {
            $where .= " AND product_new_id IN(SELECT product_new_id FROM `stocks` WHERE flag = 1 AND flag_moder = 1 AND date_start < '$date' AND date_end > '$date')";
        }

        if ((string) $search != null) {
            $match = "AND MATCH(products_new.product_name, products_new.content) AGAINST('$search')";
        }

        $count = "SELECT COUNT(*) FROM `products_new` 
		LEFT JOIN users  ON products_new.user_id  =users.user_id
		WHERE flag_delete = 0 $where $match  $Admin";
       
        return DB::getColumn($count);
    }

    public static function getProductFull($product_new_id) {
        $date = DB::now(1);
		
		if(User::isAdmin()){
			$Admin='';
		}else{
			$user_id= User::isUser();
			if(!$user_id)$user_id=0;
			
			$Admin='and ((products_new.pay=1 and  DATEDIFF(  NOW( ) ,products_new.date_add) <=50 )  or users.group_id =10  or  products_new.user_id='.$user_id.' )';
			$Admin='';
		}
		
		

        $product_new = "SELECT products_new.*,
			products.description,
			products_new.pay,
			products_new.user_id,
			products_new.product_new_id,
			users.group_id,
			IF(50 -DATEDIFF(  NOW( ) ,products_new.date_add)>0, 50 -DATEDIFF(  NOW( ) ,products_new.date_add),0 ) as time_show,
			IF(i.url_full != '', i.url_full, products.image) AS image,
			products_new.price AS price,
			s.price AS stock_price,
			s.currency_id AS stock_currency_id,
			IFNULL(s.price_description, products_new.price_description) AS price_description,
			products_new.content AS content,
			s.content AS stock_content,
			s.flag AS stock_flag,
			s.date_end,
			 DATE_ADD(s.date_end,INTERVAL 1 DAY)as action_end,
			s.date_add AS stock_date,
			categories.name AS categ_name,
			c.name AS parent_categ,
			ui.site,
			ui.icq,
			ui.skype,
			cities.name AS city,
			(SELECT COUNT(*) FROM `products_new_views` WHERE product_new_id = products_new.product_new_id) + views AS views,
			liq.urgently,
			liq.color_yellow
			FROM `products_new`
			LEFT JOIN liqpay_status as liq ON  products_new.product_new_id=liq.ads_id AND liq.section_id=3
			LEFT JOIN `products` USING(product_id)
			INNER JOIN `users_info` AS ui USING(user_id)
			INNER JOIN  users  ON  users.user_id =products_new.user_id 
			LEFT JOIN `cities` USING(city_id)
			LEFT JOIN `products_new_images` AS i  ON i.sort_id = 0 AND i.product_new_id = products_new.product_new_id
			INNER JOIN `categories` ON categories.categ_id = products_new.sub_categ_id
			INNER JOIN `categories` AS c ON c.categ_id = products_new.categ_id
			LEFT JOIN `stocks` AS s ON s.product_new_id = products_new.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '$date' AND s.date_end > '$date'
			WHERE products_new.product_new_id = $product_new_id";
       
        $product_new = DB::getAssocArray($product_new, 1);

        $product_new['phones'] 		= explode(',', preg_replace("/[^\d+ \,\-]/", '', $product_new['contact_phones']));
        $product_new['video_link']	= str_replace('watch?v=', '', end(explode('/',  $product_new['video_link'])));

      return $product_new;
    }

    public static function addProductNew($product_data, $images, $producer_new_name = null, $product_new_name = null, $product_new_description = null) {
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

            DB::insert('products_new', array(
                'product_id'		=> $product_id,
                'producer_id'		=> $producer_id,
                'categ_id'			=> $product_data['categ_id'],
                'sub_categ_id'		=> $product_data['sub_categ_id'],
                'product_name'		=> DB::getColumn("SELECT CONCAT((SELECT name FROM `producers` WHERE producer_id = products.producer_id), ' ', name) FROM `products` WHERE product_id = $product_id"),
                'user_id'			=> $product_data['user_id'],
                'user_name'			=> $product_data['user_name'],
                'contact_phones'	=> $product_data['contact_phones'],
                'currency_id'		=> $product_data['currency_id'],
                'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$product_data['currency_id']}"),
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

            $product_new_id = DB::lastInsertId();

            if (is_array($images)) {
                for ($i = 0, $c = count($images); $i < $c; $i++) {
                    DB::update('products_new_images', array(
                        'product_new_id'	=> $product_new_id,
                        'sort_id'			=> $i
                    ), array(
                        'product_new_id'	=> 0,
                        'image_id'			=> $images[$i]
                    ));
                }
            }

            return $product_new_id;
        }

        return false;
    }

    public static function getProductData($product_new_id) {
        $product = "SELECT p.product_new_id, p.product_id, p.producer_id, p.categ_id, p.sub_categ_id, p.currency_id, p.user_id,
			p.country_id, p.price, p.price_description, p.content, p.video_link, p.flag, p.flag_moder, p.flag_moder_view,
			p.contact_phones,
			s.currency_id AS stock_currency_id,
			s.price AS stock_price,
			s.price_description AS stock_price_description,
			s.content AS stock_content,
			s.date_start AS start_stock_date_range,
			s.date_end AS end_stock_date_range,
			s.flag AS stock_flag,
			s.flag_moder AS stock_flag_moder
			FROM `products_new` AS p
			LEFT JOIN `stocks` AS s USING(product_new_id)
			WHERE product_new_id = $product_new_id";

        return DB::getAssocArray($product, 1);
    }

    public static function getProductNewImages($product_new_id) {
        $images = "SELECT image_id, url_full, description
			FROM `products_new_images` WHERE product_new_id = $product_new_id ORDER BY sort_id";

        return DB::getAssocArray($images);
    }

    public static function editProductNew($product_new_id, $product_data, $images, $images_descr, $producer_new_name = null, $product_new_name = null, $product_new_description = null) {
        if ($product_new_id > 0 and is_array($product_data)) {
         
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

            $product_name = DB::getColumn("SELECT CONCAT(pr.name, ' ', p.name) FROM `products` AS p INNER JOIN `producers` AS pr USING(producer_id) WHERE product_id = {$product_id}");

            if (User::isAdmin()) {
                $write = array(
                    'product_id'		=> $product_id,
                    'producer_id'		=> $producer_id,
                    'categ_id'			=> $product_data['categ_id'],
                    'sub_categ_id'		=> $product_data['sub_categ_id'],
                    'product_name'		=> $product_name,
                    'currency_id'		=> $product_data['currency_id'],
                    'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$product_data['currency_id']}"),
                    'contact_phones'	=> $product_data['contact_phones'],
                    'price'				=> $product_data['price'],
                    'price_description'	=> $product_data['price_description'],
                    'content'			=> $product_data['content'],
                    'video_link'		=> $product_data['video_link'],
                    'flag_moder'		=> $product_data['flag_moder']
                );
            }
            else {
                $write = array(
                    'product_id'		=> $product_id,
                    'producer_id'		=> $producer_id,
                    'categ_id'			=> $product_data['categ_id'],
                    'sub_categ_id'		=> $product_data['sub_categ_id'],
                    'product_name'		=> $product_name,
                    'currency_id'		=> $product_data['currency_id'],
                    'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$product_data['currency_id']}"),
                    'contact_phones'	=> $product_data['contact_phones'],
                    'price'				=> $product_data['price'],
                    'price_description'	=> $product_data['price_description'],
                    'content'			=> $product_data['content'],
                    'video_link'		=> $product_data['video_link']
                );
            }

            DB::update('products_new', $write, array(
                'product_new_id' 	=> $product_new_id
            ));

            if (is_array($images)) {
                for ($i = 0, $c = count($images); $i < $c; $i++) {
                    DB::update('products_new_images', array(
                        'product_new_id' 	=> $product_new_id,
                        'description'		=> $images_descr[$images[$i]],
                        'sort_id'			=> $i
                    ), array(
                        'image_id' => $images[$i]
                    ));
                }
            }

            if(Site::isModerView('products_new', 'product_new_id', $product_new_id)) {
                Site::PublicLink(
                    'http://navistom.com/product/' .
                    $product_new_id . '-' . Str::get($product_name)->truncate(60)->translitURL()
                );
            }
        }
    }

    public static function addStock($product_new_id, $stock) {
        DB::insert('stocks', array(
            'product_new_id'	=> $product_new_id,
            'currency_id'		=> $stock['currency_id'],
            'country_id'		=> Request::get('country'),
            'price'				=> $stock['price'],
            'price_description'	=> $stock['price_description'],
            'content'			=> $stock['content'],
            'date_start'		=> $stock['date_start'],
            'date_end'			=> $stock['date_end'],
            'date_add'			=> DB::now()
        ), 1);

        return true;
    }

    public static function getStockData($product_new_id) {
        $query = "SELECT * FROM `stocks` WHERE product_new_id = $product_new_id";

        return DB::getAssocArray($query, 1);
    }

    public static function deleteStock($product_new_id) {
        DB::delete('stocks', array(
            'product_new_id'	=> $product_new_id
        ));

        return true;
    }

    public static function delete($product_new_id) {
        DB::update('products_new', array(
            'flag_delete'		=> 1
        ), array(
            'product_new_id'	=> $product_new_id
        ));

        DB::delete('stocks', array(
            'product_new_id'	=> $product_new_id
        ));

        return true;
    }

    public static function editFlag($product_new_id, $flag = 0) {
        DB::update('products_new', array(
            'flag'				=> $flag
        ), array(
            'product_new_id'	=> $product_new_id
        ));

        return true;
    }

    public static function editFlagModer($product_new_id, $flag_moder = 0) {
        DB::update('products_new', array(
            'flag_moder'	=> $flag_moder
        ), array(
            'product_new_id'		=> $product_new_id
        ));

        return true;
    }

    public static function transferToAds($product_new_id) {
        if ($product_new_id > 0) {
            /*
                Get product data
            */
            $data = "SELECT * FROM `products_new` WHERE product_new_id = $product_new_id";
            $data = DB::getAssocArray($data, 1);

            unset($data['product_new_id']);

            $images = "SELECT * FROM `products_new_images` WHERE product_new_id = $product_new_id";
            $images = DB::getAssocArray($images);

            $views = "SELECT * FROM `products_new_views` WHERE product_new_id = $product_new_id";
            $views = DB::getAssocArray($views);

            /*
                Insert new Ads
            */
            DB::insert('ads', $data);
            $ads_id = DB::lastInsertId();

            if ($ads_id > 0) {
                /*
                    Transfer images
                */
                for ($i = 0, $c = count($images); $i < $c; $i++) {
                    rename(UPLOADS . '/images/products/full/' 		. $images[$i]['url_full'], UPLOADS . '/images/offers/full/' 	. $images[$i]['url_full']);
                    rename(UPLOADS . '/images/products/160x200/' 	. $images[$i]['url_full'], UPLOADS . '/images/offers/160x200/' 	. $images[$i]['url_full']);
                    rename(UPLOADS . '/images/products/80x100/' 	. $images[$i]['url_full'], UPLOADS . '/images/offers/80x100/' 	. $images[$i]['url_full']);
                    rename(UPLOADS . '/images/products/64x80/' 		. $images[$i]['url_full'], UPLOADS . '/images/offers/64x80/' 	. $images[$i]['url_full']);
					@rename(UPLOADS . '/images/products/142x195/' 		. $images[$i]['url_full'], UPLOADS . '/images/offers/142x195/' 	. $images[$i]['url_full']);

                    DB::insert('ads_images', array(
                        'ads_id'		=> $ads_id,
                        'sort_id'		=> $images[$i]['sort_id'],
                        'description'	=> $images[$i]['description'],
                        'url_full'		=> $images[$i]['url_full']
                    ));
                }

                /*
                    Transfer views
                */

                for ($i = 0, $c = count($views); $i < $c; $i++) {
                    DB::insert('ads_views', array(
                        'ads_id'	=> $ads_id,
                        'user_id'	=> $views[$i]['user_id'],
                        'date_view'	=> $views[$i]['date_view']
                    ));
                }

                /*
                    Remove product data
                */

                DB::delete('products_new', array(
                    'product_new_id'	=> $product_new_id
                ));

                DB::delete('products_new_images', array(
                    'product_new_id'	=> $product_new_id
                ));

                DB::delete('products_new_views', array(
                    'product_new_id'	=> $product_new_id
                ));

                return $ads_id;
            }
            else {
                return false;
            }
        }
    }

    public static function setViews($product_new_id, $user_id = 0) {
        if (Request::getCookie('product_view_' . $product_new_id, 'int') > 0) {
            return true;
        }
        else {
            $write = array(
                'product_new_id'	=> $product_new_id,
                'user_id'			=> $user_id,
                'date_view'			=> DB::now()
            );

            DB::insert('products_new_views', $write);

            Request::setCookie('product_view_' . $product_new_id, 1);
        }

        return true;
    }

    public static function getUserId($product_new_id) {
        return DB::getColumn("SELECT user_id FROM `products_new` WHERE product_new_id = $product_new_id");
    }

    public static function getCategoriesFromSelect($parent_id = 0, $flag_min = 0, $flag_ads = 0) {
        if (is_array($parent_id)) {
            $query = "SELECT categ_id, name FROM `categories` WHERE parent_id IN(" . implode(',', $parent_id) . ") ORDER BY sort_id";

            return DB::getAssocKey($query);
        }

        if ($parent_id == 0) {
            $query = "SELECT categ_id, name, name_min,
				(SELECT COUNT(*) FROM `products_new` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND categ_id = c.categ_id) AS count
				FROM `categories` AS c
				WHERE parent_id = 0 AND flag_no_products = 0 ORDER BY sort_id";

            return DB::getAssocArray($query);
        }
        else {
            if ($flag_ads > 0) {
                $where = " AND flag_no_ads = 1";
            }

            if ($flag_min > 0) {
                $query = "SELECT categ_id, IF(name_min != '', name_min, name) AS name FROM `categories` WHERE parent_id = $parent_id $where ORDER BY sort_id";
            }
            else {
                $query = "SELECT categ_id, name FROM `categories` WHERE parent_id = $parent_id ORDER BY sort_id";
            }

            return DB::getAssocKey($query);
        }
    }

    public static function getCategoriesFromSubscribe($parent_id = 0, $ads = false) {
        $query = 'SELECT
          categ_id
          FROM `categories`
          WHERE
          parent_id '. (is_array($parent_id) ? 'IN('. implode(',', $parent_id) .')' : ('=' . $parent_id)) .' AND
          '. ($ads ? '1' : 'flag_no_products = 0');

        return DB::getAssocGroup($query);
    }

    public static function getCategoriesFromSelectOnly($parentId = 0, $flagStock = false, $hideZero = true, $userId = 0) {
        if ($flagStock) {
            $date = DB::now(1);
            $count = '(SELECT COUNT(*) FROM products_new WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND '. ($parentId > 0 ? 'sub_categ_id' : 'categ_id') .' = c.categ_id '. ($userId > 0 ? 'AND user_id = ' . $userId : '') .' AND
                        (SELECT stock_id FROM stocks WHERE product_new_id = products_new.product_new_id AND flag = 1 AND flag_moder = 1 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date .'") > 0) AS count';
        }
        else {
            $count = '(SELECT COUNT(*) FROM products_new WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND '. ($parentId > 0 ? 'sub_categ_id' : 'categ_id') .' = c.categ_id '. ($userId > 0 ? 'AND user_id = ' . $userId : '') .') AS count';
        }

        $query = 'SELECT categ_id, name, name_min,
                  '. $count .'
                  FROM
                    categories AS c
                  WHERE
                    parent_id = ' . $parentId . ' AND flag_no_products = 0
                    '. ($hideZero ? 'HAVING count > 0' : '') .'
                  ORDER BY sort_id';

        return DB::getAssocArray($query);
    }

    public static function getSubCategsList() {
        $query = "SELECT parent_id, categ_id, name
            FROM `categories`
            WHERE parent_id > 0 ORDER BY sort_id";

        return DB::getAssocGroup($query);
    }

    public static function getProducersFromSelectOnly($flagStock = false, $categoryId = 0, $subCategoryId = 0, $userId = 0) {
        if ($categoryId > 0) {
            $where = ' AND categ_id = ' . $categoryId;
        }

        if($subCategoryId > 0) {
            $where = ' AND sub_categ_id = ' . $subCategoryId;
        }

        if ( $userId > 0 ) $where .= ' AND user_id = ' . $userId;

        if ($flagStock) {
            $date = DB::now(1);
            $count = '(SELECT COUNT(*) FROM products_new WHERE p.producer_id = producer_id AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND
                (SELECT stock_id FROM stocks WHERE product_new_id = products_new.product_new_id AND flag = 1 AND flag_moder = 1 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date .'") > 0 '. $where .')';
        }
        else {
            $count = '(SELECT COUNT(*) FROM products_new WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND p.producer_id = producer_id '. $where .')';
        }

        $query = 'SELECT producer_id, name,
                    '. $count .' AS count
                    FROM producers AS p
                    WHERE flag_moder = 1
                    HAVING count > 0
                    ORDER BY sort_id, name';

        return DB::getAssocArray($query);
    }

    public static function getProductsFromSelectOnly($producerId, $flagStock = false, $categoryId = 0, $subCategoryId = 0, $userId = 0) {
        $where = '';

        if ($categoryId > 0) $where = 'AND categ_id = ' . $categoryId;

        if($subCategoryId > 0) $where = 'AND sub_categ_id = ' . $subCategoryId;

        if ( $userId > 0 ) $where .= ' AND user_id = ' . $userId;

        if ($flagStock) {
            $date = DB::now(1);
            $count = '(SELECT COUNT(*) FROM products_new WHERE p.product_id = product_id AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND
                (SELECT stock_id FROM stocks WHERE product_new_id = products_new.product_new_id AND flag = 1 AND flag_moder = 1 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date .'") > 0)';
        }
        else {
            $count = '(SELECT COUNT(*) FROM products_new WHERE p.product_id = product_id AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 '. $where .')';
        }

        $query = 'SELECT product_id, name,
                    '. $count .' AS count
                    FROM products AS p
                    WHERE producer_id = ' . $producerId . ' AND flag_moder = 1
                    HAVING count > 0
                    ORDER BY name';

        return DB::getAssocArray($query);
    }

    public function getProducersFromSelect($categ_id = 0, $sub_categ_id = 0) {
        if ($categ_id > 0 and $sub_categ_id == 0) {
            $where = "AND producer_id IN(SELECT producer_id FROM `products_new` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND categ_id = $categ_id)";
        }
        elseif ($categ_id > 0 and $sub_categ_id > 0) {
            $where = "AND producer_id IN(SELECT producer_id FROM `products_new` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND categ_id = $categ_id AND sub_categ_id = $sub_categ_id)";
        }
        elseif ($categ_id == 0 and $sub_categ_id > 0) {
            $where = "AND producer_id IN(SELECT producer_id FROM `products_new` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND sub_categ_id = $sub_categ_id)";
        }

        $query = "SELECT producer_id, name FROM `producers` WHERE 1 $where ORDER BY sort_id, name";

        return DB::getAssocArray($query);
    }

    public function getProducersListOrderByName() {
        $query = "SELECT producer_id, name,
			(SELECT COUNT(*) FROM `products_new` WHERE producer_id = producers.producer_id AND flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1) AS count
			FROM `producers` WHERE flag_moder = 1 HAVING count > 0 ORDER BY name";

        return DB::getAssocArray($query);
    }

    public function getSalespeople() {
        $query = "SELECT user_id, user_name, COUNT(*) AS count
			FROM `products_new`
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND user_name != ''
			GROUP BY user_id
			ORDER BY count DESC";

        return DB::getAssocArray($query);
    }

    public function getProductsFromSelect($producer_id) {
        $query = "SELECT product_id, name FROM `products` WHERE producer_id = $producer_id  ORDER BY name";

        return DB::getAssocKey($query);
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

    public function getCategoryMetaTags($categ_id) {
        $query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories` WHERE categ_id = $categ_id";

        return DB::getAssocArray($query, 1);
    }

    public function validationProducerName($name) {
        $producer = "SELECT producer_id FROM `producers` WHERE name = '$name'";

        return DB::getColumn($producer);
    }

    public function validationProductName($producer_id, $name) {
        $product = "SELECT product_id FROM `products` WHERE producer_id = $producer_id AND name = '$name'";

        return DB::getColumn($product);
    }

    public function getUserMessages($product_new_id, $user_id) {
        $messages = "SELECT m.message_id, m.message, m.status, m.date_add,
			u.name, u.avatar
			FROM `users_messages` AS m 
			INNER JOIN `users_info` AS u ON u.user_id = m.from_id
			WHERE m.section_id = 3 AND m.resource_id = $product_new_id AND (m.from_id = $user_id OR m.to_id = $user_id)
			ORDER BY m.date_add";

        return DB::getAssocArray($messages);
    }

    public function saveUserMessage($product_new_id, $from_id, $to_id, $message) {
        DB::insert('users_messages', array(
            'to_id'			=> $to_id,
            'from_id'		=> $from_id,
            'message'		=> $message,
            'section_id'	=> 3,
            'resource_id'	=> $product_new_id,
            'date_add'		=> DB::now()
        ));

        return DB::lastInsertId();
    }

    public function getCategoriesCount() {
        return DB::getColumn("SELECT COUNT(*) FROM `categories`");
    }

    public function getVIP($country_id, $sub_categ_id, $product_new_id) {
        $date = DB::now(1);
			
     
			
			 $query = "SELECT
				p.product_new_id, 
				p.product_name,
				categories.name AS categ_name,
				p.user_id,
				p.user_name,
				IF(i.url_full != '', i.url_full, products.image) AS image,
				s.flag AS stock_flag,
				s.date_end,
				s.price AS stock_price,
				s.currency_id AS stock_currency_id,
				p.currency_name,
				p.currency_id,
				p.price,
				p.price_description,
				p.date_add,
				products.description,
				t.color_yellow,
				t.urgently,
				
				/* (SELECT date_add from top_to_main WHERE section_id=3 AND resource_id = t.ads_id )as date_add  ,*/
				
				if((SELECT date_end from top_to_main WHERE section_id=3 AND resource_id = t.ads_id AND 1) > '$date' ,1,0 )as show_top

			FROM `liqpay_status` AS t
			INNER JOIN `products_new` AS p ON p.product_new_id = t.ads_id AND p.country_id = $country_id AND p.sub_categ_id = $sub_categ_id
			LEFT JOIN `products_new_images` AS i  ON i.product_new_id = p.product_new_id AND i.sort_id = 0
			LEFT JOIN `products` USING(product_id)
			INNER JOIN `categories`
			    ON categories.categ_id = p.sub_categ_id
			
			LEFT JOIN `stocks` AS s ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND s.date_end > '$date'
			WHERE t.section_id = 3 AND t.ads_id != $product_new_id AND DATE_SUB(t.start_competitor, INTERVAL 1 DAY) < '$date' AND t.end_competitor > '$date' AND t.show_competitor>2
			ORDER BY p.date_add  "; 
			
	   
			
			

        return DB::getAssocArray($query);
    }

    public function deleteProductImage($image_id) {
        $image = "SELECT url_full FROM `products_new_images` WHERE image_id = $image_id";
        $image = DB::getColumn($image);

        if ($image != null) {
            @unlink(UPLOADS . '/images/products/full/' . $image);
            @unlink(UPLOADS . '/images/products/160x200/' . $image);
            @unlink(UPLOADS . '/images/products/80x100/' . $image);
            @unlink(UPLOADS . '/images/products/64x80/' . $image);
			@unlink(UPLOADS . '/images/products/142x195/' . $image);
            DB::delete('products_new_images', array('image_id' => $image_id));

            return true;
        }

        return false;
    }

    public function uploadProductImage() {
        require_once(LIBS . 'AcImage/AcImage.php');

        $image_name = Str::get()->generate(20);

        $images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
                array(
                    'w'		=> 700,
                    'h'		=> 560,
                    'path'	=> UPLOADS . '/images/products/full/'
                ),
                array(
                    'w'		=> 200,
                    'h'		=> 160,
                    'path'	=> UPLOADS . '/images/products/160x200/'
                ),
                array(
                    'w'		=> 100,
                    'h'		=> 80,
                    'path'	=> UPLOADS . '/images/products/80x100/'
                ),
                array(
                    'w'		=> 80,
                    'h'		=> 64,
                    'path'	=> UPLOADS . '/images/products/64x80/'
                ),
				
				array(
                    'w'		=> 195,
                    'h'		=> 142,
                    'path'	=> UPLOADS . '/images/products/142x195/'
                ))
        );

        $write = array(
            'url_full'	=> $image_name . '.jpg'
        );

        DB::insert('products_new_images', $write);

        $image_id = DB::lastInsertId();

        $result = array(
            'uploadName' 	=> '/uploads/images/products/80x100/' . $image_name . '.jpg',
            'success'		=> true,
            'image_id'		=> $image_id
        );

        return $result;
    }
	
	public static function  removeProduct(){
		$query ="SELECT product_new_id FROM  products_new WHERE   flag_delete =1 Limit 100";
		$products= DB::getAssocArray($query);
		
	
		if(count($products)){
			array_map(function($product){
				static::removeImages($product['product_new_id']);
				DB::delete('products_new', array('product_new_id' => $product['product_new_id']));
			},$products);
			
			echo"<script>location.href='/products/remove?r=".rand(10,1000)."'</script>";
			
			
		}else{
			echo 'remove end';
		}
	}
	
	public  static function removeImages($product_new_id){
		
		$query ="SELECT url_full  FROM `products_new_images` WHERE product_new_id = $product_new_id";
		$images=DB::getAssocArray($query);
		 if(!count($images)) return 1;
	
		array_map(function($image){
			
			@unlink(UPLOADS . '/images/products/full/' . $image['url_full']);
            @unlink(UPLOADS . '/images/products/160x200/' . $image['url_full']);
            @unlink(UPLOADS . '/images/products/80x100/' . $image['url_full']);
            @unlink(UPLOADS . '/images/products/64x80/' . $image['url_full']);
			@unlink(UPLOADS . '/images/products/142x195/' . $image['url_full']);

            

		},$images);
		DB::delete('products_new_images', array('product_new_id' => $product_new_id)); 
	}
	
	public static function callsAdd($user_id, $call='1'){
		$date=DB::now(1);
		$write=array( 'user_id'=>$user_id ,'call_d'=>$call ,'date_call'=>$date ,'section_call'=>3 );

	    return DB::insert('ads_calls', $write );
		
	}
	
	
}