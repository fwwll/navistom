<?php

class Cron {
	public function index($key) {

		if ($key == 31031990) {
			ModelCron::getExchangeRatesUA();

            ModelCron::deleteNotConfirmedUsers();

			ModelCron::createSitemap(
				ModelCron::getSectionsToSitemap(),
				ModelMaps::getSitMaps(),
				ModelCron::getArticlesToSitemap(),
				ModelCron::getProductsToSitemap(),
				ModelCron::getAdsToSitemap(),
				ModelCron::getActivityToSitemap(),
				ModelCron::getResumeToSitemap(),
				ModelCron::getVacancyToSitemap(),
				ModelCron::getLabsToSitemap(),
				ModelCron::getRealtyToSitemap(),
				ModelCron::getServicesToSitemap(),
				//ModelCron::getDiagnosticToSitemap(),
				ModelCron::getDemandToSitemap()
			);
			
			Statistic::createStatisticCache();

           // self::updateUAParserData();

            echo 'Create sitemap.xml, ', 'create statistic cache, ', 'update user agent parser DB';
		}
		else {
			die('Invalid code!');
		}
	}

    public function updateUAParserData() {
        $parser = new Udger\Parser(false);
        $parser->SetDataDir( Registry::get('config')->uaParserCache );
        $parser->SetAccessKey( Registry::get('config')->uaParserAccessKey );
    }
	
	public function inArray($input, $array) {
		if (is_array($input)) {
			foreach ($input as $value) {
				if (in_array($value, $array)) {
					return true;
				}
			}
		}
		else {
			return @in_array($input, $array);
		}
		
		return false;
	}

    public function clearSubscribeStorage($key) {
        if ($key == 666) {
            Mailer\Storage::clear();
            DB::truncate('subscribe_logs');
        }
    }
	
	public function sendSubscribe($key) {
		if ($key == 31031990) {
			set_time_limit(0);

            $storage = \Mailer\Storage::get();
            $materials = ModelCabinet::getSubscribeItems($storage);
			/* echo'<pre>'; 
			var_dump($materials);
			die; */

            if (count($materials) < 1) {
                return false;
            }

            $users = User::getSubscribeUsers(250);
            if (!is_array($users) or count($users) == 0) {
                return false;
            }

            $mailer = new \Mailer\Mailer();
            $mailer->useSMTP();
            $mailer->logged(true);

            foreach($users as $user) {
                $messages = array();

                $categories = ModelCabinet::getUserSubscribeCategs($user['user_id']);
                $cities = ModelCabinet::getUserSubscribeCities($user['user_id']);

                if (is_array($categories)) {
                    $sections = array_keys($categories);
                }

                $sections = @array_unique(array_merge(
                    @array_keys($categories),
                    @array_keys($cities)
                ));

                if (count($sections) < 1) {
                    User::setUserSubscribeDate($user['user_id']);
                    continue;
                }

                foreach ($sections as $section_id) {
                    switch ($section_id) {
                        case 16:
                        case 3:
                        case 2:
                        case 4:
                            if (is_array($materials[$section_id])) {
                                for ($i = 0, $c = count($materials[$section_id]); $i < $c; $i++) {
                                    $categs = is_numeric($materials[$section_id][$i]['categs']) ? $materials[$section_id][$i]['categs'] : explode(',', $materials[$section_id][$i]['categs']);
                                    if (Cron::inArray($categs, $categories[$section_id])) {
                                        $messages[] = $materials[$section_id][$i];
                                    }
                                }
                            }
                            break;
                        case 11:
                            if (is_array($materials[$section_id])) {
                                $messages = array_merge($messages, $materials[$section_id]);
                            }
                            break;
                        case 10:
                            if (is_array($materials[$section_id])) {
                                if ($cities[$section_id][0] == -1) {
                                    $messages = array_merge($messages, $materials[$section_id]);
                                }
                                else {
                                    for ($i = 0, $c = count($materials[$section_id]); $i < $c; $i++) {
                                        if (Cron::inArray($materials[$section_id]['city_id'], $cities[$section_id])) {
                                            $messages[] = $materials[$section_id][$i];
                                        }
                                    }
                                }
                            }
                            break;
                        default:
                            if (is_array($materials[$section_id])) {
                                for ($i = 0, $c = count($materials[$section_id]); $i < $c; $i++) {
                                    $categs = is_numeric($materials[$section_id][$i]['categs']) ? $materials[$section_id][$i]['categs'] : explode(',', $materials[$section_id][$i]['categs']);

                                    if (Cron::inArray($categs, $categories[$section_id]) and ($cities[$section_id][0] == -1 or @array_search($cities[$section_id][$i]['city_id'], $cities[$section_id]))) {
                                        $messages[] = $materials[$section_id][$i];
                                    }
                                }
                            }
                            break;
                    }
                }

                if (count($messages) < 1) {
                    continue;
                }

                $mailer->buildMessage('email-mess-subscribe-complete.tpl', $messages);

                $mailer->send($user['email'], $user['name'], $user['user_id']);

                sleep(1);
            }
		} else {
            die('Invalid code');
        }
	}
	
	
	public function end($day){
		    $mailer = new \Mailer\Mailer();
           // $mailer->useSMTP();
            $mailer->logged(true);
			
			switch($day){
			    case '45':$id=88; $message_type="Осталось 5 дней  публикации";  break;
				case '48':$id=92; $message_type="Осталось 2 дня  публикации";  break;
				case '47':$id=86;$message_type="Осталось 3 дня  публикации";    break;
				case '50':$id=87;$message_type="Ваше объявление НЕ ОТОБРАЖАЕТСЯ ";  break;
				case '53':$id=90;$message_type="Ваше объявление НЕ ОТОБРАЖАЕТСЯ ";  break;
				case '55':$id=89;$message_type="Ваше объявление НЕ ОТОБРАЖАЕТСЯ ";  break;
				case '56':$id=89;$message_type="Ваше объявление НЕ ОТОБРАЖАЕТСЯ ";  break;
				default: die('error :(') ;
		    }
		    $mailer->subject=$message_type;
		    $text =DB::getAssocArray("SELECT message FROM feedback_mess_tpls WHERE mess_id =$id",1);
			
			 $users=ModelCron::user_groub_end($day);
			
			 if(!count($users)) die('no  message');
			 $email='';
			 
			 foreach($users as $user  ){
                 
				$contents=   ModelCron::getEndPay($day,  $user['user_id'] );
				$user_name =$contents[0]['user_name'];
				$email =$contents[0]['email'];
	
				$mailer->buildMessage('email-mess-end-day.tpl', 
					$contents, 
					0,
					$text['message'],
					$message_type,
					$user_name
				);

				 $meils=array(1=>$email,2=>'navistom@gmail.com');
				 foreach($meils as  $m ){
				    if($email) $mailer->send($m,$message_type);
				 }		
			}
			 
			 
			 
			 
		
		
		
		
		
		
	} 
	
	
}