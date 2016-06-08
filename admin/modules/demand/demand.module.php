<?php

class Demand {
	
	public function index($filter = null) {
		$titles = array(
			null 			=> 'Спрос - все предложения',
			'moderation'	=> 'Спрос - предложения на модерации',
			'removed'		=> 'Спрос - корзина'
		);
		
		echo Registry::get('twig')->render('demand.tpl', array(
			'title'		=> $titles[$filter],
			'demand'	=> ModelDemand::getDemandList($filter)
		));
	}
	
	public function edit($demand_id) {
		$data 	= ModelDemand::getDemandData($demand_id);
		$images = ModelDemand::getDemandImages($demand_id);
		
		$form = new Form();
		
		$form->createTab('demand-default', 'Основная информация');
		$form->createTab('demand-media', 'Фото / видео');
		
		$form->create('text', 'name', 'Заголовок', null, 'demand-default');
		$form->create('textarea', 'content', 'Описание заявки', null, 'demand-default');
		
		$form->create('switch', 'flag', 'Заявка доступна к просмотру', 1, 'demand-default');
		$form->create('switch', 'flag_moder', 'Заявка одобрена модератором', 1, 'demand-default');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'demand-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'demand-media');
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			$data['images'][$images[$i]['image_id']] = $images[$i]['url_full'];
			$form->create('hidden', 'image_description[' . $images[$i]['image_id'] .']', '', $images[$i]['description']);
			$form->attr('image_description[' . $images[$i]['image_id'] . ']', 'id', 'descr_' . $images[$i]['image_id']);
		}
		
		$form->setValues($data);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				ModelDemand::edit($demand_id, array(
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
				'/admin/demand', 
				'/admin/demand/edit-' . $demand_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Спрос - редактировать заявку',
			'Спрос - редактировать заявку'
		);
	}
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelDemand::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelDemand::deleteImage($image_id)
		));
	}
}