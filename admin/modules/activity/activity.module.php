<?php

class Activity {
	
	public function index($filter = null) {
		
		switch ($filter) {
			case 'moderation':
				$activity	= ModelActivity::getActivityList(1, 0, 0);
			break;
			case 'removed':
				$activity	= ModelActivity::getActivityList(1, 1, 1);
			break;
			default:
				$activity	= ModelActivity::getActivityList();
			break;
		}
		
		echo Registry::get('twig')->render('activity.tpl', array(
			'activity'	=> $activity,
			'title'		=> 'Все мероприятия'
		));
	}
	
	public function edit($activity_id) {
		$activity 	= ModelActivity::getActivityData($activity_id);
		$countries	= Registry::get('config')->countries_names;
		$regions	= ModelActivity::getRegionsFromSelect($activity['country_id']);
		$cities		= ModelActivity::getCitiesFromSelect($activity['region_id']);
		$categories	= ModelActivity::getCategoriesFromSelect();
		
		$activity['start_date_range'] 	= $activity['date_start'] != '0000-00-00' ? $activity['date_start'] : '';
		$activity['end_date_range'] 	= $activity['date_end'] != '0000-00-00' ? $activity['date_end'] : '';
		$activity['images'][0]			= '/uploads/images/activity/80x100/' . $activity['image'];
		
		$form = new Form();
		
		$form->createTab('activity-default', 'Основная информация');
		$form->createTab('activity-media', 'Фото / видео');
		$form->createTab('activity-d', 'Дополнительно');
		
		$form->create('text', 'name', 'Заголовок мероприятия', null, 'activity-default');
		
		$form->create('multiple', 'categ_id[]', 'Категории', $categories, 'activity-default');
		
		$form->create('daterange', 'date_range', 'Дата проведения', null, 'activity-default');
		$form->create('switch', 'flag_agreed', 'Дата проведения по согласованию', 1, 'activity-default');
		$form->create('textarea', 'content', 'Текст мероприятия', null, 'activity-default');
		
		$form->create('select', 'country_id', 'Страна проведения', $countries, 'activity-default');
		$form->create('select', 'region_id', 'Регион проведения', array(0 => 'Выберите регион') + $regions, 'activity-default');
		$form->create('select', 'city_id', 'Населенный пункт', array(0 => 'Выберите город') + $cities, 'activity-default');
		
		$form->create('switch', 'flag_moder', 'Доступно для просмотра', 1, 'activity-default');
		
		$form->create('text', 'link', 'Ссылка', null, 'activity-d');
		$form->create('file', 'attachment', 'Вложение', null, 'activity-d');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'activity-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'activity-media');
		
		$form->setValues($activity);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelActivity::editActivity($activity_id, array(
					'name'			=> Request::post('name', 'string'),
					'date_start'	=> Request::post('start_date_range', 'string'),
					'date_end'		=> Request::post('end_date_range', 'string'),
					'flag_agreed'	=> Request::post('flag_agreed', 'int'),
					'content'		=> Request::post('content'),
					'country_id'	=> Request::post('country_id', 'int'),
					'region_id'		=> Request::post('region_id', 'int'),
					'city_id'		=> Request::post('city_id', 'int'),
					'flag_moder'	=> Request::post('flag_moder', 'int'),
					'link'			=> Request::post('link', 'string'),
					'video_link'	=> Request::post('video_link', 'string')
				),
					Request::post('categ_id')
				);
			}
			
			$form->destroy(
				'/admin/activity', 
				'/admin/activity/edit-'.$activity_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Мероприятия - редактировать',
			'Мероприятия - редактировать'
		);
	}
	
	public function categories() {
		$categs = ModelActivity::getCategoriesList();
		
		echo Registry::get('twig')->render('activity-categories.tpl', array(
			'categories'	=> $categs,
			'title'			=> 'Мероприятия - рубрикатор'
		));
	}
	
	public function addCateg() {
		$form = new Form();
		
		$form->create('text', 'name', 'Название категории');
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				$categ_id = ModelActivity::addCategory(
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/activity/categories', 
				'/admin/activity/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Мероприятия - добавить категорию',
			'Мероприятия - добавить категорию'
		);
	}
	
	public function editCateg($categ_id) {
		$categ = ModelActivity::getCategData($categ_id);
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название категории');
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues($categ);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelActivity::editCategory(
					$categ_id,
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/activity/categories', 
				'/admin/activity/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Мероприятия - ркдактировать категорию',
			'Мероприятия - ркдактировать категорию'
		);
	}
	
	public function deleteCateg($categ_id) {
		ModelActivity::deleteCategory($categ_id);
		
		Header::Location('/admin/activity/categories');
	}
	
	public function sorted() {
		parse_str($_GET['data'], $sort);
		
		ModelActivity::sortedCategs($sort);
		
		return true;
	}
}