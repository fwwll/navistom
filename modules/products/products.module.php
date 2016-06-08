<?php
/**
 * Controller new products
 *
 */


class Products {

    public function index($categ_id = 0, $sub_categ_id = 0, $producer_id = 0, $product_id = 0, $page = 1, $filter = null, $search = null, $user_id = 0, $is_updates = 0, $translit = '', $nopay=0) {

	
        Site::setSectionView(3, User::isUser());

        $str = @explode('::', $translit);
        $flag = null;
        if (count($str) > 1) {
            $flag = $str[1];
        }

        $search 		= Str::get($search)->filterString();
        $categs         = ModelProducts::getCategoriesFromSelectOnly(0, ($filter == 'stocks'), true, $user_id);
		
		
		 

			$products_new	= ModelProducts::getProductsList($categ_id, $sub_categ_id, $producer_id, $product_id, $page, Registry::get('config')->itemsInPage, 1, $filter, $search, $user_id, $is_updates, $flag,$nopay);
          
        $count 	= ModelProducts::getProductsNewCount($categ_id, $sub_categ_id, $producer_id, $product_id, Request::get('country'), $user_id, $filter, $is_updates, $search, $flag ,$nopay);
		
		
		
		$count_ads	= ModelAds::getAdsCount($categ_id, $sub_categ_id, $producer_id, $product_id, Request::get('country'), $user_id, $is_updates, $search, $flag);
		
        $pagination = Site::pagination(Registry::get('config')->itemsInPage, $count, $page);

        if ($filter == 'stocks') {
            $pagination['next_page'] = $pagination['next_page'] > 0 ? 'filter-stocks/page-' . $pagination['next_page'] : 0;
            $pagination['prev_page'] = $pagination['prev_page'] > 1 ? 'filter-stocks/page-' . $pagination['prev_page'] : 0;
            $pagination['last']['url'] = $page != $pagination['last']['url'] ? 'filter-stocks/page-' . $pagination['last']['url'] : 0;
            $pagination['first']['url'] = 'filter-stocks/page-' . $pagination['first']['url'];

            for ($i = 0, $c = count($pagination['pages']); $i < $c; $i++) {
                $pagination['pages'][$i]['url'] = 'filter-stocks/' . $pagination['pages'][$i]['url'];
            }
        }
        else {
            $pagination['next_page'] = $pagination['next_page'] > 0 ? 'page-' . $pagination['next_page'] : 0;
            $pagination['prev_page'] = $pagination['prev_page'] > 1 ? 'page-' . $pagination['prev_page'] : 0;
            $pagination['last']['url'] = ($page != $pagination['last']['url']) ? 'page-' . $pagination['last']['url'] : 0;
            $pagination['first']['url'] = 'page-' . $pagination['first']['url'];
        }

        $sub_categs = array();

        if ($filter == 'stocks') {
            $meta = Site::getDefaultMetaTags('stocks');

            Header::SetTitle($meta['meta_title']);

            Header::SetH1Tag($meta['title']);

            Header::SetMetaTag('description', $meta['meta_description']);
            Header::SetMetaTag('keywords', $meta['meta_keys']);
        }

        if ($categ_id > 0 or $sub_categ_id > 0) {

            if ($sub_categ_id > 0) {
                $categ_id = ModelProducts::getParentIdCategory($sub_categ_id);
            }

            $sub_categs = ModelProducts::getCategoriesFromSelectOnly($categ_id, ($filter == 'stocks'), true, $user_id);

            $meta = ModelProducts::getCategoryMetaTags($sub_categ_id > 0 ? $sub_categ_id : $categ_id);

            if ($filter == 'stocks') {
                Header::SetTitle($meta['meta_title'] . ' - акции');

                Header::SetH1Tag('Акции - ' . $meta['name']);

                Header::SetMetaTag('description', $meta['meta_description']  . ' - акции');
                Header::SetMetaTag('keywords', $meta['meta_keys']  . ', акции');
            }
            else {
                Header::SetTitle($meta['meta_title']);

                Header::SetH1Tag($meta['title']);

                Header::SetMetaTag('description', $meta['meta_description']);
                Header::SetMetaTag('keywords', $meta['meta_keys']);
            }
        }
            
        $producers = ModelProducts::getProducersFromSelectOnly(($filter == 'stocks'), $categ_id, $sub_categ_id, $user_id);

        $products = array();

        if ($producer_id > 0 or $product_id > 0) {
            if ($product_id > 0) {
                $producer_id = ModelProducts::getParentProducerId($product_id);
            }

            for ($i = 0, $c = count($producers); $i < $c; $i++) {
                if ($producers[$i]['producer_id'] == $producer_id) {
                    $prefix = $producers[$i]['name'];

                    break;
                }
            }

            $products = ModelProducts::getProductsFromSelectOnly($producer_id, ($filter == 'stocks'), $categ_id, $sub_categ_id, $user_id);

            if ($product_id > 0) {
                $prefix .= ' ' . $products[$product_id];
            }

            Header::SetTitle(Header::GetTitle() . ' - ' . $prefix);

            Header::SetH1Tag($prefix);

            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $prefix);
            Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ' - ' . $prefix);
        }

        if ($page > 1) {
            Header::SetTitle(Header::GetTitle() . ' - страница ' . $page);
            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - страница ' . $page);
        }

        if ($user_id > 0) {
            Header::SetTitle(Header::GetTitle() . ' - ' . $products_new[0]['user_name']);
            Header::SetH1Tag(Header::GetH1Tag() . ' - ' . $products_new[0]['user_name']);
            Header::SetMetaTag('description', Header::GetMetaTag('description') . ' - ' . $products_new[0]['user_name']);
            Header::SetMetaTag('keywords', Header::GetMetaTag('keywords') . ', ' . $products_new[0]['user_name']);
        }
		
        Header::SetTitle(Header::GetTitle() . ' - ' . ' new condition');
		
        $subscribe = ModelCabinet::getUserSubscribeCategs(User::isUser(), -1, $filter != null ? 2 : 3);

        if ($sub_categ_id > 0) {
            $subscribe_categ = $categ_id;
        }

        if ($subscribe && count($subscribe) > 0) {
            $subCategoriesKeys = array();
            $categoriesKeys = array();
            for ($i = 0, $c = count($sub_categs); $i < $c; $i++) {
                $subCategoriesKeys[] = $sub_categs[$i]['categ_id'];
            }

            for ($i = 0, $c = count($categs); $i < $c; $i++) {
                $categoriesKeys[] = $categs[$i]['categ_id'];
            }

            if ($categ_id > 0 && $sub_categ_id == 0) {
                $subscribeStatus = in_array($categ_id, $subscribe) && count(array_intersect($subCategoriesKeys, $subscribe)) >= count($subCategoriesKeys);
            } elseif ($sub_categ_id > 0) {
                $subscribeStatus = in_array($sub_categ_id, $subscribe);
            } else {
                $subscribeStatus = (count(@array_intersect($categoriesKeys, $subscribe)) >= count($categoriesKeys));
            }
        } else {
            $subscribeStatus = false;
        }

		   if($_GET['tp']=='new'){
	            
			 $tpl_name='products-new-new2.tpl';  
		   }else{
			 //$tpl_name='products-new.tpl'; 
			 $tpl_name='products-new-new2.tpl';	
		   }
			 if($nopay){
				$tpl_name='products-nopay.tpl';  
			 }
		
        echo Registry::get('twig')->render($tpl_name, array(
            'categs'			=> $categs,
            'producers'			=> $producers,
            'products'			=> $products,
            'sub_categs'		=> $sub_categs,
            'parent_id'			=> $categ_id,
            'parent_producer'	=> $producer_id,
            'products_new'		=> $products_new,
            'pagination'		=> $pagination,
            'is_stock_add_access'=> User::isUserAccess(2),
            'subscribe_status'	=> $subscribeStatus,
            'subscribe_categ'	=> $subscribe_categ,
			'prefix'			=>$meta['title'],
			'ads_url'			=>($count_ads ? preg_replace('/products/','ads',Request::get('route')):0), 
            'replace_user_url'  => preg_replace('/(\/page-[0-9]*)?\/user\-[0-9]*\-[a-z\-_]*/', '', Request::get('route')),
            'url'               => preg_replace('/products(\/page-[0-9]*)?/', '', Request::get('route'))
        ));

	}

    public function full($product_new_id) {
        ModelProducts::setViews($product_new_id, User::isUser());

        $product = ModelProducts::getProductFull($product_new_id);
		  
		  if(Site::is_image('http://navistom.com/uploads/images/products/full/'.($product["image"]))){
			    $product['big_img']='/uploads/images/products/full/';
				$product['class']='big';
		   }else{
			    $product['big_img']='/uploads/images/products/80x100/';
				$product['class']='min';
		   }
		  

        if (!$product['product_new_id']) {
            Header::Location('/404');
        }

        if (!User::isAdmin() and $product['user_id'] != User::isUser() and ($product['flag'] == 0 or $product['flag_show'] == 0)) {
            Header::Location('/404');
        }

        if (Registry::get('ajax') == -1 and $product['flag_delete'] > 0) {
            Header::Location('/' . Registry::get('country_url') . '/products/' . 'sub_categ-' . $product['sub_categ_id'] . '-' . Str::get($product['categ_name'])->truncate(60)->translitURL() .'#404');
        }

        $meta = ModelProducts::getCategoryMetaTags($product['sub_categ_id']);

        $exchanges = User::getExchangesUser($product['country_id'], $product['user_id']);

        if ($exchanges[$product['currency_id']]) {
            $price = bcmul($product['price'], $exchanges[$product['currency_id']][0]['rate'], 2);
        }
        else {
            $price = $product['price'];
        }

        if ($exchanges[$product['stock_currency_id']]) {
            $stock_price = bcmul($product['stock_price'], $exchanges[$product['stock_currency_id']][0]['rate'], 2);
        }
        else {
            $stock_price = $product['stock_price'];
        }

        if($product['stock_flag'] > 0) {
            foreach ($exchanges as $currency_id => $val) {
                $prices[] = array(
                    'name'	=> $val[0]['name_min'],
                    'val'	=> bcdiv($stock_price, $val[0]['rate'], 2)
                );
            }
        }
        else {
            foreach ($exchanges as $currency_id => $val) {
                $prices[] = array(
                    'name'	=> $val[0]['name_min'],
                    'val'	=> bcdiv($price, $val[0]['rate'], 2)
                );
            }
        }

        Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/products/160x200/' . $product['image']);

        $currency = Registry::get('config')->default_currency;

        $vip = ModelProducts::getVIP($product['country_id'], $product['sub_categ_id'], $product_new_id);

        if(count($vip) > 0) {
            Registry::set('exchanges', User::getExchanges(Request::get('country')));
        }

        $phoneMeta = explode(',', $product['contact_phones']);

        if ($product['stock_flag'] > 0) {
            Header::SetTitle('Акция - ' .
                $product['product_name'] . ' - скидка, ' .
                $meta['meta_title']
            );

            Header::SetMetaTag('description',
                'Акция - ' . $product['product_name'] . ' ' . $product['description'] . ', ' .
                $phoneMeta[0] .
                ', цена ' . $stock_price . ' ' . $currency[1]
            );

            Header::SetMetaTag('keywords',
                $product['product_name'] . ', ' .
                'акция, скидка, распродажа, ' .
                Registry::get('country_name')
            );
        }
        else {
            Header::SetTitle(
                $product['product_name'].
                ' - купить ' .
                Str::get($product['description'])->strToLower() . ' - ' .
                $product['user_name']
            );

            Header::SetMetaTag('description',
                $product['product_name'] . ' ' . $product['description'] . ', ' .
                $phoneMeta[0] .
                ', цена ' . $price . ' ' . $currency[1] . ' - ' . $product['user_name'] . ' - ' . $product['city']
            );

            Header::SetMetaTag('keywords', $product['product_name']);
        }
		   Header::SetTitle(Header::GetTitle() . ' - ' . ' new condition');
		
           if($_GET['tp']=='new'){  
			  $tpl_name='product-new-full.tpl';
		   }else{
			 $tpl_name='product-new-full.tpl';
             $tpl_name='product-new-new-full.tpl';			 
		   }
			  $product['end_stamp'] =time($product['date_end']);
		 // Site::d($product['price_description'],1);
			  
        echo Registry::get('twig')->render($tpl_name, array(
            'product'		=> $product,
            'price'			=> $price,
            'stock_price'	=> $stock_price,
            'currency'		=> $currency[$product['country_id']],
            'prices'		=> $prices,
            'gallery'		=> ModelProducts::getProductGallery($product_new_id),
            'vip'			=> $vip
        ));
    }

    public function stock() {
        echo Registry::get('twig')->render('stock.tpl', array(

        ));
    }

    public function sendMessage($product_new_id) {

        if (Request::PostIsNull('message', 'user_id')) {

            if (Request::post('user_id', 'int') != User::isUser()) {
                $message_id = ModelProducts::saveUserMessage(
                    $product_new_id,
                    User::isUser(),
                    Request::post('user_id', 'int'),
                    Request::post('message', 'string')
                );

                /**
                 * New Notification
                 */

                $from 			= User::getUserContacts();
                $to				= User::getUserInfo(Request::post('user_id', 'int'));

                $data			= ModelProducts::getProductFull($product_new_id);
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
                        'name'				=> $data['product_name'],
                        'price'				=> $data['price'],
                        'currency_name'		=> $data['currency_name'],
                        'description'		=> $data['description'],
                        'link'				=> $base_url . 'product/' . $product_new_id . '-' . $translit,
                        'vip_link'			=> $base_url . 'vip-request-3-' . $product_new_id
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

            echo Registry::get('twig')->render('send-user-message.tpl', array(
                'data'		=> ModelProducts::getProroductNewUserInfo($product_new_id),
                'messages'	=> ModelProducts::getUserMessages($product_new_id, User::isUser()),
                'mess_tpls'	=> Site::getMessTplsToSelect(3),
                'controller'=> 'product'
            ));
        }
    }

    public function quickSelection() {
        echo Registry::get('twig')->render('quick-selection.tpl', array(
            'categs'	=> ModelProducts::getCategoriesFromSelect()
        ));
    }

    public function allCategories() {
		Site::get_meta('products-all-categories');
        echo Registry::get('twig')->render('all-categories.tpl', array(
            'categs'		=> Site::getCategoriesFromSelect(),
            'sub_categs'	=> Site::getCategoriesFromSelect(0, 3, true),
			'label'			=>'в продам новое'
        ));
    }

    public function allProducers() {
		Site::get_meta('products-all-producers');
        echo Registry::get('twig')->render('all-producers.tpl', array(
            'producers'	=> ModelProducts::getProducersListOrderByName(),
			'label'			=>'в продам новое'
        ));
    }

    public function allSalespeople() {
		     Site::get_meta('products-all-salespeople');
        echo Registry::get('twig')->render('all-salespeople.tpl', array(
            'salespeople'	=> ModelProducts::getSalespeople(),
			'label'			=>' в продам новое'
        ));
    }

    /**
     * Add product action
     *
     */
    public function add() {
		
        Header::SetTitle('Добавить новый товар' . ' - ' . Header::GetTitle());
        Header::SetMetaTag('description', 'Добавить новый товар');
        Header::SetMetaTag('keywords', 'Добавить новый товар');
		
		$price =Site::getPriceCategoriy(3);
		$price_json=Site::dataJsoneString ($price);
		$chekbox= Site::getPriceCategoriyCheked(3);

        echo Registry::get('twig')->render('product-add.tpl', array(
            'categs'		=> ModelProducts::getCategoriesFromSelect(),
            'producers'		=> ModelProducts::getProducersFromSelect(),
           'is_add_access'	=> User::isUserAccess(4), 
            'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1)),
			'price'			=> $price,
			'price_json'	=> $price_json,
			'chekbox'		=>$chekbox
        ));
    }

    public function addStock($product_new_id) {
        echo Registry::get('twig')->render('stock-add.tpl', array(
            'is_add_access'	=> User::isUserAccess(2),
            'product_new_id'=> $product_new_id
        ));
    }

    public function editStock($product_new_id) {
        echo Registry::get('twig')->render('stock-edit.tpl', array(
            'data'			=> ModelProducts::getStockData($product_new_id),
            'product_new_id'=> $product_new_id
        ));
    }

    public function deleteStock($product_new_id) {
        if (User::isUser() == ModelProducts::getUserId($product_new_id) or User::isAdmin()) {
            ModelProducts::deleteStock($product_new_id);

            echo json_encode(array(
                'success'	=> true,
                'message'	=> 'Акция удалена'
            ));
        }
        else {
            echo json_encode(array(
                'success'	=> false,
                'message'	=> 'У Вас нет прав для удаления этой акции!'
            ));
        }
    }

    public function addStockAjax($product_new_id) {
        if (User::isUser() == ModelProducts::getUserId($product_new_id) or User::isAdmin()) {
            if (Request::PostIsNull('price', 'date_start', 'date_end')) {

                ModelProducts::addStock($product_new_id, array(
                    'currency_id'		=> Request::post('currency_id', 'int'),
                    'price'				=> Request::post('price', 'float'),
                    'price_description'	=> Request::post('price_description', 'string'),
                    'content'			=> Request::post('content', 'string'),
                    'date_start'		=> Request::post('date_start', 'string'),
                    'date_end'			=> Request::post('date_end', 'string')
                ));

                echo json_encode(array(
                    'success'	=> true,
                    'message'	=> 'Акция успешно добавлена'
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

    public function editStockAjax($product_new_id) {
        if (User::isUser() == ModelProducts::getUserId($product_new_id) or User::isAdmin()) {
            if (Request::PostIsNull('price', 'date_start', 'date_end')) {

                DB::update('stocks', array(
                    'currency_id'		=> Request::post('currency_id', 'int'),
                    'country_id'		=> Request::get('country'),
                    'price'				=> Request::post('price', 'float'),
                    'price_description'	=> Request::post('price_description', 'string'),
                    'content'			=> Request::post('content', 'string'),
                    'date_start'		=> Request::post('date_start', 'string'),
                    'date_end'			=> Request::post('date_end', 'string')
                ), array(
                    'product_new_id'	=> $product_new_id
                ));

                echo json_encode(array(
                    'success'	=> true,
                    'message'	=> 'Акция успешно отредактирована'
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

    public function addAjax() {
        Header::ContentType("text/plain");

        if (User::isUserAccess(4)) {
            if (Request::PostIsNull('categ_id', 'sub_categ_id', 'price')) {
                $user_info = User::getUserContacts();

                $product_new_id = ModelProducts::addProductNew(array(
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
                        'video_link'		=> Request::post('video_link', 'url'),
                        'flag_moder'		=> User::isPostModeration(3) ? 1 : 0,
                        'flag_vip_add'		=> Request::post('submit', 'string') == 'vip' ? 1 : 0
                    ),
                    Request::post('images'),
                    Request::post('new_producer_name', 'string'),
                    Request::post('new_product_name', 'string'),
                    Request::post('new_product_description', 'string')
                );

                if(Request::post('submit', 'string') == 'vip') {
                    ModelMain::updateVipRequest(3, $product_new_id, Request::post('vipStatus', 'int'));
					$data=ModelPayment::startPayments($product_new_id,3); 
					
                }

                if ($product_new_id > 0) {
                    $result = array(
                        'success'	=> true,
                        'message'	=> User::isPostModeration(3) ? 'Новый товар успешно добавлен' : 'Товар добавлен на модерацию и появится на сайте через некоторое время',
						'send_data'	=> $data['send_data'],
						'portmone'	=> $data['portmone'],
						'product_id'=>$product_new_id
						
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

    public function edit($product_new_id) {
        $data 	= ModelProducts::getProductData($product_new_id);
        $images	= ModelProducts::getProductNewImages($product_new_id);

        echo Registry::get('twig')->render('product-edit.tpl', array(
            'data'			=> $data,
            'categs'		=> ModelProducts::getCategoriesFromSelect(),
            'producers'		=> ModelProducts::getProducersFromSelect(),
            'products'		=> ModelProducts::getProductsFromSelect($data['producer_id']),
            'sub_categs'	=> ModelProducts::getCategoriesFromSelect($data['categ_id']),
            'images'		=> $images,
            'images_count'	=> 7 - count($images)
        ));
    }

    public function editAjax($product_new_id) {
        if (User::isUser() == ModelProducts::getUserId($product_new_id) or User::isAdmin()) {
            if (Request::PostIsNull('categ_id', 'sub_categ_id', 'price')) {

                ModelProducts::editProductNew($product_new_id, array(
                        'product_id'		=> Request::post('product_id', 'int'),
                        'producer_id'		=> Request::post('producer_id', 'int'),
                        'categ_id'			=> Request::post('categ_id', 'int'),
                        'sub_categ_id'		=> Request::post('sub_categ_id', 'int'),
                        'currency_id'		=> Request::post('currency_id', 'int'),
                        'contact_phones'	=> Request::post('contact_phones', 'string'),
                        'price'				=> Request::post('price', 'float'),
                        'price_description'	=> Request::post('price_description', 'string'),
                        'content'			=> Request::post('content', 'string'),
                        'video_link'		=> Request::post('video_link', 'url'),
                        'flag_moder'		=> Request::post('flag_moder', 'int')
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

    public function delete($product_new_id) {
        if (User::isUser() == ModelProducts::getUserId($product_new_id) or User::isAdmin()) {
            ModelProducts::delete($product_new_id);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    public function flag($product_new_id, $flag = 0) {
        if (User::isUser() == ModelProducts::getUserId($product_new_id) or User::isAdmin()) {
            ModelProducts::editFlag($product_new_id, $flag);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    public function flagModer($product_new_id, $flag_moder = 0) {
        if (User::isAdmin()) {
            ModelProducts::editFlagModer($product_new_id, $flag_moder);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    public function transferToAds($product_new_id) {
        if (User::isAdmin()) {
            ModelProducts::transferToAds($product_new_id);
        }

        Header::Location($_SERVER['HTTP_REFERER']);
    }

    /**
     * Validation new producer
     * AJAX
     *
     */
    public function validationProducerName() {
        Header::ContentType("text/plain");

        $producer_name 	= Str::get(Request::get('fieldValue'))->filterString()->trim();
        $producer_id	= ModelProducts::validationProducerName($producer_name);
        $result[0]		= Request::get('fieldId', 'string');

        if ($producer_id > 0) {
            $result[1] = false;
        }
        else {
            $result[1] = true;
        }

        echo json_encode($result);
    }

    /**
     * Validation new product
     * AJAX
     *
     */
    public function validationProductName() {
        Header::ContentType("text/plain");

        $product_name 	= Str::get(Request::get('fieldValue'))->filterString()->trim();
        $producer_id	= Request::get('producer_id', 'int');
        $result[0]		= Request::get('fieldId', 'string');

        $product_id		= ModelProducts::validationProductName($producer_id, $product_name);

        if ($product_id > 0) {
            $result[1] = false;
        }
        else {
            $result[1] = true;
        }

        echo json_encode($result);
    }

    /**
     * Get sub categories list
     * AJAX
     *
     * @param int $categ_id
     */
    public function getSubCategs($categ_id = 0, $flag_min = 0, $flag_ads = 0) {
        Header::ContentType("text/plain");

        $categs = ModelProducts::getCategoriesFromSelect($categ_id, $flag_min, $flag_ads);

        echo json_encode($categs);
    }

    /**
     * Get producers list
     * AJAX
     *
     * @param int $producer_id
     */
    public function getProducers($sub_categ_id = 0) {
        Header::ContentType("text/plain");

        $producers = ModelProducts::getProducersFromSelect(0, $sub_categ_id);

        echo json_encode($producers);
    }

    /**
     * Get products list
     * AJAX
     *
     * @param int $producer_id
     */
    public function getProducts($producer_id = 0) {
        Header::ContentType("text/plain");

        $products = ModelProducts::getProductsFromSelect($producer_id);

        echo json_encode($products);
    }

    /**
     * Upload and resize images
     * AJAX
     *
     */
    public function uploadImage() {
        Header::ContentType("text/plain");

        $img = ModelProducts::uploadProductImage();

        echo json_encode($img);
    }

    public function deleteImage($image_id) {
        Header::ContentType("text/plain");

        echo json_encode(array(
            'success'	=> ModelProducts::deleteProductImage($image_id)
        ));
    }

    public function getSubCategsSubscribe() {
        Header::ContentType("text/plain");

        if(is_array(Request::post('categs'))) {
            echo json_encode(
                ModelProducts::getCategoriesFromSelect(Request::post('categs'))
            );
        }
    }
	
	public function  remove(){
		if(User::isAdmin()){
			ModelProducts::removeProduct();	
		}else{
			Header::Location("/404");
		}
	}
	
	public function calls($user_id){

		 if( ModelProducts::callsAdd($user_id)){ 
			echo json_encode( array('call'=>1)) ;
		 }else{
			 echo json_encode(array('error'=>0));
		 }
	}
}