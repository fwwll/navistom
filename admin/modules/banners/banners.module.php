<?php

class Banners {

    public function index() {

        echo Registry::get('twig')->render('banners.tpl', array(
            'title' 			=> 'Баннера сайта',
            'banners'			=> ModelBanners::getBannersList(),
            'banners_default'	=> ModelBanners::getBannersList(1),
            'no_active'			=> ModelBanners::getBannersList(0, 1)
        ));
    }

    public function add() {
        $form = new Form();

        $form->create('text', 'name', 'Название баннера (системное)');

        $form->create('text', 'company', 'Рекламодатель');

        $form->create('text', 'link', 'Ссылка');
        $form->create('radiobuttons', 'target', 'Открывать ссылку в', array('_self' => 'Текущем окне', '_blank' => 'Новом окне'));

        $form->create('select', 'type', 'Место размещения', array(
            1 => 'Правая колонка сайта (ширина - 250px)',
            2 => 'Листинг сайта (ширина - 520px)',
            3 => 'Страница объявления (ширина - 700px)',
            4 => 'Топ сайта (ширина - 800px)',
            5 => 'Фон сайта (ширина 1650px)'
        ));

        $form->create('file', 'image', 'Изображение');

        $form->create('textarea', 'code', 'Код AdSense:');

        $form->create('spinner', 'percent', 'Процент показов баннера');

        $form->create('daterange', 'date_range', 'Период действия баннера', null);

        $form->create('select', 'country_id', 'Страна', array( 1 => 'Украина', 0 => 'Остальные страны' ));

        $form->create('switch', 'flag', 'Баннер доступен к просмотру', 1);

        $form->create('switch', 'flag_default', 'Установить как баннер по умолчанию', 1);

        $form->setValues(array(
            'percent' 	=> 100,
            'flag'		=> 1,
            'target'	=> '_self'
        ));

        $form->required('name', 'percent', 'type');

        if ($send = $form->isSend()) {
            if ($form->checkForm()) {

                if ($_FILES['image']['name'] != null) {
                    $image = ModelBanners::uploadImage($_FILES['image'], Request::post('type', 'int'));
                }
                else {
                    $image = '';
                }

                if (Request::post('code') == null) {
                    $code = '';
                }
                else {
                    $code = Request::post('code');
                }

                $banner_id = ModelBanners::addBanner(array(
                    'name'			=> Request::post('name', 'string'),
                    'company'		=> Request::post('company', 'string'),
                    'image'			=> $image,
                    'code'			=> $code,
                    'link'			=> Request::post('link', 'string'),
                    'target'		=> Request::post('target', 'string'),
                    'type'			=> Request::post('type', 'int'),
                    'country_id'	=> Request::post('country_id', 'int'),
                    'percent'		=> Request::post('percent', 'int'),
                    'date_start'	=> Request::post('start_date_range', 'string'),
                    'date_end'		=> Request::post('end_date_range', 'string'),
                    'flag'			=> Request::post('flag', 'int'),
                    'flag_default'	=> Request::post('flag_default', 'int')
                ));
            }

            $form->destroy(
                '/admin/banners',
                '/admin/banner/edit-'.$banner_id
            );
        }

        echo Admin::displayFormTPL(
            $form->display(),
            'Добавить баннер',
            'Добавить баннер'
        );
    }

