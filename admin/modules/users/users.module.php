<?php

class users {
	
	private $user_permissions_tmp = array();
	
	public function index($sort = null) {
		
		switch ($sort) {
			case 'administrators':
				$where = "group_id = 4";
				$title = "Список администраторов портала";
			break;
			case 'unconfirmed':
                $join = 'INNER JOIN `users_confirms` USING(user_id)';
                $where = '1';
				$title = "Неподтвержденные пользователи";
			break;
			case 'zayavka':
               
                $where = 'user_dalete =1';
				$title = "Заявка на удаление";
			break; 
			 
			default:
				$where = "1";
				$title = "Список всех пользователей портала";  
			break;
		}

        if ($sort == 'unique-permissions') {
            echo Registry::get('twig')->render('users-unique-permissions.tpl', array(
                'title' => 'Пользователи, у которых заканчивается проплаченный период',
                'table'	=> array(
                    'title'		=> 'Пользователи, у которых заканчивается проплаченный период',
                    'data'		=> ModelUsers::getUsersAccessWarnings()
                )
            ));

            return false;
        }
		
		$query = "SELECT user_id, email, group_id, flag, flag_moder, 
			users_info.name, users_info.date_add, users_info.date_edit, users_info.country_id, 
			users_groups.name AS group_name
			FROM `users`
			INNER JOIN `users_info` USING(user_id)
			INNER JOIN `users_groups` USING(group_id)
			$join
			WHERE $where ";
		
		$users = DB::getAssocArray($query);
		
		
		echo Registry::get('twig')->render('users.tpl', array(
			'title' => 'Все пользователи',
			'table'	=> array(
				'title'		=> $title,
				'data'		=> $users
			)
		));
	}
	
	public function profile($id) {
		$user = User::getFullUserInfo($id);
		
		echo Registry::get('twig')->render('user-profile.tpl', array(
			'title' => 'Профиль пользователя',
			'user'	=> $user
		));
	}
	
	public function message($id) {
		$user = User::getFullUserInfo($id);
		
		echo Registry::get('twig')->render('user-send-message.tpl', array(
			'title' => 'Написать пользователю',
			'user'	=> $user
		));
	}
	
