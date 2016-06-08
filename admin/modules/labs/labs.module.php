<?php

class Labs {
	public function index($filter = null) {
		$titles = array(
			null 			=> 'З/Т лаборатории - все предложения',
			'moderation'	=> 'З/Т лаборатории - предложения на модерации',
			'removed'		=> 'З/Т лаборатории - корзина'
		);
		
		echo Registry::get('twig')->render('labs.tpl', array(
			'title'	=> $titles[$filter],
			'labs'	=> ModelLabs::getLabsList($filter)
		));
	}
	
	public function edit($lab_id) {
		$data 	= ModelLabs::getLabData($lab_id);
		$images	= ModelLabs::getLabImages($lab_id);
		
		$data['categ_id[]'] = explode(',', $data['categ_id']);
		
		
		$form = new Form();
		
		$form->createTab('lab-default', 'Основная информация');
		$form->createTab('lab-media', 'Фото / видео');
		
		$form->create('text', 'user_name', 'Название лаборатории', null, 'lab-default');
		$form->create('text', 'address', 'Адрес', null, 'lab-default');
		$form->create('multiple', 'categ_id[]', 'Виды работ', ModelLabs::getCategoriesFromSelect(), 'lab-default');
		$form->create('select', 'region_id', 'Регион предоставления услуг', Site::getRegionsFromSelect($data['country_id']), 'lab-default');
		$form->create('textarea', 'content', 'Описание предоставляемых услуг', null, 'lab-default');
		
		$form->create('switch', 'flag', 'Услуга доступна к просмотру', 1, 'lab-default');
		$form->create('switch', 'flag_moder', 'Услуга одобрена модератором', 1, 'lab-default');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'lab-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'lab-media');
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			$data['images'][$images[$i]['image_id']] = $images[$i]['url_full'];
			$form->create('hidden', 'image_description[' . $images[$i]['image_id'] .']', '', $images[$i]['description']);
			$form->attr('image_description[' . $images[$i]['image_id'] . ']', 'id', 'descr_' . $images[$i]['image_id']);
		}
		
		$form->setValues($data);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelLabs::edit($lab_id, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'region_id'			=> Request::post('region_id', 'int'),
					'address'			=> Request::post('address', 'string'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag_moder'		=> Request::post('flag_moder', 'int')
				), 
					Request::post('categ_id'), 
					Request::post('images'), 
					Request::post('image_description')
				);
			}
			
			$form->destroy(
				'/admin/labs', 
				'/admin/lab/edit-' . $lab_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'З/Т Лаб. - редактировать услугу',
			'З/Т Лаб. - редактировать услугу'
		);
	}
	
	public function categories() {
		echo Registry::get('twig')->render('labs-categories.tpl', array(
			'title'			=> 'З/Т Лаб. - все категории',
			'categories'	=> ModelLabs::getCategoriesList()
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
				$categ_id = ModelLabs::addCategory(
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/labs/categories', 
				'/admin/labs/category/edit-' . $categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'З/Т Лаб. - добавить категорию',
			'З/Т Лаб. - добавить категорию'
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
			ModelLabs::getCategoryData($categ_id)
		);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelLabs::editCategory(
					$categ_id,
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/labs/categories', 
				'/admin/labs/category/edit-' . $categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'З/Т Лаб. - редактировать категорию',
			'З/Т Лаб. - редактировать категорию'
		);
	}
	
	public function categoryDelete($categ_id) {
		ModelLabs::deleteCategory($categ_id);
		
		Header::Location('/admin/labs/categories');
	}
	
	public function categorySorted() {
		parse_str($_GET['data'], $sort);
		
		ModelLabs::categorySorted($sort);
		
		return true;
	}
	
	public function jobs() {
		echo Registry::get('twig')->render('labs-jobs.tpl', array(
			'title'	=> 'Все виды работ',
			'jobs'	=> ModelLabs::getJobsList()
		));
	}
	
	public function jobAdd() {
		$form = new Form();
		
		$form->create('text', 'name', 'Название вида работ');
		
		$form->create('switch', 'flag_moder', 'Доступнно на сайте', 1);
		
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues(array(
			'flag_moder' => 1
		));
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				$job_id = ModelLabs::addJob(
					Request::post('name', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string'),
					Request::post('flag_moder', 'int')
				);
			}
			
			$form->destroy(
				'/admin/labs/jobs', 
				'/admin/labs/job/edit-' . $job_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'З/Т Лаб. - добавить вид работы',
			'З/Т Лаб. - добавить вид работы'
		);
	}
	
	public function jobEdit($job_id) {
		$form = new Form();
		
		$form->create('text', 'name', 'Название вида работ');
		
		$form->create('switch', 'flag_moder', 'Доступнно на сайте', 1);
		
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues(
			ModelLabs::getJobData($job_id)
		);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelLabs::editJob(
					$job_id,
					Request::post('name', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string'),
					Request::post('flag_moder', 'int')
				);
			}
			
			$form->destroy(
				'/admin/labs/jobs', 
				'/admin/labs/job/edit-' . $job_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'З/Т Лаб. - редактировать вид работы',
			'З/Т Лаб. - редактировать вид работы'
		);
	}
	
	public function jobDelete($job_id) {
		ModelLabs::deleteJob($job_id);
		
		Header::Location('/admin/labs/jobs');
	}
	
	public function jobSorted() {
		parse_str($_GET['data'], $sort);
		
		ModelLabs::jobSorted($sort);
		
		return true;
	}
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelLabs::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelLabs::deleteImage($image_id)
		));
	}
}