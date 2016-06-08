<?php

class ModelAds {
	public function getAdsList($filter = null) {
		switch ($filter) {
			case 'removed':
				$where = "flag_delete = 1";
			break;
			case 'moderation':
				$where = "flag_moder = 0 AND flag_delete = 0";
			break;
			default:
				$where = "flag_delete = 0";
			break;
		}
		
		$ads = "SELECT ads_id, product_name, user_id, user_name, date_add, flag, flag_moder, flag_delete
				FROM `ads` WHERE $where
				ORDER BY date_add DESC";
		
		return DB::getAssocArray($ads);
	}
	
	public function getAdsData($ads_id) {
		$ads = "SELECT * FROM `ads` WHERE ads_id = $ads_id";
		
		return DB::getAssocArray($ads, 1);
	}
	
	public function getAdsImages($ads_id) {
		$images = "SELECT image_id, CONCAT('/uploads/images/ads/80x100/', url_full) AS url_full, description
			FROM `ads_images` WHERE ads_id = $ads_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function editAds($ads_id, $ads_data, $images, $images_descr) {
		if ($ads_id > 0 and is_array($ads_data)) {
			DB::update('ads', array(
				'product_id'		=> $ads_data['product_id'],
				'producer_id'		=> $ads_data['producer_id'],
				'categ_id'			=> $ads_data['categ_id'],
				'sub_categ_id'		=> $ads_data['sub_categ_id'],
				'product_name'		=> DB::getColumn("SELECT CONCAT(pr.name, ' ', p.name) FROM `products` AS p INNER JOIN `producers` AS pr USING(producer_id) WHERE product_id = {$ads_data['product_id']}"),
				'currency_id'		=> $ads_data['currency_id'],
				'currency_name'		=> DB::getColumn("SELECT name_min FROM `currency` WHERE currency_id = {$ads_data['currency_id']}"),
				'price'				=> $ads_data['price'],
				'price_description'	=> $ads_data['price_description'],
				'content'			=> $ads_data['content'],
				'country_id'		=> $ads_data['country_id'],
				'video_link'		=> $ads_data['video_link'],
				'flag'				=> $ads_data['flag'],
				'flag_moder'		=> $ads_data['flag_moder']
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
			
			return true;
		}
		
		return false;
	}
	
	public function deleteAds($ads_id) {
		DB::update('ads', array(
			'flag_delete' => 1
		), array(
			'ads_id' => $ads_id
		));
		
		return true;
	}
	
	public function reestablish($ads_id) {
		DB::update('ads', array(
			'flag_delete' => 0
		), array(
			'ads_id' => $ads_id
		));
		
		return true;
	}
	
	public function getProducersFromSelect() {
		$query = "SELECT producer_id, name FROM `producers` WHERE flag_moder = 1 ORDER BY sort_id";
		
		return (array) DB::getAssocKey($query);
	}
	
	public function getCategoriesFromSelect($parent_id = 0) {
		$query = "SELECT categ_id, name FROM `categories` WHERE parent_id = $parent_id ORDER BY sort_id";
		
		return (array) DB::getAssocKey($query);
	}
	
	public function getProductsFromSelect($producer_id) {
		$query = "SELECT product_id, name FROM `products` WHERE producer_id = $producer_id";
		
		return DB::getAssocKey($query);
	}
	
	public function deleteImage($image_id) {
		$image = "SELECT url_full FROM `ads_images` WHERE image_id = $image_id";
		$image = DB::getColumn($image);
		
		if ($image != null) {
			unlink(UPLOADS . '/images/offers/full/' . $image);
			unlink(UPLOADS . '/images/offers/160x200/' . $image);
			unlink(UPLOADS . '/images/offers/80x100/' . $image);
			unlink(UPLOADS . '/images/offers/64x80/' . $image);
			
			DB::delete('ads_images', array('image_id' => $image_id));
			
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
			))
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
}