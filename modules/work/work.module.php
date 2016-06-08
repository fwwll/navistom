<?php

class Work {
	public function resumeIndex($categ_id = 0, $city_id = 0, $min = null, $max = null, $user_id = 0, $page = 1, $search = null, $is_updates = 0, $translit = '') {
		Site::setSectionView(6, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
		$search = (string) Str::get($search)->trim()->strToLower()->removeSymbols();
		
		$cities	= ModelWork::getWorkCities(1, $categ_id, 1, true);
		$resume = ModelWork::getResumeList(1, $categ_id, $city_id, Request::get('country'), $min, $max, $user_id, $page, $search, Registry::get('config')->itemsInPage, $is_updates, $search, $flag);
		$count 	= ModelWork::getWorkCount(1, $categ_id, $city_id, Request::get('country'), $min, $max, $user_id, $search, $is_updates, $flag);

		if ($categ_id > 0) {
			$meta = ModelWork::getCategoryMetaTags($categ_id);
			
			Header::SetTitle($meta['meta_title'] . ' -  резюме');
			
			Header::SetH1Tag('Резюме - ' . $meta['title']);
			
			Header::SetMetaTag('description', $meta['meta_description']);
			Header::SetMetaTag('keywords', $meta['meta_keys']);
		}
		
		if ($city_id > 0) {
			foreach ($cities as $key => $val) {
				if ($key == $city_id) {
					Header::SetTitle(Header::GetTitle() . ' - ' . $val);
					
					Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $val);
					
					Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $val);
					Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $val);
					
					break;
				}
			}
		}
		
		if ($page > 1) {
			Header::SetTitle(Header::GetTitle() . ' - страница ' . $page);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - страница ' . $page);
		}
		
		if ($user_id > 0) {
			$user = User::getUserName($user_id);
			
			Header::SetTitle(Header::GetTitle() . ' - ' . $user);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $user);
			Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $user);
		}
		
		$categs = ModelWork::getCategoriesFromSelect(true);
		
		
		$city=Site::cityName();
       if ($city) {
		    Header::SetTitle( $city.' - '.Header::GetTitle() );
			Header::SetH1Tag( $city.' - '.Header::GetH1Tag());
			Header::SetMetaTag('description',$city.' - '.Header::GetMetaTag('description') );	
            Header::SetMetaTag('keywords', $city.','.Header::GetMetaTag('keywords')	);		
	   }
		
		
		if (User::isUser()) {
			$subscribe 	= ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 6);
			
			if ($categ_id > 0 and is_array($subscribe)) {
				$subscribe_status = in_array($categ_id, $subscribe);
			}
			else {
				$subscribe_status =  count($subscribe) >= count($categs);
			}
		}
		if($_GET['tp']=='new'){
	            
			 $tpl_name='resume-new2.tpl';  
		   }else{
			 $tpl_name='resume-new2.tpl';	
		   }
		
		echo Registry::get('twig')->render($tpl_name, array(
			'categories'	=> $categs,
			'cities'		=> $cities,
			'resume'		=> $resume,
			'pagination'	=> Site::pagination(Registry::get('config')->itemsInPage, $count, $page),
			'subscribe_status'	=> $subscribe_status,
			'cityName'=>Site::cityName()
		));
	}
	
	public function resumeFull($work_id) {
		ModelWork::setViews($work_id, User::isUser());
		
		$resume = ModelWork::getWorkFull($work_id);
		

        if (!$resume['work_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $resume['user_id'] != User::isUser() and $resume['flag'] == 0) {
			Header::Location('/404');
		}
		
		Header::SetTitle('Резюме ' . $resume['user_name'] . ' ' . $resume['user_surname'] . ' - ' . implode(', ', $resume['categs']));
		
		Header::SetMetaTag('description', 'Резюме ' . $resume['user_name'] . ' ' . $resume['user_surname'] . ' - ' . implode(', ', $resume['categs']));
		Header::SetMetaTag('keywords', $resume['user_name'] . ' ' . $resume['user_surname'] . ', ' . implode(', ', $resume['categs']));
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/' . ($resume['image'] != '' ? 'images/work/160x200/' . $resume['image'] : 'users/avatars/full/' . $resume['avatar']));
		
		$vip = ModelWork::getResumeVIP($resume['country_id'], $resume['categs'], $work_id);
		
		if($_GET['tp']=='new'){  
			 $tpl_name='resume-new-full.tpl';
		   }else{
			 //$tpl_name='resume-full.tpl';
			$tpl_name='resume-new-full.tpl';	
		   }
		
		echo Registry::get('twig')->render($tpl_name, array(
			'resume'		=> $resume,
			'employment'	=> ModelWork::getWorkEmployment($work_id),
			'education'		=> ModelWork::getWorkEducation($work_id),
			'traning'		=> ModelWork::getWorkTraning($work_id),
			'langs'			=> ModelWork::getWorkLangs($work_id),
			'gallery'		=> ModelWork::getWorkGallery($work_id),
			'vip'			=> $vip
		));
	}
	
	public function resumeAdd() {
		
		Header::SetTitle('Добавить резюме' . ' - ' . Header::GetTitle());
		Header::SetMetaTag('description', 'Добавить резюме');
		Header::SetMetaTag('keywords', 'Добавить резюме');
		
		echo Registry::get('twig')->render('resume-add.tpl', array(
			'categories'	=> ModelWork::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect(Request::get('country')),
			'is_add_access'	=> User::isUserAccess(6),
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		));
	}
	
	public function resumeAddAjax() {
		Header::ContentType("text/plain");
		
		if (User::isUserAccess(6)) {
			if (Request::PostIsNull('region_id', 'city_id', 'user_surname', 'user_name')) {
				
				$user_info = User::getUserContacts();
				
				$work_id = ModelWork::add(User::isUser(), 1, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'user_surname'		=> Request::post('user_surname', 'string'),
					'employment_type'	=> Request::post('employment_type', 'int'),
					'leave'				=> Request::post('leave', 'int'),
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'user_firstname'	=> Request::post('user_firstname', 'string'),
					'user_brith'		=> Request::post('birth_date_year', 'int') . '-' . Request::post('birth_date_month', 'int') . '-' . Request::post('birth_date_day', 'int'),
					'country_id'		=> Request::get('country'),
					'city_id'			=> Request::post('city_id', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', ''),
					'flag_vip_add'		=> Request::post('submit') == 'vip' ? 1 : 0
				), 
					Request::post('work'),
					Request::post('education'),
					Request::post('traning'),
					Request::post('langs'),
					Request::post('categ_id'), 
					Request::post('images'),
					User::isPostModeration(6)
				);
				
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(6, $work_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($work_id , 6);
					
				}
				
				$result = array(
					'success'	=> true,
					'message'	=> User::isPostModeration(6) ? 'Резюме успешно добавлено' : 'Резюме добавлено на модерацию',
					'work_id'	=> $work_id,
					'send_data'	=> $data['send_data'],
					'portmone'	=> $data['portmone'],
					'product_id'=>$work_id
				);
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
				'message'	=> 'Вы не можете размещать резюме'
			);
		}
		
		echo json_encode($result);
	}
	
	public function resumeEdit($work_id) {
		$data 	= ModelWork::getResumeData($work_id);
		$images	= ModelWork::getWorkImages($work_id);
		
		$days 	= range(1, 31);
		$months	= array ('месяц', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		$years	= range(1943, date('Y') - 10);
		
		echo Registry::get('twig')->render('resume-edit.tpl', array(
			'data'			=> $data,
			'employments'	=> ModelWork::getWorkEmployment($work_id),
			'educations'	=> ModelWork::getWorkEducation($work_id),
			'tranings'		=> ModelWork::getWorkTraning($work_id),
			'langs'			=> ModelWork::getWorkLangs($work_id),
			'categories'	=> ModelWork::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect($data['country_id']),
			'cities'		=> Site::getCitiesFromSelect($data['region_id']),
			'days'			=> $days,
			'months'		=> $months,
			'years'			=> $years,
			'images'		=> $images,
			'images_count'	=> 7 - count($images)
		));
	}
	
	public function resumeEditAjax($work_id) {
		Header::ContentType("text/plain");
		
		if (User::isUser() == ModelWork::getResuneUserId($work_id) or User::isAdmin()) {
			if (Request::PostIsNull('region_id', 'city_id', 'user_surname', 'user_name')) {
				
				ModelWork::resumeEdit($work_id, array(
					'user_name'			=> Request::post('user_name', 'string'),
					'user_surname'		=> Request::post('user_surname', 'string'),
					'employment_type'	=> Request::post('employment_type', 'int'),
					'leave'				=> Request::post('leave', 'int'),
					'user_firstname'	=> Request::post('user_firstname', 'string'),
					'user_brith'		=> Request::post('birth_date_year', 'int') . '-' . Request::post('birth_date_month', 'int') . '-' . Request::post('birth_date_day', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'contact_phones'	=> Request::post('contact_phones', 'string')
				),
					Request::post('work'),
					Request::post('education'),
					Request::post('traning'),
					Request::post('langs'),
					Request::post('categ_id'), 
					Request::post('images')
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
	
	public function resumeDelete($work_id) {
		if (User::isUser() == ModelWork::getResuneUserId($work_id) or User::isAdmin()) {
			ModelWork::resumeDelete($work_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function resumeFlag($work_id, $flag = 0) {
		if (User::isUser() == ModelWork::getResuneUserId($work_id) or User::isAdmin()) {
			ModelWork::editResumeFlag($work_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function resumeFlagModer($work_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelWork::editResumeFlagModer($work_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function vacancyDelete($vacancy_id) {
		if (User::isUser() == ModelWork::getVacancyUserId($vacancy_id) or User::isAdmin()) {
			ModelWork::vacancyDelete($vacancy_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function vacancyFlag($vacancy_id, $flag = 0) {
		if (User::isUser() == ModelWork::getVacancyUserId($vacancy_id) or User::isAdmin()) {
			ModelWork::editVacancyFlag($vacancy_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function vacancyFlagModer($vacancy_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelWork::editVacancyFlagModer($vacancy_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function vacancyIndex($categ_id = 0, $city_id = 0, $min = null, $max = null, $user_id = 0, $page = 1, $search = null, $is_updates = 0, $translit = '') {
		Site::setSectionView(10, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
		$search = (string) Str::get($search)->trim()->strToLower()->removeSymbols();
		
		$vacancy 	= ModelWork::getVacancyList($categ_id, $city_id, Request::get('country'), $min, $max, $user_id, $page, $search, Registry::get('config')->itemsInPage, $is_updates, $flag);
		$cities		= ModelWork::getVacancyCities(Request::get('country'), $categ_id, true);
		$count 		= ModelWork::getVacancyCount($categ_id, $city_id, Request::get('country'), $min, $max, $user_id, $search, $is_updates, $flag);
		
		$meta		= Site::getDefaultMetaTags('vacancy');
		
		Header::SetTitle($meta['meta_title']);
		
		Header::SetH1Tag($meta['title']);
		
		Header::SetMetaTag('description', $meta['meta_description']);
		Header::SetMetaTag('keywords', $meta['meta_keys']);
		
		if ($categ_id > 0) {
			$meta = ModelWork::getVacancyCategoryMetaTags($categ_id);
			
			Header::SetTitle($meta['meta_title_vacancy']);
			
			Header::SetH1Tag('Вакансии - ' . $meta['title_vacancy']);
			
			Header::SetMetaTag('description', $meta['meta_description_vacancy']);
			Header::SetMetaTag('keywords', $meta['meta_keys_vacancy']);
		}
		
		/* if ($city_id > 0) {
			foreach ($cities as $key => $val) {
				if ($key == $city_id) {
					Header::SetTitle(Header::GetTitle() . ' - ' . $val);
					
					Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $val);
					
					Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $val);
					Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $val);
					
					break;
				}
			}
		} */
		$city=Site::cityName();
       if ($city) {
		    Header::SetTitle( $city.' - '.Header::GetTitle() );
			Header::SetH1Tag( $city.' - '.Header::GetH1Tag());
			Header::SetMetaTag('description',$city.' - '.Header::GetMetaTag('description') );	
            Header::SetMetaTag('keywords', $city.','.Header::GetMetaTag('keywords')	);		
	   }
		
		if ($page > 1) {
			Header::SetTitle(Header::GetTitle() . ' - страница ' . $page);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - страница ' . $page);
		}
		
		if ($user_id > 0) {
			$user = User::getUserName($user_id);
			
			Header::SetTitle(Header::GetTitle() . ' - ' . $user);
			Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $user);
			Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $user);
		}
		
		$categs = ModelWork::getCategoriesFromSelect(true, true);
		
		if (User::isUser()) {
			$subscribe 	= ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 15);
			
			if ($categ_id > 0 and is_array($subscribe)) {
				$subscribe_status = in_array($categ_id, $subscribe);
			}
			else {
                Debug::log($subscribe, count($categs), User::isUser());
				$subscribe_status =  count($subscribe) >= count($categs);
			}
		}
			if($_GET['tp']=='new'){
	            
			 $tpl_name='vacancy-new2.tpl';  
		   }else{
			 /* $tpl_name='vacancy.tpl'; */
			 $tpl_name='vacancy-new2.tpl';	
		   }
		
		echo Registry::get('twig')->render($tpl_name, array(
			'categories'	=> $categs,
			'cities'		=> $cities,
			'vacancy'		=> $vacancy,
			'pagination'	=> Site::pagination(Registry::get('config')->itemsInPage, $count, $page),
			'subscribe_status'	=> $subscribe_status,
			'cityName'=>Site::cityName()
		));
	}
	
	public function vacancyFull($vacancy_id) {
		ModelWork::setVacancyViews($vacancy_id, User::isUser());
		
		$vacancy = ModelWork::getVacancyFull($vacancy_id);

        if (!$vacancy['vacancy_id']) {
            Header::Location('/404');
        }

		if (!User::isAdmin() and $vacancy['user_id'] != User::isUser() and $vacancy['flag'] == 0) {
			Header::Location('/404');
		}
		
		Header::SetTitle('требуется ' . implode(', ', $vacancy['categs']) . ', ' . $vacancy['company_name'] . ', г. ' . $vacancy['city_name']);
		
		Header::SetMetaTag('description', $vacancy['company_name'] . ' требуется ' . implode(', ', $vacancy['categs']) . ', ' . $vacancy['contact_phones'] . ' ' . $vacancy['city_name']);
		Header::SetMetaTag('keywords',$vacancy['company_name'] . ', требуется ' . implode(', ', $vacancy['categs']) . ', ' . Header::GetMetaTag('keywords'). ', ' . Registry::get('country_name'));
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/work/160x200/' . $vacancy['logotype']);
		
		$vip = ModelWork::getVacancyVIP($vacancy['country_id'], $vacancy['categs'], $vacancy_id);
		
		if($_GET['tp']=='new'){  
			 $tpl_name='vacancy-new-full.tpl';
		   }else{
			 $tpl_name='vacancy-full.tpl';
			$tpl_name='vacancy-new-full.tpl';			 
		   }
		
		echo Registry::get('twig')->render($tpl_name, array(
			'vacancy'		=> $vacancy,
			'vip'			=> $vip,
			'gallery'		=> ModelWork::getWorkGallery($vacancy_id,1),
			'isUserResume'	=> ModelWork::isUserResume(User::isUser())
		));
	}
	
	public function sendResumeToVacancy($vacancy_id) {
		if (Request::post('vacancy_id')) {
			
			// Attach 
			include_once(LIBS . 'mpdf/mpdf.php');
			
			$resume = ModelWork::getWorkFull(Request::post('work_id', 'int'));
			
			$html = Registry::get('twig')->render('resume-pdf.tpl', array(
				'resume'		=> $resume,
				'employment'	=> ModelWork::getWorkEmployment(Request::post('work_id', 'int')),
				'education'		=> ModelWork::getWorkEducation(Request::post('work_id', 'int')),
				'traning'		=> ModelWork::getWorkTraning(Request::post('work_id', 'int')),
				'langs'			=> ModelWork::getWorkLangs(Request::post('work_id', 'int'))
			));
			
			$mpdf = new mPDF('utf-8', 'A4', '8', '', 0, 0, 0, 0, 0, 0);
			
			$stylesheet = file_get_contents(TPL . 'Navistom/styles/main.css');
			$stylesheet2 = file_get_contents('assets/acorn-ui/acorn-ui.css');
			$mpdf->WriteHTML($stylesheet2, 1);
			$mpdf->WriteHTML($stylesheet, 1);
			
			$mpdf->list_indent_first_level = 0; 
			$mpdf->WriteHTML($html, 2);
			
			$attach = array(
				'file'	=> $mpdf->Output('Resume.pdf', 'S'),
				'name'	=> $resume['user_surname'] . ' ' . $resume['user_name']
			);
			
			/**
			 * New Notification
			 */
			
			$from 		= User::getUserContacts();
			$to			= User::getUserInfo(Request::post('user_id', 'int'));
			
			$data			= ModelWork::getVacancyFull($vacancy_id);
			$data['name']	= implode(', ', $data['categs']);
			$translit		= Str::get($data['name'])->truncate(60)->translitURL();
			$base_url		= 'http://navistom.com/' . Registry::get('config')->country[$data['country_id']] . '/';
			
			Notification::sendResumeToVacancy(
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
					'name'				=> 'Требуется ' . $data['name'] . ', г. ' . $data['city_name'],
					'price'				=> $data['price'],
					'currency_name'		=> $data['currency_name'],
					'link'				=> $base_url . 'work/vacancy/' . $vacancy_id . '-' . $translit,
					'vip_link'			=> $base_url . 'vip-request-15-' . $vacancy_id
				),
				$attach
			);
			
			/* End Notification */
			
			/*Site::sendMessageToMail(
				'Новое резюме с NaviStom на рассмотрение', 
				$user_from_info['email'],
				array(
					'user_name'		=> $user_from_info['name'],
					'message'		=> "Пользователь <b>{$user_to_info['name']}</b> отправил Вам свое резюме на рассмотрение к вакансии: <br> <a href='http://navistom.com/work/vacancy/$vacancy_id-$translit'><b>{$data['name']}</b></a>
										<p>С резюме Вы можете ознакомиться, просмотрев вложение
										
										</p>
										",
					'description'	=> Request::post('message', 'string') != '' ? 'Комментарий соискателя: <br>' . Request::post('message', 'string') : '',
					'user_email'	=> $user_to_info['email'],
					'user_phones'	=> $user_to_info['contact_phones']
				),
				'email-basic.html',
				null,
				array(
					'email'	=> $user_to_info['email'],
					'name'	=> $user_to_info['name']
				),
				$attach
			);*/
			
			echo json_encode(array(
				'success'	=> true,
				'message'	=> 'ваше резюме успешно отправлено работодателю'
			));
		}
		else {
			echo Registry::get('twig')->render('send-my-resume.tpl', array(
				'resume'	=> ModelWork::getUserResume(User::isUser()),
				'vacancy'	=> ModelWork::getVacancyFull($vacancy_id)
			));
		}
	}
	
	public function vacancyAdd() {
		$user_info = User::getUserContacts();

		echo Registry::get('twig')->render('vacancy-add.tpl', array(
			'categories'	=> ModelWork::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect(Request::get('country')),
			'is_add_access'	=> User::isUserAccess(6),
			'user_name'		=> $user_info['name'],
			'contact_phones'=> explode(',', $user_info['contact_phones']),
			'company_info'	=> ModelWork::getCompanyInfo(User::isUser()),
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		));
	}
	
	public function vacancyAddAjax() {
		Header::ContentType("text/plain");
		
		if (User::isUserAccess(6)) {
			if (Request::PostIsNull('region_id', 'city_id')) {
				
				if ($_FILES['image']['name'] != null) {
					$logotype = ModelWork::uploadLogotype($_FILES['image']);
				}
				else {
					$logotype = Request::post('logotype', 'string') != '' ? Request::post('logotype', 'string') : '';
				}
				
				$user_info = User::getUserContacts();
				
				$vacancy_id = ModelWork::addVacancy(User::isUser(), array(
					'name'				=> Request::post('company_name', 'string'),
					'site'				=> Request::post('company_site', 'url'),
					'logotype'			=> $logotype,
					'description'		=> Request::post('company_description', 'string'),
					'user_name'			=> Request::post('user_name', 'string')
				), array(
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'price'				=> Request::post('price', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'type_id'			=> Request::post('type', 'int'),
					'experience_type'	=> Request::post('experience_type', 'int'),
					'education_type'	=> Request::post('education_type', 'int'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag_vip_add'		=> Request::post('submit') == 'vip' ? 1 : 0
				), 
					Request::post('categ_id'), 
					Request::get('country'), 
					User::isPostModeration(6),
					Request::post('company_id', 'int'),
					Request::post('images')
				);
				
				if(Request::post('submit', 'string') == 'vip') {
					ModelMain::updateVipRequest(15, $vacancy_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($vacancy_id , 15); 
				}
				
				$result = array(
					'success'	=> true,
					'message'	=> User::isPostModeration(6) ? 'Вакансия успешно добавлена' : 'Вакансия добавлена на модерацию',
					'work_id'	=> $work_id,
					'send_data'	=> $data['send_data'],
					'portmone'	=> $data['portmone'],
					'product_id'=>$vacancy_id
				);
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
				'message'	=> 'Вы не можете размещать вакансии'
			);
		}
		
		echo json_encode($result);
	}
	
	public function vacancyEdit($vacancy_id) {
		$data 				= ModelWork::getVacancyData($vacancy_id);
		$data['categ_id'] 	= explode(',', $data['categ_id']);
		$user_info 			= User::getUserContacts();
		$images	= ModelWork::getWorkImages($vacancy_id,1);
		echo Registry::get('twig')->render('vacancy-edit.tpl', array(
			'data'			=> $data,
			'company_info'	=> ModelWork::getCompanyInfo($data['user_id']),
			'contact_phones'=> explode(',', $user_info['contact_phones']),
			'categories'	=> ModelWork::getCategoriesFromSelect(),
			'regions'		=> Site::getRegionsFromSelect($data['country_id']),
			'images'		=> $images,
			'images_count'	=> 7 - count($images),
			'cities'		=> Site::getCitiesFromSelect($data['region_id'])
		));
	}
	
	public function vacancyEditAjax($vacancy_id) {
		Header::ContentType("text/plain");
		
		if (User::isUser() == ModelWork::getVacancyUserId($vacancy_id) or User::isAdmin()) {
			if (Request::PostIsNull('region_id', 'city_id')) {
				
				if ($_FILES['image']['name'] != null) {
					$logotype = ModelWork::uploadLogotype($_FILES['image']);
				}
				else {
					$logotype = Request::post('logotype', 'string') != '' ? Request::post('logotype', 'string') : '';
				}
				
				ModelWork::editVacancy($vacancy_id, array(
					'name'				=> Request::post('company_name', 'string'),
					'site'				=> Request::post('company_site', 'url'),
					'logotype'			=> $logotype,
					'description'		=> Request::post('company_description', 'string'),
					'user_name'			=> Request::post('user_name', 'string')
				), array(
					'contact_phones'	=> Request::post('contact_phones', 'string'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'price'				=> Request::post('price', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'type_id'			=> Request::post('type', 'int'),
					'experience_type'	=> Request::post('experience_type', 'int'),
					'education_type'	=> Request::post('education_type', 'int'),
					'content'			=> Request::post('content', 'string'),
					'video_link'		=> Request::post('video_link', 'url')
				), 
					Request::post('categ_id'), 
					Request::post('company_id', 'int'),
					Request::post('images')
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
	
	public function sendMessage($work_id) {
		
		if (Request::PostIsNull('message', 'user_id')) {
			if (Request::post('user_id', 'int') != User::isUser() or User::isAdmin()) {
				$message_id = ModelWork::saveUserMessage(
					$work_id, 
					6,
					User::isUser(), 
					Request::post('user_id', 'int'), 
					Request::post('message', 'string')
				);
				
				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelWork::getWorkFull($work_id);
				$data['name']	= implode(', ', $data['categs']);
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
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/work/resume/$work_id-$translit'><b>Резюме {$data['name']}</b></a>",
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
				
				$data			= ModelWork::getWorkFull($work_id);
				$data['name']	= implode(', ', $data['categs']);
				$translit		= Str::get($data['name'])->truncate(60)->translitURL();
				$base_url		= 'http://navistom.com/' . Registry::get('config')->country[$data['country_id']] . '/';
				
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
						'name'				=> 'Резюме ' . $data['name'] . ', г. ' . $data['city_name'],
						'price'				=> $data['price'],
						'currency_name'		=> $data['currency_name'],
						'link'				=> $base_url . 'work/resume/' . $work_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-6-' . $work_id
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
			$data 			= ModelWork::getWorkFull($work_id);
			$user_from_info = User::getUserInfo($data['user_id']);
			
			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $work_id
				),
				'mess_tpls'	=> Site::getMessTplsToSelect(6),
				'controller'=> 'work/resume'
			));
		}
	}
	
	public function sendMessageVacancy($vacancy_id) {
		
		if (Request::PostIsNull('message', 'user_id')) {
			
			if (Request::post('user_id', 'int') != User::isUser() or User::isAdmin()) {
				$message_id = Site::saveUserMessage(
					$vacancy_id,
					100, 
					User::isUser(), 
					Request::post('user_id', 'int'), 
					Request::post('message', 'string')
				);
				
				/*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
				$user_to_info	= User::getUserContacts();
				
				$data			= ModelWork::getVacancyFull($vacancy_id);
				$data['name']	= implode(', ', $data['categs']);
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
						'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/work/vacancy/$vacancy_id-$translit'><b>Требуется {$data['name']}</b></a>",
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
				
				$data		= ModelWork::getVacancyFull($vacancy_id);
				$translit	= Str::get(implode(', ', $data['categs']))->truncate(60)->translitURL();
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
						'name'				=> 'Требуется ' . implode(', ', $data['categs']) . ', г. ' . $data['city_name'],
						'link'				=> $base_url . 'work/vacancy/' . $vacancy_id . '-' . $translit,
						'vip_link'			=> $base_url . 'vip-request-15-' . $vacancy_id
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
			$data 			= ModelWork::getVacancyFull($vacancy_id);
			$user_from_info = User::getUserInfo($data['user_id']);
			
			echo Registry::get('twig')->render('send-user-message.tpl', array(
				'data'		=> array(
					'user_id'		=> $data['user_id'],
					'avatar'		=> $user_from_info['avatar'],
					'name'			=> $user_from_info['name'],
					'contact_phones'=> $user_from_info['contact_phones'],
					'resource_id'	=> $vacancy_id
				),
				'mess_tpls'	=> Site::getMessTplsToSelect(15),
				'controller'=> 'work/vacancy'
			));
		}
	}
	
	public function uploadImage() {
		Header::ContentType("text/plain");
		
		$img = ModelWork::uploadImage();

		echo json_encode($img);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		echo json_encode(array(
			'success'	=> ModelWork::deleteImage($image_id)
		));
	}
	
  public function remove(){
	 
	    if(User::isAdmin()){
		  	ModelWork::remove();
	    }else{
		  Header::Location('/404');
		}
  }	
}