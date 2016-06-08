<?php

class Cabinet {
	public function index() {
		if (User::isUser()) {
            $permissions = User::getUserPermissions(User::isUser());
			unset($permissions[11]); 
            $permissionsLimit = User::getUserAccessLimits();
            $countsDetail = User::getUserMaterialsCountDetail(User::isUser());
            $warnings = array();

            if (!User::checkUserAccessRequest(User::isUser())) for ($i = 0, $c = count($permissions); $i < $c; $i++) {
                $limits =& $permissionsLimit[$permissions[$i]['section_id']];

                if (is_numeric($limits['dateDiff'])) {
                    if ($limits['dateDiff'] <= 0) {
                        $warnings[] = array(
                            'type' => 1,
                            'diff' => $limits['dateDiff'],
                            'name' => $permissions[$i]['name']
                        );
                    }
                    elseif($limits['dateDiff'] <= 14) {
                        $warnings[] = array(
                            'type' => 2,
                            'diff' => $limits['dateDiff'],
                            'name' => $permissions[$i]['name']
                        );
                    }
                }

                if (is_numeric($limits['countDiff']) && $limits['countDiff'] <= 0) {
                    $warnings[] = array(
                        'type' => 3,
                        'name' => $permissions[$i]['name']
                    );
                }
            }
                   
            echo Registry::get('twig')->render('cabinet-profile.tpl', array(
                'user'			=> User::getFullUserInfo(User::isUser()),
                'permissions'	=> $permissions,
                //'counts'		=> User::getUserMaterialsCount(User::isUser()),
                'countsDetail'  => $countsDetail,
                'updates_counts'=> User::getUserUpdatesCount(User::isUser()),
                'warnings'      => $warnings,
                'mess_count'	=> ModelCabinet::getMessCount(User::isUser()),
				'zayavka'		=> ModelCabinet::flagZayavka (User::isUser())
            ));
		}
		else {
			Header::Location("/login");
		}
	}
	
	public function profileEdit() {
		if (User::isUser()) {
			
			if (Request::PostIsNull('user_name', 'user_email', 'user_region')) {
				User::editUserEmail(
					User::isUser(), 
					Request::post('user_email', 'string')
				);
				
				if ($_FILES['user_avatar']['name'] != null) {
					User::deleteUserAvatar(
						Request::post('this_avatar', 'string')
					);
					
					$avatar_name = mb_strtolower(Request::post('user_name', 'translitURL'), 'UTF-8') . '_' . User::isUser();
					$user_avatar = User::addUserAvatar('user_avatar', $avatar_name);
				}
				else {
					$user_avatar = Request::post('this_avatar', 'string');
				}
				
				User::editUserInfo(
					User::isUser(),
					Request::post('user_name', 'string'),
					Request::post('user_region', 'int'),
					Request::post('user_city', 'int'),
					Request::post('user_icq', 'string'),
					Request::post('user_skype', 'string'),
					Request::post('user_contact_phone', 'string'),
					$user_avatar,
					Request::post('site', 'url')
				);
				
				User::setUserInfoToSession(User::isUser());
				
				Debug::setStatus('cabinet', array(
					'succes'	=> true,
					'message'	=> 'Изменения сохранены'
				));
				
				Header::Location("/cabinet/profile/edit");
			}
			elseif (Request::PostIsNull('passw', 'new_passw', 'new_passw_2')) {
				if (md5(md5(Request::post('passw'))) == User::getUserPassw(User::isUser()) and Request::post('new_passw') == Request::post('new_passw_2')) {
					User::editUserPassw(User::isUser(), Request::post('new_passw'));
					
					Header::Location("/cabinet/profile/edit");
				}
				else {
					Header::Location("/cabinet/profile/edit");
				}
			}
			else {
				$data = User::getFullUserInfo(User::isUser());
				
				echo Registry::get('twig')->render('cabinet-profile-edit.tpl', array(
					'data'			=> $data,
					'countries'		=> Registry::get('config')->countries_names,
					'regions'		=> Site::getRegionsFromSelect(1),
					'cities'		=> Site::getCitiesFromSelect($data['region_id']),
					'complete'		=> Debug::getStatus('cabinet'),
					'mess_count'	=> ModelCabinet::getMessCount(User::isUser())
				));
			}
		}
		else {
			Header::Location("/#login");
		}
	}
	
