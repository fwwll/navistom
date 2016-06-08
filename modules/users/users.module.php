<?php
class Users {
	public function remove($user_id){
		
		 if( User::isUser() == $user_id or  User::isAdmin() ){
			ModelUsers::remove($user_id);
		} 
		
		Header::Location('/admin/users/zayavka');
	}
	public function login() {
		 
		if (Request::PostIsNull('user_email', 'user_passw')) {
			
			if (ModelUsers::authUser(Request::post('user_email', 'email'), Request::post('user_passw'))) {
				
				 $va= Request::getCookie('history');
				 //Request::removeCookie('history');
				 if($va){
					Header::Location($va) ;
				 }
				
				//Header::Location("/cabinet");
			}
			else {
				Header::Location("/login");
			}
		}
		else {
			 echo Registry::get('twig')->render('login.tpl');
			
		}
	}
	
	public function logout() {
		Request::removeSession('_USER');
		Request::removeSession('_ADMIN');
		
		Request::setCookie('_aut_key', '');
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function registration() {
		$country = Site::getUserCountryDefault();
		
		echo Registry::get('twig')->render('registration.tpl', array(
			'regions'			=> ModelUsers::getRegionsFromSelect(1)
		));
	}
	
	public function registrationAjax() {
		Header::ContentType("text/plain");
		
		if (Request::PostIsNull('user_name', 'user_email', 'user_passw', 'user_passw_2')) {
			$email	= Request::post('user_email', 'string');
			
			if (Str::get($email)->isEmail()) {
				
				if (ModelUsers::isCaptcha()) {
					
					$user_id = ModelUsers::addUser(
						$email,
						Request::post('user_passw', 'hash')
					);
					
					if ($user_id > 0) {
						
						if ($_FILES['user_avatar']['name'] != null) {
							$avatar_name 	= mb_strtolower(Request::post('user_name', 'translitURL'), 'UTF-8') . '-' . $user_id;
							$avatar 		= ModelUsers::addUserAvatar('user_avatar', $avatar_name);
						}
						else {
							$avatar = 'none.jpg';
						}
						
						
						ModelUsers::addUserInfo(
							$user_id,
							1,
							Request::post('user_region', 'int'),
							Request::post('user_city', 'int'),
							Request::post('user_name', 'string'),
							Request::post('user_icq', 'string'),
							Request::post('user_skype', 'string'),
							Request::post('user_contact_phone'),
							$avatar,
							Request::post('user_site', 'url')
						);
						
						$key = ModelUsers::addConfirm($user_id);
						
						Site::sendMessageToMail('Регистрация на сайте NaviStom.com', $email, 
							array(
								'username'		=> Request::post('user_name', 'string'),
								'date'			=> date('Y-m-d H:i:s'),
								'key'			=> $key
							), 
							'email-mess-registration-complete.tpl'
						);
						
						$result = array(
							'success'	=> true,
							'no_mess'	=> true,
							'message'	=> 'Регистрация прошла успешно, Вам на почту было выслано сообщение для подтверждения регистрации.
							    Если  ссылка  не  пришла, проверьте папку Спама или обратитесь к администратору сайта +38-044-573-97-73 пн-пт с 10-00 до 17-00'
						);
						
						echo json_encode($result);
						
						return true;
					}
				}
			}
			else {
				
			}
		}
		else {
			
		}
		
		$result = array(
			'success'	=> false,
			'message'	=> 'Ошибка регистрации'
		);
		
		echo json_encode($result);
	}
	
	public function passwRecovery() {
		if (Request::PostIsNull('user_email')) {
			$email = Request::post('user_email', 'string');
			
			$is_user = DB::getColumn("SELECT user_id FROM `users` WHERE email = '$email'");
			
			if ($is_user > 0) {
                if (ModelUsers::isNotConfirmedByUserId($is_user)) {
                    echo json_encode(array(
                        'success'	=> false,
                        'message'	=> '
                            Вы еще не подтвердили регистрацию на сайте. <br />
                            Чтобы восстановить пароль, подтвердите регистрацию из отправленного Вам на почту письма. <br />
                            <a href="/send-confirm-code-'. $is_user .'">Отправить код активации еще раз</a>'
                    ));

                    return false;
                }

				$new_passw = Str::get()->generate(10);
				
				$info = User::getUserInfo($is_user);
				
				Site::sendMessageToMail('Восстановление пароля на NaviStom.com', $email, 
					array(
						'username'		=> $info['name'],
						'date'			=> date('Y-m-d H:i:s'),
						'passw'			=> $new_passw,
						'email'			=> $email
					), 
					'email-mess-passw-recovery-complete.tpl'
				);
				
				DB::update('users', array(
					'passw'		=> md5(md5($new_passw))
				), array(
					'user_id'	=> $is_user
				));
				
				echo json_encode(array(
					'success'	=> true,
					'no_mess'	=> true,
					'message'	=> 'Вам на почту было выслано сообщение с новым паролем'
				));
			}
			else {
				echo json_encode(array(
					'success'	=> false,
					'message'	=> 'Пользователь с таким e-mail не зарегистрирован'
				));
			}
		}
		else {
			echo json_encode(array(
				'success'	=> false,
				'message'	=> 'Вы не ввели e-mail адрес'
			));
		}
	}
	
	public function userConfirm($key) {
		$user_id = ModelUsers::userConfirm($key);
		
		if ($user_id > 0) {
			ModelUsers::LoginUserConfirm( $user_id );
            User::setDefaultSubscribeData( $user_id );

			
			Header::Location("/cabinet");
			
			/*$result = array(
				'message'	=> 'Ваша регистрация подтверждена. Пожалуйста,  авторизируйтесь,  введя логин и пароль, указанный Вами при первичной регистрации.',
				'class'		=> 'a-mess-green', 
			);*/
		}
		else {
			$result = array(
				'message'	=> 'Пользователя с таким кодом не существует',
				'class'		=> 'a-mess-red', 
			);
		}
		
		echo Registry::get('twig')->render('auth-key-message.tpl', $result); 
	}
	
	public function getCitiesList($region_id) {
		Header::ContentType("text/plain");
		
		echo json_encode( 
			ModelUsers::getCitiesFromSelect($region_id, Request::post('regions')) 
		);
	}
	
	public function getRegionsList($country_id) {
		Header::ContentType("text/plain");
		
		echo json_encode( 
			ModelUsers::getRegionsFromSelect($country_id) 
		);
	}
	
	public function validationEmail() {
		Header::ContentType("text/plain");
		
		$email		= Request::get('fieldValue', 'string');
		$result[0]	= Request::get('fieldId', 'string');
		
		if (User::checkUserEmail($email)) {
			$result[1] = true;
		}
		else {
			$result[1] = false;
		}
		
		echo json_encode($result);
	}
	
	public function validationCaptcha() {
		Header::ContentType("text/plain");
		
		$action = Request::post('action', 'string');
		$key	= Request::post('qaptcha_key', 'string');
		
		$result['error'] = false;
		
		if($action != null and $key != null) {
			Request::setSession('qaptcha_key', false);
			
			if($action == 'qaptcha') {
				Request::setSession('qaptcha_key', $key);
				echo json_encode($result);
			}
			else {
				$result['error'] = true;
				echo json_encode($result);
			}
		}
		else {
			$result['error'] = true;
			echo json_encode($result);
		}
	}
	
	public function loginAjax() {
		Header::ContentType("text/plain");
		
		if (Request::PostIsNull('user_email', 'user_passw')) {
			if (ModelUsers::authUser(Request::post('user_email', 'string'), Request::post('user_passw'))) {
				
				$result = array(
					'no_mess'	=> true,
					'success'	=> true
				);
			}
			else {
                if ($userId = ModelUsers::isNotConfirmed(Request::post('user_email', 'string'), Request::post('user_passw'))) {
                    $result = array(
                        'success'	=> false,
                        'error'		=> '
                            Вы еще не подтвердили регистрацию на сайте. <br />
                            Чтобы войти, подтвердите регистрацию из отправленного Вам на почту письма.
                            <a href="/send-confirm-code-'. $userId .'">Отправить код активации еще раз</a>'
                    );
                }
                else {
                    $result = array(
                        'success'	=> false,
                        'error'		=> 'Неверный e-mail или пароль!'
                    );
                }
			}
		}
		else {
			$result = array(
				'success'	=> false,
				'error'		=> 'Одно из полей не заполнено!'
			);
		}
		
		echo json_encode($result);
	}

    public function sendConfirmCode($userId) {
        if ($userId > 0) {
            $query = '
                SELECT
                    u.email,
                    c.user_key,
                    c.send_count,
                    c.date_last_send <= DATE_SUB(NOW(), INTERVAL 20 MINUTE ) AS date_interval,
                    i.name
                FROM `users_confirms` AS c
                INNER JOIN `users` AS u USING(user_id)
                INNER JOIN `users_info` AS i USING(user_id)
                WHERE c.user_id = ' . (int)$userId;

            $user  = DB::getAssocArray($query, 1);

            if ($user['user_key']) {
                if ($user['send_count'] >= 3 or $user['date_interval'] == 0) {
                    echo Registry::get('twig')->render('message.tpl', array(
                        'class' => 'a-mess-red',
                        'message' => $user['send_count'] >= 3 ? 'Вы превысили лимит на количество отправленных подтверждений!' : 'С момента последней отправки сообщения прошло менее  20 минут, пожалуйста, подождите письмо еще немного.'
                    ));

                    return;
                }

                Site::sendMessageToMail('Регистрация на сайте NaviStom.com', $user['email'],
                    array(
                        'username'		=> $user['name'],
                        'date'			=> date('Y-m-d H:i:s'),
                        'key'			=> $user['user_key']
                    ),
                    'email-mess-registration-complete.tpl'
                );

                echo Registry::get('twig')->render('message.tpl', array(
                    'class' => 'a-mess-green',
                    'message' => 'Вам на почту было выслано новое сообщение для подтверждения регистрации.
                        Если  ссылка  не  пришла, проверьте папку Спама или обратитесь к администратору сайта +38-044-573-97-73 пн-пт с 10-00 до 17-00'
                ));

                return;
            }
        }

        echo Registry::get('twig')->render('message.tpl', array(
            'class' => 'a-mess-red',
            'message' => 'Пользователь уже подтвержден'
        ));
    }
	
	public function getUserInfoAjax($user_id) {
		echo Registry::get('twig')->render('user-info.tpl', array(
			'data'	=> User::getFullUserInfo($user_id)
		));
	}

    public function accessRequestAjax() {
        Header::ContentType("text/plain");

        if (User::isUser()) {
            ModelUsers::saveUserAccessRequest(User::isUser(), Request::get('url', 'url'), Request::get('type', 'int'));

            $result = array(
                'success'	=> true,
                'no_mess'	=> true,
                'message'	=> 'Ваша заявка отправлена администрации сайта'
            );
        }

        echo json_encode($result);
    }
	
	public function feedbackAjax() {
		Header::ContentType("text/plain");
		
		if (Request::PostIsNull('user_email', 'user_name', 'message')) {
			if (ModelUsers::isCaptcha() or User::isUser()) {
				
				$browser = Site::getUserBrowserInfo();
				
				$mess_id = ModelUsers::saveUserFeedbackMess(
					User::isUser(),
					Request::post('user_name', 'string'),
					Request::post('user_email', 'email'),
					Request::post('user_phone', 'string'),
					Request::post('message', 'string'),
					Site::getRealIP(),
					$browser['ua_family'],
					$browser['ua_version'],
					$browser['os_name']
				);
				
				if ($mess_id > 0) {
					$result = array(
						'success'	=> true,
						'no_mess'	=> true,
						'message'	=> 'Ваше письмо отправлено администрации сайта'
					);
				}
				else {
					$result = array(
						'success'	=> false,
						'message'	=> 'Системная ошибка'
					);
				}
			}
			else {
				$result = array(
					'success'	=> false,
					'message'	=> 'Капча не пройдена!'
				);
			}
		}
		else {
			$result = array(
				'success'	=> false,
				'message'	=> 'Одно из полей не заполнено!'
			);
		}
		
		echo json_encode($result);
	}
	
	public function feedback() {
		  Header::SetTitle("Обратная связь с админом NaviStom");
		echo Registry::get('twig')->render('feedback.tpl', array(
			
		));
	}
	
	public function faq() {
		echo Registry::get('twig')->render('faq.tpl', array(
			
		));
	}
	
	public function advert() {
		echo Registry::get('twig')->render('advert.tpl', array(
			
		));
	}
	
	public function banner($banner_id) {
		$link = DB::getColumn("SELECT link FROM `banners` WHERE banner_id = $banner_id");
		
		DB::query("UPDATE LOW_PRIORITY `banners` SET clicks = clicks + 1 WHERE banner_id = $banner_id");
		
		Header::Location($link);
	}
	
	public function sendUserError() {
		if (Request::get('url', 'url') != '') {
			echo Registry::get('twig')->render('send-user-error.tpl', array(
				'url'	=> Request::get('url', 'url')
			));
		}
		else {
			if (Request::PostIsNull('url', 'user_email', 'message')) {
				if (ModelUsers::isCaptcha() or User::isUser()) {
					$browser = Site::getUserBrowserInfo();
					
					$mess_id = ModelUsers::saveUserErrorMess(
						User::isUser(),
						Request::post('user_email', 'email'),
						Request::post('user_phone', 'string'),
						Request::post('url', 'url'),
						Request::post('message', 'string'),
						Site::getRealIP(),
						$browser['ua_family'],
						$browser['ua_version'],
						$browser['os_name']
					);
					
					if ($mess_id > 0) {
						$result = array(
							'success'	=> true,
							'no_mess'	=> true,
							'message'	=> 'Ваше письмо об ошибке отправлено администрации сайта'
						);
					}
					else {
						$result = array(
							'success'	=> false,
							'message'	=> 'Системная ошибка'
						);
					}
				}
				else {
					$result = array(
						'success'	=> false,
						'message'	=> 'Вы не прошли капчу'
					);
				}
			}
			else {
				$result = array(
					'success'	=> false,
					'message'	=> 'Не все обязательные поля заполнены'
				);
			}
			
			echo json_encode($result);
		}
	}
	
	public function usersAgreement() {
		echo Registry::get('twig')->render('user-agreement.tpl');
	}
	
	public function lightContent($section_id, $content_id, $date_start = null, $date_end = null) {
		if ($section_id > 0 and $content_id > 0) {
			if (Request::post('date_start')) {
				if (User::isAdmin()) {
					if (date_diff(date_create(), date_create(Request::post('date_end')))->invert == 0) {
						ModelUsers::lightContent(
							$section_id,
							$content_id,
							$date_start != null ? $date_start : Request::post('date_start', 'string'),
							$date_end != null ? $date_end : Request::post('date_end', 'string')
						);
						
						if ($date_start == null) {
							echo json_encode(array(
								'success'	=> true,
								'no_mess'	=> true,
								'message'	=> 'Объявление выделено'
							));
						}
					}
					else {
						echo json_encode(array(
							'success'	=> false,
							'message'	=> 'Не верно указана дата окончания'
						));
					}
				}
			}
			else {
				echo Registry::get('twig')->render('light-content.tpl', array(
					'section_id'	=> $section_id,
					'content_id'	=> $content_id,
					'data'			=> ModelUsers::getLightContentData($section_id, $content_id)
				));
			}
		}
	}
	
	public function lightContentDelete($section_id, $content_id, $is_top = 1) {
		DB::delete('light_content', array(
			'section_id'	=> $section_id,
			'resource_id'	=> $content_id
		));
		
		if ($is_top > 0) {
		
			echo json_encode(array(
				'success'	=> true,
				'no_mess'	=> true,
				'message'	=> 'Выделение удалено'
			));
		}
	}
	
	public function addToTop($section_id, $resource_id, $no_mess = 0) {
		if ($section_id > 0 and $resource_id > 0) {
			if (Request::post('date_start')) {
				if (User::isAdmin()) {
					if (date_diff(date_create(), date_create(Request::post('date_end')))->invert == 0) {
						DB::insert('top_to_section', array(
							'section_id'	=> $section_id,
							'resource_id'	=> $resource_id,
							'date_start'	=> Request::post('date_start', 'string'),
							'date_end'		=> Request::post('date_end', 'string'),
							'sort_id'		=> Request::post('sort_id', 'int'),
							'date_add'		=> DB::now()
						), 1);
						
						Site::removeFlagVipAdd($section_id, $resource_id);
						
						Users::addToTopCateg($section_id, $resource_id, 1);
						
						if ($no_mess == 0) {
							echo json_encode(array(
								'success'	=> true,
								'no_mess'	=> true,
								'message'	=> 'Объявление добавлено в топ раздела'
							));
						}
					}
					else {
						echo json_encode(array(
							'success'	=> false,
							'message'	=> 'Не верно указана дата окончания'
						));
					}
				}
			}
			else {
				echo Registry::get('twig')->render('add-to-top.tpl', array(
					'section_id'	=> $section_id,
					'resource_id'	=> $resource_id,
					'data'			=> ModelUsers::getTopData('top_to_section', $section_id, $resource_id),
					'action'		=> 'add-to-top',
					'title'			=> 'Добавить объявление в топ раздела'
				));
			}
		}
	}
	
	
	public function addToTopPayment($data) {
			$no_mess = 0;

			$section_id =$data['section_id'];
			$resource_id=$data['resource_id'];
			$date_start=$data['curr_time'];
			$date_end=$data['time_end'];

		

			$where=array(
				'section_id'	=> $section_id,
				'resource_id'	=> $resource_id
			);

			$count=DB::getTableCount("top_to_section",$where);


			if (!$count) {

				$flag=DB::insert('top_to_section', array(
				'section_id'	=> $section_id,
				'resource_id'	=> $resource_id,
				'date_start'	=> $date_start,
				'date_end'		=> $date_end,
				'sort_id'		=> 9999,
				'date_add'		=> DB::now()
				), 1);

			}else{
			
				DB::update('top_to_section',array(
					 'date_start'	=> $date_start,
					 'date_end'		=> $date_end,
					 'date_add'		=> DB::now()
					),$where 
				);
			}


			//Site::removeFlagVipAdd($section_id, $resource_id);
//flag_moder_view
			//Users::addToTopCateg($section_id, $resource_id, 1);

			//------------------------------------------------//
			
			
				DB::delete('top_to_category', array(
					'section_id'	=> $section_id,
					'resource_id'	=> $resource_id,
				));
			
			
			DB::insert('top_to_category', array(
				'section_id'	=> $section_id,
				'resource_id'	=> $resource_id,
				'date_start'	=> $date_start,
				'date_end'		=> $date_end,
				'sort_id'		=> 999,
				'date_add'		=> DB::now()
			), 1);

			//Site::removeFlagVipAdd($section_id, $resource_id);
			

			//----------------------------------------------//








	}
	
	public function addToTopCateg($section_id, $resource_id, $no_mess = 0) {
		if ($section_id > 0 and $resource_id > 0) {
			if (Request::post('date_start')) {
				
					if (date_diff(date_create(), date_create(Request::post('date_end')))->invert == 0) {
                        DB::delete('top_to_category', array(
                            'section_id'	=> $section_id,
                            'resource_id'	=> $resource_id,
                        ));

						DB::insert('top_to_category', array(
							'section_id'	=> $section_id,
							'resource_id'	=> $resource_id,
							'date_start'	=> Request::post('date_start', 'string'),
							'date_end'		=> Request::post('date_end', 'string'),
							'sort_id'		=> Request::post('sort_id', 'int'),
							'date_add'		=> DB::now()
						), 1);
						
						Site::removeFlagVipAdd($section_id, $resource_id);
						
						Users::lightContent($section_id, $resource_id, Request::post('date_start', 'string'), Request::post('date_end', 'string'));
						
						if ($no_mess == 0) {
							
							echo json_encode(array(
								'success'	=> true,
								'no_mess'	=> true,
								'message'	=> 'Объявление добавлено в топ рубрики'
							));
						}
					}
					else {
						echo json_encode(array(
							'success'	=> false,
							'message'	=> 'Не верно указана дата окончания'
						));
					}
				
			}
			else {
				echo Registry::get('twig')->render('add-to-top.tpl', array(
					'section_id'	=> $section_id,
					'resource_id'	=> $resource_id,
					'data'			=> ModelUsers::getTopData('top_to_category', $section_id, $resource_id),
					'action'		=> 'add-to-top-categ',
					'title'			=> 'Добавить объявление в топ рубрики'
				));
			}
		}
	}
	
	public function addToTopCategDelete($section_id, $resource_id, $no_mess = 0) {
		DB::delete('top_to_category', array(
			'section_id'	=> $section_id,
			'resource_id'	=> $resource_id
		));
		
		Users::lightContentDelete($section_id, $resource_id, 0);
		
		if ($no_mess == 0) {
			echo json_encode(array(
				'success'	=> true,
				'no_mess'	=> true,
				'message'	=> 'Объявление удалено из топ рубрики'
			));
		}
	}
	
	public function addToTopDelete($section_id, $resource_id, $no_mess = 0) {
		DB::delete('top_to_section', array(
			'section_id'	=> $section_id,
			'resource_id'	=> $resource_id
		));
		
		Users::addToTopCategDelete($section_id, $resource_id, 1);
		
		if ($no_mess == 0) {
			echo json_encode(array(
				'success'	=> true,
				'no_mess'	=> true,
				'message'	=> 'Объявление удалено из топ раздела'
			));
		}
	}
	
	public function addToTopMain($section_id, $resource_id) {
		if ($section_id > 0 and $resource_id > 0) {
			if (Request::post('date_start')) {
				if (User::isAdmin()) {
					  ModelPayment::insertAdmin($resource_id, $section_id);
					   
					if (date_diff(date_create(), date_create(Request::post('date_end')))->invert == 0) {
						DB::insert('top_to_main', array(
							'section_id'	=> $section_id,
							'resource_id'	=> $resource_id,
							'date_start'	=> Request::post('date_start', 'string'),
							'date_end'		=> Request::post('date_end', 'string'),
							'sort_id'		=> Request::post('sort_id', 'int'),
							'date_add'		=> DB::now()
						), 1);
						
						Site::removeFlagVipAdd($section_id, $resource_id);
						
						Users::lightContent($section_id, $resource_id, Request::post('date_start', 'string'), Request::post('date_end', 'string'));
						Users::addToTop($section_id, $resource_id, 1);
						
						echo json_encode(array(
							'success'	=> true,
							'no_mess'	=> true,
							'message'	=> 'Объявление добавлено в топ главной страницы'
						));
					}
					else {
						echo json_encode(array(
							'success'	=> false,
							'message'	=> 'Не верно указана дата окончания'
						));
					}
				}
			}
			else {
				echo Registry::get('twig')->render('add-to-top.tpl', array(
					'section_id'	=> $section_id,
					'resource_id'	=> $resource_id,
					'data'			=> ModelUsers::getTopData('top_to_main', $section_id, $resource_id),
					'action'		=> 'add-to-top-main',
					'title'			=> 'Добавить объявление в топ главной страницы',
					'curr_time'		=> DB::now(1)
				));
			}
		}
	}
	
	
	public static function addToTopMainPayment( $data) {
		
		$section_id =$data['section_id'];
		$resource_id=$data['resource_id'];
		$date_start=$data['curr_time'];
		$date_end=$data['time_end'];

		
		
		if ($section_id > 0 and $resource_id > 0) {
			if ($date_start && $date_end) {
				$where=array('section_id'=> $section_id, 'resource_id'=>$resource_id ); 
				 $count=DB::getTableCount("top_to_main",$where);
					if (!$count) {
						
						
						$flag=DB::insert('top_to_main', array(
							'section_id'	=> $section_id,
							'resource_id'	=> $resource_id,
							'date_start'	=> $date_start,
							'date_end'		=> $date_end,
							'sort_id'		=> 999,
							'date_add'		=> DB::now()
						), 1);
						
				    }else{
					
					    DB::update('top_to_main',array(
									 'date_start'	=> $date_start,
									 'date_end'		=> $date_end,
									 'date_add'		=> DB::now()
								   ),$where 
								  );

					}
						
						
						
						
						Site::removeFlagVipAdd2($section_id, $resource_id);
					  //Users::lightContent($section_id, $resource_id, $date_start, $date_end);
						//ModelUsers::lightContent($section_id,$resource_id,$date_start ,$date_end );
						
                        $count=DB::getTableCount("light_content",$where);
						if($count){
							DB::update('light_content',array(
									 'date_end'		=> $date_end,
								     ),$where 
								   );
							
						}else{
							DB::insert('light_content', array(
										'section_id'	=> $section_id,
										'resource_id'	=> $resource_id,
										'date_start'	=> $date_start,
										'date_end'		=> $date_end,
										'date_add'		=> DB::now()
									), 1);
						
					   }
						
						Users::addToTopPayment($data);
							
								

					
							  $where=array(
									'section_id'	=> $section_id,
									'resource_id'	=> $resource_id
									);
							
								DB::update('light_content',array(
									 'date_start'	=> $date_start,
									 'date_end'		=> $date_end,
									 'date_add'		=> DB::now()
									),$where
								 );
					
					
					
					
							
				
						   $table=Site::getSectionsTable($section_id);
						   $column=Site::getSectionsTableIdName($section_id);
						 
						   
		                 //  DB::update($table ,array('flag'=> 1), array($column=> $resource_id));
						
					
			
			
		}
	 }
	
    }
	public function addToTopMainDelete($section_id, $resource_id) {
		DB::delete('top_to_main', array(
			'section_id'	=> $section_id,
			'resource_id'	=> $resource_id
		));
		
		Users::lightContentDelete($section_id, $resource_id, 0);
		Users::addToTopDelete($section_id, $resource_id, 1);
		
		echo json_encode(array(
			'success'	=> true,
			'no_mess'	=> true,
			'message'	=> 'Объявление удалено из топ главной страницы'
		));
	}
	
	public function updateDateAdd($table, $resource_id_name, $resource_id) {
		$datediff = DB::getColumn("SELECT DATEDIFF(NOW(), date_add) FROM `$table` WHERE $resource_id_name = $resource_id");
		
		if($datediff > 13 or User::isAdmin()) {
			if($table == 'products_new') {
				DB::update('stocks', array(
					'date_add'			=> DB::now()
				), array(
					'product_new_id'	=> $resource_id
				));
			}
			
			DB::update($table, array(
				'date_add'			=> DB::now(),
				'is_update'			=> 1
			), array(
				$resource_id_name	=> $resource_id
			));
		}
		
		/* if($table == 'ads' and User::isAdmin() ){
			DB::update('ads',  array('pay'=>1) , array('ads_id'=>$resource_id));
		} */
		
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}

    public function setAdvCountry($countryId = 1) {
        if (User::isAdmin()) {
            Request::setSession('adv_country', $countryId);
        }

        Header::Location();
    }
	
	
	public function addUpdateTopLiqpay($section_id ) {
		
		//$section_id 
		//$resource_id
		
		
		if ($section_id > 0 and $resource_id > 0) {
			if (Request::post('date_start')) {
				if (User::isAdmin()) {
					if (date_diff(date_create(), date_create(Request::post('date_end')))->invert == 0) {
						DB::insert('top_to_main', array(
							'section_id'	=> $section_id,
							'resource_id'	=> $resource_id,
							'date_start'	=> Request::post('date_start', 'string'),
							'date_end'		=> Request::post('date_end', 'string'),
							'sort_id'		=> Request::post('sort_id', 'int'),
							'date_add'		=> DB::now()
						), 1);
						
						Site::removeFlagVipAdd($section_id, $resource_id);
						Users::lightContent($section_id, $resource_id, Request::post('date_start', 'string'), Request::post('date_end', 'string'));
						Users::addToTop($section_id, $resource_id, 1);
						
						echo json_encode(array(
							'success'	=> true,
							'no_mess'	=> true,
							'message'	=> 'Объявление добавлено в топ главной страницы'
						));
					}
					else {
						echo json_encode(array(
							'success'	=> false,
							'message'	=> 'Не верно указана дата окончания'
						));
					}
				}
			}
			else {
				echo Registry::get('twig')->render('add-to-top.tpl', array(
					'section_id'	=> $section_id,
					'resource_id'	=> $resource_id,
					'data'			=> ModelUsers::getTopData('top_to_main', $section_id, $resource_id),
					'action'		=> 'add-to-top-main',
					'title'			=> 'Добавить объявление в топ главной страницы'
				));
			}
		}
	}
	
	
	
	
}