    public function edit($banner_id) {
        $data = ModelBanners::getBannerData($banner_id);

        $data['start_date_range'] 	= $data['date_start'];
        $data['end_date_range'] 	= $data['date_end'];

        $form = new Form();

        $form->create('text', 'name', 'Название баннера (системное)');
        $form->create('text', 'company', 'Рекламодатель');

        $form->create('text', 'link', 'Ссылка');
        $form->create('radiobuttons', 'target', 'Открывать ссылку в', array('_self' => 'Текущем окне', '_blank' => 'Новом окне'));

        $form->create('select', 'type', 'Место размещения', array(
            1 => 'Правая колонка сайта (ширина - 250px)',
            2 => 'Листинг сайта (ширина - 520px)',
            3 => 'Страница объявления (ширина - 700px)',
            4 => 'Топ сайта (ширина - 800px)',
            5 => 'Фон сайта (ширина 1650px)'
        ));

        $form->create('file', 'image', 'Изображение');

        $form->create('textarea', 'code', 'Код AdSense:');

        $form->create('spinner', 'percent', 'Процент показов баннера');

        $form->create('daterange', 'date_range', 'Период действия баннера', null);

        $form->create('select', 'country_id', 'Страна', array( 1 => 'Украина', 0 => 'Остальные страны' ));

        $form->create('switch', 'flag', 'Баннер доступен к просмотру', 1);

        $form->create('switch', 'flag_default', 'Установить как баннер по умолчанию', 1);

        $form->setValues($data);

        $form->required('name', 'percent');

        if ($send = $form->isSend()) {
            if ($form->checkForm()) {

                if ($_FILES['image']['name'] != null) {
                    $image = ModelBanners::uploadImage($_FILES['image'], Request::post('type', 'int'));
                }
                else {
                    $image = $data['image_original'];
                }

                ModelBanners::editBanner($banner_id, array(
                    'name'			=> Request::post('name', 'string'),
                    'company'		=> Request::post('company', 'string'),
                    'image'			=> $image,
                    'code'			=> Request::post('code'),
                    'link'			=> Request::post('link', 'string'),
                    'target'		=> Request::post('target', 'string'),
                    'type'			=> Request::post('type', 'int'),
                    'country_id'	=> Request::post('country_id', 'int'),
                    'percent'		=> Request::post('percent', 'int'),
                    'date_start'	=> Request::post('start_date_range', 'string'),
                    'date_end'		=> Request::post('end_date_range', 'string'),
                    'flag'			=> Request::post('flag', 'int'),
                    'flag_default'	=> Request::post('flag_default', 'int')
                ));
            }

            $form->destroy(
                '/admin/banners',
                '/admin/banner/edit-'.$banner_id
            );
        }

        echo Admin::displayFormTPL(
            $form->display(),
            'Редактировать баннер',
            'Редактировать баннер'
        );
    }

    public function delete($banner_id) {
        $data = ModelBanners::getBannerData($banner_id);

		 if(is_file($data['image'])){
                 
			 if (unlink($data['image'])) {
                  ModelBanners::deleteBanner($banner_id);
			 }  
			 
		 }else{
			  
			ModelBanners::deleteBanner($banner_id); 
		 }
        Header::Location($_SERVER['HTTP_REFERER']);
    }


    /**
     * Top providers methods
     */

    public function topProviders() {
        echo Registry::get('twig')->render('top-providers.tpl', array(
            'title'     => 'Топ - Поставщики',
            'providers' => ModelBanners::getProvidersList()
        ));
    }

    public function providerAdd($providerId = 0) {
        $form = new Form();

        $form->create('text', 'name', 'Название поставщика');

        $form->create('text', 'link', 'Ссылка');
        $form->create('radiobuttons', 'target', 'Открывать ссылку в', array('_self' => 'Текущем окне', '_blank' => 'Новом окне'));
        $form->create('spinner', 'position', 'Позиция в списке');
        $form->create('file', 'image', 'Изображение (точный размер 144х108px, авторесайз)');
        $form->create('daterange', 'date_range', 'Период отображения', null);
        $form->create('switch', 'flag', 'Доступен к просмотру', 1);

        if ($providerId > 0) {
            $data  = ModelBanners::getProviderData($providerId);
            $data['old_image'] = $data['image'];
            $data['start_date_range'] = $data['date_start'];
            $data['end_date_range'] = $data['date_end'];
            $data['image'] = 'uploads/providers/' . $data['image'];

            $form->setValues($data);
        }
        else {
            $form->setValues(array(
                'position' 	=> 1,
                'flag'		=> 1,
                'target'	=> '_self'
            ));
        }

        $form->required('name', 'link');

        if ($send = $form->isSend()) {
            if ($form->checkForm()) {
                if (Request::file('image')) {
                    $image = ModelBanners::uploadProviderImage('image');
                    $providerId && @unlink(implode(DIRECTORY_SEPARATOR, array(
                        UPLOADS,
                        'providers',
                        $data['old_image']
                    )));
                }
                else {
                    $image = $data['old_image'];
                }

                $providerId = ModelBanners::addProvider(
                    $providerId,
                    Request::post('name', 'string'),
                    Request::post('link', 'url'),
                    Request::post('target', 'string'),
                    Request::post('position', 'int'),
                    $image,
                    Request::post('start_date_range', 'string'),
                    Request::post('end_date_range', 'string'),
                    Request::post('flag', 'int')
                );
            }

            $form->destroy(
                '/admin/top-providers',
                '/admin/top-provider/edit-' . $providerId
            );
        }

        echo Admin::displayFormTPL(
            $form->display(),
            $providerId ? 'Редактировать топ-поставщика' : 'Добавить топ-поставщика'
        );
    }

    public function providerDelete($providerId) {
        $providerId > 0 && ModelBanners::deleteProvider($providerId);
        Header::Location();
    }

    public function providerSorted() {
        parse_str(Request::get('data'), $sort);
        ModelBanners::sortProviders($sort);
    }
}