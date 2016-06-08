<?php

class Realty {
	public function index($filter = null) {
		$titles = array(
			null 			=> 'Недвижимость - все предложения',
			'moderation'	=> 'Недвижимость - предложения на модерации',
			'removed'		=> 'Недвижимость - корзина'
		);
		
		echo Registry::get('twig')->render('realty.tpl', array(
			'title'		=> $titles[$filter],
			'realty'	=> ModelRealty::getRealtyList($filter)
		));
	}
	
	public function edit($realty_id) {
		$data = ModelRealty::getRealtyData($realty_id);
		$images	= ModelRealty::getRealtyImages($realty_id);
		
		$form = new Form();
		
		$form->createTab('realty-default', 'Основная информация');
		$form->createTab('realty-media', 'Фото / видео');
		
		$form->create('text', 'name', 'Заголовок', null, 'realty-default');
		
		$form->create('select', 'categ_id', 'Рубрика', ModelRealty::getCategoriesFromSelect(), 'realty-default');
		
		$form->create('select', 'region_id', 'Регион', array(0 => 'Выберите регион') + Site::getRegionsFromSelect($data['country_id']), 'realty-default');
		$form->create('select', 'city_id', 'Населенный пункт', array(0 => 'Выберите город') + Site::getCitiesFromSelect($data['region_id']), 'realty-default');
		$form->create('text', 'address', 'Адрес', null, 'realty-default');
		
		$form->create('text', 'price', 'Стоимость', null, 'realty-default');
		$form->create('select', 'currency_id', 'Валюта', array(1 => 'Гривен'), 'realty-default');
		$form->create('text', 'price_description', 'Описание цены', null, 'realty-default');
		
		$form->create('textarea', 'content', 'Описание предоставляемых услуг', null, 'realty-default');
		
		$form->create('switch', 'flag', 'Предложение доступно к просмотру', 1, 'realty-default');
		$form->create('switch', 'flag_moder', 'Предложение одобрено модератором', 1, 'realty-default');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'realty-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'realty-media');
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			$data['images'][$images[$i]['image_id']] = $images[$i]['url_full'];
			$form->create('hidden', 'image_description[' . $images[$i]['image_id'] .']', '', $images[$i]['description']);
			$form->attr('image_description[' . $images[$i]['image_id'] . ']', 'id', 'descr_' . $images[$i]['image_id']);
		}
		
		$form->setValues($data);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelRealty::editRealty($realty_id, array(
					'categ_id'			=> Request::post('categ_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'price_description'	=> Request::post('price_description', 'string'),
					'name'				=> Request::post('name', 'string'),
					'address'			=> Request::post('address', 'string'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag'				=> Request::post('flag', 'int'),
					'flag_moder'		=> Request::post('flag_moder', 'int')
				), 
					Request::post('images'), 
					Request::post('image_description')
				);
			}
			
			$form->destroy(
				'/admin/realty', 
				'/admin/realty/edit-' . $realty_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Недвижимость - редактировать предложение',
			'Недвижимость - редактировать предложение'
		);
	}
	
	public function categories() {
		echo Registry::get('twig')->render('realty-categories.tpl', array(
			'title'			=> 'Недвижимость - все категории',
			'categories'	=> ModelRealty::getCategoriesList()
		));
	}
	
	public function categoryAdd() {
		$form = new Form();
		
		$form->create('text', 'name', 'Название категории');
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				$categ_id = ModelRealty::addCategory(
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/realty/categories', 
				'/admin/realty/category/edit-' . $categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Недвижимость - добавить категорию',
			'Недвижимость - добавить категорию'
		);
	}
	
	public function categoryEdit($categ_id) {
		$form = new Form();
		
		$form->create('text', 'name', 'Название категории');
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues(
			ModelRealty::getCategoryData($categ_id)
		);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelRealty::editCategory(
					$categ_id,
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/realty/categories', 
				'/admin/realty/category/edit-' . $categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Недвижимость - редактировать категорию',
			'Недвижимость - редактировать категорию'
		);
	}
	
	public function categoryDelete($categ_id) {
		ModelRealty::deleteCategory($categ_id);
		
		Header::Location('/admin/realty/categories');
	}
	
	public function categorySorted() {
		parse_str($_GET['data'], $sort);
		
		ModelRealty::categorySorted($sort);
		
		return true;
	}
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelRealty::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelRealty::deleteImage($image_id)
		));
	}
}