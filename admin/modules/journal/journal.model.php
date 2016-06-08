<?php

class ModelJournal {
	
	public function getJournalsList() {
		$query = "SELECT * FROM `journals` ORDER BY year DESC, num DESC";
		
		return DB::getAssocArray($query);
	}
	
	public function getJournalData($journal_id) {
		$journal 	= "SELECT * FROM `journals` WHERE journal_id = $journal_id";
		$pages		= "SELECT * FROM `journals_pages` WHERE journal_id = $journal_id ORDER BY page";	
		
		return array(
			'journal'	=> DB::getAssocArray($journal, 1),
			'pages'		=> DB::getAssocArray($pages)
		);
	}
	
	public function add($num, $year, $images, $titles) {
		$dir_name = UPLOADS . '/journals/' . $year . '-' . $num;
		
		DB::insert('journals', array(
			'num'		=> $num,
			'year'		=> $year,
			'date_add'	=> DB::now()
		));
		
		$journal_id = DB::lastInsertId();
		
		if ($journal_id > 0) {
			for ($i = 0, $c = count($images); $i < $c; $i++) {
				DB::update('journals_pages', array(
					'journal_id'	=> $journal_id,
					'title'			=> $titles[$images[$i]]
				), array(
					'page_id'		=> $images[$i]
				));
			}
			
			rename(UPLOADS . '/journals/tmp', $dir_name);
			
			mkdir(UPLOADS . '/journals/tmp', 0777);
			chmod(UPLOADS . '/journals/tmp', 0777);
		}
		
		return $journal_id;
	}
	
	public function edit($journal_id, $num, $year, $images, $titles) {
		DB::update('journals', array(
			'num'		=> $num,
			'year'		=> $year,
		), array(
			'journal_id'	=> $journal_id
		));
		
		for ($i = 0, $c = count($images); $i < $c; $i++) {
			DB::update('journals_pages', array(
				'title'			=> $titles[$images[$i]]
			), array(
				'page_id'		=> $images[$i]
			));
		}
		
		return true;
	}
	
	public function delete($journal_id) {
		$dir = "SELECT CONCAT(year, '-', num) FROM `journals` WHERE journal_id = $journal_id";
		
		DB::delete('journals', array(
			'journal_id'	=> $journal_id
		));
		
		DB::delete('journal_pages', array(
			'journal_id'	=> $journal_id
		));
		
		if (is_dir(UPLOADS . '/journals/' . $dir)) {
			unlink(UPLOADS . '/journals/' . $dir);
		}
	}
	
	public function uploadImage($image) {
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = str_replace('.jpg', '', $image['name']);
		
		$img = AcImage::createImage($image['tmp_name']);
		$img->setRewrite(true);
		$img->saveAsJPG(UPLOADS . '/journals/tmp/' . $image_name . '.jpg');
		
		
		$img = AcImage::createImage($image['tmp_name']);
		$img->resizeByWidth(100);
		$img->saveAsJPG(UPLOADS . '/journals/tmp/' . $image_name . '-thumb.jpg');
		
		$write = array(
			'image'	=> $image_name . '.jpg',
			'page'	=> $image_name
		);
		
		DB::insert('journals_pages', $write);
		
		$image_id = DB::lastInsertId();
		
		$result = array(
			'uploadName' 	=> '/uploads/journals/tmp/' . $image_name . '-thumb.jpg',
			'success'		=> true,
			'image_id'		=> $image_id
		);
		
		return $result;	
	}
}