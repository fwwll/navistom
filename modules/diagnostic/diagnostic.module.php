<?php

class Diagnostic {
	
	public function index($city_id = 0, $user_id = 0, $page = 1, $search = null, $is_updates = 0, $translit = '') {
		
		
	
		Site::setSectionView(10, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
		$search = (string) Str::get($search)->trim()->strToLower()->removeSymbols();
		
		$cities = ModelDiagnostic::getDiagnosticCities(Request::get('country'));
		
		if ($city_id > 0) {
			foreach ($cities as $key => $value) {
				if ($key == $city_id) {
					Header::SetTitle(Header::GetTitle() . ' - ' . $value);
					
					Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $value);
					
					Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $value);
					Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $value);
					
					break;
				}
			}
		}
		
		
		if ($user_id > 0) {
			$user = User::getUserName($user_id);
			
			Header::SetTitle(Header::GetTitle() . ' - ' . $user);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $user);
			Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $user);
		}
		
		if ($page > 1) {
			Header::SetTitle(Header::GetTitle() . ' - страница ' . $page);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - страница ' . $page);
		}
		
		if (User::isUser()) {
			$subscribe 	= ModelCabinet::getUserSubscribeCities(User::isUser(), 10);
		
			if ($city_id > 0 and is_array($subscribe)) {
				$subscribe_status = ($subscribe[10][0] == -1 or in_array($city_id, $subscribe));
			}
			else {
				$subscribe_status =  $subscribe[10][0] == -1;
			}
		}
		
		echo Registry::get('twig')->render('diagnostic.tpl', array(
			'cities'		=> $cities,
			'diagnostic'	=> ModelDiagnostic::getDiagnosticList(Request::get('country'), $city_id, $user_id, $page, Registry::get('config')->itemsInPage, $search, $is_updates, $flag),
			'subscribe_status'	=> $subscribe_status
		));
	}
	
	public function full($diagnostic_id) {
		ModelDiagnostic::setViews(User::isUser(), $diagnostic_id);
		
		$diagnostic = ModelDiagnostic::getDiagnosticFull($diagnostic_id);

        if (!$diagnostic['diagnostic_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $diagnostic['user_id'] != User::isUser() and $diagnostic['flag'] == 0) {
			Header::Location('/404');
		}

		Header::SetTitle($diagnostic['name']);
			
		Header::SetMetaTag('description', Str::get($diagnostic['content'])->truncate(200, null, 1) . ', ' . implode(',',$diagnostic['phones']));
		Header::SetMetaTag('keywords', '-');
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/diagnostic/160x200/' . $diagnostic['url_full']);
		
		$vip = ModelDiagnostic::getVIP($diagnostic['country_id'], $diagnostic['city_id'], $diagnostic_id);
		
		echo Registry::get('twig')->render('diagnostic-full.tpl', array(
			'diagnostic'	=> $diagnostic,
			'gallery'		=> ModelDiagnostic::getDiagnosticGallery($diagnostic_id),
			'vip'			=> $vip
		));
	}
	
	public function add() {
		Header::SetTitle('Добавить диагностику' . ' - ' . Header::GetTitle());
		Header::SetMetaTag('description', 'Добавить диагностику');
		Header::SetMetaTag('keywords', 'Добавить диагностику');
		
		echo Registry::get('twig')->render('diagnostic-add.tpl', array(
			'regions'		=> Site::getRegionsFromSelect(Request::get('country')),
			'is_add_access'	=> User::isUserAccess(10),
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		));
	}
	
	public function addAjax() {
		Header::ContentType("text/plain");
		
		if (User::isUserAccess(10)) {
			if (Request::PostIsNull('user_name', 'city_id', 'name')) {
				$user_info = User::getUserContacts();
				
				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelDiagnostic::uploadAttach($_FILES['attachment']);
				}
				else {
					$attachment = '';
				}
				
				$diagnostic_id = ModelDiagnostic::add(User::isUser(), Request::get('country'), array(
					'user_name'			=> Request::post('user_name', 'string'),
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'city_id'			=> Request::post('city_id', 'int'),
					'address'			=> Request::post('address', 'string'),
					'link'				=> Request::post('link', 'url'),
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
					'attach'			=> $attachment,
					'video_link'		=> Request::post('video_link', 'url'),
					'flag_vip_add'		=> Request::post('submit', 'string') == 'vip' ? 1 : 0
				), 
					Request::post('images'), 
					User::isPostModeration(10) ? 1 : 0
				);
				
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(10, $diagnostic_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($diagnostic_id,10);
				}
				
				if ($diagnostic_id > 0) {
					$result = array(
						'success'	=> true,
						'message'	=> User::isPostModeration(10) ? 'Предложение успешно добавлено' : 'Предложение добавлено на модерацию',
						'send_data'	=> $data['send_data'],
						'portmone'	=> $data['portmone'],
						'product_id'=>$diagnostic_id
					);
				}
				else {
					$result = array(
						'success'	=> false,
						'message'	=> 'Неведомая ошибка'
					);
				}
			}
			else {
				$result = array(
					'success'	=> false,
					'message'	=> 'Не все обязательные поля заполнены'
				);
			}
		}
		else {
			$result = array(
				'success'	=> false,
				'message'	=> 'У Вас нет прав для добавления материала в этот раздел'
			);
		}
		
		echo json_encode($result);
	}
	
	public function edit($diagnostic_id) {
		$data 	= ModelDiagnostic::getDiagnosticData($diagnostic_id);
		$images	= ModelDiagnostic::getDiagnosticImages($diagnostic_id); 
		
		echo Registry::get('twig')->render('diagnostic-edit.tpl', array(
			'data'			=> $data,
			'regions'		=> Site::getRegionsFromSelect($data['country_id']),
			'cities'		=> Site::getCitiesFromSelect($data['region_id']),
			'images'		=> $images,
			'images_count'	=> 7 - count($images)
		));
	}
	
	public function editAjax($diagnostic_id) {
		if (User::isUser() == ModelDiagnostic::getUserId($diagnostic_id) or User::isAdmin()) {
			if (Request::PostIsNull('user_name', 'city_id', 'name')) {
				
				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelDiagnostic::uploadAttach($_FILES['attachment']);
					
					if (Request::post('attach', 'string') != null) {
						unlink(UPLOADS . '/docs/' . Request::post('attach', 'string'));
					}
				}
				else {
					if (Request::post('attach', 'string') != null) {
						$attachment = Request::post('attach', 'string');
					}
					else {
						$attachment = '';
					}
				}
				
				ModelDiagnostic::edit($diagnostic_id, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'city_id'			=> Request::post('city_id', 'int'),
					'address'			=> Request::post('address', 'string'),
					'link'				=> Request::post('link', 'url'),
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
					'attach'			=> $attachment,
					'video_link'		=> Request::post('video_link', 'url'),
					'contact_phones'	=> Request::post('contact_phones', 'string'),
				), 
					Request::post('images'), 
					Request::post('image_description')
				);
				
				echo json_encode(array(
					'success'	=> true,
					'message'	=> 'Изменения сохранены'
				));
			}
			else {
				echo json_encode(array(
					'success'	=> false,
					'message'	=> 'Не все обязательные поля заполнены'
				));
			}
		}
		else {
			echo json_encode(array(
				'success'	=> false,
				'message'	=> 'У Вас нет прав для редактирования этого материала'
			));
		}
	}
	
	public function delete($diagnostic_id) {
		if (User::isUser() == ModelDiagnostic::getUserId($diagnostic_id) or User::isAdmin()) {
			ModelDiagnostic::delete($diagnostic_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flag($diagnostic_id, $flag = 0) {
		if (User::isUser() == ModelDiagnostic::getUserId($diagnostic_id) or User::isAdmin()) {
			ModelDiagnostic::editFlag($diagnostic_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flagModer($diagnostic_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelDiagnostic::editFlagModer($diagnostic_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function sendMessage($diagnostic_id) {
		if (Request::PostIsNull('message', 'user_id')) {
			
			if (Request::post('user_id', 'int') != User::isUser()) {
				$message_id = Site::saveUserMessage(
					$diagnostic_id, 
					10,
					User::isUser(), 
					Request::post('user_id', 'int'), 
					Request::post('message', 'string')
				);
				
				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelDiagnostic::getDiagnosticFull($diagnostic_id);
				$translit		= Str::get($data['name'])->truncate(60)->translitURL();
				
				if ($_FILES['attach']['name'] != '') {
					$attach = array(
						'file'	=> $_FILES['attach']['tmp_name'],
						'name'	=> $_FILES['attach']['name']
					);
				}
				
				Site::sendMessageToMail(
					'Новое сообщение с NaviStom.com', 
					$user_from_info['email'],
					array(
						'user_name'		=> $user_from_info['name'],
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/diagnostic/$diagnostic_id-$translit'><b>{$data['name']}</b></a>",
						'description'	=> nl2br(Request::post('message', 'string')),
						'user_email'	=> Request::post('user_email', 'string'),
						'user_phones'	=> Request::post('user_phones', 'string')
					),
					'email-basic.html',
					null,
					array(
						'email'	=> Request::post('user_email', 'string') != '' ? Request::post('user_email', 'string') : $user_to_info['email'],
						'name'	=> $user_to_info['name']
					), null, $attach
				);*/
				
				/**
				 * New Notification
				 */
				
				$from 			= User::getUserContacts();
				$to				= User::getUserInfo(Request::post('user_id', 'int'));
				
				$data		= ModelDiagnostic::getDiagnosticFull($diagnostic_id);
				$translit	= Str::get($data['name'])->truncate(60)->translitURL();
				$base_url	= 'http://navistom.com/' . Registry::get('config')->country[$data['country_id']] . '/';
				
				if ($_FILES['attach']['name'] != '') {
					$attach = array(
						'file'	=> $_FILES['attach']['tmp_name'],
						'name'	=> $_FILES['attach']['name']
					);
				}
				
				Notification::newMessage(
					array(
						'name'				=> $from['name'],
						'email'				=> $from['email'],
						'contact_phones' 	=> Request::post('user_phones', 'string'),
						'contact_email'		=> Request::post('user_email', 'string')
					), 
					array(
						'name'				=> $to['name'],
						'email'				=> $to['email']
					), 
					Request::post('message', 'string'),
					array(
						'name'				=> $data['name'],
						'link'				=> $base_url . 'diagnostic/' . $diagnostic_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-10-' . $diagnostic_id
					),
					$attach
				);
				
				/* End Notification */
				
				echo json_encode(array(
					'success'	=> true,
					'message'	=> 'Ваше сообщение было успешно отправлено пользователю'
				));
			}
			else {
				echo json_encode(array(
					'success'	=> false,
					'message'	=> 'Вы пытаетесь отправить сообщение самому себе :)'
				));
			}
		}
		else {
			$data 			= ModelDiagnostic::getDiagnosticFull($diagnostic_id);
			$user_from_info = User::getUserInfo($data['user_id']);
			
			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $diagnostic_id
				),
				'mess_tpls'	=> Site::getMessTplsToSelect(10),
				'controller'=> 'demand'
			));
		}
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