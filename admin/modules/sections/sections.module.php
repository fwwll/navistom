<?php

class sections {
	public function index() {
		$data = DB::getAssocArray("SELECT section_id, name, name_sys, flag, sort_id, date_add, date_edit FROM `sections` ORDER BY sort_id");
		
		echo Registry::get('twig')->render('sections.tpl', array(
			'title' => 'Разделы сайта',
			'table'	=> array(
				'title'		=> 'Список всех разделов сайта',
				'data'		=> $data
			)
		));
	}
	
	public function add() {
		$form = new Form();
		
		$form->createTab('section-default', 'Основная информация');
		$form->createTab('section-meta', 'Meta - теги');
		$form->createTab('section-options', 'Дополнительно');
		
		$form->create('text', 'name', 'Название раздела на сайте', null, 'section-default');
		//$form->create('text', 'name_sys', 'Имя модуля', null, 'section-default');
		//$form->create('text', 'alt_name', 'Системное имя', null, 'section-default');
		//$form->create('text', 'link', 'Ссылка на раздел', null, 'section-default');
		//$form->create('text', 'controller', 'Контроллер', null, 'section-default');
		$form->create('spinner', 'sort_id', 'Порядок сортировки', null, 'section-default');
		$form->create('switch', 'flag', 'Отображать на сайте', 1, 'section-default');
		
		$form->create('text', 'title', 'Заголовок H1', null, 'section-meta');
		$form->create('textarea', 'meta_title', 'Meta title', null, 'section-meta');
		$form->create('textarea', 'meta_description', 'Meta description', null, 'section-meta');
		$form->create('textarea', 'meta_keys', 'Meta keywords', null, 'section-meta');
		
		//$form->create('radiobuttons', 'target', 'Открывать раздел в', array('_self' => 'Текущем окне', '_blank' => 'Новом окне'), 'section-options');
		//$form->create('text', 'class', 'Класс элемента', null, 'section-options');
		//$form->create('text', 'icon', 'Иконка (HTML)', null, 'section-options');
		//$form->create('text', 'active_class', 'Класс активной ссылки', null, 'section-options');
		
		$form->setValues(array(
			'sort_id' 	=> 9999,
			'flag'		=> 1,
			'target'	=> '_self'
		));
		
		$form->required('name');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				$write = array(
					'name' 				=> Request::post('name', 'string'),
					'name_sys' 			=> Request::post('name_sys', 'string'),
					'alt_name'			=> Request::post('alt_name', 'translitURL') != null ? Request::post('alt_name', 'translitURL') : Request::post('name', 'translitURL'),
					'link'				=> Request::post('link', 'string'),
					'controller'		=> Request::post('controller', 'string'),
					'sort_id'			=> Request::post('sort_id', 'int'),
					'title'				=> Request::post('title', 'string'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string'),
					'target'			=> Request::post('target', 'string'),
					'class'				=> Request::post('class', 'string'),
					'active_class'		=> Request::post('active_class', 'string'),
					'icon'				=> Request::post('icon'),
					'flag'				=> Request::post('flag', 'int'),
					'date_add'			=> DB::now()
				);
				
				if (DB::insert('sections', $write)) {
					Debug::setStatus('form', 'Раздел успешно сохранен');
					
					$form->destroy(
						'/admin/sections', 
						'/admin/section/edit-'.DB::lastInsertId()
					);
				}
				else {
					Debug::setStatus('form', 'Произойшла ошибка при сохранении');
				}
			}
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить новый раздел',
			'Добавить раздел в меню'
		);
	}
	
	public function edit($id) {
		$form = new Form();
		
		$form->createTab('section-default', 'Основная информация');
		$form->createTab('section-meta', 'Meta - теги');
		//$form->createTab('section-options', 'Дополнительно');
		
		$form->create('text', 'name', 'Название раздела на сайте', null, 'section-default');
		//$form->create('text', 'name_sys', 'Имя модуля', null, 'section-default');
		//$form->create('text', 'alt_name', 'Системное имя', null, 'section-default');
		//$form->create('text', 'link', 'Ссылка на раздел', null, 'section-default');
		//$form->create('text', 'controller', 'Контроллер', null, 'section-default');
		$form->create('spinner', 'sort_id', 'Порядок сортировки', null, 'section-default');
		$form->create('switch', 'flag', 'Отображать на сайте', 1, 'section-default');
		
		$form->create('text', 'title', 'Заголовок H1', null, 'section-meta');
		$form->create('textarea', 'meta_title', 'Meta title', null, 'section-meta');
		$form->create('textarea', 'meta_description', 'Meta description', null, 'section-meta');
		$form->create('textarea', 'meta_keys', 'Meta keywords', null, 'section-meta');
		
		//$form->create('radiobuttons', 'target', 'Открывать раздел в', array('_self' => 'Текущем окне', '_blank' => 'Новом окне'), 'section-options');
		//$form->create('text', 'class', 'Класс элемента', null, 'section-options');
		//$form->create('text', 'icon', 'Иконка (HTML)', null, 'section-options');
		//$form->create('text', 'active_class', 'Класс активной ссылки', null, 'section-options');
		
		$data = DB::getAssocArray("SELECT * FROM `sections` WHERE section_id = $id", 1);
		
		$form->setValues($data);
		
		$form->required('name');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				$write = array(
					'name' 				=> Request::post('name', 'string'),
					//'name_sys' 			=> Request::post('name_sys', 'string'),
					//'alt_name'			=> Request::post('alt_name', 'translitURL') != null ? Request::post('alt_name', 'translitURL') : Request::post('name', 'translitURL'),
					//'link'				=> Request::post('link', 'string'),
					//'controller'		=> Request::post('controller', 'string'),
					'title'				=> Request::post('title', 'string'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string'),
					'flag'				=> Request::post('flag', 'int'),
					//'target'			=> Request::post('target', 'string'),
					//'class'				=> Request::post('class', 'string'),
					//'active_class'		=> Request::post('active_class', 'string'),
					//'icon'				=> Request::post('icon')
				);
				
				if (DB::update('sections', $write, array('section_id' => $id))) {
					Debug::setStatus('form', 'Раздел успешно сохранен');
					
					$form->destroy(
						'/admin/sections', 
						'/admin/section/edit-'.$id
					);
				}
				else {
					Debug::setStatus('form', 'Произойшла ошибка при сохранении');
				}
			}
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Изменить раздел '.$data['name'],
			'Редактировать раздел'
		);
	}
	
	public function delete($id) {
		if ((int)$id > 0) {
			DB::delete('sections', array('section_id' => $id));
		}
		
		Header::Location('/admin/sections');
	}
	
	public function sorted() {
		parse_str($_GET['data'], $sort);
		
		$query = "UPDATE `sections` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['section']); $i < $c; $i++) 
			$query .= " WHEN section_id = " . (int)$sort['section'][$i] . " THEN $i ";
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
		
		return true;
	}
}

?>
