<?php

class ModelBanners {

    public function getBannersList($flag_default = 0, $flag_no_active = 0) {

        if ($flag_no_active > 0) {
            $where = "AND (flag = 0 OR DATEDIFF(date_end, '". DB::now(1) ."') < -1)";
        }
        else {
            $where = "AND flag = 1 AND DATEDIFF(date_end, '". DB::now(1) ."') > -1";
        }

        if ($flag_default > 0) {
            $where = "";
        }

        $query = "SELECT country_id, banner_id, name, company, percent, date_start, date_end, views, clicks, date_add, flag,
			DATEDIFF(date_end, '". DB::now(1) ."') AS days
			FROM `banners`
			WHERE flag_default = $flag_default $where  
			ORDER BY percent DESC";
			

        return DB::getAssocGroup($query);
    }

    public function addBanner($data = array()) {
        DB::insert('banners', array(
            'name'			=> $data['name'],
            'image'			=> $data['image'],
            'code'			=> $data['code'],
            'link'			=> $data['link'],
            'target'		=> $data['target'],
            'percent'		=> $data['percent'],
            'date_start'	=> $data['date_start'],
            'date_end'		=> $data['date_end'],
            'date_add'		=> DB::now(),
            'flag'			=> $data['flag'],
            'flag_default'	=> $data['flag_default'],
            'country_id'	=> $data['country_id'],
            'type'			=> $data['type'],
            'company'		=> $data['company']
        ));

        return DB::lastInsertId();
    }

    public function editBanner($banner_id, $data) {
        DB::update('banners', array(
            'name'			=> $data['name'],
            'image'			=> $data['image'],
            'code'			=> $data['code'],
            'link'			=> $data['link'],
            'target'		=> $data['target'],
            'percent'		=> $data['percent'],
            'date_start'	=> $data['date_start'],
            'date_end'		=> $data['date_end'],
            'flag'			=> $data['flag'],
            'flag_default'	=> $data['flag_default'],
            'country_id'	=> $data['country_id'],
            'type'			=> $data['type'],
            'company'		=> $data['company']
        ), array(
            'banner_id' 	=> $banner_id
        ));

        return true;
    }

    public function getBannerData($banner_id) {
        $query = "SELECT *, image AS image_original, CONCAT('../uploads/banners/', image) AS image FROM `banners` WHERE banner_id = $banner_id";

        return DB::getAssocArray($query, 1);
    }

    public function deleteBanner($banner_id) {
        DB::delete('banners', array('banner_id' => $banner_id));

        return true;
    }

    public function uploadImage($file, $type = 1) {
        $image_name = Str::get()->generate(20);

        require_once(LIBS . 'AcImage/AcImage.php');

        $img = AcImage::createImage($file['tmp_name']);

        switch ($type) {
            case 1:
                $img->resizeByWidth(250);
                break;
            case 2:
                $img->resizeByWidth(800);
                break;
            case 3:
                $img->resizeByWidth(700);
                break;
            case 4:
                $img->resizeByWidth(800);
                break;
            case 5:
                /* $img->resizeByWidth(1650); */
				$img->resizeByWidth(1920);
                break;
        }

        $img->saveAsJPG( join(DIRECTORY_SEPARATOR, array(UPLOADS, 'banners', $image_name . '.jpg')));

        return $image_name . '.jpg';
    }

    public function uploadProviderImage($name) {
        include_once(CLASSES . 'img.class.php');

        $imageName = Str::get()->generate(20);
        $img = new img();

        if ($image = $img->uploadImg($name, join(DIRECTORY_SEPARATOR, array(UPLOADS, 'providers')), $imageName)) {
            $img->resize($image, 144, 108, null, 0xffffff);

            return join('.', array($imageName, end(explode('.', $image))));
        }

        return false;
    }

    public function getProvidersList() {
        $query = '
            SELECT
              provider_id,
              name,
              flag,
              DATEDIFF(date_end, NOW()) AS days,
              (SELECT COUNT(*) FROM top_provider_transitions WHERE provider_id = top_providers.provider_id) AS transitions
            FROM
              `top_providers`
            WHERE 1
            ORDER BY position';

        return DB::getAssocArray($query);
    }

    public function getProviderData($providerId) {
        return DB::select('*')->from('top_providers')->where(array('provider_id' => $providerId))->getAssoc(1);
    }

    public function addProvider($providerId = 0, $name, $link, $target = null, $position = 1, $image, $dateStart, $dateEnd, $flag) {
        $data = array(
            'name' => $name,
            'link' => $link,
            'target' => $target,
            'position' => $position,
            'image' => $image,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'flag' => $flag
        );

        if ($providerId > 0) {
            DB::update('top_providers', $data, array(
                'provider_id' => $providerId
            ));

            return $providerId;
        }
        else {
            DB::insert('top_providers', array_merge($data, array(
                'date_add' => DB::now()
            )));

            return DB::lastInsertId();
        }
    }

    public function sortProviders($sort) {
        DB::sorted('top_providers', $sort, 'position');
    }

    public function deleteProvider($providerId) {
        @unlink(join(DIRECTORY_SEPARATOR, array(
            UPLOADS,
            'providers',
            DB::select('image')->from('top_providers')->where(array( 'provider_id' => $providerId ))->getColumn()
        )));

        DB::delete('top_providers', array(
            'provider_id' => $providerId
        ));

        DB::delete('top_provider_transitions', array(
            'provider_id' => $providerId
        ));
    }
}