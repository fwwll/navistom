<?php

class Ads {
    public function index($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $page = 1, $search = null, $user_id = 0, $is_updates = 0, $translit = '') {
	             
        Site::setSectionView(4, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
        $ads 		= ModelAds::getAdsList($categ_id, $sub_categ_id, $producer_id, $product_id, $page, Registry::get('config')->itemsInPage, Request::get('country'), $search, $user_id, $is_updates, $flag);
        $count		= ModelAds::getAdsCount($categ_id, $sub_categ_id, $producer_id, $product_id, Request::get('country'), $user_id, $is_updates, $search, $flag);
		
		 $count_products =ModelProducts::getProductsNewCount($categ_id, $sub_categ_id, $producer_id, $product_id, Request::get('country'), $user_id, $filter, $is_updates, $search, $flag); 
		 
        $pagination	= Site::pagination(Registry::get('config')->itemsInPage, $count, $page);
         
		  
        if ($categ_id > 0 or $sub_categ_id > 0) {
            if ($sub_categ_id > 0) {
                $categ_id = ModelAds::getParentIdCategory($sub_categ_id);
            }
				$sub_categs = ModelAds::getCategoriesFromSelectOnly($categ_id, $user_id);
				$meta = ModelProducts::getCategoryMetaTags($sub_categ_id > 0 ? $sub_categ_id : $categ_id);
				Header::SetTitle($meta['meta_title'] . ' - б/у');
				Header::SetH1Tag('Продам Б/У - ' . $meta['title']);
				Header::SetMetaTag('description', $meta['meta_description'] . ' - ' . Header::GetMetaTag('description'));
				Header::SetMetaTag('keywords', $meta['meta_keys'] . ', ' . Header::GetMetaTag('keywords'));
        }

        //$producers	= ModelAds::getProducersFromSelect($categ_id, $sub_categ_id);
        $producers = ModelAds::getProducersFromSelectOnly($categ_id, $sub_categ_id, $user_id);

        if ($producer_id > 0 or $product_id > 0) {

            if ($product_id > 0) {
                $producer_id = ModelAds::getParentProducerId($product_id);
            }

            for ($i = 0, $c = count($producers); $i < $c; $i++) {
                if ($producers[$i]['producer_id'] == $producer_id) {
                    $prefix = $producers[$i]['name'];

                    break;
                }
            }

            $products = ModelAds::getProductsFromSelectOnly($producer_id, $categ_id, $sub_categ_id, $user_id);

            if ($product_id > 0) {
                for ($i = 0, $c = count($products); $i < $c; $i++) {
                    if ($products[$i]['product_id'] == $product_id) {
                        $prefix .= ' ' . $products[$i]['name'];

                        break;
                    }
                }
            }

            Header::SetTitle(Header::GetTitle() . ' - ' . $prefix);

            Header::SetH1Tag('Продам Б/У - ' . $prefix);

            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $prefix);
            Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ' - ' . $prefix);
        }

        if ($page > 1) {
            Header::SetTitle(Header::GetTitle() . ' - страница ' . $page);
            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - страница ' . $page);
        }