	public function add() {
		$countries	= ModelUsers::getCountriesListFromSelect();
		$regions 	= array(0 => 'Выберите из списка') + ModelUsers::getRegionsFromSelect(1);
		$groups 	= ModelUsers::getUserGroupsFromSelect();
		
		$form = new Form();
		
		$form->createTab('user-default', 'Основная информация');
		$form->createTab('user-contact', 'Контактные данные');
		$form->createTab('user-subscribe', 'Настройка подписки');
		$form->createTab('user-access', 'Управление доступом');
		
		$form->create('text', 'name', 'Ник или ФИО', null, 'user-default');
		$form->create('text', 'email', 'E-mail', null, 'user-default');
		 
		$form->create('pgenerate', 'passw', 'Пароль', null, 'user-default');
		
		$form->create('select', 'country_id', 'Страна', $countries, 'user-default');
		$form->create('select', 'region_id', 'Регион', $regions, 'user-default');
		$form->create('select', 'city_id', 'Населенный пункт', array(0 => 'Выберите регион...'), 'user-default');
			
		$form->create('file', 'avatar', 'Фото или аватар', null, 'user-default');
		$form->create('switch', 'flag_moder', 'Добавить без модерации', 1, 'user-default');
		
		$form->create('select', 'group_id', 'Группа пользователя', $groups, 'user-default');
		
		$form->create('text', 'contact_phones[0]', 'Телефон 1', null, 'user-contact');
		$form->create('text', 'contact_phones[1]', 'Телефон 2', null, 'user-contact');
		$form->create('text', 'contact_phones[2]', 'Телефон 3', null, 'user-contact');
		
		$form->create('text', 'icq', 'ICQ', null, 'user-contact');
		$form->create('text', 'skype', 'Skype', null, 'user-contact');
		
		$form->create('switch', 'flag_subscribe', 'Включить рассылку', 1, 'user-subscribe');
		
		$form->create('switch', 'flag_permissions', 'Использовать настройки группы', 1, 'user-access');
		
		$query = DB::DBObject()->prepare("SELECT section_id, name_sys FROM `sections` WHERE flag = 1 AND section_id NOT IN(1, 17) ORDER BY sort_id");
		$query->execute();
		
		while ($array = $query->fetch(PDO::FETCH_OBJ)) {
			$form->create('title', 'title-'.$array->section_id, $array->name_sys, null, 'user-access');
			
			$form->create('switch', 'flag_view['.$array->section_id.']', 'Разрешить просмотр раздела', 1, 'user-access');
			$form->setValues(array('flag_view['.$array->section_id.']' => 1));
			
			$form->create('switch', 'flag_add['.$array->section_id.']', 'Разрешить пользователю добавлять материал', 1, 'user-access');
			
			$form->create('switch', 'mod_type['.$array->section_id.']', 'Постмодерация', 1, 'user-access');
			
			$form->create('switch', 'flag_limit['.$array->section_id.']', 'Ограничить добавление', 1, 'user-access');
			$form->attr('flag_limit['.$array->section_id.']', 'class', 'user_access_check');
			
			$form->hide(array(
				'count['.$array->section_id.']',
				'time_life['.$array->section_id.']',
				'date_range['.$array->section_id.']'
			));
			
			$form->create('spinner', 'count['.$array->section_id.']', 'Количество разрешенных материалов', null, 'user-access');
			$form->create('spinner', 'time_life['.$array->section_id.']', 'Период отображения после размещения (дней)', null, 'user-access');
			$form->create('daterange', 'date_range['.$array->section_id.']', 'Ограничить период размещения', null, 'user-access');
			
			$sections[] = $array->section_id;
		}
		
		$form->setValues(array(
			'flag_moder' 		=> 1,
			'flag_default' 		=> 1,
			'group_id'			=> 2,
			'flag_permissions'	=> 1,
			'country_id' 		=> Site::getGeoCuuntry()
		));
		
		$form->required('name', 'email', 'passw', 'country_id');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				
				$user_id = User::addNewUser(
					Request::post('email', 'string'),
					Request::post('passw', 'hash'),
					Request::post('group_id', 'int'),
					1,
					Request::post('flag_moder', 'int') 
				);

				if ($_FILES['avatar']['name'] != null) {
					$avatar_name = mb_strtolower(Request::post('name', 'translitURL'), 'UTF-8') . '_' . $user_id;
					$avatar = User::addUserAvatar('avatar', $avatar_name);
				}
				else {
					$avatar = '';
				}
				
				User::setUserInfo($user_id, array(
					'country_id'		=> Request::post('country_id', 'int'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'name'				=> Request::post('name', 'string'),
					'contact_phones'	=> implode(',', Request::post('contact_phones')),
					'avatar'			=> $avatar,
					'icq'				=> Request::post('icq', 'string'),
					'skype'				=> Request::post('skype', 'string'),
					'ip_address'		=> Site::getRealIP()
				));
				
				if (Request::post('flag_permissions', 'int') == 0) {
					$flag_limit	= Request::post('flag_limit');
					$mod_type	= Request::post('mod_type');
					$count		= Request::post('count');
					$time_life 	= Request::post('time_life');
					$flag_add	= Request::post('flag_add');
					$flag_view	= Request::post('flag_view');
					
					$date_start = Request::post('start_date_range');
					$date_end	= Request::post('end_date_range');
					
					for ($i = 0, $c = count($sections); $i < $c; $i++) {
						$key = $sections[$i];
						
						$write = array(
							'user_id'			=> $user_id,
							'section_id'		=> (int) $key,
							'flag_view'			=> (int) $flag_view[$key],
							'flag_add'			=> (int) $flag_add[$key],
							'flag_limit'		=> (int) $flag_limit[$key],
							'mod_type'			=> (int) $mod_type[$key],
							'count'				=> (int) $count[$key],
							'time_life'			=> (int) $time_life[$key],
							'flag_date_limit'	=> $date_start[$key] != null ? 1 : 0,
							'date_start'		=> $date_start[$key],
							'date_end'			=> $date_end[$key]
						);
						
						DB::insert('users_permissions', $write);
					}
				}
			}
			
			$form->destroy(
				'/admin/users', 
				'/admin/user/edit-'.$user_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить нового пользователя',
			'Добавить нового пользователя'
		);
	}
	
