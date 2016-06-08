<?php

class ModelProducts {
	public static function getProducts($filter = null, $limit = 10, $count = 10) {
		
		$date = DB::now(1);
		
		switch ($filter) {
			case 'stocks':
				$where = "AND s.flag > 0";
			break;
			case 'moderation':
				$where = "AND p.flag_moder = 0 AND flag_delete = 0";
			break;
			case 'removed':
				$where = "AND flag_delete = 1";
			break;
			default:
				$where = "AND flag_delete = 0";
			break;
		}
		
		$products = "SELECT p.product_new_id, p.product_name, p.user_name, p.user_id, p.flag, p.flag_moder, p.date_add, p.flag_delete,
			s.flag AS stock_flag 
			FROM `products_new` AS p
			LEFT JOIN `stocks` AS s ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.date_start < '$date' AND s.date_end > '$date'
			WHERE p.flag = 1 $where
			ORDER BY p.date_add DESC
			";
		
		return DB::getAssocArray($products);
	}
	
	public static function getProductData($product_new_id) {
		$product = "SELECT p.product_id, p.producer_id, p.categ_id, p.sub_categ_id, p.currency_id, 
			p.country_id, p.price, p.price_description, p.content, /* p.link, */  '' as link  , p.video_link, p.flag, p.flag_moder,
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
			 //Site::d($product);
		
		return DB::getAssocArray($product, 1);
	}
	
	public function deleteProductNew($product_new_id) {
		DB::update('products_new', array('flag_delete' => 1), array('product_new_id' => $product_new_id));
		
		return true;
	}
	
	public static function getProducerUser($producer_id) {
		$query = "SELECT user_id FROM `products_new` WHERE producer_id = $producer_id";
		
		$user_id = DB::getColumn($query);
		
		if ($user_id > 0) {
			return $user_id;
		}
		else {
			$query = "SELECT user_id FROM `ads` WHERE producer_id = $producer_id";
		
			$user_id = DB::getColumn($query);
			
			if ($user_id > 0) {
				return $user_id;
			}
		}
		
		return false;
	}
	
	public function getProductUser($product_id) {
		$query = "SELECT user_id FROM `products_new` WHERE product_id = $product_id";
		
		$user_id = DB::getColumn($query);
		
		if ($user_id > 0) {
			return $user_id;
		}
		else {
			$query = "SELECT user_id FROM `ads` WHERE product_id = $product_id";
		
			$user_id = DB::getColumn($query);
			
			if ($user_id > 0) {
				return $user_id;
			}
		}
		
		return false;
	}
	
