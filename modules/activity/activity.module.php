<?php

class Activity {
	public function index($categ_id = 0, $city_id = 0, $page = 1, $user_id = 0, $sort_by = null, $is_updates = 0, $search = null, $translit = '') {
		Site::setSectionView(5, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }

		$search = (string) Str::get($search)->trim()->strToLower()->removeSymbols();

		$activity	= ModelActivity::getActivityList($categ_id, $city_id, $page, $user_id, $sort_by, Registry::get('config')->itemsInPage, $is_updates, $search, $flag);
		$categs		= ModelActivity::getCategoriesFromSelect(1);
		$count 		= ModelActivity::getActivityCount($categ_id, $city_id, $user_id, Request::get('country'), $is_updates, $search, $flag);
		$cities		= ModelActivity::getCitiesFromFilterSelect(Request::get('country'), $categ_id);
		$count=$count+4; 
		  /* if($_SERVER['REMOTE_ADDR']=='93.188.36.16'){
			echo'<pre>';
			var_dump($count );
			die;
		}   */
		
		

        for($i = 0, $c = count($categs); $i < $c; $i ++) {
            $groupCategories[$categs[$i]['categ_id']] = $categs[$i]['name'];
        }

        for ($i = 0, $c = count($activity); $i < $c; $i++) {
            $activity[$i]['categs'] = array_intersect_key($groupCategories, array_flip(explode(',', $activity[$i]['categs'])));
        }

		$pagination = Site::pagination(Registry::get('config')->itemsInPage, $count, $page);

		if ($categ_id > 0) {
			$meta = ModelActivity::getCategoryMetaTags($categ_id);

			Header::SetTitle($meta['meta_title']);

			Header::SetH1Tag($meta['title']);

			Header::SetMetaTag('description', $meta['meta_description']);
			Header::SetMetaTag('keywords', $meta['meta_keys']);
		}

		if ($city_id > 0) {
			for ($i = 0, $c = count($cities); $i < $c; $i++) {
				if ($cities[$i]['city_id'] == $city_id) {
					Header::SetTitle( $cities[$i]['name'].' - '. Header::GetTitle() );

					Header::SetH1Tag($cities[$i]['name'].' - '. Header::GetH1Tag() );

					Header::SetMetaTag('description', $cities[$i]['name'].' - '.Header::GetMetaTag('description') );
					Header::SetMetaTag('keywords',  $cities[$i]['name']. ', '. Header::GetMetaTag('keywords') );

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

		/* $city=Site::cityName();
       if ($city) {
		    Header::SetTitle( $city.' '.Header::GetTitle() );
			Header::SetH1Tag( $city.' '.Header::GetH1Tag());
			Header::SetMetaTag('description',$city.' '.Header::GetMetaTag('description') );	
            Header::SetMetaTag('keywords', $city.','.Header::GetMetaTag('keywords')	);		
	   }
		 */
		
		if (User::isUser()) {
			$subscribe 	= ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 5);

			if ($categ_id > 0 and is_array($subscribe)) {
				$subscribe_status = in_array($categ_id, $subscribe);
			}
			else {
				$subscribe_status =  count($subscribe) == count($categs);
			}
		}
         
		if($_GET['tp']=='new'){
	            
			 $tpl_name='activity-new2.tpl';  
		   }else{
			/*  $tpl_name='activity.tpl';  */
			$tpl_name='activity-new2.tpl'; 
			 
		   }

		 // Site::d($activity);
		echo Registry::get('twig')->render($tpl_name, array(
			'activity'			=> $activity,
			'categs'			=> $categs,
			'cities'			=> $cities,
			'pagination'		=> $pagination,
			'subscribe_status'	=> $subscribe_status,
            'isView'            => User::isShowSection(5),
			'cityName'=>Site::cityName(),
			 'category_name'=> Site::categoryName('activity'),
		));
	}

	public function add() {
		$categs 	= ModelActivity::getCategoriesFromSelect();
		$regions	= Site::getRegionsFromSelect(Request::get('country'));

		Header::SetTitle('Добавить мероприятие' . ' - ' . Header::GetTitle());
		Header::SetMetaTag('description', 'Добавить мероприятие');
		Header::SetMetaTag('keywords', 'Добавить мероприятие');

		echo Registry::get('twig')->render('activity-add.tpl', array(
			'categs'		=> $categs,
			'regions'		=> $regions,
			'is_add_access'	=> User::isUserAccess(5),
			'subscribe_status'	=> $subscribe_status,
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		));
	}

	public function full($activity_id) {

		ModelActivity::setViews($activity_id, User::isUser());

		$activity	= ModelActivity::getActivityFull($activity_id);

        if (!$activity['activity_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $activity['user_id'] != User::isUser() and $activity['flag'] == 0) {
			Header::Location('/404');
		}

		if (Registry::get('ajax') == -1 and $activity['flag_delete'] > 0) {
			Header::Location('/' . Registry::get('country_url') . '/activity/' . 'categ-' . key($activity['categs']) . '-' . Str::get($activity['categs'][key($activity['categs'])])->truncate(60)->translitURL() .'#404');
		}

		$lectors	= ModelActivity::getActivityLectors($activity_id);
		

		Header::SetTitle($activity['name']);

		if ($activity['flag_agreed'] > 0) {
			$dates = 'дата по согласованию';
		}
		else {
			$dates = Str::get($activity['date_start'])->getRusDate();

			if ($activity['date_end'] != '0000-00-00') {
				$dates .= ' - ' . Str::get($activity['date_end'])->getRusDate();
			}
		}

		for ($i = 0, $c = count($lectors); $i < $c; $i++) {
			$lectors_name[] = $lectors[$i]['name'];
		}

		Header::SetMetaTag('description',
			$activity['name'] . ', ' .
			$dates . ', ' .
			implode(', ', (array)$lectors_name) . ', ' .
			$activity['city_name'] . ' ' . $activity['addres'] . ', ' .
			$activity['user_name'] . ', ' .
			$activity['contact_phones']
		);
		Header::SetMetaTag('keywords', '-');

		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/activity/160x200/' . $activity['image']);

		$vip = ModelActivity::getVIP($activity['country_id'], $activity['categs'], $activity_id);
		
		if($_GET['tp']=='new'){
			  $tpl_name='activity-new-full.tpl';  
		   }else{
			  $tpl_name='activity-full.tpl';
			$tpl_name='activity-new-full.tpl';	
		   }

		echo Registry::get('twig')->render($tpl_name, array(
			'activity'	=> $activity,
			'lectors'	=> $lectors,
			'vip'		=> $vip,
			'gallery'	=>  ModelActivity::getActivityGallery($activity_id)
		));
	}

	public function allUsers() {
		Site::get_meta('activity-all-users');
		echo Registry::get('twig')->render('activity-all-users.tpl', array(
			'users'	=> ModelActivity::getAllUsers()
		));
	}

	public function addAjax() {
		Header::ContentType("text/plain");

		if (User::isUserAccess(5)) {
			if (Request::PostIsNull('region_id', 'city_id', 'name')) {

				$lector_name 		= Request::post('lector_name');
				$lector_description	= Request::post('lector_description');

				foreach ($lector_name as $i => $val) {
					if ($_FILES['lector_image']['name'][$i] != null) {
						$lector_image = ModelActivity::uploadLectorImage($_FILES['lector_image']['tmp_name'][$i]);
					}

					$lectors[] = array(
						'lector_name'			=> (string) Str::get($lector_name[$i])->filterString(),
						'lector_description'	=> (string) Str::get($lector_description[$i])->filterString(),
						'lector_image'			=> $lector_image
					);
				}

				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelActivity::uploadActivityAttach($_FILES['attachment']);
				}
				else {
					$attachment = '';
				}

				if ($_FILES['image']['name'] != null) {
					$image = ModelActivity::uploadActivityImage('image');
				}
				else {
					$image = '';
				}
				
				
				
				

				$activity_id = ModelActivity::add(User::isUser(), array(
					'country_id'	=> Request::get('country'),
					'city_id'		=> Request::post('city_id', 'int'),
					'region_id'		=> Request::post('region_id', 'int'),
					'address'		=> Request::post('address', 'string'),
					'date_start'	=> Request::post('date_start', 'string'),
					'date_end'		=> Request::post('date_end', 'string'),
					'flag_agreed'	=> Request::post('flag_agreed', 'int'),
					'name'			=> Request::post('name', 'string'),
					'content'		=> Request::post('content', 'string'),
					'link'			=> Request::post('link', 'url'),
					'contact_phones'=> Request::post('contact_phones', 'string'),
					'image'			=> $image,
					'attachment'	=> $attachment,
					'video_link'	=> Request::post('video_link', 'url'),
					'flag'			=> 1,
					'flag_moder'	=> User::isPostModeration(5) ? 1 : 0,
					'flag_vip_add'	=> Request::post('submit') == 'vip' ? 1 : 0
				), $lectors, Request::post('categ_id'),Request::post('images'));
				
				
				    
				
					
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(5, $activity_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($activity_id,5);
					
				}

				$result = array(
					'success'		=> true,
					'message'		=> User::isPostModeration(5) ? 'Мероприятие успешно добавлено' : 'Мероприятие добавлено на модерацию',
					'activity_id'	=> $activity_id,
					'send_data'	=> $data['send_data'],
					'portmone'	=> $data['portmone'],
					'product_id'=>$activity_id
				);
			}
		}
		else {
			$result = array(
				'success'	=> false,
				'message'	=> 'Вы не можете размещать мероприятия'
			);
		}
			
		echo json_encode($result);
	}

	public function edit($activity_id) {
		$data = ModelActivity::getActivityData($activity_id);
		$images	= ModelActivity::getActivityImages($activity_id);
		

		echo Registry::get('twig')->render('activity-edit.tpl', array(
			'data'			=> $data,
			'images'		=> $images,
			'images_count'	=> 7 - count($images),
			'lectors'		=> ModelActivity::getActivityLectors($activity_id),
			'categs'		=> ModelActivity::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect($data['country_id']),
			'cities'		=> Site::getCitiesFromSelect($data['region_id'])
		));
	}

	public function editAjax($activity_id) {
		if (User::isUser() == ModelActivity::getUserId($activity_id) or User::isAdmin()) {
			if (Request::PostIsNull('region_id', 'city_id', 'name')) {

				$lector_name 		= Request::post('lector_name');
				$lector_description	= Request::post('lector_description');
				$lector_images		= Request::post('this_lector_image');

				foreach ($lector_name as $i => $val) {
					if ($_FILES['lector_image']['name'][$i] != null) {
						$lector_image = ModelActivity::uploadLectorImage($_FILES['lector_image']['tmp_name'][$i]);

						if ($lector_images[$i] != null) {
							@unlink(UPLOADS . '/images/activity/lectors/' . $lector_images[$i]);
						}
					}
					else {
						$lector_image = $lector_images[$i];
					}

					$lectors[] = array(
						'name'			=> (string) Str::get($lector_name[$i])->filterString(),
						'description'	=> (string) Str::get($lector_description[$i])->filterString(),
						'image'			=> $lector_image
					);
				}


				if ($_FILES['attachment']['name'] != null) {
					$attachment = ModelActivity::uploadActivityAttach($_FILES['attachment']);

					if (Request::post('this_attachment') != null) {
						unlink(UPLOADS . '/docs/' . Request::post('this_attachment'));
					}
				}
				elseif (Request::post('this_attachment') != null) {
					$attachment = Request::post('this_attachment');
				}
				else {
					$attachment = '';
				}

				if ($_FILES['image']['name'] != null) {
					$image = ModelActivity::uploadActivityImage('image');

					if (Request::post('this_image') != null) {
						@unlink(UPLOADS . '/images/activity/full/' 		. Request::post('this_image'));
						@unlink(UPLOADS . '/images/activity/160x200/' 	. Request::post('this_image'));
						@unlink(UPLOADS . '/images/activity/80x100/' 	. Request::post('this_image'));
						@unlink(UPLOADS . '/images/activity/64x80/' 	. Request::post('this_image'));
					}
				}
				elseif (Request::post('this_image') != null) {
					$image = Request::post('this_image');
				}
				else {
					$image = '';
				}

				ModelActivity::editActivity($activity_id, array(
					'city_id'		=> Request::post('city_id', 'int'),
					'city_name'		=> DB::getColumn("SELECT name FROM `cities` WHERE city_id = " . Request::post('city_id', 'int')),
					'region_id'		=> Request::post('region_id', 'int'),
					'address'		=> Request::post('address', 'string'),
					'date_start'	=> Request::post('date_start', 'string'),
					'date_end'		=> Request::post('date_end', 'string'),
					'flag_agreed'	=> Request::post('flag_agreed', 'int'),
					'name'			=> Request::post('name', 'string'),
					'content'		=> Request::post('content', 'string'),
					'contact_phones'=> Request::post('contact_phones', 'string'),
					'link'			=> Request::post('link', 'url'),
					'image'			=> $image,
					'attachment'	=> $attachment,
					'video_link'	=> Request::post('video_link', 'url')
				),
					Request::post('categ_id'),
					$lectors,Request::post('images') 
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

	public function delete($activity_id) {
		if (User::isUser() == ModelActivity::getUserId($activity_id) or User::isAdmin()) {
			ModelActivity::delete($activity_id);
		}

		Header::Location($_SERVER['HTTP_REFERER']);
	}

	public function flag($activity_id, $flag = 0) {
		if (User::isUser() == ModelActivity::getUserId($activity_id) or User::isAdmin()) {
			ModelActivity::editFlag($activity_id, $flag);
		}

		Header::Location($_SERVER['HTTP_REFERER']);
	}

	public function flagModer($activity_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelActivity::editFlagModer($activity_id, $flag_moder);
		}

		Header::Location($_SERVER['HTTP_REFERER']);
	}

	public function sendMessage($activity_id) {

		if (Request::PostIsNull('message', 'user_id')) {

			if (Request::post('user_id', 'int') != User::isUser()) {
				$message_id = ModelActivity::saveUserMessage(
					$activity_id,
					User::isUser(),
					Request::post('user_id', 'int'),
					Request::post('message', 'string')
				);

				/**
				 * New Notification
				 */

				$from 			= User::getUserContacts();
				$to				= User::getUserInfo(Request::post('user_id', 'int'));

				$data		= ModelActivity::getActivityFull($activity_id);
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
						'link'				=> $base_url . 'activity/' . $activity_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-5-' . $activity_id
					),
					$attach
				);

				/* End Notification */

				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelActivity::getActivityFull($activity_id);
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
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/activity/$activity_id-$translit'><b>{$data['name']}</b></a>",
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
			$data 			= ModelActivity::getActivityFull($activity_id);
			$user_from_info = User::getUserInfo($data['user_id']);

			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $activity_id
				),
				'messages'	=> ModelProducts::getUserMessages($product_new_id, User::isUser()),
				'mess_tpls'	=> Site::getMessTplsToSelect(5),
				'controller'=> 'activity'
			));
		}
	}

	
	
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelActivity::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelActivity::deleteImage($image_id)
		));
	}
	
	public function remove(){
		 if(User::isAdmin()){ 
		    ModelActivity::deleteActivity();
		 }else{
		    Header::Location('/404');
	     }
	}
	
}