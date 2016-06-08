<?php

class ModelArticles {
	public function getCommentsList() {
		$comments = "SELECT c.comment_id, c.comment, c.date_add, c.article_id, a.name,
			IF(c.user_id > 0, (SELECT name FROM `users_info` WHERE user_id = c.user_id), user_name) AS user_name
			FROM `articles_comments` AS c
			INNER JOIN `articles` AS a USING(article_id)
			ORDER BY c.date_add DESC";
		
		$comments = DB::DBObject()->query($comments);
		$comments->execute();
		
		$regExp 	= "/\[quot(\d+)\](\n*\t*\s*.*\n*\t*\s*)\[\/quot(\d+)\]/u";
		$replace	= "<div class=\"response\">$2</div>";

		while ($array = $comments->fetch(PDO::FETCH_ASSOC)) {
			
			$result[] = array(
				'comment'		=> preg_replace($regExp, $replace, $array['comment']),
				'comment_id'	=> $array['comment_id'],
				'date_add'		=> $array['date_add'],
				'article_id'	=> $array['article_id'],
				'name'			=> $array['name'],
				'user_name'		=> $array['user_name']
			);
		}
		
		return $result;
	}
	
	public function getComment($comment_id) {
		$comment = "SELECT comment FROM `articles_comments` WHERE comment_id = $comment_id";
		
		return DB::getAssocArray($comment, 1);
	}
	
	public function updateComment($comment_id, $comment,$field='comment'  ) {
		DB::update('articles_comments', 
			array(
				$field => $comment
			),
			array(
				'comment_id' => $comment_id
		));
		
		return true;
	}
	
	public function deleteComment($comment_id){
		DB::delete('articles_comments', array('comment_id' => $comment_id));
	}
	
	public function getCount($days = null) {
		if ($days != '') {
			$where = 'AND date_public >= NOW() - INTERVAL ' . $days .' DAY';
		}
		
		$query = "SELECT COUNT(*) FROM `articles` 
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 $where";
		
		return DB::getColumn($query);
	}
	
	public function getAllViews() {
		
	}
	
	public function getPopular($count) {
		$query = "SELECT article_id, name, views, views / DATEDIFF(NOW(), date_public) AS q, DATEDIFF(NOW(), date_public) AS period
			FROM articles 
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0
			ORDER BY q DESC 
			LIMIT $count";
		
		return DB::getAssocArray($query);
	}
	
	public function getCalendarData() {
		$articles = "SELECT article_id, name, date_public FROM `articles` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND date_public != '0000-00-00'";
		
		$articles = DB::DBObject()->query($articles);
		$articles->execute();
	
		while ($array = $articles->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'id'	=> $array['article_id'],
				'title'	=> $array['name'],
				'start'	=> $array['date_public'],
				'url'	=> '/articles/#!/article/edit-' . $array['article_id'],
				'allDay'=> false
			);
		}
		
		return $result;
	}
	
	public function editArticle($article_id, $data, $categs, $images, $tags, $images_descr) {
		if ($article_id > 0 and is_array($data)) {
			DB::update('articles', array(
				'name'				=> $data['name'],
				'author'			=> $data['author'],
				'source_name'		=> $data['source_name'],
				'source_link'		=> $data['source_link'],
				'content_min'		=> $data['content_min'],
				'content'			=> $data['content'],
				'video_link'		=> $data['video_link'],
				'image_id'			=> $images[0],
				'date_public'		=> $data['date_public'],
				'flag'				=> $data['flag'],
				'flag_moder'		=> $data['flag_moder'],
				'meta_title'		=> $data['meta_title'],
				'meta_description'	=> $data['meta_description'],
				'meta_keys'			=> $data['meta_keys'],
				'flag_video'		=> $data['flag_video']
			), array(
				'article_id' => $article_id
			));
			
			if (is_array($categs)) {
				DB::delete('articles_categs', array('article_id' => $article_id));
				
				for ($i = 0, $c = count($categs); $i < $c; $i++) {
					DB::insert('articles_categs', array(
						'article_id'	=> $article_id,
						'categ_id'		=> $categs[$i]
					));
				}
			}
			
			if (is_array($tags)) {
				DB::delete('article_tags', array('article_id' => $article_id));
				
				for ($i = 0, $c = count($tags); $i < $c; $i++) {
					DB::insert('articles_tags', array(
						'article_id'	=> $article_id,
						'tag_id'		=> $tags[$i]
					));
				}
			}
			
			if (is_array($images)) {
				for ($i = 0, $c = count($images); $i < $c; $i++) {
					DB::update('articles_images', array(
						'article_id' 	=> $article_id,
						'description'	=> $images_descr[$images[$i]],
						'sort_id'		=> $i
					), array(
						'image_id' => $images[$i]
					));
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	public function getImagesDescription($article_id) {
		$images = "SELECT image_id, description FROM `articles_images` WHERE article_id = $article_id";
		
		return DB::getAssocArray($images);
	}
	
	public function deleteImage($image_id) {
		$image_name = "SELECT url_full FROM `articles_images` WHERE image_id = $image_id";
		$image_name = DB::getColumn($image_name);
		
		$path = UPLOADS.'/images/articles';
		
		if ($image_name != null) {
			unlink($path . '/50x75/' 	. $image_name);
			unlink($path . '/100x150/' 	. $image_name);
			unlink($path . '/175x250/' 	. $image_name);
			unlink($path . '/full/' 	. $image_name);
			
			DB::delete('articles_images', array('image_id' => $image_id));
			
			return true;
		}
		
		return false;
	}
	
	public function uploadArticleImages() {
		
		require_once(LIBS . 'AcImage/AcImage.php');
		
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImage($_FILES['qqfile']['tmp_name'], $image_name, array(
			array(
				'w'		=> 700,
				'h'		=> 560,
				'path'	=> UPLOADS . '/images/articles/full/'
			),
			array(
				'w'		=> 150,
				'h'		=> 100,
				'crop'	=> -1,
				'path'	=> UPLOADS . '/images/articles/100x150/'
			),
			array(
				'w'		=> 250,
				'h'		=> 175,
				'path'	=> UPLOADS . '/images/articles/175x250/'
			),
			array(
				'w'		=> 75,
				'h'		=> 50,
				'path'	=> UPLOADS . '/images/articles/50x75/'
			))
		);
		
		$write = array(
			'url_full'	=> $image_name . '.jpg'
		);
		
		DB::insert('articles_images', $write);
		
		$image_id = DB::lastInsertId();
		
		$result = array(
			'uploadName' 	=> '/uploads/images/articles/100x150/' . $image_name . '.jpg',
			'success'		=> true,
			'image_id'		=> $image_id
		);
		
		return $result;	
	}
}