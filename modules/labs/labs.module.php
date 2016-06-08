<?php

class Labs {
	public function index($categ_id = 0, $region_id = 0, $user_id = 0, $page = 1, $search = null, $is_updates = 0, $city_id = 0, $translit = '') {
		
		
		Site::setSectionView(7, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
		$search = (string) Str::get($search)->trim()->strToLower()->removeSymbols();
		
		$count 		= ModelLabs::getLabsCount(Request::get('country'), $categ_id, $region_id, $user_id, $search, $is_updates, $city_id, $flag);
		$pagination	= Site::pagination(Registry::get('config')->itemsInPage, $count, $page);
		$regions	= ModelLabs::getLabsRegionsList(Request::get('country'), $categ_id);
		$cities=        ModelLabs::getLabsCities(Request::get('country'),1, $categ_id);
		   // Site::d($cities);      
		
		if ($categ_id > 0) {
			$meta = ModelLabs::getCategoryMetaTags($categ_id);
			
			Header::SetTitle($meta['meta_title']);
			
			Header::SetH1Tag($meta['title']);
			
			Header::SetMetaTag('description', $meta['meta_description']);
			Header::SetMetaTag('keywords', $meta['meta_keys']);
		}
		
		if ($region_id > 0) {
			foreach ($regions as $key => $value) {
				if ($key == $region_id) {
					Header::SetTitle(Header::GetTitle() . ' - ' . $value);
					
					Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $value);
					
					Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $value);
					Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $value);
					
					break;
				}
			}
		}
		
		$labs = ModelLabs::getLabsList(Request::get('country'), $categ_id, $region_id, $user_id, $page, Registry::get('config')->itemsInPage, $search, $is_updates, $city_id, $flag);
		
		if ($city_id > 0) {
			Header::SetTitle( $labs[0]['city_name']. ' - ' . Header::GetTitle());
					
			Header::SetH1Tag($labs[0]['city_name'].' - '.Header::GetH1Tag()  );
			
			Header::SetMetaTag('description',$labs[0]['city_name'].' - '.Header::GetMetaTag('description')  );
			Header::SetMetaTag('keywords',$labs[0]['city_name'].', '.Header::GetMetaTag('keywords')  );
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
		
		$categs = ModelLabs::getCategoriesFromSelect(1);
		
		if (User::isUser()) {
			$subscribe 	= ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 7);
			
			if ($categ_id > 0 and is_array($subscribe)) {
				$subscribe_status = in_array($categ_id, $subscribe);
			}
			else {
				$subscribe_status =  count($subscribe) == count($categs);
			}
		}
		
		 if($_GET['tp']=='new'){
	           
			 $tpl_name='labs-new2.tpl';  
		   }else{
			 /* $tpl_name='labs.tpl'; */
			 $tpl_name='labs-new2.tpl';  
			 
			 			 
		   }
		
		echo Registry::get('twig')->render($tpl_name, array(
			'categs'		=> $categs,
			'regions'		=> $regions,
			'labs'			=> $labs,
			'pagination'	=> $pagination,
			'cities'         	=>$cities,
			'subscribe_status'	=> $subscribe_status,
			'cityName'=>Site::cityName()
		));
	}
	
	public function full($lab_id) {
		ModelLabs::setViews(User::isUser(), $lab_id);
		
		$lab = ModelLabs::getLabFull($lab_id);

        if (!$lab['lab_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $lab['user_id'] != User::isUser() and $lab['flag'] == 0) {
			Header::Location('/404');
		}

		Header::SetTitle($lab['name'] . ' г. ' . $lab['city_name']);
			
		Header::SetMetaTag('description', @implode(', ', $lab['categs']) . ', ' . $lab['contact_phones'] . ', ' . $lab['region_name'] . ' ' . $lab['city_name']);
		Header::SetMetaTag('keywords', '-');
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/labs/160x200/' . $lab['image']);
		
		$vip = ModelLabs::getVIP($lab['country_id'], $lab['categs'], $lab_id);
		
		
		if($_GET['tp']=='new'){
	           
			 $tpl_name='lab-new-full.tpl';  
		   }else{
			 $tpl_name='lab-full.tpl'; 
			$tpl_name='lab-new-full.tpl';			 
		   }
		
		echo Registry::get('twig')->render($tpl_name , array(
			'lab'		=> $lab,
			'vip'		=> $vip,
			'gallery'	=> ModelLabs::getLabGallery($lab_id)
		));
	}
	
	public function add() {
		Header::SetTitle('Добавить зуботехническую лабораторию' . ' - ' . Header::GetTitle());
		Header::SetMetaTag('description', 'Добавить зуботехническую лабораторию');
		Header::SetMetaTag('keywords', 'Добавить зуботехническую лабораторию');
		
		echo Registry::get('twig')->render('lab-add.tpl', array(
			'categs'		=> ModelLabs::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect(Request::get('country')),
			'is_add_access'	=> User::isUserAccess(7),
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		));
	}
	
	public function addAjax() {
		Header::ContentType("text/plain");
		
		if (User::isUserAccess(7)) {
			if (Request::PostIsNull('name', 'region_id')) {
				$user_info = User::getUserContacts();
				
				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelLabs::uploadAttach($_FILES['attachment']);
				}
				else {
					$attachment = '';
				}
				
				$lab_id = ModelLabs::add(
					User::isUser(),
					Request::get('country'),
					array(
						'user_name'			=> $user_info['name'],
						'contact_phones'	=> Request::post('contact_phones', 'string'),
						'region_id'			=> Request::post('region_id', 'int'),
						'city_id'			=> Request::post('city_id', 'int'),
						'name'				=> Request::post('name', 'string'),
						'address'			=> Request::post('address', 'string'),
						'content'			=> Request::post('content', 'string'),
						'attach'			=> $attachment,
						'link'				=> Request::post('link', 'url'),
						'video_link'		=> Request::post('video_link', 'url'),
						'flag_vip_add'		=> Request::post('submit', 'string') == 'vip' ? 1 : 0
					),
					Request::post('categ_id'),
					Request::post('images'),
					User::isPostModeration(7) ? 1 : 0
				);
				
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(7, $lab_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($lab_id,7); 
					
				}
				
				if ($lab_id > 0) {
					$result = array(
						'success'	=> true,
						'message'	=> User::isPostModeration(7) ? 'Услуга успешно добавлена' : 'Услуга добавлена на модерацию',
						'send_data'	=> $data['send_data'],
						'portmone'	=> $data['portmone'],
						'product_id'=>$lab_id
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
	
	public function edit($lab_id) {
		$data 				= ModelLabs::getLabData($lab_id);
		$data['categ_id']	= explode(',', $data['categ_id']);
		$images				= ModelLabs::getLabImages($lab_id);
		
		echo Registry::get('twig')->render('lab-edit.tpl', array(
			'data'			=> $data,
			'images'		=> $images,
			'categs'		=> ModelLabs::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect($data['country_id']),
			'cities'		=> Site::getCitiesFromSelect($data['region_id']),
			'images_count'	=> 7 - count($images)
		));
	}
	
	public function editAjax($lab_id) {
		Header::ContentType("text/plain");
		
		if (User::isUser() == ModelLabs::getUserId($lab_id) or User::isAdmin()) {
			if (Request::PostIsNull('name', 'region_id')) {
				
				$user_info = User::getUserContacts();
				
				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelLabs::uploadAttach($_FILES['attachment']);
					
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
				
				ModelLabs::edit($lab_id, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'name'				=> Request::post('name', 'string'),
					'address'			=> Request::post('address', 'string'),
					'content'			=> Request::post('content', 'string'),
					'link'				=> Request::post('link', 'url'),
					'attach'			=> $attachment,
					'video_link'		=> Request::post('video_link', 'url'),
					'contact_phones'	=> Request::post('contact_phones', 'string'),
				), 
					Request::post('categ_id'), 
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
	
	public function delete($lab_id) {
		if (User::isUser() == ModelLabs::getUserId($lab_id) or User::isAdmin()) {
			ModelLabs::delete($lab_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flag($lab_id, $flag = 0) {
		if (User::isUser() == ModelLabs::getUserId($lab_id) or User::isAdmin()) {
			ModelLabs::editFlag($lab_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flagModer($lab_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelLabs::editFlagModer($lab_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function sendMessage($lab_id) {
		if (Request::PostIsNull('message', 'user_id')) {
			
			if (Request::post('user_id', 'int') != User::isUser()) {
				$message_id = Site::saveUserMessage(
					$lab_id, 
					7,
					User::isUser(), 
					Request::post('user_id', 'int'), 
					Request::post('message', 'string')
				);
				
				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelLabs::getLabFull($lab_id);
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
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/lab/$lab_id-$translit'><b>{$data['name']}</b></a>",
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
				
				$data		= ModelLabs::getLabFull($lab_id);
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
						'link'				=> $base_url . 'lab/' . $lab_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-7-' . $lab_id
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
			$data 			= ModelLabs::getLabFull($lab_id);
			$user_from_info = User::getUserInfo($data['user_id']);
			
			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $lab_id
				),
				'mess_tpls'	=> Site::getMessTplsToSelect(7),
				'controller'=> 'lab'
			));
		}
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
	
	 public function remove(){
	
	   if(User::isAdmin()){
		  	ModelLabs::remove();
	    }else{
		  Header::Location('/404');
		}
	 }	
}