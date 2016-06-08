<?php
//error_reporting(E_ALL);
class Products {
	public function index($filter = null) {
		echo Registry::get('twig')->render('products.tpl', array(
			'title' 	=> $filter == 'stocks' ? 'Товары с акцией' : 'Новые товары',
			'products'	=> ModelProducts::getProducts($filter)
		));
	}
	
	public function indexAjax() {
		
	}
	
	public function add() {
		
	}
	
	public function delete($product_new_id) {
		ModelProducts::deleteProductNew($product_new_id);
		
		Header::Location('/admin/products');
	}
	
	public function edit($product_new_id) {
		$product	= ModelProducts::getProductData($product_new_id);
		$categs 	= ModelProducts::getCategoriesFromSelect();
		$sub_categs	= ModelProducts::getCategoriesFromSelect($product['categ_id']);
		$producers	= ModelProducts::getProducersFromSelect();
		 
		 $arr_ob=[];
		 foreach($producers as $p){
			$arr_ob[$p['producer_id']]=$p["name"] ;
		 }
		 
		$products	= ModelProducts::getProductsFromSelect($product['producer_id']);
		
		$images		= ModelProducts::getProductNewImages($product_new_id);
		
		$form = new Form();
		
		$form->createTab('p-default', 'Основная информация');
		$form->createTab('p-additionally', 'Дополнительно');
		$form->createTab('p-media', 'Фото / видео');
		$form->createTab('p-stock', 'Акция');
		
		$form->create('select', 'categ_id', 'Рубрика', $categs, 'p-default');
		$form->create('select', 'sub_categ_id', 'Раздел', $sub_categs, 'p-default');
		$form->create('select', 'producer_id', 'Производитель', $arr_ob, 'p-default');
		$form->create('select', 'product_id', 'Товар', $products, 'p-default');
		
		$form->create('text', 'price', 'Стоимость', null, 'p-default');
		$form->create('select', 'currency_id', 'Валюта', array(1 => 'Гривен'), 'p-default');
		$form->create('text', 'price_description', 'Описание цены', null, 'p-default');
		
		//$form->create('select', 'country_id', 'Страна', ModelUsers::getCountriesListFromSelect(), 'p-default');
		
		$form->create('switch', 'flag', 'Товар доступен к просмотру', 1, 'p-default');
		$form->create('switch', 'flag_moder', 'Товар одобрен модератором', 1, 'p-default');
		
		$form->create('text', 'name', 'Заголовок', null, 'p-additionally');
		$form->create('textarea', 'content', 'Описание товара', null, 'p-additionally');
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'p-media');
		$form->create('uploader', 'images', 'Фотографии', null, 'p-media');
		
		$form->create('text', 'stock_price', 'Акционная цена', null, 'p-stock');
		$form->create('select', 'stock_currency_id', 'Валюта', array(1 => 'Гривен'), 'p-stock');
		$form->create('text', 'stock_price_description', 'Описание цены', null, 'p-stock');
		$form->create('textarea', 'stock_content', 'Описание акции', null, 'p-stock');
		$form->create('daterange', 'stock_date_range', 'Период действия акции', null, 'p-stock');
		
