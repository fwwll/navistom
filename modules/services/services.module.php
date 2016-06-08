<?php

class Services {
	
	public function index($categ_id = 0, $city_id = 0, $user_id = 0, $page = 1, $search = null, $is_updates = 0, $translit = '') {
		Site::setSectionView(9, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
		$search = (string) Str::get($search)->filterString();
		
		$count = ModelServices::getServicesCount(Request::get('country'), $categ_id, $city_id, $user_id, $search, $is_updates, $flag);
		
		$cities = ModelServices::getServicesCities(Request::get('country'), true, $categ_id);
		
		if ($categ_id > 0) {
			$meta = ModelServices::getCategoryMetaTags($categ_id);
			
			Header::SetTitle($meta['meta_title']);
			
			Header::SetH1Tag( $meta['title']);
			
			Header::SetMetaTag('description', $meta['meta_description']);
			Header::SetMetaTag('keywords', $meta['meta_keys']);
		}
		
		/* if ($city_id > 0) {
			foreach ($cities as $key => $value) {
				if ($key == $city_id) {
					Header::SetTitle(Header::GetTitle() . ' - ' . $value);
					
					Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $value );
					
					Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $value);
					Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $value);
					
					break;
				}
			}
		} */
		 
		if ($user_id > 0) {
			$user = User::getUserName($user_id);
			
			Header::SetTitle(Header::GetTitle() . ' - ' . $user);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $user);
			Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $user);
		}
		 