	public function editProductNew($product_new_id, $product_data, $images, $images_descr, $stock) {
		if ($product_new_id > 0 and is_array($product_data)) {
			DB::update('products_new', array(
				'product_id'		=> $product_data['product_id'],
				'producer_id'		=> $product_data['producer_id'],
				'categ_id'			=> $product_data['categ_id'],
				'sub_categ_id'		=> $product_data['sub_categ_id'],
				'product_name'		=> DB::getColumn("SELECT CONCAT(pr.name, ' ', p.name) FROM `products` AS p INNER JOIN `producers` AS pr USING(producer_id) WHERE product_id = {$product_data['product_id']}"),
				'currency_id'		=> $product_data['currency_id'],
				'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$product_data['currency_id']}"),
				'price'				=> $product_data['price'],
				'price_description'	=> $product_data['price_description'],
				'content'			=> $product_data['content'],
				'link'				=> $product_data['link'],
				'video_link'		=> $product_data['video_link'],
				'flag'				=> $product_data['flag'],
				'flag_moder'		=> $product_data['flag_moder']
			), array(
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
			
			if (is_array($stock)) {
				DB::insert('stocks', array(
					'product_new_id'	=> $product_new_id,
					'currency_id'		=> $stock['currency_id'],
					'price'				=> $stock['price'],
					'price_description'	=> $stock['price_description'],
					'content'			=> $stock['content'],
					'date_start'		=> $stock['date_start'],
					'date_end'			=> $stock['date_end'],
					'flag'				=> $stock['flag'],
					'flag_moder'		=> $stock['flag_moder'],
					'date_add'			=> DB::now()
				), 1);
			}
		}
	}
	
	/**
	 * Get categories list for select
	 *
	 * @return array
	 */
	public static function getCategoriesFromSelect($parent_id = 0) {
		$query = "SELECT categ_id, name FROM `categories` WHERE parent_id = $parent_id ORDER BY sort_id";
		
		return (array) DB::getAssocKey($query);
	}
	
	public function getCategoriesList() {
		
		$array = array();
		
		function getCategories($parent_id = 0, &$array) {
			
			$query 	= "SELECT categ_id, parent_id, name, date_add, date_edit 
				FROM `categories`
				WHERE parent_id = $parent_id
				ORDER BY sort_id";
			
			$query = DB::DBObject()->prepare($query);
			$query->execute();
			
			while ($categ = $query->fetch(PDO::FETCH_OBJ)) {
				$array[] = array(
					'categ_id'	=> $categ->categ_id,
					'parent_id'	=> $categ->parent_id,
					'name'		=> $categ->name,
					'date_add'	=> $categ->date_add,
					'date_edit'	=> $categ->date_edit
				);
				
				if ($categ->parent_id == 0)
					getCategories($categ->categ_id, $array);
			}
		}
		
		getCategories(0, $array);
		
		return (array) $array;
	}
	
	public function getCategory($categ_id) {
		$query = "SELECT parent_id, name, title, meta_title, meta_description, meta_keys, flag_no_ads, flag_no_products FROM `categories` WHERE categ_id = $categ_id";
		
		return (array) DB::getAssocArray($query, 1);
	}
	
	public function addCategory($name, $parent_id = 0, $title = null, $meta_title = null, $meta_desc = null, $meta_keys = null, $flag_no_ads = 0, $flag_no_products = 0) {
		$write = array(
			'parent_id'			=> $parent_id,
			'name'				=> $name,
			'date_add'			=> DB::now(),
			'title'				=> $title,
			'meta_title'		=> $meta_title,
			'meta_description'	=> $meta_desc,
			'meta_keys'			=> $meta_keys,
			'flag_no_ads'		=> $flag_no_ads,
			'flag_no_products'	=> $flag_no_products
		);
		
		if (DB::insert('categories', $write)) {
			return DB::lastInsertId();
		}
		
		return false;
	}
	
	public function editCategory($categ_id, $write) {
		DB::update('categories', $write, array('categ_id' => $categ_id));
		
		return true;
	}
	
	public function addProducer($name, $description, $sort_id = 0, $flag_moder = 0) {
		$write = array(
			'sort_id'		=> $sort_id,
			'name'			=> $name,
			'description'	=> $description,
			'date_add'		=> DB::now(),
			'flag_moder'	=> $flag_moder
		);
		
		if (DB::insert('producers', $write))
			return DB::lastInsertId();
			
		return false;
	}
	
	public static function getProducersList() {
		$query = "SELECT producer_id, name, date_add, date_edit, flag_moder FROM `producers` ORDER BY flag_moder, sort_id, date_add";
		
		return DB::getAssocArray($query);
	}
	
	public static function getProducer($producer_id) {
		$query = "SELECT sort_id, name, description, flag_moder FROM `producers` WHERE producer_id = $producer_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function editProducer($producer_id, $write) {
		DB::update('producers', $write, array('producer_id' => $producer_id));
		
		return true;
	}
	
	public function deleteProducer($producer_id) {
		DB::delete('producers', array('producer_id' => $producer_id));
		
		return true;
	}
	
	public function deleteProducerProduct($product_id) {
		DB::delete('products', array('product_id' => $product_id));
		
		return true;
	}
	
	public function deleteCateg($categ_id) {
		DB::delete('categories', array('categ_id' => $categ_id));
		
		return true;
	}
	
	public static function getProducersFromSelect($flag_moder = 0) {
		if ($flag_moder > 0) {
			$where = " WHERE flag_moder = 1";
		}
		else {
			$where = "1";
			$where = "";
		}
		
		$query = "SELECT producer_id, name, flag_moder FROM `producers`  $where ORDER BY name";
		//return (array) DB::getAssocKey($query);
		return (array) DB::getAssocArray($query);
	}
	
	public static function getProductsFromSelect($producer_id, $flag_moder = 0) {
		$where="";
		if ($flag_moder > 0) {
			$where = " AND flag_moder = 1";
		}
		
		$query = "SELECT product_id, name FROM `products` WHERE producer_id = $producer_id $where ORDER BY name";
		
		
		
		return  DB::getAssocKey($query);
	}
	
	public function addProducerProduct($producer_id, $name, $description, $flag_moder = 0) {
		$write = array(
			'producer_id'	=> $producer_id,
			'name'			=> $name,
			'description'	=> $description,
			'date_add'		=> DB::now(),
			'flag_moder'	=> $flag_moder
		);
		
		if (DB::insert('products', $write))
			return DB::lastInsertId();
			
		return false;
	}

    public function saveCategoriesPositions($sort) {
        $query = "UPDATE `categories` SET sort_id = CASE ";

        for ($i = 0, $c = count($sort['category']); $i < $c; $i++) {
            $query .= " WHEN categ_id = " . (int)$sort['category'][$i] . " THEN $i ";
        }

        $query .= "ELSE sort_id END";

        DB::query($query);
    }
	
	public function getProducersProductsList() {
		$query = "SELECT products.product_id, products.name, products.date_add, products.date_edit, products.flag_moder, producers.name AS producer, products.flag_moder
			FROM `products` 
			LEFT JOIN `producers` USING(producer_id)
			ORDER BY products.flag_moder, products.date_add DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getProducerProduct($product_id) {
		$query = "SELECT producer_id, name, description, flag_moder FROM `products` WHERE product_id = $product_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function editProducerProduct($product_id, $write) {
		DB::update('products', $write, array('product_id' => $product_id));
		
		return true;
	}
	
	public static function getProductNewImages($product_new_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/products/80x100/', url_full) AS url_full, description
			FROM `products_new_images` WHERE product_new_id = $product_new_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `products_new_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/products/full/' . $image);
			unlink(UPLOADS . '/images/products/160x200/' . $image);
			unlink(UPLOADS . '/images/products/80x100/' . $image);
			unlink(UPLOADS . '/images/products/64x80/' . $image);
			
			DB::delete('products_new_images', array('image_id' => $image_id));
			
			return true;
		}
		
		return false;
	}
	
	public function uploadImages() {
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
}