		$form->create('switch', 'stock_flag', 'Акция доступна к просмотру', 1, 'p-stock');
		$form->create('switch', 'stock_flag_moder', 'Акция одобрена модератором', 1, 'p-stock');
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			$product['images'][$images[$i]['image_id']] = $images[$i]['url_full'];
			$form->create('hidden', 'image_description[' . $images[$i]['image_id'] .']', '', $images[$i]['description']);
			$form->attr('image_description[' . $images[$i]['image_id'] . ']', 'id', 'descr_' . $images[$i]['image_id']);
		}
		
		$form->setValues($product);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelProducts::editProductNew($product_new_id, array(
					'product_id'		=> Request::post('product_id', 'int'),
					'producer_id'		=> Request::post('producer_id', 'int'),
					'categ_id'			=> Request::post('categ_id', 'int'),
					'sub_categ_id'		=> Request::post('sub_categ_id', 'int'),
					'currency_id'		=> Request::post('currency_id', 'int'),
					'price'				=> Request::post('price', 'float'),
					'price_description'	=> Request::post('price_description', 'string'),
					'content'			=> Request::post('content', 'string'),
					'country_id'		=> Request::post('country_id', 'int'),
					'link'				=> Request::post('link', 'url'),
					'video_link'		=> Request::post('video_link', 'url'),
					'flag'				=> Request::post('flag', 'int'),
					'flag_moder'		=> Request::post('flag_moder', 'int')
				), Request::post('images'), Request::post('image_description'), array(
					'currency_id'		=> Request::post('stock_currency_id', 'int'),
					'price'				=> Request::post('stock_price', 'float'),
					'price_description'	=> Request::post('stock_price_description', 'string'),
					'content'			=> Request::post('stock_content', 'string'),
					'date_start'		=> Request::post('start_stock_date_range', 'string'),
					'date_end'			=> Request::post('end_stock_date_range', 'string'),
					'flag'				=> Request::post('stock_flag', 'int'),
					'flag_moder'		=> Request::post('stock_flag_moder', 'int')
				));
			}
			
			$form->destroy(
				'/admin/products', 
				'/admin/product/edit-'.$product_new_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать товар',
			'Редактировать товар'
		);
	}
	
	public function categories() {
        $categories = ModelProducts::getCategoriesList();

        $parentCategories   = array();
        $childCategories    = array();

        for($i = 0, $c = count($categories); $i < $c; $i++) {
            if ($categories[$i]['parent_id'] == 0) {
                $parentCategories[] = $categories[$i];
            } else {
                $childCategories[$categories[$i]['parent_id']][] = $categories[$i];
            }
        }

		echo Registry::get('twig')->render('categories.tpl', array(
			'title' 		    => 'Все категории',
			'parentCategories'	=> $parentCategories,
            'childCategories'   => $childCategories
		));
	}
	
	public function addCateg() {
		$categs = ModelProducts::getCategoriesFromSelect();
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название рубрики');
		$form->create('select', 'parent_id', 'Родительская рубрика', array(0 => 'Нету') + $categs);
		
		$form->create('switch', 'flag_no_ads', 'Не отображать в Б/У', 1);
		$form->create('switch', 'flag_no_products', 'Не отображать в новом', 1);
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				$categ_id = ModelProducts::addCategory(
					Request::post('name', 'string'),
					Request::post('parent_id', 'int'),
					Request::post('title', 'string'),
					Request::post('meta_title', 'string'),
					Request::post('meta_description', 'string'),
					Request::post('meta_keys', 'string'),
					Request::post('flag_no_ads', 'int'),
					Request::post('flag_no_products', 'int')
				);
			}
			
			$form->destroy(
				'/admin/products/categories', 
				'/admin/products/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить рубрику',
			'Добавить рубрику'
		);
	}
	
	public function editCateg($categ_id) {
		$categs = ModelProducts::getCategoriesFromSelect();
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название');
		$form->create('select', 'parent_id', 'Рубрика', array(0 => 'Нету') + $categs);
		
		$form->create('switch', 'flag_no_ads', 'Отображать в Б/У', 1);
		$form->create('switch', 'flag_no_products', 'Не отображать в новом', 1);
		
		$form->create('text', 'title', 'Заголовок H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues(
			ModelProducts::getCategory($categ_id)
		);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				$write = array(
					'name'				=> Request::post('name', 'string'),
					'parent_id'			=> Request::post('parent_id', 'int'),
					'title'				=> Request::post('title', 'string'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string'),
					'flag_no_ads'		=> Request::post('flag_no_ads', 'int'),
					'flag_no_products'	=> Request::post('flag_no_products', 'int')
				);
				
				ModelProducts::editCategory($categ_id, $write);
			}
			
			$form->destroy(
				'/admin/products/categories', 
				'/admin/products/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать',
			'Редактировать'
		);
	}
	
	public function deleteCateg($categ_id) {
		ModelProducts::deleteCateg($categ_id);
		
		Header::Location('/admin/products/categories');
	}
	
	
	public function producers() {
		echo Registry::get('twig')->render('producers.tpl', array(
			'title' 	=> 'Все производители',
			'producers'	=> ModelProducts::getProducersList()
		));
	}
	
	public function addProducer() {
		$form = new Form();
		
		$form->create('text', 'name', 'Название производителя');
		$form->create('switch', 'flag_moder', 'Опубликовано', 1);
		
		$form->required('name');
		
		$form->setValues(array(
			'flag_moder'	=> 1
		));
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				$producer_id = ModelProducts::addProducer(
					Request::post('name', 'string'),
					'',
					0,
					Request::post('flag_moder', 'int')
				);
			}
			
			$form->destroy(
				'/admin/products/producers', 
				'/admin/products/producer/edit-'.$producer_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить производителя',
			'Добавить производителя'
		);
	}
	
	public function editProducer($producer_id) {
		$data = ModelProducts::getProducer($producer_id);
		
		if ($data['flag_moder'] == 0) {
			$user_id = ModelProducts::getProducerUser($producer_id);
			
			if ($user_id > 0) {
				
				$user = Registry::get('twig')->render('user-info.tpl', array(
					'data'	=> User::getFullUserInfo($user_id)
				));
			}
		}
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название производителя');
		$form->create('switch', 'flag_moder', 'Опубликовано', 1);
		
		$form->required('name');
		
		$form->setValues($data);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				$write = array(
					'name'			=> Request::post('name', 'string'),
					'description'	=> '',
					'sort_id'		=> 0,
					'flag_moder'	=> Request::post('flag_moder', 'int')
				);
				
				ModelProducts::editProducer($producer_id, $write);
			}
			
			$form->destroy(
				'/admin/products/producers', 
				'/admin/products/producer/edit-'.$producer_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Изменить производителя',
			'Изменить производителя'
		);
	}
	
	public function deleteProducer($producer_id) {
		ModelProducts::deleteProducer($producer_id);
		
		Header::Location('/admin/products/producers');
	}
	
	
	public function producersProducts() {
		echo Registry::get('twig')->render('producers-products.tpl', array(
			'title' 				=> 'Все товары производителей',
			'producers_products'	=> ModelProducts::getProducersProductsList()
		));
	}
	
	public function producersProductsAdd() {
		$producers = ModelProducts::getProducersFromSelect();
		
		for ($i = 0, $c = count($producers); $i < $c; $i++) {
			if ($producers[$i]['flag_moder'] == 0) {
				$producers_list[$producers[$i]['producer_id']] = '<span class="moder">' . $producers[$i]['name'] . '</span>';
			}
			else {
				$producers_list[$producers[$i]['producer_id']] = $producers[$i]['name'];
			}
		}
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название товара');
		$form->create('textarea', 'description', 'Назначение');
		
		$form->create('select', 'producer_id', 'Производитель', $producers_list);
		
		$form->create('switch', 'flag_moder', 'Опубликовано', 1);
		
		$form->required('name', 'description');
		
		$form->setValues(array(
			'flag_moder'	=> 1
		));
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				$product_id = ModelProducts::addProducerProduct(
					Request::post('producer_id', 'int'),
					Request::post('name', 'string'),
					Request::post('description', 'string'),
					Request::post('flag_moder', 'int')
				);
			}
			
			$form->destroy(
				'/admin/products/producers_products', 
				'/admin/products/producers_product/edit-'.$product_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить товар производителя',
			'Добавить товар производителя'
		);
	}
	
	public function reestablish($product_new_id) {
		DB::update('products_new', array(
			'flag_delete'		=> 0
		), array(
			'product_new_id'	=> $product_new_id
		));
		
		Header::Location('/admin/products/filter-removed');
	}
	
	public function producersProductsEdit($product_id) {
		$producers 	= ModelProducts::getProducersFromSelect();
		$data		= ModelProducts::getProducerProduct($product_id);
		
		$form 		= new Form();
		
		if ($data['flag_moder'] == 0) {
			$user_id = ModelProducts::getProductUser($product_id);
			
			if ($user_id > 0) {
				$form->create('a', 'user_info', 'ajax-link', 'Информация о пользователе, который добавил товар');
				$form->attr('user_info', 'href', 'user/profile-' . $user_id);
			}
		}
		
		for ($i = 0, $c = count($producers); $i < $c; $i++) {
			if ($producers[$i]['flag_moder'] == 0) {
				$producers_list[$producers[$i]['producer_id']] = '<span class="moder">' . $producers[$i]['name'] . '</span>';
			}
			else {
				$producers_list[$producers[$i]['producer_id']] = $producers[$i]['name'];
			}
		}

		$form->create('text', 'name', 'Название товара');
		
		$form->create('select', 'product_replace_id', 'Существующие товары производителя', array( 0 => 'Выберите из списка...') + ModelProducts::getProductsFromSelect($data['producer_id'], 1));
		
		$form->create('textarea', 'description', 'Назначение');
		
		$form->create('select', 'producer_id', 'Производитель', $producers_list);
		
		$form->create('switch', 'flag_moder', 'Опубликовано', 1);
		
		$form->required('name', 'description');
		
		$form->setValues($data);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				$write = array(
					'producer_id'	=> Request::post('producer_id', 'int'),
					'name'			=> Request::post('name', 'string'),
					'description'	=> Request::post('description', 'string'),
					'flag_moder'	=> Request::post('flag_moder', 'int')
				);
				
				ModelProducts::editProducerProduct($product_id, $write);
				
				DB::update('products_new', array(
					'product_name'	=> DB::getColumn("SELECT CONCAT((SELECT name FROM `producers` WHERE producer_id = products.producer_id), ' ', name) FROM `products` WHERE product_id = $product_id")
				), array(
					'product_id'	=> $product_id
				));
				
				DB::update('ads', array(
					'product_name'	=> DB::getColumn("SELECT CONCAT((SELECT name FROM `producers` WHERE producer_id = products.producer_id), ' ', name) FROM `products` WHERE product_id = $product_id")
				), array(
					'product_id'	=> $product_id
				));
			}
			
			$form->destroy(
				'/admin/products/producers_products', 
				'/admin/products/producers_product/edit-'.$product_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить товар производителя',
			'Добавить товар производителя'
		);
	}
	
	public function producersProductsDelete($product_id) {
		ModelProducts::deleteProducerProduct($product_id);
		
		Header::Location('/admin/products/producers_products');
	}
	
	public function deleteByUser($user_id) {
		if ($user_id > 0) {
			DB::update('products_new', array(
				'flag_delete'	=> 1
			), array(
				'user_id'	=> $user_id
			));
			
			Header::Location('/admin/users');
		}
	}
	
	public function reorderByUser($user_id) {
		if ($user_id > 0) {
			DB::update('products_new', array(
				'flag_delete'	=> 0
			), array(
				'user_id'	=> $user_id
			));
			
			Header::Location('/admin/users');
		}
	}
	
	public function uploadImage() {
		header("Content-Type: text/plain");
		
		$result = ModelProducts::uploadImages();
		
		echo json_encode($result);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		if (ModelProducts::deleteImage($image_id)) {
			$result = array(
				'success' => true
			);
		}
		else {
			$result = array(
				'success' => false
			);
		}
		
		echo json_encode($result);
	}

    public function sortedCategories() {
        parse_str($_GET['data'], $sort);
        ModelProducts::saveCategoriesPositions($sort);

        return true;
    }
}