        if ($user_id > 0) {
            Header::SetTitle(Header::GetTitle() . ' - ' . $ads[0]['user_name']);
            Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $ads[0]['user_name']);
            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $ads[0]['user_name']);
            Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $ads[0]['user_name']);
        }
         Header::SetTitle(Header::GetTitle() . ' - ' . ' used condition');
        $categs = ModelAds::getCategoriesFromSelectOnly(0, $user_id);

        $subscribe = ModelCabinet::getUserSubscribeCategs(User::isUser(), -1, 4);

        if ($sub_categ_id > 0) {
            $subscribe_categ = $categ_id;
        }

        if ($categ_id > 0 and is_array($subscribe)) {
            if ($sub_categ_id > 0) {
                $subscribe_status = in_array($sub_categ_id, $subscribe);
            }
            else {

                $subscribe_status = in_array($categ_id, ModelCabinet::getUserSubscribeCategs(User::isUser(), 1, 4));
            }
        }
        else {
            $categoriesKeys = array();
            for ($i = 0, $c = count($categs); $i < $c; $i++) {
                $categoriesKeys[] = $categs[$i]['categ_id'];
            }

            $subscribe_status =  count(@array_intersect($categoriesKeys, $subscribe)) >= count($categoriesKeys);
        }
          if($_GET['tp']=='new'){ 
			 $tpl_name='ads-new2.tpl';  
			 
		   }else{
			 /* $tpl_name='ads.tpl'; */
			 $tpl_name='ads-new2.tpl'; 
        			 
		   }
	
        echo Registry::get('twig')->render($tpl_name, array(
            'categs'			=> $categs,
            'producers'			=> $producers,
            'products'			=> $products,
            'sub_categs'		=> $sub_categs,
            'parent_id'			=> $categ_id,
            'parent_producer'	=> $producer_id,
            'ads'				=> $ads,
            'pagination'		=> $pagination,
            'subscribe_status'	=> $subscribe_status,
            'subscribe_categ'	=> $subscribe_categ,
			'prefix'			=>$meta['title'],
			 'products_url'		=>($count_products ? preg_replace('/ads/','products',Request::get('route')):0), 
            'replace_user_url'  => preg_replace('/(\/page-[0-9]*)?\/user\-[0-9]*\-[a-z\-_]*/', '', Request::get('route')),
            'url'               => preg_replace('/ads(\/page-[0-9]*)?/', '', Request::get('route'))
        ));
    }

	
	
    public function full($ads_id) {
        ModelAds::setViews($ads_id, User::isUser());

        $ads = ModelAds::getAdsFull($ads_id);
		  
		   if(! count($ads)){
			  Header::Location('/404'); 
		  } 
		  
		
		 if(Site::is_image('http://navistom.com/uploads/images/offers/full/'.($ads["url_full"]))){
			    $ads['big_img']='/uploads/images/offers/full/';
				$ads['class']='big';
		   }else{
			    $ads['big_img']='/uploads/images/offers/160x200/';
				$ads['class']='min';
		   }
        // Site::d($ads['big_img']);
        if (!$ads['ads_id']) {
           // Header::Location('/404');
        }

        if (!User::isAdmin() and $ads['user_id'] != User::isUser() and ($ads['flag'] == 0 or $ads['flag_show'] == 0)) {
            Header::Location('/404');
        }

        if (Registry::get('ajax') == -1 and $ads['flag_delete'] > 0) {
            Header::Location('/' . Registry::get('country_url') . '/ads/' . 'sub_categ-' . $ads['sub_categ_id'] . '-' . Str::get($ads['categ_name'])->truncate(60)->translitURL() .'#404');
        }

        $meta = ModelProducts::getCategoryMetaTags($ads['sub_categ_id']);

        Header::SetTitle($ads['product_name'] .
            ' - б\у - купить, ' .
            Str::get($ads['description'])->strToLower()
        );

        Header::SetMetaTag('description',
            $ads['product_name'] . ' б\у, ' . $ads['description'] . ', ' .
            $ads['contact_phones'] .
            ', цена ' . $ads['price'] . ' ' . $ads['currency_name']
        );

        Header::SetMetaTag('keywords',$ads['product_name'] . ' б\у');
		Header::SetTitle(Header::GetTitle() . ' - ' . ' used condition');
        
        $exchanges = User::getExchangesUser($ads['country_id'], $ads['user_id']);
   
        if ($exchanges[$ads['currency_id']]){
            $price = bcmul($ads['price'], $exchanges[$ads['currency_id']][0]['rate'], 2);
        }
        else {
            $price = $ads['price'];
        }

        foreach ($exchanges as $currency_id => $val) {
            $prices[] = array(
                'name'	=> $val[0]['name_min'],
                'val'	=> @bcdiv($price, $val[0]['rate'], 2)
            );
        }

        Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/offers/160x200/' . $ads['url_full']);

        $currency = Registry::get('config')->default_currency;

        $vip = ModelAds::getVIP($ads['country_id'], $ads['sub_categ_id'], $ads_id);
		
        if(count($vip) > 0) {
            Registry::set('exchanges', User::getExchanges(Request::get('country')));
        }
		
        if($_GET['tp']=='new'){

		 $tpl_name='ads_new-full.tpl';  
	    }else{
		 $tpl_name='ads-full.tpl';
         $tpl_name='ads_new-full.tpl'; 		 
	    }
		 
        echo Registry::get('twig')->render($tpl_name, array(
            'ads'		=> $ads,
            'price'		=> $price,
            'currency'	=> $currency[$ads['country_id']],
            'prices'	=> $prices,
            'gallery'	=> ModelAds::getAdsGallery($ads_id),
            'vip'		=> $vip
        ));
    }

    public function add() {
		$price =Site::getPriceCategoriy(4);
		$price_json=Site::dataJsoneString ($price);
		$chekbox= Site::getPriceCategoriyCheked(4);
		//Site::d($price);
        echo Registry::get('twig')->render('ads-add.tpl', array(
            'categs'		=> ModelAds::getCategoriesFromSelect(),
            'producers'		=> ModelAds::getProducersFromSelect(),
            'is_add_access'	=> User::isUserAccess(4),
            'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1)),
			'price'			=> $price,
			'price_json'	=> $price_json,
			'chekbox'		=>$chekbox
        ));
    }

    public function addAjax() {
        Header::ContentType("text/plain");
        if (User::isUserAccess(4)) {
            if (Request::PostIsNull('categ_id', 'sub_categ_id', 'price')) {
                $user_info = User::getUserContacts();

                $ads_id = ModelAds::addAds(array(
                        'product_id'		=> Request::post('product_id', 'int'),
                        'producer_id'		=> Request::post('producer_id', 'int'),
                        'categ_id'			=> Request::post('categ_id', 'int'),
                        'sub_categ_id'		=> Request::post('sub_categ_id', 'int'),
                        'user_id'			=> User::isUser(),
                        'user_name'			=> $user_info['name'],
                        'contact_phones'	=> Request::post('contact_phones', 'string'),
                        'currency_id'		=> Request::post('currency_id', 'int'),
                        'country_id'		=> Request::get('country'),
                        'price'				=> Request::post('price', 'float'),
                        'price_description'	=> Request::post('price_description', 'string'),
                        'content'			=> Request::post('content', 'string'),
                        'link'				=> Request::post('link', 'url'),
                        'video_link'		=> Request::post('video_link', 'url'),
                        'flag_moder'		=> User::isPostModeration(4) ? 1 : 0,
                        'flag_vip_add'		=> Request::post('submit', 'string') == 'vip' ? 1 : 0
                    ),
                    Request::post('images'),
                    Request::post('new_producer_name', 'string'),
                    Request::post('new_product_name', 'string'),
                    Request::post('new_product_description', 'string')
                );

                if(Request::post('submit', 'string') == 'vip') {
                    ModelMain::updateVipRequest(4, $ads_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($ads_id,4); 
				
                }

                if ($ads_id > 0) {
                    $result = array(
                        'success'	=> true,
                        'message'	=> User::isPostModeration(4) ? 'Товар успешно добавлен' : 'Товар добавлен на модерацию и появится на сайте через некоторое время ',
						'send_data'	=> $data['send_data'],
						'portmone'	=> $data['portmone'],
						'product_id'=>$ads_id
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

    public function edit($ads_id) {
        $data 	= ModelAds::getAdsData($ads_id);
        $images	= ModelAds::getAdsImages($ads_id);
		 if($_GET['tp']=='new'){ 
			 $tpl_name='ads-edit-new2.tpl';  
		   }else{
			 $tpl_name='ads-edit.tpl';
			  	
		   }

        echo Registry::get('twig')->render($tpl_name, array(
            'data'			=> $data,
            'images'		=> $images,
            'categs'		=> ModelAds::getCategoriesFromSelect(),
            'sub_categs'	=> ModelAds::getCategoriesFromSelect($data['categ_id']),
            'producers'		=> ModelAds::getProducersFromSelect(),
            'products'		=> ModelAds::getProductsFromSelect($data['producer_id']),
            'images_count'	=> 7 - count($images)
        ));
    }

    public function editAjax($ads_id) {
        if (User::isUser() == ModelAds::getUserId($ads_id) or User::isAdmin()) {
            if (Request::PostIsNull('categ_id', 'sub_categ_id', 'price')) {

                ModelAds::editAds($ads_id, array(
                        'product_id'		=> Request::post('product_id', 'int'),
                        'producer_id'		=> Request::post('producer_id', 'int'),
                        'categ_id'			=> Request::post('categ_id', 'int'),
                        'sub_categ_id'		=> Request::post('sub_categ_id', 'int'),
                        'currency_id'		=> Request::post('currency_id', 'int'),
                        'contact_phones'	=> Request::post('contact_phones', 'string'),
                        'price'				=> Request::post('price', 'float'),
                        'price_description'	=> Request::post('price_description', 'string'),
                        'content'			=> Request::post('content', 'string'),
                        'video_link'		=> Request::post('video_link', 'url')
                    ),
                    Request::post('images'),
                    Request::post('image_description'),
                    Request::post('new_producer_name', 'string'),
                    Request::post('new_product_name', 'string'),
                    Request::post('new_product_description', 'string')
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

    public function quickSelection() {
        echo Registry::get('twig')->render('quick-selection.tpl', array(
            'categs'	=> ModelProducts::getCategoriesFromSelect()
        ));
    }

    public function allCategories() {
		Site::get_meta('abs-all-categories');  
        echo Registry::get('twig')->render('all-categories.tpl', array(
		
            'categs'		=> Site::getCategoriesFromSelect(0, 4),
            'sub_categs'	=> Site::getCategoriesFromSelect(0, 4, true),
			'label'			=>'в продам Б/У'
        ));
    }

    public function allProducers() {
	    Site::get_meta('abs-all-producers');
        echo Registry::get('twig')->render('all-producers.tpl', array(
            'producers'	=> ModelAds::getProducersListOrderByName(),
			'label'			=>'в продам Б/У'
        ));
    }

    public function allSalespeople() {
		   Site::get_meta('abs-all-salespeople');
        echo Registry::get('twig')->render('all-salespeople.tpl', array(
            'salespeople'	=> ModelAds::getSalespeople(),
			'label'			=>' в продам Б/У'
        ));
    }

    public function getProducers($sub_categ_id = 0) {
        Header::ContentType("text/plain");

        $producers = ModelAds::getProducersFromSelect(0, $sub_categ_id);

        echo json_encode($producers);
    }

    public function delete($ads_id) {
        if (User::isUser() == ModelAds::getUserId($ads_id) or User::isAdmin()) {
            ModelAds::delete($ads_id);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    public function flag($ads_id, $flag = 0) {
        if (User::isUser() == ModelAds::getUserId($ads_id) or User::isAdmin()) {
            ModelAds::editFlag($ads_id, $flag);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    public function flagModer($ads_id, $flag_moder = 0) {
        if (User::isAdmin()) {
            ModelAds::editFlagModer($ads_id, $flag_moder);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    public function transferToProducts($ads_id) {
        if (User::isAdmin()) {
            ModelAds::transferToProducts($ads_id);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    /**
     * Get sub categories list
     * AJAX
     *
     * @param int $categ_id
     */
    public function getSubCategs($categ_id = 0, $flag_min = 0) {
        Header::ContentType("text/plain");

        $categs = ModelAds::getCategoriesFromSelect($categ_id, $flag_min);

        echo json_encode($categs);
    }

    public function sendMessage($ads_id) {
        if (Request::PostIsNull('message', 'user_id')) {

            if (Request::post('user_id', 'int') != User::isUser() or User::isAdmin()) {

                $message_id = ModelAds::saveUserMessage(
                    $ads_id,
                    User::isUser(),
                    Request::post('user_id', 'int'),
                    Request::post('message', 'string')
                );

                /**
                 * New Notification
                 */

                $from 			= User::getUserContacts();
                $to				= User::getUserInfo(Request::post('user_id', 'int'));

                $data			= ModelAds::getAdsFull($ads_id);
                $translit		= Str::get($data['product_name'])->truncate(60)->translitURL();
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
                        'name'				=> $data['product_name'] . ', Б/У',
                        'price'				=> $data['price'],
                        'currency_name'		=> $data['currency_name'],
                        'description'		=> $data['description'],
                        'link'				=> $base_url . 'ads/' . $ads_id . '-' . $translit,
                        'vip_link'			=> $base_url . 'vip-request-4-' . $ads_id
                    ),
                    $attach
                );

                /* End Notification */

                /*$user_from_info = User::getUserInfo(Request::post('user_id', 'int'));
                $user_to_info	= User::getUserContacts();

                $data			= ModelAds::getAdsFull($ads_id);
                $translit		= Str::get($data['product_name'])->truncate(60)->translitURL();

                if ($_FILES['attach']['name'] != '') {
                    $attach = array(
                        'file'	=> $_FILES['attach']['tmp_name'],
                        'name'	=> $_FILES['attach']['name']
                    );
                }

                Site::sendMessageToMail(
                    'Новое сообщение c NaviStom.com',
                    $user_from_info['email'],
                    array(
                        'user_name'		=> $user_from_info['name'],
                        'message'		=> (User::isAdmin() ? 'Администратор' : 'Пользователь') . " <b>{$user_to_info['name']}</b> написал Вам сообщение на объявление <br> <a href='http://navistom.com/ads/$ads_id-$translit'><b>{$data['product_name']}, Б/У</b></a>",
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
            echo Registry::get('twig')->render('send-user-message.tpl', array(
                'data'		=> ModelAds::getAdsUserInfo($ads_id),
                'messages'	=> ModelAds::getUserMessages($ads_id, User::isUser()),
                'mess_tpls'	=> Site::getMessTplsToSelect(4),
                'controller'=> 'ads'
            ));
        }
    }

    public function uploadImage() {
    // Header::ContentType("text/plain");

      $img = ModelAds::uploadImage();

		echo json_encode($img);
    }

    public function deleteImage($image_id) {
        Header::ContentType("text/plain");

        echo json_encode(array(
            'success'	=> ModelAds::deleteImage($image_id)
        ));
    }

    public function addVipRequest($ads_id) {

    }
	
	
	
	public function noPay($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $page = 1, $search = null, $user_id = 0, $is_updates = 0, $translit = '') {
		
	
		if(! User::isUser()){
		   header('Location: /404');
		 die;
		}
		Request::setCookie('alert', 1, null);		 
        Site::setSectionView(4, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }
		
        $ads 		= ModelAds::getAdsListNoPay($categ_id, $sub_categ_id, $producer_id, $product_id, $page, Registry::get('config')->itemsInPage, Request::get('country'), $search, $user_id, $is_updates, $flag);
        $count		= ModelAds::getAdsCount($categ_id, $sub_categ_id, $producer_id, $product_id, Request::get('country'), $user_id, $is_updates, $search, $flag ,1);
		
		
		 $count_products =ModelProducts::getProductsNewCount($categ_id, $sub_categ_id, $producer_id, $product_id, Request::get('country'), $user_id, $filter, $is_updates, $search, $flag); 
		    
        $pagination	= Site::pagination(Registry::get('config')->itemsInPage, $count, $page,1);
         
		  
        if ($categ_id > 0 or $sub_categ_id > 0) {
            if ($sub_categ_id > 0) {
                $categ_id = ModelAds::getParentIdCategory($sub_categ_id);
            }
				$sub_categs = ModelAds::getCategoriesFromSelectOnly($categ_id, $user_id);
				$meta = ModelProducts::getCategoryMetaTags($sub_categ_id > 0 ? $sub_categ_id : $categ_id);
				Header::SetTitle($meta['meta_title'] . ' - б/у');
				Header::SetH1Tag('Продам Б/У - ' . $meta['title']);
				Header::SetMetaTag('description', $meta['meta_description'] . ' - ' . Header::GetMetaTag('description'));
				Header::SetMetaTag('keywords', $meta['meta_keys'] . ', ' . Header::GetMetaTag('keywords'));
        }

        //$producers	= ModelAds::getProducersFromSelect($categ_id, $sub_categ_id);
        $producers = ModelAds::getProducersFromSelectOnly($categ_id, $sub_categ_id, $user_id);

        if ($producer_id > 0 or $product_id > 0) {

            if ($product_id > 0) {
                $producer_id = ModelAds::getParentProducerId($product_id);
            }

            for ($i = 0, $c = count($producers); $i < $c; $i++) {
                if ($producers[$i]['producer_id'] == $producer_id) {
                    $prefix = $producers[$i]['name'];

                    break;
                }
            }

            $products = ModelAds::getProductsFromSelectOnly($producer_id, $categ_id, $sub_categ_id, $user_id);

            if ($product_id > 0) {
                for ($i = 0, $c = count($products); $i < $c; $i++) {
                    if ($products[$i]['product_id'] == $product_id) {
                        $prefix .= ' ' . $products[$i]['name'];

                        break;
                    }
                }
            }

            Header::SetTitle(Header::GetTitle() . ' - ' . $prefix);

            Header::SetH1Tag('Продам Б/У - ' . $prefix);

            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $prefix);
            Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ' - ' . $prefix);
        }

        if ($page > 1) {
            Header::SetTitle(Header::GetTitle() . ' - страница ' . $page);
            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - страница ' . $page);
        }

        if ($user_id > 0) {
            Header::SetTitle(Header::GetTitle() . ' - ' . $ads[0]['user_name']);
            Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $ads[0]['user_name']);
            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $ads[0]['user_name']);
            Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $ads[0]['user_name']);
        }

        $categs = ModelAds::getCategoriesFromSelectOnly(0, $user_id);

        $subscribe = ModelCabinet::getUserSubscribeCategs(User::isUser(), -1, 4);

        if ($sub_categ_id > 0) {
            $subscribe_categ = $categ_id;
        }

        if ($categ_id > 0 and is_array($subscribe)) {
            if ($sub_categ_id > 0) {
                $subscribe_status = in_array($sub_categ_id, $subscribe);
            }
            else {

                $subscribe_status = in_array($categ_id, ModelCabinet::getUserSubscribeCategs(User::isUser(), 1, 4));
            }
        }
        else {
            $categoriesKeys = array();
            for ($i = 0, $c = count($categs); $i < $c; $i++) {
                $categoriesKeys[] = $categs[$i]['categ_id'];
            }

            $subscribe_status =  count(@array_intersect($categoriesKeys, $subscribe)) >= count($categoriesKeys);
        }
          
	
        echo Registry::get('twig')->render('nopay.tpl', array(
            'categs'			=> $categs,
            'producers'			=> $producers,
            'products'			=> $products,
            'sub_categs'		=> $sub_categs,
            'parent_id'			=> $categ_id,
            'parent_producer'	=> $producer_id,
            'ads'				=> $ads,
            'pagination'		=> $pagination,
            'subscribe_status'	=> $subscribe_status,
            'subscribe_categ'	=> $subscribe_categ,
			'prefix'			=>$meta['title'],
			 'products_url'		=>($count_products ? preg_replace('/ads/','products',Request::get('route')):0), 
            'replace_user_url'  => preg_replace('/(\/page-[0-9]*)?\/user\-[0-9]*\-[a-z\-_]*/', '', Request::get('route')),
            'url'               => preg_replace('/ads(\/page-[0-9]*)?/', '', Request::get('route'))
        ));
    }
    public function calls($user_id){

		 if( ModelAds::callsAdd($user_id)){
			echo json_encode( array('call'=>1)) ;
		 }else{
			 echo json_encode(array('error'=>0));
		 }
	}
	
	public function remove(){
	    if(User::isAdmin())	
		    ModelAds::removeAds();
		  else
			Header::Location('/404');  
	}
	
}