	public function passwEdit() {
		if (isset($_POST['passw'])) {
			if (Request::PostIsNull('passw', 'new_passw', 'new_passw_2')) {
				if (md5(md5(Request::post('passw'))) == User::getUserPassw(User::isUser()) and Request::post('new_passw') == Request::post('new_passw_2')) {
					User::editUserPassw(User::isUser(), Request::post('new_passw'));
					
					Debug::setStatus('cabinet', array(
						'succes'	=> true,
						'message'	=> 'Пароль успешно изменен'
					));
					
					Header::Location("/cabinet/profile/passw");
				}
				else {
					Debug::setStatus('cabinet', array(
						'succes'	=> false,
						'message'	=> 'Старый пароль введен не верно'
					));
					
					Header::Location("/cabinet/profile/passw");
				}
			}
			else {
				Debug::setStatus('cabinet', array(
					'succes'	=> false,
					'message'	=> 'Не все обязательные поля заполнены'
				));
				
				Header::Location("/cabinet/profile/passw");
			}
		}
		else {
			echo Registry::get('twig')->render('cabinet-passw-edit.tpl', array(
				'complete'		=> Debug::getStatus('cabinet'),
				'mess_count'	=> ModelCabinet::getMessCount(User::isUser())
			));
		}
	}
	
	public function exchanges() {
		if (User::isUser()) {
			if (Request::post('rate') or Request::post('exchange_default')) {
				if ($state = ModelCabinet::saveUserExchange(User::isUser(), Request::post('rate'), Request::post('exchange_default'))) {
					Debug::setStatus('cabinet', array(
						'succes'	=> true,
						'message'	=> ($state == 2) ? 'Курс НБУ установлен' : 'Ваш курс валют успешно сохранен'
					));
					
					Header::Location("/cabinet/profile/exchanges");
				}
				else {
					Debug::setStatus('cabinet', array(
						'succes'	=> false,
						'message'	=> 'Ошибка сохранения'
					));
					
					Header::Location("/cabinet/profile/exchanges");
				}
			}
			else {
				echo Registry::get('twig')->render('cabinet-exchanges.tpl', array(
					'currensies'		=> ModelCabinet::getCurrensyList(User::getUserCountry()),
					'currency_default'	=> ModelCabinet::getCurrensyDefault(User::getUserCountry()),
					'exchanges'			=> ModelCabinet::getExchanges(User::isUser(), User::getUserCountry()),
					'complete'			=> Debug::getStatus('cabinet'),
                    'isUserExchanges'   => ModelCabinet::isUserExchanges(User::isUser())
				));
			}
		}
		else {
			Header::Location("/");
		}
	}
	
	public function exchangeDefault() {
		$default = ModelCabinet::getExchanges(0, User::getUserCountry());
		
		ModelCabinet::saveUserExchange(User::isUser(), $default);
		
		Header::Location("/cabinet/profile/exchanges");
	}
	
	public function messages() {
		if (User::isUser()) {
			echo Registry::get('twig')->render('cabinet-messages.tpl', array(
				'dialogs'		=> ModelCabinet::getUserDialogs(User::isUser()),
				'mess_count'	=> ModelCabinet::getMessCount(User::isUser()),
				'active'		=> 1,
				'mess_count'	=> ModelCabinet::getMessCount(User::isUser())
			));
		}
		else {
			Header::Location("/login");
		}
	}
	
	public function dialogFull($from_id, $resource_id, $section_id, $status) {
		if (User::isUser()) {
			
			if ($status == 0) {
				ModelCabinet::setSattusDialog($from_id, User::isUser(), $resource_id, $section_id);
			}
			
			echo Registry::get('twig')->render('cabinet-dialog-full.tpl', array(
				'data'	=> ModelCabinet::getDialogFull($from_id, $resource_id, $section_id)
			));
		}
		else {
			Header::Location("/login");
		}
	}
	
