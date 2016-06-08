<?php

class Feedback {
	public function index() {
		echo Registry::get('twig')->render('feedback-mess.tpl', array(
			'title'	=> 'Сообщения из обратной связи',
			'data'	=> ModelFeedback::getUserFeedbackMess()
		));
	}
	
	public function errors() {
		echo Registry::get('twig')->render('feedback-errors.tpl', array(
			'title'	=> 'Сообщения об ошибках',
			'data'	=> ModelFeedback::getUsersErrorsMess()
		));
	}

    public function access() {
        echo Registry::get('twig')->render('feedback-access.tpl', array(
            'title'	=> 'Заявки на расширение доступа',
            'data'	=> ModelFeedback::getAccessRequests()
        ));
    }

    public function deleteAccess($user_id) {
        ModelFeedback::deleteAccessRequest($user_id);

        Header::Location('/admin/feedback/access');
    }
	
	public function viewMess($mess_id) {
		ModelFeedback::viewMess($mess_id);
		
		Header::Location('/admin/feedback');
	}
	
	public function deleteMess($mess_id) {
		ModelFeedback::deleteMess($mess_id);
		
		Header::Location('/admin/feedback');
	}
	
	public function view($mess_id) {
		ModelFeedback::view($mess_id);
		
		Header::Location('/admin/feedback/errors');
	}
	
	public function delete($mess_id) {
		ModelFeedback::delete($mess_id);
		
		Header::Location('/admin/feedback/errors');
	}
	
	public function sendMessage($mess_id) {
		$data = ModelFeedback::getUserFeedbackMess($mess_id);
		
		ModelFeedback::saveAnswer($mess_id, User::isUser(), 1, Request::post('message'));
		
		$user_to_info = User::getUserContacts();
		
		$message = '<p style="background:#f5f5f5; border:#e4e4e4 1px solid; padding:10px">' . $data['message'] . '</p><br>' . 
			Request::post('message') . 
			'<br/><br/><p>Контактная информация NaviStom.com:</p>												
			<p>Тел.: <strong>+38 (044) 573-97-73</strong><br/>
				Email: <strong><a href="mailto:navistom@navistom.com">navistom@navistom.com</a></strong></p>';
		
		if (Site::sendMessageToMail('Ответ NaviStom.com на Ваше сообщение', $data['user_email'], $message)) {
			echo json_encode(array(
				'success'	=> true,
				'message'	=> 'Ваше сообщение было успешно отправлено пользователю'
			));
		}
		else {
			echo json_encode(array(
				'success'	=> false,
				'message'	=> 'Неведомая ошибка!'
			));
		}
	}
	
	public function sendMessageError($mess_id) {
		$data = ModelFeedback::getUsersErrorsMess($mess_id);
		
		ModelFeedback::saveAnswer($mess_id, User::isUser(), 2, Request::post('message'));
		
		$message = '<p style="background:#f5f5f5; border:#e4e4e4 1px solid; padding:10px">' . $data['message'] . '</p><br>' .
			Request::post('message') . 
			'<br/><br/><p>Контактная информация NaviStom.com:</p>												
			<p>Тел.: <strong>+38 (044) 573-97-73</strong><br/>
				Email: <strong><a href="mailto:navistom@navistom.com">navistom@navistom.com</a></strong></p>';
		
		if (Site::sendMessageToMail('Ответ NaviStom.com на Ваше сообщение об ошибке', $data['user_email'], $message)) {
			echo json_encode(array(
				'success'	=> true,
				'message'	=> 'Ваше сообщение было успешно отправлено пользователю'
			));
		}
		else {
			echo json_encode(array(
				'success'	=> false,
				'message'	=> 'Неведомая ошибка!'
			));
		}
	}
	
	public function messTpls() {
		echo Registry::get('twig')->render('feedback-mess-tpls.tpl', array(
			'title'	=> 'Все шаблоны писем',
			'tpls'	=> ModelFeedback::getMessTplsList()
		));
	}
	
	public function messTplsAdd() {
		$form = new Form();
		
		$form->create('text', 'title', 'Название шаблона');
		
		$form->create('select', 'section_id', 'Раздел сайта', ModelFeedback::getSectionsList());
		
		$form->create('textarea', 'message', 'Текст шаблона');
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				DB::insert('feedback_mess_tpls', array(
					'title'			=> Request::post('title', 'string'),
					'section_id'	=> Request::post('section_id', 'int'),
					'message'		=> Request::post('message', 'string')
				));
				
				$mess_id = DB::lastInsertId();
			}
			
			$form->destroy(
				'/admin/feedback/mess-tpls', 
				'/admin/feedback/mess-tpls/edit-' . $mess_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Шаблоны писем - добавить шаблон',
			'Шаблоны писем - добавить шаблон'
		);
	}
	
	public function messTplsEdit($mess_id) {
		$form = new Form();
		
		$form->create('text', 'title', 'Название шаблона');
		
		$form->create('select', 'section_id', 'Раздел сайта', ModelFeedback::getSectionsList());
		
		$form->create('textarea', 'message', 'Текст шаблона');
		
		$form->setValues(
			ModelFeedback::getMessTplData($mess_id)
		);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				DB::update('feedback_mess_tpls', array(
					'title'			=> Request::post('title', 'string'),
					'section_id'	=> Request::post('section_id', 'int'),
					'message'		=> Request::post('message', 'string')
				), array(
					'mess_id'		=> $mess_id
				));
			}
			
			$form->destroy(
				'/admin/feedback/mess-tpls', 
				'/admin/feedback/mess-tpls/edit-' . $mess_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Шаблоны писем - редактировать шаблон',
			'Шаблоны писем - редактировать шаблон'
		);
	}
	
	public function messTplsDelete($mess_id) {
		DB::delete('feedback_mess_tpls', array(
			'mess_id'	=> $mess_id
		));
		
		Header::Location('/admin/feedback/mess-tpls');
	}
}