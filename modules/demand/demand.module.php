<?php

class Demand {
	
	public function index($user_id = 0, $page = 1, $search = null, $is_updates = 0, $translit = '') {
		
		
		Site::setSectionView(11, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
		$search = (string) Str::get($search)->filterString();
		
		$count		= ModelDemand::getDemandCount(Request::get('country'), $user_id, $search, $is_updates, $flag);
		$pagination = Site::pagination(Registry::get('config')->itemsInPage, $count, $page);
		
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
		
		$subscribe 	= ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 11);
		if($_GET['tp']=='new'){
			  $tpl_name='demand-new2.tpl';  
		   }else{
			 /*  $tpl_name='demand.tpl'; */
				$tpl_name='demand-new2.tpl';
		   }
		
		echo Registry::get('twig')->render($tpl_name, array(
			'demand'		=> ModelDemand::getDemandList(Request::get('country'), $user_id, $page, Registry::get('config')->itemsInPage, $search, $is_updates, $flag),
			'pagination'	=> $pagination,
			'subscribe_status'	=> $subscribe[0]
		));
	}
	
	public function full($demand_id) {
		ModelDemand::setViews(User::isUser(), $demand_id);
		
		$demand = ModelDemand::getDemandFull($demand_id);

        if (!$demand['demand_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $demand['user_id'] != User::isUser() and $demand['flag'] == 0) {
			Header::Location('/404');
		}
		
		if (Registry::get('ajax') == -1 and $demand['flag_delete'] > 0) {
			Header::Location('/demand');
		}
		
		Header::SetTitle($demand['name']);
			
		Header::SetMetaTag('description', Str::get($demand['content'])->truncate(200, null, 1));
		Header::SetMetaTag('keywords', '-');
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/demand/160x200/' . $demand['url_full']);
		
		$vip = ModelDemand::getVIP($demand['country_id'], $demand_id);
		
		if($_GET['tp']=='new'){
			  $tpl_name='demand-new-full.tpl';  
		   }else{
			  $tpl_name='demand-full.tpl';
			 $tpl_name='demand-new-full.tpl'; 	
		   }
		
		echo Registry::get('twig')->render($tpl_name, array(
			'demand'	=> $demand,
			'gallery'	=> ModelDemand::getDemandGallery($demand_id),
			'vip'		=> $vip
		));
	}
	
	public function add() {
		Header::SetTitle('Добавить заявку' . ' - ' . Header::GetTitle());
		Header::SetMetaTag('description', 'Добавить заявку');
		Header::SetMetaTag('keywords', 'Добавить заявку');
		
		echo Registry::get('twig')->render('demand-add.tpl', array(
			'is_add_access'	=> User::isUserAccess(11), 

			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		));
	}
	
	public function addAjax() {
		Header::ContentType("text/plain");
		
		if (User::isUserAccess(11)) {
			if (Request::PostIsNull('name')) {
				$user_info = User::getUserContacts();
				
				$demand_id = ModelDemand::add(User::isUser(), Request::get('country'), array(
					'user_name'			=> $user_info['name'],
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag_vip_add'		=> Request::post('submit', 'string') == 'vip' ? 1 : 0
				), 
					Request::post('images'),
					User::isPostModeration(11)
				);
				
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(11, $demand_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($demand_id,11); 
					
				}
				
				if ($demand_id > 0) {
					$result = array(
						'success'	=> true,
						'message'	=> User::isPostModeration(11) ? 'Заявка успешно добавлена' : 'Заявка добавлена на модерацию',
						'send_data'	=> $data['send_data'],
						'portmone'	=> $data['portmone'],
						'product_id'=>$demand_id
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
	
	public function edit($demand_id) {
		$data	= ModelDemand::getDemandData($demand_id);
		$images	= ModelDemand::getDemandImages($demand_id);
		
		echo Registry::get('twig')->render('demand-edit.tpl', array(
			'data'			=> $data,
			'images'		=> $images,
			'images_count'	=> 7 - count($images)
		));
	}
	
	public function editAjax($demand_id) {
		if (User::isUser() == ModelDemand::getUserId($demand_id) or User::isAdmin()) {
			if (Request::PostIsNull('name')) {

				ModelDemand::edit($demand_id, array(
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
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
	
	public function delete($demand_id) {
		if (User::isUser() == ModelDemand::getUserId($demand_id) or User::isAdmin()) {
			ModelDemand::delete($demand_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flag($demand_id, $flag = 0) {
		if (User::isUser() == ModelDemand::getUserId($demand_id) or User::isAdmin()) {
			ModelDemand::editFlag($demand_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flagModer($demand_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelDemand::editFlagModer($demand_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function sendMessage($demand_id) {
		if (Request::PostIsNull('message', 'user_id')) {
			
			if (Request::post('user_id', 'int') != User::isUser()) {
				$message_id = Site::saveUserMessage(
					$demand_id, 
					11,
					User::isUser(), 
					Request::post('user_id', 'int'), 
					Request::post('message', 'string')
				);
				
				/**
				 * New Notification
				 */
				
				$from 		= User::getUserContacts();
				$to			= User::getUserInfo(Request::post('user_id', 'int'));
				
				$data		= ModelDemand::getDemandFull($demand_id);
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
						'link'				=> $base_url . 'demand/' . $demand_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-11-' . $demand_id
					),
					$attach
				);
				
				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelDemand::getDemandFull($demand_id);
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
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/demand/$demand_id-$translit'><b>{$data['name']}</b></a>",
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
			$data 			= ModelDemand::getDemandFull($demand_id);
			$user_from_info = User::getUserInfo($data['user_id']);
			
			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $demand_id
				),
				'mess_tpls'	=> Site::getMessTplsToSelect(11),
				'controller'=> 'demand'
			));
		}
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
	
	
	public function remove(){
		
		if(User::isAdmin())	
		     ModelDemand::removeDemand();
		  else
			Header::Location('/404');  
	}
}