	public function sendMessage($from_id, $resource_id, $section_id) {
		
		$message_id = ModelCabinet::addMessage(
			User::isUser(),
			$from_id,
			$resource_id,
			$section_id,
			Request::post('message', 'string')
		);
		
		if ($message_id > 0) {
			
			$user_info 	= User::getUserInfo($from_id);
			$to_info	= User::getUserContacts();
			
			Site::sendMessageToMail('Новое сообщение', $user_info['email'], 
				array(
					'user_name'		=> $user_info['name'],
					'title'			=> 'Новое сообщение на портале NaviStom.com',
					'message'		=> 'Пользователь <b>' . $to_info['name'] . '</b> прислал вам сообщение',
					'description'	=> 'Для того чтобы прочитать и ответить на сообщение перейдите в свой личный кабинет, в раздел сообщения  
										<a href="http://navistom.com/ua/cabinet/messages#/cabinet/dialog-' . $from_id . '-' . $resource_id . '-' . $section_id . '-0">Перейти к сообщению</a> <br />
                     					Если Вы не авторизированы пожалуйста войдите в личный кабинет по ссылке <a href="http://navistom.com/ua/#/login">Вход</a>'
				), 
				'email-basic.html'
			);
			
			echo  json_encode(array(
				'success'	=> true,
				'message'	=>  Registry::get('twig')->render('cabinet-message.tpl', ModelCabinet::getMessage($message_id))
			));
			
			return true;
		}
		
		echo  json_encode(array(
			'success'	=> false,
			'error'		=> 'Error!'
		));
		
		return false;
	}
	
	public function materials() {
		$vip = User::getUserVip(User::isUser());
		
		if (is_array($vip)) {
			for ($i = 0, $c = count($vip); $i < $c; $i++) {
				$vips[$vip[$i]['section_id']] = $vip[$i];
			}
		}
		
		echo Registry::get('twig')->render('cabinet-materials.tpl', array(
			'active'		=> 2,
			'permissions'	=> User::getUserPermissions(User::isUser()),
			'counts'		=> User::getUserMaterialsCount(User::isUser()),
			'vips'			=> $vips,
			'mess_count'	=> ModelCabinet::getMessCount(User::isUser())
		));
	}
	
	public function vip($section_id) {
		echo Registry::get('twig')->render('cabinet-vip.tpl', array(
			'data'		=> User::getUserVip(User::isUser(), $section_id),
		));
	}
	
	public function vipAjax($section_id) {
		$materials = Request::post('material');
		
		User::deleteVipMaterial(User::isUser(), $section_id);
		
		for ($i = 0, $c = count($materials); $i < $c; $i++) {
			if ($materials[$i] > 0) {
				User::addVipMaterial($section_id, $materials[$i]);
			}
		}
		
		echo json_encode(array(
			'success'	=> true,
			'message'	=> 'Изменения сохранены'
		));
	}
	
	public function getUserMaterials($section_id) {
		$q 		= Request::get('q', 'string');
		$data 	= User::getUserMaterialsToSelect($section_id, User::isUser(), $q);

		echo json_encode($data);
	}
	
	public function faq() {
		echo Registry::get('twig')->render('cabinet-faq.tpl', array(
			'active'		=> 3,
		));
	}
	