		$city=Site::cityName();
       if ($city) {
		    Header::SetTitle( $city.' - '.Header::GetTitle() );
			Header::SetH1Tag( $city.' - '.Header::GetH1Tag());
			Header::SetMetaTag('description',$city.' - '.Header::GetMetaTag('description') );	
            Header::SetMetaTag('keywords', $city.','.Header::GetMetaTag('keywords')	);		
	   }
	   
	   
	   
	   
		$categs		= ModelServices::getCategoriesFromSelect(true);
		$subscribe 	= ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 9);
			
		if ($categ_id > 0 and is_array($subscribe)) {
			$subscribe_status = in_array($categ_id, $subscribe);
		}
		else {
			$subscribe_status =  count($subscribe) == count($categs);
		}
		
		if($_GET['tp']=='new'){
	            
			 $tpl_name='services-new2.tpl';  
		   }else{
			 /* $tpl_name='services.tpl'; */
			 $tpl_name='services-new2.tpl'; 			 
		   }
		     
		echo Registry::get('twig')->render($tpl_name, array(
			'categs'	=> $categs,
			'cities'	=> $cities,
			'services'	=> ModelServices::getServicesList(Request::get('country'), $categ_id, $city_id, $user_id, $page, Registry::get('config')->itemsInPage, $search, $is_updates, $flag),
			'pagination'=> Site::pagination(Registry::get('config')->itemsInPage, $count, $page),
			'subscribe_status'	=> $subscribe_status,
			'cityName'=>Site::cityName()
		));
	}
	
	public function full($service_id) {
		ModelServices::setViews(User::isUser(), $service_id);
		
		$service = ModelServices::getServiceFull($service_id);

        if (!$service['service_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $service['user_id'] != User::isUser() and $service['flag'] == 0) {
			Header::Location('/404');
		}
		
		Header::SetTitle($service['name']);
			
		Header::SetMetaTag('description', Str::get($service['content'])->truncate(200) . ', ' . $service['contact_phones']);
		Header::SetMetaTag('keywords', '-');
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/services/160x200/' . $service['url_full']);
		
		$vip = ModelServices::getVIP($service['country_id'], $service['categs'], $service_id);
		
		//Site::d($vip);
		
		
		$exchanges = User::getExchangesUser($service['country_id'], $service['user_id']);
		

        if ($exchanges[$service['currency_id']]) {
            $price = bcmul($service['price'], $exchanges[$service['currency_id']][0]['rate'], 2);
        }
        else {
            $price = $service['price'];
        }

        foreach ($exchanges as $currency_id => $val) {
            $prices[] = array(
                'name'	=> $val[0]['name_min'],
                'val'	=> @bcdiv($price, $val[0]['rate'], 2)
            );
        }
		
		$currency = Registry::get('config')->default_currency;
		
		if($_GET['tp']=='new'){
	            
			 $tpl_name='service-new-full.tpl';  
		   }else{
			 $tpl_name='service-full.tpl';
			$tpl_name='service-new-full.tpl';			 
		   }
		
		
		
		echo Registry::get('twig')->render($tpl_name, array(
			'service'	=> $service,
			'gallery'	=> ModelServices::getServiceGallery($service_id),
			'prices'    =>$prices,
			'price'		=> $price,
			 'currency'	=> $currency[$service['country_id']],
			'vip'		=> $vip
		));
	}
	
	public function add() {
		$price =Site::getPriceCategoriy(9);
		$price_json=Site::dataJsoneString ($price);
		$chekbox= Site::getPriceCategoriyCheked(9);
		echo Registry::get('twig')->render('service-add.tpl', array(
			'categs'		=> ModelServices::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect(Request::get('country')),
			'is_add_access'	=> User::isUserAccess(9),
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1)),
			'price'			=> $price,
			'price_json'	=> $price_json,
			'chekbox'		=>$chekbox
		));
	}
	
	public function addAjax() {
		Header::ContentType("text/plain");
		
		if (User::isUserAccess(9)) {
			if (Request::PostIsNull('user_name', 'region_id', 'city_id', 'name')) {
				$user_info = User::getUserContacts();
				
				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelServices::uploadAttach($_FILES['attachment']);
				}
				else {
					$attachment = '';
				}
				
				$service_id = ModelServices::add(User::isUser(), Request::get('country'), array(
					'user_name'			=> Request::post('user_name', 'string'),
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'address'			=> Request::post('address', 'string'),
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
					'attach'			=> $attachment,
					'video_link'		=> Request::post('video_link', 'url'),
					 'country_id'		=> Request::get('country'),
					'price'				=> Request::post('price', 'float'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'flag_vip_add'		=> Request::post('submit', 'string') == 'vip' ? 1 : 0
				), 
					Request::post('categ_id'), 
					Request::post('images'), 
					User::isPostModeration(9) ? 1 : 0
				);
				
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(9, $service_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($service_id,9); 
					

					
				}
				
				if ($service_id > 0) {
					$result = array(
						'success'	=> true,
						'message'	=> User::isPostModeration(9) ? 'Предложение успешно добавлено' : 'Предложение добавлено на модерацию',
						
						'send_data'	=> $data['send_data'],
						'portmone'	=> $data['portmone'],
						'product_id'=>$service_id
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
	
	public function edit($service_id) {
		$data	= ModelServices::getServiceData($service_id);
		$images	= ModelServices::getServiceImages($service_id);
		
		$data['categ_id'] = explode(',', $data['categ_id']);
		
		echo Registry::get('twig')->render('service-edit.tpl', array(
			'data'			=> $data,
			'categs'		=> ModelServices::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect($data['country_id']),
			'cities'		=> Site::getCitiesFromSelect($data['region_id']),
			'images'		=> $images,
			'images_count'	=> 7 - count($images)
		));
	}
	
	public function editAjax($service_id) {
		if (User::isUser() == ModelServices::getUserId($service_id) or User::isAdmin()) {
			if (Request::PostIsNull('user_name', 'region_id', 'city_id', 'name')) {
				
				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelServices::uploadAttach($_FILES['attachment']);
					
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
				
				ModelServices::edit($service_id, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'address'			=> Request::post('address', 'string'),
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'name'				=> Request::post('name', 'string'),
					'content'			=> Request::post('content', 'string'),
					'attach'			=> $attachment,
					'currency_id'		=> Request::post('currency_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'video_link'		=> Request::post('video_link', 'url')
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
	
	public function delete($service_id) {
		if (User::isUser() == ModelServices::getUserId($service_id) or User::isAdmin()) {
			ModelServices::delete($service_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flag($service_id, $flag = 0) {
		if (User::isUser() == ModelServices::getUserId($service_id) or User::isAdmin()) {
			ModelServices::editFlag($service_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flagModer($service_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelServices::editFlagModer($service_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function sendMessage($service_id) {
		if (Request::PostIsNull('message', 'user_id')) {
			
			if (Request::post('user_id', 'int') != User::isUser()) {
				$message_id = Site::saveUserMessage(
					$service_id, 
					9,
					User::isUser(), 
					Request::post('user_id', 'int'), 
					Request::post('message', 'string')
				);
				
				/**
				 * New Notification
				 */
				
				$from 		= User::getUserContacts();
				$to			= User::getUserInfo(Request::post('user_id', 'int'));
				
				$data		= ModelServices::getServiceFull($service_id);
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
						'link'				=> $base_url . 'service/' . $service_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-9-' . $service_id
					),
					$attach
				);
				
				/* End Notification */
				
				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelServices::getServiceFull($service_id);
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
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/service/$service_id-$translit'><b>{$data['name']}</b></a>",
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
			$data 			= ModelServices::getServiceFull($service_id);
			$user_from_info = User::getUserInfo($data['user_id']);
			
			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $service_id
				),
				'mess_tpls'	=> Site::getMessTplsToSelect(9),
				'controller'=> 'service'
			));
		}
	}
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelServices::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelServices::deleteImage($image_id)
		));
	}
	
	public function remove(){
	
		if(User::isAdmin()){
		  	 ModelServices::servicesRemove();
		}else{
		  Header::Location('/404');
		}
	}
}