	public function edit($user_id) {
		
		$user = User::getFullUserInfo($user_id);
		
		$contact_phones = @explode(',', $user['contact_phones']);
		
		for ($i = 0, $c = count($contact_phones); $i < $c; $i++) {
			$user['contact_phones[' . $i . ']'] = $contact_phones[$i];
		}
		
		$data_permissions = DB::DBObject()->query("SELECT section_id, flag_view, flag_add, flag_limit, 
			mod_type, count, time_life, flag_date_limit, date_start, date_end
			FROM `users_permissions` 
			WHERE user_id = $user_id"
		);
		
		$data_permissions = $data_permissions->fetchAll(PDO::FETCH_FUNC, array($this, 'groupEditFunc'));
		
		$countries	= ModelUsers::getCountriesListFromSelect();
		$regions 	= array(0 => 'Выберите из списка') + ModelUsers::getRegionsFromSelect(1);
		$cities		= ModelUsers::getCitiesFromSelect($user['region_id']);
		$groups 	= ModelUsers::getUserGroupsFromSelect();
		
		$form = new Form();
		
		$form->createTab('user-default', 'Основная информация');
		$form->createTab('user-contact', 'Контактные данные');
		$form->createTab('user-subscribe', 'Настройка подписки');
		$form->createTab('user-access', 'Управление доступом');
		
		$form->create('text', 'name', 'Ник или ФИО', null, 'user-default');
		$form->create('text', 'email', 'E-mail', null, 'user-default');

        $form->create('pgenerate', 'passw', 'Новый пароль', null, 'user-default');
		
		$form->create('select', 'country_id', 'Страна', $countries, 'user-default');
		$form->create('select', 'region_id', 'Регион', $regions, 'user-default');
		$form->create('select', 'city_id', 'Населенный пункт', array(0 => 'Выберите регион') + $cities, 'user-default');
			
		$form->create('file', 'avatar', 'Фото или аватар', null, 'user-default');
		$form->create('switch', 'flag_moder', 'Промодерирован', 1, 'user-default');
		
		$form->create('select', 'group_id', 'Группа пользователя', $groups, 'user-default');
		
		$form->create('text', 'contact_phones[0]', 'Телефон 1', null, 'user-contact');
		$form->create('text', 'contact_phones[1]', 'Телефон 2', null, 'user-contact');
		$form->create('text', 'contact_phones[2]', 'Телефон 3', null, 'user-contact');
		
		$form->create('text', 'site', 'Сайт', null, 'user-contact');
		
		$form->create('text', 'icq', 'ICQ', null, 'user-contact');
		$form->create('text', 'skype', 'Skype', null, 'user-contact');
		
		$form->create('file', 'avatar', 'Фото или аватар', null, 'user-default');
		
		$groups = DB::DBObject()->query("SELECT group_id, name FROM `users_groups` ORDER BY sort_id");
		$groups = $groups->fetchAll(PDO::FETCH_KEY_PAIR);
		
		$form->create('select', 'group_id', 'Группа пользователя', $groups, 'user-default');
		
		$form->create('switch', 'flag_subscribe', 'Включить рассылку', 1, 'user-subscribe');
		
		$form->create('switch', 'flag_permissions', 'Использовать настройки группы', 1, 'user-access');
		
		$query = DB::DBObject()->prepare("SELECT section_id, name_sys FROM `sections` WHERE flag = 1 AND section_id NOT IN(1, 17) ORDER BY sort_id");
		$query->execute();
		
		while ($array = $query->fetch(PDO::FETCH_OBJ)) {
			$form->create('title', 'title-'.$array->section_id, $array->name_sys, null, 'user-access');
			
			$form->create('switch', 'flag_view['.$array->section_id.']', 'Разрешить просмотр раздела', 1, 'user-access');
			$form->setValues(array('flag_view['.$array->section_id.']' => 1));
			
			$form->create('switch', 'flag_add['.$array->section_id.']', 'Разрешить пользователю добавлять материал', 1, 'user-access');
			
			$form->create('switch', 'mod_type['.$array->section_id.']', 'Постмодерация', 1, 'user-access');
			
			$form->create('switch', 'flag_limit['.$array->section_id.']', 'Ограничить добавление', 1, 'user-access');
			$form->attr('flag_limit['.$array->section_id.']', 'class', 'user_access_check');
			
			$form->hide(array(
				'count['.$array->section_id.']',
				'time_life['.$array->section_id.']',
				'date_range['.$array->section_id.']'
			));
			
			$form->create('spinner', 'count['.$array->section_id.']', 'Количество разрешенных материалов', null, 'user-access');
			$form->create('spinner', 'time_life['.$array->section_id.']', 'Период отображения после размещения (дней)', null, 'user-access');
			$form->create('daterange', 'date_range['.$array->section_id.']', 'Ограничить период размещения', null, 'user-access');
			
			$sections[] = $array->section_id;
		}
		
		$user_avatar = $user['avatar'];
		$user['avatar'] = $user['avatar_full']; 
		
		$form->setValues($user);
		$form->setValues($this->user_permissions_tmp);
		
		if (count($this->user_permissions_tmp) < 1) {
			$form->setValues(array(
				'flag_permissions'	=> 1
			));
		}
		
		$form->required('name', 'email', 'country_id', 'region_id', 'city_id');
		
		if ($send = $form->isSend()) {
			
			if ($form->checkForm()) {
				
				User::editUser(
					$user_id, 
					Request::post('email', 'string'), 
					Request::post('group_id', 'int'),
					1,
					Request::post('flag_moder', 'int'),
                    (Request::post('group_id', 'int') == $user['group_id'] ? 0 : 1)
				);

                if (Request::post('group_id', 'int') != $user['group_id']) {
                    User::updateUserAdsPermissionsByGroup(Request::post('group_id', 'int'), $user_id);
                }
				
				if ($_FILES['avatar']['name'] != null) {
					User::deleteUserAvatar($user_avatar);
					
					$avatar_name = mb_strtolower(Request::post('name', 'translitURL'), 'UTF-8') . '_' . $user_id;
					$user_avatar = User::addUserAvatar('avatar', $avatar_name);
				}

                if (Request::post('passw', 'string') != null) {
                    User::editUserPassw($user_id, Request::post('passw', 'string'));
                }
				
				$write = array(
					'country_id'		=> Request::post('country_id', 'int'),
					'region_id'			=> Request::post('region_id', 'int'),
					'city_id'			=> Request::post('city_id', 'int'),
					'name'				=> Request::post('name', 'string'),
					'contact_phones'	=> implode(',', Request::post('contact_phones')),
					'avatar'			=> $user_avatar,
					'icq'				=> Request::post('icq', 'string'),
					'site'				=> Request::post('site', 'url'),
					'skype'				=> Request::post('skype', 'string'),
                    'flag_default_permission' => Request::post('flag_permissions')
				);
				
				DB::update('users_info', $write, array('user_id' => $user_id));
				
				if (Request::post('flag_permissions', 'int') == 0) {
					$flag_limit	= Request::post('flag_limit');
					$mod_type	= Request::post('mod_type');
					$count		= Request::post('count');
					$time_life 	= Request::post('time_life');
					$flag_add	= Request::post('flag_add');
					$flag_view	= Request::post('flag_view');
					
					$date_start = Request::post('start_date_range');
					$date_end	= Request::post('end_date_range');
					
					for ($i = 0, $c = count($sections); $i < $c; $i++) {
						$key = $sections[$i];
						
						$write = array(
							'flag_view'			=> (int) $flag_view[$key],
							'flag_add'			=> (int) $flag_add[$key],
							'flag_limit'		=> (int) $flag_limit[$key],
							'mod_type'			=> (int) $mod_type[$key],
							'count'				=> (int) $count[$key],
							'time_life'			=> (int) $time_life[$key],
							'flag_date_limit'	=> $date_start[$key] != null ? 1 : 0,
							'date_start'		=> $date_start[$key],
							'date_end'			=> $date_end[$key]
						);
						
						if (count($this->user_permissions_tmp) > 0) {
							DB::update('users_permissions', $write, array('user_id' => $user_id, 'section_id' => $key));
						}
						else {
							DB::insert('users_permissions', array_merge(array('user_id' => $user_id, 'section_id' => $key), $write));
						}

                        $permissions[$key] = $write;
					}

                    User::updateUserAdsPermissionsByUser($user_id, $permissions);
				}
                else {
                    DB::delete('users_permissions', array(
                        'user_id' => $user_id
                    ));
                }
			}
			
			$form->destroy(
				'/admin/users', 
				'/admin/user/edit-'.$user_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать профиль пользователя',
			'Редактировать профиль пользователя'
		);
	}
	
	public function delete($user_id) {
		ModelUsers::delete($user_id);
		
		Header::Location('/admin/users');
	}
	
	public function vip($user_id) {
		$sections 	= ModelUsers::getSectionsList();
		$data		= ModelUsers::getUserVip($user_id);
		
		for ($i = 0, $c = count($data); $i < $c; $i++) {
			$values[$data[$i]['section_id']] = $data[$i];
		}
		
		//die(var_dump($values));
		
		$form = new Form();
		
		foreach ($sections as $key => $value) {
			$form->create('title', 'title-'.$key, $value);
			$form->create('daterange', 'date_range[' . $key . ']', 'Период действия');
			$form->create('spinner', 'count[' . $key . ']', 'Количество VIP мест');
			
			$form->setValues(array(
				'start_date_range[' . $key . ']' 	=> $values[$key]['date_start'],
				'end_date_range[' . $key . ']' 		=> $values[$key]['date_end'],
				'count[' . $key . ']'				=> $values[$key]['count']
			));
		}
		
		//$form->create('select', 'section_id', 'Раздел', array(0 => 'Выберите раздел') + $sections);
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				
				$count 		= Request::post('count');
				$date_start	= Request::post('start_date_range');
				$date_end	= Request::post('end_date_range');
				
				foreach ($count as $key => $value) {
					ModelUsers::addUserVip(
						$user_id,
						$key,
						$value,
						$date_start[$key],
						$date_end[$key]
					);
				}
			}
			
			$form->destroy(
				'/admin/users', 
				'/admin/user/vip-' . $user_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить VIP',
			'Добавить VIP'
		);
	}
	
