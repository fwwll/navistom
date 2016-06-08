<?php

class ModelWork {
	
	public function getWorkList($type = 1) {
		$work = "SELECT work_id, user_id, user_name, name, flag, flag_moder, flag_vip_add, date_add, date_edit 
			FROM `work` WHERE type = $type AND flag_delete = 0
			ORDER BY date_add DESC";
		
		return DB::getAssocArray($work);
	}
	
	public function getCategoriesList() {
		$categs = "SELECT categ_id, name, date_add, date_edit FROM `categories_work` ORDER BY sort_id";
		
		return DB::getAssocArray($categs);
	}
	
	public function getCategoryData($categ_id) {
		$categ = "SELECT name, title, meta_title, meta_description, meta_keys,
			title_vacancy, meta_title_vacancy, meta_description_vacancy, meta_keys_vacancy
			FROM `categories_work` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($categ, 1);
	}
	
	public function categoryAdd($name, $title, $meat_title = null, $meta_descr = null, $meta_keys = null) {
		$write = array(
			'name'				=> $name,
			'title'				=> $title,
			'meta_title'		=> $meat_title,
			'meta_description'	=> $meta_descr,
			'meta_keys'			=> $meta_keys,
			'date_add'			=> DB::now()
		);
		
		DB::insert('categories_work', $write);
		
		return DB::lastInsertId();
	}
	
	public function categoryEdit($categ_id, $name, $title, $meat_title = null, $meta_descr = null, $meta_keys = null, $title_vacancy, $meat_title_vacancy = null, $meta_descr_vacancy = null, $meta_keys_vacancy = null) {
		$write = array(
			'name'						=> $name,
			'title'						=> $title,
			'meta_title'				=> $meat_title,
			'meta_description'			=> $meta_descr,
			'meta_keys'					=> $meta_keys,
			
			'title_vacancy'				=> $title_vacancy,
			'meta_title_vacancy'		=> $meat_title_vacancy,
			'meta_description_vacancy'	=> $meta_descr_vacancy,
			'meta_keys_vacancy'			=> $meta_keys_vacancy
		);
		
		DB::update('categories_work', $write, array('categ_id' => $categ_id));
		
		return true;
	}
	
	public function categoryDelete($categ_id) {
		DB::delete('categories_work', array(
			'categ_id' => $categ_id
		));
		
		return true;
	}
	
	public function categoriesSorted($sort) {
		$query = "UPDATE `categories_work` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['categ']); $i < $c; $i++) { 
			$query .= " WHEN categ_id = " . (int)$sort['categ'][$i] . " THEN $i ";
		}
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
	}
}