<?php

class Work {
	public function index($filter) {
		if ($filter == 'resume') {
			$type = 1;
		}
		else {
			$type = 2;
		}
		
		echo Registry::get('twig')->render('work.tpl', array(
			'work'	=> ModelWork::getWorkList($type),
			'title'	=> 'Работа - Все ' . ($type == 1 ? 'резюме' : 'вакансии')
		)); 
	}
	
	public function add() {
		
	}
	
	public function edit($work_id) {
		$form = new Form();
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Работа - редактировать предложение',
			'Работа - редактировать предложение'
		);
	}
	
	public function categories() {
		echo Registry::get('twig')->render('work-categories.tpl', array(
			'categories'	=> ModelWork::getCategoriesList(),
			'title'			=> 'Работа - Все категории'
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
				$categ_id = ModelWork::categoryAdd(
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string')
				);
			}
			
			$form->destroy(
				'/admin/work/categories', 
				'/admin/work/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить рубрику',
			'Добавить рубрику'
		);
	}
	
	public function categoryEdit($categ_id) {
		$category = ModelWork::getCategoryData($categ_id);
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название категории');
		
		$form->create('title', 'title-resume', 'Meta - теги для резюме');
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->create('title', 'title-vacancy', 'Meta - теги для вакансий');
		
		$form->create('text', 'title_vacancy', 'Заголовок H1');
		$form->create('textarea', 'meta_title_vacancy', 'Meta title');
		$form->create('textarea', 'meta_description_vacancy', 'Meta description');
		$form->create('textarea', 'meta_keys_vacancy', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues($category);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelWork::categoryEdit(
					$categ_id, 
					Request::post('name', 'string'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string'),
					Request::post('title_vacancy', 'string'),
					Request::post('meta_title_vacancy', 'string'),
					Request::post('meta_description_vacancy', 'string'),
					Request::post('meta_keys_vacancy', 'string')
				);
			}
			
			$form->destroy(
				'/admin/work/categories', 
				'/admin/work/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать рубрику',
			'Редактировать рубрику'
		);
	}
	
	public function categoryDelete($categ_id) {
		ModelWork::categoryDelete($categ_id);
		
		Header::Location('/admin/work/categories');
	}
	
	public function categoriesSorted() {
		parse_str($_GET['data'], $sort);
		
		ModelWork::categoriesSorted($sort);
		
		return true;
	}
}