	public function sorted() {
		parse_str($_GET['data'], $sort);
		
		$query = "UPDATE `users_groups` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['group']); $i < $c; $i++) 
			$query .= " WHEN group_id = " . (int)$sort['group'][$i] . " THEN $i ";
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
		
		return true;
	}
	
	public function groups() {
		
		$array = DB::getAssocArray("SELECT group_id, name, date_add, date_edit FROM `users_groups`");
		
		echo Registry::get('twig')->render('users-groups.tpl', array(
			'title' 	=> 'Группы пользователей',
			'groups'	=> $array
		));
	}
	
	public function groupView($group_id) {
		$query = "SELECT group_id, name, description, date_add, date_edit
			FROM `users_groups`
			WHERE group_id = $group_id";
		
		$group = DB::getAssocArray($query, 1);
		
		$query = "SELECT users_permissions.*, sections.name_sys
			FROM `users_permissions` 
			INNER JOIN `sections` USING(section_id)
			WHERE group_id = $group_id";
		
		$permissions = DB::getAssocArray($query);
		
		/*$query = DB::DBObject()->prepare($query);
		$query->execute();
		
		while ($array = $query->fetch(PDO::FETCH_OBJ)) {
			
		}*/
		
		echo Registry::get('twig')->render('group-profile.tpl', array(
			'title' 		=> 'Параметры доступа группы',
			'group'			=> $group,
			'permissions'	=> $permissions
		));
	}
	
