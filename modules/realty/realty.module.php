<?php

class Realty {
	public function index($categ_id = 0, $city_id = 0, $user_id = 0, $page = 1, $search = null, $is_updates = 0, $translit = '') {
		Site::setSectionView(8, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
		$cities = ModelRealty::getRealtyCities(Request::get('country'), $categ_id);
		
		$search = (string) Str::get($search)->filterString();
		
		$count = ModelRealty::getRealtyCount(Request::get('country'), $categ_id, $city_id, $user_id, $search, $is_updates, $flag);
		
		if ($categ_id > 0) {
			$meta = ModelRealty::getCategoryMetaTags($categ_id);
			
			Header::SetTitle($meta['meta_title']);
			
			Header::SetH1Tag($meta['title']);
			
			Header::SetMetaTag('description', $meta['meta_description']);
			Header::SetMetaTag('keywords', $meta['meta_keys']);
		}
		
		/* if ($city_id > 0) {
			foreach ($cities as $key => $value) {
				if ($key == $city_id) {
					Header::SetTitle(Header::GetTitle() . ' - ' . $value);
					
					Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $value);
					
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
		
		if ($page > 1) {
			Header::SetTitle(Header::GetTitle() . ' - страница ' . $page);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - страница ' . $page);
		}
		
	  $city=Site::cityName();
       if ($city) {
		    Header::SetTitle( $city.' - '.Header::GetTitle() );
			Header::SetH1Tag( $city.' - '.Header::GetH1Tag());
			Header::SetMetaTag('description',$city.' - '.Header::GetMetaTag('description') );	
            Header::SetMetaTag('keywords', $city.','.Header::GetMetaTag('keywords')	);		
	   }
		$categs = ModelRealty::getCategoriesFromSelect(1);
		
		if (User::isUser()) {
			$subscribe 	= ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 8);
			
			if ($categ_id > 0 and is_array($subscribe)) {
				$subscribe_status = in_array($categ_id, $subscribe);
			}
			else {
				$subscribe_status =  count($subscribe) == count($categs);
			}
		}
		
		if($_GET['tp']=='new'){
		 $tpl_name='realty-new2.tpl';
	    }else{
		 $tpl_name='realty.tpl';
		 $tpl_name='realty-new2.tpl';	
		}
		
		echo Registry::get('twig')->render($tpl_name, array(
			'categs'	=> $categs,
			'cities'	=> $cities,
			'realty'	=> ModelRealty::getRealtyList(Request::get('country'), $categ_id, $city_id, $user_id, $page, Registry::get('config')->itemsInPage, $search, $is_updates, $flag),
			'pagination'=> Site::pagination(Registry::get('config')->itemsInPage, $count, $page),
			'subscribe_status'	=> $subscribe_status,
			'cityName'=>Site::cityName()
		));
	}
	
	public function full($realty_id) {
		ModelRealty::setViews(User::isUser(), $realty_id);
		
		$realty = ModelRealty::getRealtyFull($realty_id);

        if (!$realty['realty_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $realty['user_id'] != User::isUser() and $realty['flag'] == 0) {
			Header::Location('/404');
		}

		Header::SetTitle($realty['name'] . ', г. ' . $realty['city_name']);
			
		Header::SetMetaTag('description', Str::get($realty['content'])->truncate(200, null, 1) . ', ' . $realty['contact_phones']);
		Header::SetMetaTag('keywords', '-');
		
		$exchanges = User::getExchangesUser($realty['country_id'], $realty['user_id']);
		
		if ($exchanges[$realty['currency_id']]) {
			$price = bcmul($realty['price'], $exchanges[$realty['currency_id']][0]['rate'], 2);
		}
		else {
			$price = $realty['price'];
		}
		
		foreach ($exchanges as $currency_id => $val) {
			$prices[] = array(
				'name'	=> $val[0]['name_min'],
				'val'	=> bcdiv($price, $val[0]['rate'], 2)
			);
		}
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/realty/160x200/' . $realty['url_full']);
		
		$currency = Registry::get('config')->default_currency;
		
		$vip = ModelRealty::getVIP($realty['country_id'], $realty['categ_id'], $realty_id);
		
		if(count($vip) > 0) {
			Registry::set('exchanges', User::getExchanges(Request::get('country')));
		}
		
		if($_GET['tp']=='new'){

		 $tpl_name='realty-new-full.tpl';
		 // $tpl_name='realty-full.tpl';	
	    }else{
		 $tpl_name='realty-full.tpl';   
	      $tpl_name='realty-new-full.tpl'; 
		}
		 // Site::d($vip);
		echo Registry::get('twig')->render($tpl_name, array(
			'realty'	=> $realty,
			'price'		=> $price,
			'currency'	=> $currency[$realty['country_id']],
			'prices'	=> $prices,
			'gallery'	=> ModelRealty::getRealtyGallery($realty_id),
			'vip'		=> $vip
		));
	}
	
	public function add() {
		Header::SetTitle('Добавить недвижимость' . ' - ' . Header::GetTitle());
		Header::SetMetaTag('description', 'Добавить недвижимость');
		Header::SetMetaTag('keywords', 'Добавить недвижимость');
		
		echo Registry::get('twig')->render('realty-add.tpl', array(
			'categs'		=> ModelRealty::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect(Request::get('country')),
			'is_add_access'	=> User::isUserAccess(8),
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		));
	}
	
	public function addAjax() {
		Header::ContentType("text/plain");
		
		if (User::isUserAccess(8)) {
			if (Request::PostIsNull('categ_id', 'city_id', 'name')) {
				$user_info = User::getUserContacts();
				
				$realty_id = ModelRealty::add(User::isUser(), Request::get('country'), array(
					'user_name'			=> $user_info['name'],
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'categ_id'			=> Request::post('categ_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'price_description'	=> Request::post('price_description', 'string'),
					'name'				=> Request::post('name', 'string'),
					'address'			=> Request::post('address', 'string'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag_vip_add'		=> Request::post('submit', 'string') == 'vip' ? 1 : 0
				), 
					Request::post('images'), 
					User::isPostModeration(8)
				);
				
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(8, $realty_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($realty_id,8); 
				}
				
				if ($realty_id > 0) {
					$result = array(
						'success'	=> true,
						'message'	=> User::isPostModeration(8) ? 'Предложение успешно добавлено' : 'Предложение добавлено на модерацию',
						'send_data'	=> $data['send_data'],
						'portmone'	=> $data['portmone'],
						'product_id'=>$realty_id
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
	
	public function edit($realty_id) {
		$data	= ModelRealty::getRealtyData($realty_id);
		$images	= ModelRealty::getRealtyImages($realty_id);
		
		echo Registry::get('twig')->render('realty-edit.tpl', array(
			'data'			=> $data,
			'categs'		=> ModelRealty::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect($data['country_id']),
			'cities'		=> Site::getCitiesFromSelect($data['region_id']),
			'images'		=> $images,
			'images_count'	=> 7 - count($images)
		));
	}
	
	public function editAjax($realty_id) {
		if (User::isUser() == ModelRealty::getUserId($realty_id) or User::isAdmin()) {
			if (Request::PostIsNull('categ_id', 'city_id', 'name')) {

				ModelRealty::edit($realty_id, array(
					'categ_id'			=> Request::post('categ_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'price_description'	=> Request::post('price_description', 'string'),
					'name'				=> Request::post('name', 'string'),
					'address'			=> Request::post('address', 'string'),
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
	
	public function delete($realty_id) {
		if (User::isUser() == ModelRealty::getUserId($realty_id) or User::isAdmin()) {
			ModelRealty::delete($realty_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flag($realty_id, $flag = 0) {
		if (User::isUser() == ModelRealty::getUserId($realty_id) or User::isAdmin()) {
			ModelRealty::editFlag($realty_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flagModer($realty_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelRealty::editFlagModer($realty_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function sendMessage($realty_id) {
		if (Request::PostIsNull('message', 'user_id')) {
			
			if (Request::post('user_id', 'int') != User::isUser()) {
				$message_id = Site::saveUserMessage(
					$realty_id, 
					8,
					User::isUser(), 
					Request::post('user_id', 'int'), 
					Request::post('message', 'string')
				);
				
				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelRealty::getRealtyFull($realty_id);
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
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/realty/$realty_id-$translit'><b>{$data['name']}</b></a>",
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
				
				$from 		= User::getUserContacts();
				$to			= User::getUserInfo(Request::post('user_id', 'int'));
				
				$data		= ModelRealty::getRealtyFull($realty_id);
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
						'price'				=> $data['price'],
						'currency_name'		=> $data['currency_name'],
						'link'				=> $base_url . 'realty/' . $realty_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-8-' . $realty_id
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
			$data 			= ModelRealty::getRealtyFull($realty_id);
			$user_from_info = User::getUserInfo($data['user_id']);
			
			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $realty_id
				),
				'mess_tpls'	=> Site::getMessTplsToSelect(8),
				'controller'=> 'realty'
			));
		}
	}
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelRealty::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelRealty::deleteImage($image_id)
		));
	}
	
	
	public function remove(){
	
	   if(User::isAdmin()){
		  	ModelRealty::remove();
	    }else{
		  Header::Location('/404');
		}
	 }	
	
}