	public function subscribe() {

		if (Request::post('subscribe') or !is_null(Request::post('subscribe_status'))) {
			$categs 	= is_array(Request::post('categs')) ? Request::post('categs') : array();
			$sub_categs	= is_array(Request::post('sub_categs')) ? Request::post('sub_categs') : array();
			$cities		= is_array(Request::post('cities')) ? Request::post('cities') : array();
			$sections	= is_array(Request::post('section_id')) ? Request::post('section_id') : array();			
			
			$sections 	= @array_unique(array_merge(
				@array_keys($categs),
				@array_keys($cities),
				@array_values($sections)
			));
			
			User::saveSubscribeStatus(User::isUser(), Request::post('subscribe_status', 'int'), Request::post('news_status', 'int'));
			
			ModelCabinet::deleteSubscribeData(User::isUser());
			
			if(is_array($sections)) {
				foreach ($sections as $section_id) {
					switch ($section_id) {
						case 2:
						case 3:
						case 4:
							ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs[$section_id], 1);
							ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $sub_categs[$section_id]);
						break;
						case 10:
							ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, $cities[$section_id]);
						break;
						case 11:
							ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, array(1));
						break;
						default:
							ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs[$section_id]);
							ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, $cities[$section_id]);
						break;
					}
				}
			}
			
			Debug::setStatus('complete', array(
				'success'	=> true,
				'message'	=> 'Параметры подписки сохранены.'
			));
			
			Header::Location("/cabinet/profile/subscribe");
		}
		else {
			
			if (!User::isUser()) {
				Header::Location('/login');
				
				die();
			}
			
			echo Registry::get('twig')->render('cabinet-subscribe.tpl', array(
				'subscribe_sections'	=> Site::getSectionsList(1),
				'articles_categs'		=> ModelArticles::getCategoriesFromSelect(),
				'products_categs'		=> ModelProducts::getCategoriesFromSelect(),
				'services_categs'		=> ModelServices::getCategoriesFromSelect(),
				'activity_categs'		=> ModelActivity::getCategoriesFromSelect(),
				'work_categs'			=> ModelWork::getCategoriesFromSelect(),
				'labs_categs'			=> ModelLabs::getCategoriesFromSelect(),
				'realty_categs'			=> ModelRealty::getCategoriesFromSelect(),
				'cities'				=> Site::getCitiesFromSelect(0, Request::get('country')),
				'regions'				=> Site::getRegionsFromSelect(Request::get('country')),
				'complete'				=> Debug::getStatus('complete'),
				'subscribe_categs'		=> ModelCabinet::getUserSubscribeCategs(User::isUser(), 1),
				'subscribe_sub_categs'	=> ModelCabinet::getUserSubscribeCategs(User::isUser()),
				'subscribe_cities'		=> ModelCabinet::getUserSubscribeCities(User::isUser()),
				'status'				=> User::getSubscribeStatus(User::isUser())
			));
		}
	}
	
	public function subscribeSaveAjax() {
		Header::ContentType("text/plain");
		
		$categs 	= is_array(Request::post('categs')) 	? Request::post('categs') 		: array();
		$sub_categs	= is_array(Request::post('sub_categs')) ? Request::post('sub_categs') 	: array();
		$cities		= is_array(Request::post('cities')) 	? Request::post('cities') 		: array();
		$sections	= is_array(Request::post('section_id')) ? Request::post('section_id') 	: array();			
		
		$sections 	= @array_unique(array_merge(
			@array_keys($categs),
			@array_keys($cities),
			@array_values($sections)
		));
		
		User::saveSubscribeStatus(User::isUser(), Request::post('subscribe_status', 'int'), Request::post('news_status', 'int'));
		
		ModelCabinet::deleteSubscribeData(User::isUser());
		
		if(is_array($sections)) {
			foreach ($sections as $section_id) {
				switch ($section_id) {
					case 2:
					case 3:
					case 4:
						ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs[$section_id], 1);
						ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $sub_categs[$section_id]);
					break;
					case 10:
						ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, $cities[$section_id]);
					break;
					case 11:
						ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, array(1));
					break;
					default:
						ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs[$section_id]);
						ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, $cities[$section_id]);
					break;
				}
			}
		}
		
		echo json_encode(array(
			'success'	=> true,
			'message'	=> 'Изменения сохранены'
		));
	}
	
	public function subscribeAdd($section_id, $categ_id = 0, $sub_categ_id = 0) {
		switch ($section_id) {
			case 2:
			case 3:
			case 4:
				if ($categ_id > 0) {
					$categs = array($categ_id);
				}
				else {
					$categs = array_keys(ModelProducts::getCategoriesFromSubscribe(0, $section_id == 4));
				}
				
				if ($sub_categ_id > 0) {
					ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, array($categ_id), 1);
					
					ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, array($sub_categ_id));
				}
				else {
					$sub_categs = array_keys(ModelProducts::getCategoriesFromSelect($categs));

					ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs, 1);
					ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $sub_categs);
				}
			break;
			case 5:
				if ($categ_id > 0) {
					$categs = array($categ_id);
				}
				else {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id);
					
					$categs_r = ModelActivity::getCategoriesFromSelect();
					
					for ($i = 0, $c = count($categs_r); $i < $c; $i++) {
						$categs[] = $categs_r[$i]['categ_id'];
					}
				}
				
				ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, array($sub_categ_id > 0 ? $sub_categ_id : -1));
				ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs);
			break;
			case 6:
			case 15:
				if ($categ_id > 0) {
					$categs = array($categ_id);
				}
				else {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id);
					
					$categs = array_keys(ModelWork::getCategoriesFromSelect());
				}
				
				ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, array($sub_categ_id > 0 ? $sub_categ_id : -1));
				ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs);
			break;
			case 7:
				if ($categ_id > 0) {
					$categs = array($categ_id);
				}
				else {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id);
					
					$categs_r = ModelLabs::getCategoriesFromSelect();
					
					for ($i = 0, $c = count($categs_r); $i < $c; $i++) {
						$categs[] = $categs_r[$i]['categ_id'];
					}
				}
				
				ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, array($sub_categ_id > 0 ? $sub_categ_id : -1));
				ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs);
			break;
			case 8:
				if ($categ_id > 0) {
					$categs = array($categ_id);
				}
				else {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id);
					
					$categs_r = ModelRealty::getCategoriesFromSelect();
					
					for ($i = 0, $c = count($categs_r); $i < $c; $i++) {
						$categs[] = $categs_r[$i]['categ_id'];
					}
				}
				
				ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, array($sub_categ_id > 0 ? $sub_categ_id : -1));
				ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs);
			break;
			case 10:
				ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, array($sub_categ_id > 0 ? $sub_categ_id : -1));
			break;
			case 11:
				ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, array(1));
			break;
			case 16:
				if ($categ_id > 0) {
					$categs = array($categ_id);
				}
				else {
					$categs = array_keys(ModelArticles::getCategoriesFromSelect());
				}
				
				ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs);
			break;
			case 9:
				if ($categ_id > 0) {
					$categs = array($categ_id);
				}
				else {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id);
					
					$categs = array_keys(ModelServices::getCategoriesFromSelect());
				}
				
				ModelCabinet::saveUserSubscribeCities(User::isUser(), $section_id, array($sub_categ_id > 0 ? $sub_categ_id : -1));
				ModelCabinet::saveUserSubscribeCategs(User::isUser(), $section_id, $categs);
			break;
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function subscribeDelete($section_id, $categ_id, $sub_categ_id = 0) {
		switch ($section_id) {
			case 2:
			case 3:
			case 4:
				if ($sub_categ_id > 0) {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id, $sub_categ_id);
				}
				else {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id, $categ_id);
				}
				
				if ($categ_id > 0) {
					ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id, array_keys(ModelProducts::getCategoriesFromSelect($categ_id)));
				}
			break;
			case 10:
				ModelCabinet::deleteSubscribeCities(User::isUser(), $section_id, $sub_categ_id);
			break;
			case 11:
				ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id, 1);
			break;
			case 16:
				ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id, $categ_id);
			break;
			default:
				ModelCabinet::deleteSubscribeCities(User::isUser(), $section_id, $sub_categ_id);
				ModelCabinet::deleteSubscribeCategs(User::isUser(), $section_id, $categ_id);
			break;
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public  function zayavka($user_id){
		  if(User::isUser()==$user_id){
			  echo ModelCabinet::zayavka($user_id);
		  }
		  
		 // Header::Location('/cabinet');
	}
	
}