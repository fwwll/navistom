<?php

class Ads {
	public function index($filter = null) {
		echo Registry::get('twig')->render('ads.tpl', array(
			'title' 	=> 'Товары Б/У',
			'ads'		=>  ModelAds::getAdsList($filter)
		));
	}
	
	public function edit($ads_id) {
		$ads		= ModelAds::getAdsData($ads_id);
		$categs 	= ModelAds::getCategoriesFromSelect();
		$sub_categs	= ModelAds::getCategoriesFromSelect($ads['categ_id']);
		$producers	= ModelAds::getProducersFromSelect();
		$products	= ModelAds::getProductsFromSelect($ads['producer_id']);
		$countries	= ModelUsers::getCountriesListFromSelect();
		
		$images		= ModelAds::getAdsImages($ads_id);
		
		$form = new Form();
		
		$form->createTab('p-default', 'Основная информация');
		$form->createTab('p-additionally', 'Дополнительно');
		$form->createTab('p-media', 'Фото / видео');
		
		$form->create('select', 'categ_id', 'Рубрика', $categs, 'p-default');
		$form->create('select', 'sub_categ_id', 'Раздел', $sub_categs, 'p-default');
		$form->create('select', 'producer_id', 'Производитель', $producers, 'p-default');
		$form->create('select', 'product_id', 'Товар', $products, 'p-default');
		
		$form->create('text', 'price', 'Стоимость', null, 'p-default');
		$form->create('select', 'currency_id', 'Валюта', array(1 => 'Гривен'), 'p-default');
		$form->create('text', 'price_description', 'Описание цены', null, 'p-default');
		
		$form->create('select', 'country_id', 'Страна', $countries, 'p-default');
		
		$form->create('switch', 'flag', 'Товар доступен к просмотру', 1, 'p-default');
		$form->create('switch', 'flag_moder', 'Товар одобрен модератором', 1, 'p-default');
		
		$form->create('textarea', 'content', 'Описание товара', null, 'p-additionally');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'p-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'p-media');
		
		$form->create('text', 'stock_price', 'Акционная цена', null, 'p-stock');
		$form->create('select', 'stock_currency_id', 'Валюта', array(1 => 'Гривен'), 'p-stock');
		$form->create('text', 'stock_price_description', 'Описание цены', null, 'p-stock');
		$form->create('textarea', 'stock_content', 'Описание акции', null, 'p-stock');
		$form->create('daterange', 'stock_date_range', 'Период действия акции', null, 'p-stock');
		
		$form->create('switch', 'stock_flag', 'Акция доступна к просмотру', 1, 'p-stock');
		$form->create('switch', 'stock_flag_moder', 'Акция одобрена модератором', 1, 'p-stock');
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			$ads['images'][$images[$i]['image_id']] = $images[$i]['url_full'];
			$form->create('hidden', 'image_description[' . $images[$i]['image_id'] .']', '', $images[$i]['description']);
			$form->attr('image_description[' . $images[$i]['image_id'] . ']', 'id', 'descr_' . $images[$i]['image_id']);
		}
		
		$form->setValues($ads);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelAds::editAds($ads_id, array(
					'product_id'		=> Request::post('product_id', 'int'),
					'producer_id'		=> Request::post('producer_id', 'int'),
					'categ_id'			=> Request::post('categ_id', 'int'),
					'sub_categ_id'		=> Request::post('sub_categ_id', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'country_id'		=> Request::post('country_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'price_description'	=> Request::post('price_description', 'string'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag'				=> Request::post('flag', 'int'),
					'flag_moder'		=> Request::post('flag_moder', 'int')
				), 
					Request::post('images'), Request::post('image_description')
				);
			}
			
			$form->destroy(
				'/admin/ads', 
				'/admin/ads/edit-'.$ads_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать товар',
			'Редактировать товар'
		);
	}
	
	public function delete($ads_id) {
		ModelAds::deleteAds($ads_id);
		
		Header::Location('/admin/ads');
	}
	
	public function reestablish($ads_id) {
		ModelAds::reestablish($ads_id);
		
		Header::Location('/admin/ads');
	}
	
	public function uploadImage() {
		header("Content-Type: text/plain");
		
		$result = ModelAds::uploadImages();
		
		echo json_encode($result);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		if (ModelAds::deleteImage($image_id)) {
			$result = array(
				'success' => true
			);
		}
		else {
			$result = array(
				'success' => false
			);
		}
		
		echo json_encode($result);
	}
}