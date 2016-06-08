<?php

class Journal {
	
	public function index() {
		echo Registry::get('twig')->render('journals.tpl', array(
			'title'		=> 'Все журналы',
			'journals'	=> ModelJournal::getJournalsList()
		));
	}
	
	public function add() {
		$form = new Form();
		
		$form->create('spinner', 'num', 'Номер журнала');
		$form->create('spinner', 'year', 'Год издательства');
		
		$form->create('uploader', 'images', 'Страницы журнала');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				$journal_id = ModelJournal::add(
					Request::post('num', 'int'),
					Request::post('year', 'int'),
					Request::post('images'),
					Request::post('image_description')
				);
			}
			
			$form->destroy(
				'/admin/journal', 
				'/admin/journal/edit-'.$journal_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить журнал',
			'Добавить журнал'
		);
	}
	
	public function edit($journal_id) {
		$form = new Form();
		
		$data = ModelJournal::getJournalData($journal_id);
		
		for ($i = 0, $c = count($data['pages']); $i < $c; $i++) {
			$data['journal']['images'][$data['pages'][$i]['page_id']] = '/uploads/journals/' . $data['journal']['year'] . '-' . $data['journal']['num'] . '/' . $data['pages'][$i]['page'] . '-thumb.jpg';
			
			$form->create('hidden', 'image_description[' . $data['pages'][$i]['page_id'] . ']', '', $data['pages'][$i]['title']);
			$form->attr('image_description[' . $data['pages'][$i]['page_id'] . ']', 'id', 'descr_' . $data['pages'][$i]['page_id']);
		}
		
		$form->create('spinner', 'num', 'Номер журнала');
		$form->create('spinner', 'year', 'Год издательства');
		
		$form->create('uploader', 'images', 'Страницы журнала');
		
		$form->setValues(
			$data['journal']
		);
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				ModelJournal::edit(
					$journal_id,
					Request::post('num', 'int'),
					Request::post('year', 'int'),
					Request::post('images'),
					Request::post('image_description')
				);
			}
			
			$form->destroy(
				'/admin/journal', 
				'/admin/journal/edit-'.$journal_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать журнал',
			'Редактировать журнал'
		);
	}
	
	public function delete($journal_id) {
		ModelJournal::delete($journal_id);
		
		Header::Location('/admin/journal');
	}
	
	public function uploadImage() {
		header("Content-Type: text/plain");
		
		$result = ModelJournal::uploadImage($_FILES['qqfile']);
		
		echo json_encode($result);
	}
}