	public function groupAdd() {
		
		$form = new Form();
		
		$form->createTab('group-default', 'Основные настройки');
		$form->createTab('group-access', 'Доступ к разделам сайта');
		$form->createTab('group-admin', 'Доступ к панели управления');
		
		$form->create('text', 'name', 'Название группы', null, 'group-default');
		$form->create('textarea', 'description', 'Описание', null, 'group-default');
		
		$query = DB::DBObject()->prepare("SELECT section_id, name_sys FROM `sections` WHERE section_id NOT IN(1, 17) ORDER BY sort_id");
		$query->execute();
		
		while ($array = $query->fetch(PDO::FETCH_OBJ)) {
			$form->create('title', 'title-'.$array->section_id, $array->name_sys, null, 'group-access');
			
			$form->create('switch', 'flag_view['.$array->section_id.']', 'Разрешить просмотр раздела', 1, 'group-access');
			$form->setValues(array('flag_view['.$array->section_id.']' => 1));
			
			$form->create('switch', 'flag_add['.$array->section_id.']', 'Разрешить пользователям добавлять материал', 1, 'group-access');
			
			$form->create('switch', 'mod_type['.$array->section_id.']', 'Постмодерация', 1, 'group-access');
			
			$form->create('switch', 'flag_limit['.$array->section_id.']', 'Ограничить добавление', 1, 'group-access');
			$form->attr('flag_limit['.$array->section_id.']', 'class', 'user_access_check');
			
			$form->hide(array(
				'count['.$array->section_id.']',
				'time_life['.$array->section_id.']',
				'date_range['.$array->section_id.']'
			));
			
			$form->create('spinner', 'count['.$array->section_id.']', 'Количество разрешенных материалов', null, 'group-access');
			$form->create('spinner', 'time_life['.$array->section_id.']', 'Период отображения после размещения (дней)', null, 'group-access');
			$form->create('daterange', 'date_range['.$array->section_id.']', 'Ограничить период размещения', null, 'group-access');
			
			$form->create('switch', 'flag_admin', 'Разрешить доступ к панели управления', 1, 'group-admin');
			
			$sections[] = $array->section_id;
		}
		
		$form->required('name');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				
				$flag_limit	= Request::post('flag_limit');
				$mod_type	= Request::post('mod_type');
				$count		= Request::post('count');
				$time_life 	= Request::post('time_life');
				$flag_add	= Request::post('flag_add');
				$flag_view	= Request::post('flag_view');
				
				$date_start = Request::post('start_date_range');
				$date_end	= Request::post('end_date_range');
				
				$write = array(
					'name'			=> Request::post('name', 'string'),
					'description'	=> Request::post('description', 'string'),
					'date_add'		=> DB::now(),
					'flag_admin'	=> Request::post('flag_admin', 'int')
				);
				
				if (DB::insert('users_groups', $write)) {
					$group_id = DB::lastInsertId();
					
					for ($i = 0, $c = count($sections); $i < $c; $i++) {
						$key = $sections[$i];
						
						$write = array(
							'group_id'			=> $group_id,
							'section_id'		=> (int) $key,
							'flag_view'			=> (int) $flag_view[$key],
							'flag_add'			=> (int) $flag_add[$key],
							'flag_limit'		=> (int) $flag_limit[$key],
							'mod_type'			=> (int) $mod_type[$key],
							'count'				=> (int) $count[$key],
							'time_life'			=> (int) $time_life[$key],
							'flag_date_limit'	=> $date_start[$key] != null ? 1 : 0,
							'date_start'		=> $date_start[$key],
							'date_end'			=> $date_end[$key]
						);
						
						DB::insert('users_permissions', $write);
					}
				}
				
				$form->destroy(
					'/admin/users/groups', 
					'/ admin/user/group/edit-'.DB::lastInsertId()
				);
			}
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить новоую группу пользователей',
			'Добавить новоую группу пользователей'
		);
	}
	
	public function groupEditFunc($section_id, $flag_view, $flag_add, $flag_limit, $mod_type, $count, $time_life, $flag_date_limit, $date_start, $date_end) {
		$this->user_permissions_tmp = array_merge($this->user_permissions_tmp, array(
			'flag_view['.$section_id.']'		=> $flag_view,
			'flag_add['.$section_id.']'			=> $flag_add,
			'flag_limit['.$section_id.']'		=> $flag_limit,
			'mod_type['.$section_id.']'			=> $mod_type,
			'count['.$section_id.']'			=> $count,
			'time_life['.$section_id.']'		=> $time_life,
			'flag_date_limit['.$section_id.']'	=> $flag_date_limit,
			'start_date_range['.$section_id.']'	=> $date_start != '0000-00-00' ? $date_start : '',
			'end_date_range['.$section_id.']'	=> $date_end != '0000-00-00' ? $date_end : ''
		));
		
		return true;
	}
	
	public function groupEdit($group_id) {
		
		$data_group = DB::getAssocArray("SELECT group_id, name, description, flag_admin FROM `users_groups` WHERE group_id = $group_id", 1);
		
		$data_permissions = DB::DBObject()->query("SELECT section_id, flag_view, flag_add, flag_limit, 
			mod_type, count, time_life, flag_date_limit, date_start, date_end
			FROM `users_permissions` 
			WHERE group_id = $group_id"
		);
		
		$data_permissions = $data_permissions->fetchAll(PDO::FETCH_FUNC, array($this, 'groupEditFunc'));
		
		$form = new Form();
		
		$form->createTab('group-default', 'Основные настройки');
		$form->createTab('group-access', 'Доступ к разделам сайта');
		$form->createTab('group-admin', 'Доступ к панели управления');
		
		$form->create('text', 'name', 'Название группы', null, 'group-default');
		$form->create('textarea', 'description', 'Описание', null, 'group-default');
		
		$query = DB::DBObject()->prepare("SELECT section_id, name_sys FROM `sections` WHERE section_id NOT IN(1, 17) ORDER BY sort_id");
		$query->execute();
		
		while ($array = $query->fetch(PDO::FETCH_OBJ)) {
			$form->create('title', 'title-'.$array->section_id, $array->name_sys, null, 'group-access');
			
			$form->create('switch', 'flag_view['.$array->section_id.']', 'Разрешить просмотр раздела', 1, 'group-access');
			
			$form->create('switch', 'flag_add['.$array->section_id.']', 'Разрешить пользователям добавлять материал', 1, 'group-access');
			
			$form->create('switch', 'mod_type['.$array->section_id.']', 'Постмодерация', 1, 'group-access');
			
			$form->create('switch', 'flag_limit['.$array->section_id.']', 'Ограничить добавление', 1, 'group-access');
			$form->attr('flag_limit['.$array->section_id.']', 'class', 'user_access_check');
			
			$form->hide(array(
				'count['.$array->section_id.']',
				'time_life['.$array->section_id.']',
				'date_range['.$array->section_id.']'
			));
			
			$form->create('spinner', 'count['.$array->section_id.']', 'Количество разрешенных материалов', null, 'group-access');
			$form->create('spinner', 'time_life['.$array->section_id.']', 'Период отображения после размещения (дней)', null, 'group-access');
			$form->create('daterange', 'date_range['.$array->section_id.']', 'Ограничить период размещения', null, 'group-access');
			
			$sections[] = $array->section_id;
		}
		
		$form->create('switch', 'flag_admin', 'Разрешить доступ к панели управления', 1, 'group-admin');
		
		$form->required('name');
		
		$form->setValues($data_group);
		$form->setValues($this->user_permissions_tmp);
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				
				$flag_limit	= Request::post('flag_limit');
				$mod_type	= Request::post('mod_type');
				$count		= Request::post('count');
				$time_life 	= Request::post('time_life');
				$flag_add	= Request::post('flag_add');
				$flag_view	= Request::post('flag_view');
				
				$date_start = Request::post('start_date_range');
				$date_end	= Request::post('end_date_range');
				
				$write = array(
					'name'			=> Request::post('name', 'string'),
					'description'	=> Request::post('description', 'string'),
					'flag_admin'	=> Request::post('flag_admin', 'int')
				);
				
				if (DB::update('users_groups', $write, array('group_id' => $group_id))) {

					for ($i = 0, $c = count($sections); $i < $c; $i++) {
						$key = $sections[$i];
						
						$write = array(
							'flag_view'			=> (int) $flag_view[$key],
							'flag_add'			=> (int) $flag_add[$key],
							'flag_limit'		=> (int) $flag_limit[$key],
							'mod_type'			=> (int) $mod_type[$key],
							'count'				=> (int) $count[$key],
							'time_life'			=> (int) $time_life[$key],
							'flag_date_limit'	=> $date_start[$key] != null ? 1 : 0,
							'date_start'		=> $date_start[$key],
							'date_end'			=> $date_end[$key]
						);
						
						if (count($this->user_permissions_tmp["flag_view[$key]"]) > 0) {
							DB::update('users_permissions', $write, array('group_id' => $group_id, 'section_id' => $key));
						}
						else {
							DB::insert('users_permissions', array_merge(array('group_id' => $group_id, 'section_id' => $key), $write));
						}

                        User::updateUserAdsPermissionsByGroup($group_id);
					}
				}
				
				$form->destroy(
					'/admin/users/groups', 
					'/admin/users/group/edit-'.$group_id
				);
			}
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Изменить группу "'.$data_group['name'].'"',
			'Изменить группу '.$data_group['name']
		);
	}
	
	public function groupDelete($group_id) {
		ModelUsers::groupDelete($group_id);
		
		Header::Location('/admin/users/groups');
	}
	
	public function settings() {
		$form = new Form();
		
		//$form->createTab('settings-global', 'Глобальные');
		$form->createTab('settings-access', 'Настройки доступа по умолчанию');
		
		//$form->create('radiobuttons', 'moderation_type', 'Тип модерации', array( 0 => 'Постмодерация', 1 => 'Премодерация'), 'settings-global');
		
		$query = DB::DBObject()->prepare("SELECT section_id, name_sys FROM `sections` WHERE flag = 1 ORDER BY sort_id");
		$query->execute();
		
		while ($array = $query->fetch(PDO::FETCH_OBJ)) {
			$form->create('title', 'title-'.$array->section_id, $array->name_sys, null, 'settings-access');
			
			$form->create('switch', 'flag_add['.$array->section_id.']', 'Разрешить пользователям добавлять материал', 1, 'settings-access');
			$form->create('spinner', 'count['.$array->section_id.']', 'Количество разрешенных материалов', null, 'settings-access');
			$form->create('switch', 'flag_date_limit['.$array->section_id.']', 'Ограничить период размещения', 1, 'settings-access');
			$form->create('spinner', 'life_time['.$array->section_id.']', 'Период отображения после размещения (дней)', null, 'settings-access');
			$form->create('hidden', 'section['.$array->section_id.']', null, 1, 'settings-access');
		}
		
		$values = Admin::getDefaultPermissionToForm();
		$form->setValues($values);
		
		if ($send = $form->isSend()) {
			$sections 			= array_keys(Request::post('section'));
			
			$flag_add			= Request::post('flag_add');
			$count				= Request::post('count');
			$flag_date_limit 	= Request::post('flag_date_limit');
			$life_time			= Request::post('life_time');
			 
			for ($i = 0, $c = count($sections); $i < $c; $i++) {
				$id = $sections[$i];
				
				$write = array(
					'section_id' 		=> Str::get($id)->filterInt(),
					'flag_add'			=> Str::get($flag_add[$id])->filterInt(),
					'count'				=> Str::get($count[$id])->filterInt(),
					'flag_date_limit'	=> Str::get($flag_date_limit[$id])->filterInt(),
					'life_time'			=> Str::get($life_time[$id])->filterInt()
				);
				
				DB::insert('users_permissions_default', $write, 1);
				unset($write);
			}
			
			$form->destroy(
				'/admin/users', 
				'/admin/users/settings'
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Настройки пользователей',
			'Настройки пользователей'
		);
	}
	
	public function auth($user_id) {
		if (User::isAdmin()) {
			User::setUserInfoToSession($user_id);
			
			Header::Location('/cabinet');
		}
	}

    public function updateUserPermissions($group_id) {
        User::updateUserAdsPermissionsByGroup($group_id);

        Header::Location('/admin/users/groups');
    }
}