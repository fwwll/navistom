<?php

class Services {
	
	public function index($filter = null) {
		$titles = array(
			null => 'Сервис - все предложения',
			'moderation'	=> 'Сервис - предложения на модерации',
			'removed'		=> 'Сервис - корзина'
		);
		
		echo Registry::get('twig')->render('services.tpl', array(
			'title'		=> $titles[$filter],
			'services'	=> ModelServices::getServicesList($filter)
		));
	}
	
	public function edit($service_id) {
		$data 	= ModelServices::getServiceData($service_id);
		$images	= ModelServices::getServiceImages($service_id);
		
		$data['categ_id[]'] = explode(',', $data['categ_id']);
		
		$form = new Form();
		
		$form->createTab('service-default', 'Основная информация');
		$form->createTab('service-media', 'Фото / видео');
		
		$form->create('text', 'user_name', 'Название сервисного центра', null, 'service-default');
		$form->create('text', 'address', 'Адрес', null, 'service-default');
		$form->create('multiple', 'categ_id[]', 'Виды работ', ModelServices::getCategoriesFromSelect(), 'service-default');
		$form->create('select', 'region_id', 'Регион', Site::getRegionsFromSelect($data['country_id']), 'service-default');
		$form->create('select', 'city_id', 'Город', Site::getCitiesFromSelect($data['region_id']), 'service-default');
		
		$form->create('text', 'name', 'Заголовок', null, 'service-default');
		$form->create('textarea', 'content', 'Описание предоставляемых услуг', null, 'service-default');
		
		$form->create('switch', 'flag', 'Услуга доступна к просмотру', 1, 'service-default');
		$form->create('switch', 'flag_moder', 'Услуга одобрена модератором', 1, 'service-default');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'service-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'service-media');
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			$data['images'][$images[$i]['image_id']] = $images[$i]['url_full'];
			$form->create('hidden', 'image_description[' . $images[$i]['image_id'] .']', '', $images[$i]['description']);
			$form->attr('image_description[' . $images[$i]['image_id'] . ']', 'id', 'descr_' . $images[$i]['image_id']);
		}
		
		$form->setValues($data);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelServices::edit($service_id, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'address'			=> Request::post('address', 'string'),
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag_moder'		=> Request::post('flag_moder', 'int'),
					'flag'				=> Request::post('flag', 'int')
				), 
					Request::post('categ_id'), 
					Request::post('images'), 
					Request::post('image_description')
				);
			}
			
			$form->destroy(
				'/admin/services', 
				'/admin/service/edit-' . $service_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Сервис - редактировать предложение',
			'Сервис - редактировать предложение'
		);
	}
	
	public function categories() {
		echo Registry::get('twig')->render('services-categories.tpl', array(
			'title'			=> 'Сервис - все рубрики',
			'categories'	=> ModelServices::getCategoriesList()
		));
	}
	
	public function categoryAdd() {
		$form = new Form();
		
		$form->create('text', 'name', 'Название рубрики');
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				$categ_id = ModelServices::addCategory(
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/services/categories', 
				'/admin/services/category/edit-' . $categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Сервис - добавить рубрику',
			'Сервис - добавить рубрику'
		);
	}
	
	public function categoryEdit($categ_id) {
		$form = new Form();
		
		$form->create('text', 'name', 'Название рубрики');
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues(
			ModelServices::getCategoryData($categ_id)
		);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelServices::editCategory(
					$categ_id,
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/services/categories', 
				'/admin/services/category/edit-' . $categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Сервис - редактировать рубрику',
			'Сервис - редактировать рубрику'
		);
	}
	
	public function categoryDelete($categ_id) {
		ModelServices::deleteCategory($categ_id);
		
		Header::Location('/admin/services/categories');
	}
	
	public function categorySorted() {
		parse_str($_GET['data'], $sort);
		
		ModelServices::categorySorted($sort);
		
		return true;
	}
}