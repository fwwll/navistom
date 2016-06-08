<?php

class Diagnostic {
	
	public function index($filter = null) {
		$titles = array(
			null 			=> 'Диагностика - все предложения',
			'moderation'	=> 'Диагностика - предложения на модерации',
			'removed'		=> 'Диагностика - корзина'
		);
		
		echo Registry::get('twig')->render('diagnostic.tpl', array(
			'title'			=> $titles[$filter],
			'diagnostic'	=> ModelDiagnostic::getDiagnosticList($filter)
		));
	}
	
	public function edit($diagnostic_id) {
		$data 	= ModelDiagnostic::getDiagnosticData($diagnostic_id);
		$images = ModelDiagnostic::getDiagnosticImages($diagnostic_id);
		
		$form = new Form();
		
		$form->createTab('diagnostic-default', 'Основная информация');
		$form->createTab('diagnostic-media', 'Фото / видео');
		
		$form->create('text', 'user_name', 'Название диагностического центра', null, 'diagnostic-default');
		$form->create('text', 'address', 'Адрес', null, 'diagnostic-default');
		$form->create('select', 'region_id', 'Регион', Site::getRegionsFromSelect($data['country_id']), 'diagnostic-default');
		$form->create('select', 'city_id', 'Город', Site::getCitiesFromSelect($data['region_id']), 'diagnostic-default');
		
		$form->create('text', 'name', 'Заголовок', null, 'diagnostic-default');
		$form->create('textarea', 'content', 'Описание предоставляемых услуг', null, 'diagnostic-default');
		
		$form->create('switch', 'flag', 'Услуга доступна к просмотру', 1, 'diagnostic-default');
		$form->create('switch', 'flag_moder', 'Услуга одобрена модератором', 1, 'diagnostic-default');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'diagnostic-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'diagnostic-media');
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			$data['images'][$images[$i]['image_id']] = $images[$i]['url_full'];
			$form->create('hidden', 'image_description[' . $images[$i]['image_id'] .']', '', $images[$i]['description']);
			$form->attr('image_description[' . $images[$i]['image_id'] . ']', 'id', 'descr_' . $images[$i]['image_id']);
		}
		
		$form->setValues($data);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelDiagnostic::edit($diagnostic_id, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'city_id'			=> Request::post('city_id', 'int'),
					'address'			=> Request::post('address', 'string'),
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag_moder'		=> Request::post('flag_moder', 'int'),
					'flag'				=> Request::post('flag', 'int')
				), 
					Request::post('images'), 
					Request::post('image_description')
				);
			}
			
			$form->destroy(
				'/admin/diagnostic', 
				'/admin/diagnostic/edit-' . $diagnostic_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Диагностика - редактировать предложение',
			'Диагностика - редактировать предложение'
		);
	}
	
	public function statistic() {
		echo Registry::get('twig')->render('diagnostic-statistic.tpl', array(
			'section_views'	=> Statistic::getSectionViews(10),
			'count'			=> DB::getTableCount('diagnostic'),
			'content_views'	=> DB::getTableCount('diagnostic_views'),
			'top_week'		=> ModelDiagnostic::getStatisticWeek()
		));
	}
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelDiagnostic::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelDiagnostic::deleteImage($image_id)
		));
	}
}