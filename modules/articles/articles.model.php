<?php

class ModelArticles {
	public function getCategoriesList() {
		$categs = "SELECT categ_id, name FROM `categories_articles` ORDER BY sort_id";
		
		return  DB::getAssocArray($categs);
	}
	
	public function getTagsList() {
		$tags = "SELECT tag_id, name FROM `tags` ORDER BY name";
		
		return  DB::getAssocArray($tags);
	}
	
	public function getVips() {
		$date = DB::now();
		
		$vips = "SELECT articles.article_id, name, url_full
			FROM `articles_vip` AS av 
			INNER JOIN `articles` USING(article_id)
			INNER JOIN `articles_images` USING(image_id)
			WHERE date_start <= '$date' AND date_end > '$date' - INTERVAL 1 DAY";

		return DB::getAssocArray($vips);
	}
	
	public function getArticlesVideo() {
		$article = "SELECT a.article_id, name, url_full
			FROM `articles` AS a
			LEFT JOIN `articles_images` USING(image_id)
			WHERE flag = 1  AND flag_delete = 0 AND flag_video = 1";
		
		return DB::getAssocArray($article);
	}
	
	public function getArticlesArchive() {
		$dates = "SELECT MONTH(date_public) AS month, YEAR(date_public) AS year
					FROM `articles`
					WHERE flag = 1
					GROUP BY YEAR(date_public), MONTH(date_public)
					ORDER BY date_public DESC";
		
		$dates = DB::DBObject()->query($dates);
		$dates->execute();
		
		while ($array = $dates->fetch(PDO::FETCH_ASSOC)) {
			$result[] = array(
				'name'	=> Str::get($array['month'])->getRusMonth()->ucwords() . ' ' . $array['year'],
				'year'	=> $array['year'],
				'month'	=> $array['month']
			);
		}
		
		return $result;
	}
	
	public function getArticlesList($categ_id = 0, $page = 0, $tag_id = 0, $count = 15, $search = null, $date = null) {
		
		$page	= $page > 0 ? $page : 1;
		$limit 	= ($count * $page) - $count;
		
		if (!User::isAdmin()) {
			$where = "AND flag = 1 AND date_public <= '". DB::now() ."' ";
		}
		
		if ($categ_id > 0) {
			$meta 	= DB::getAssocArray("SELECT meta_title, meta_description, meta_keys FROM `categories_articles` WHERE categ_id = $categ_id", 1);
			$where 	.= "AND a.article_id IN(SELECT article_id FROM `articles_categs` WHERE categ_id = $categ_id)";
		}
		
		if ($tag_id > 0) {
			$meta 	= DB::getAssocArray("SELECT meta_title, meta_description, meta_keys FROM `tags` WHERE tags_id = $tag_id", 1);
			$where 	.= "AND a.article_id IN(SELECT article_id FROM `articles_tags` WHERE tag_id = $tag_id)";
		}
		
		if ($date != null) {
			$date = explode('-', $date);
			$where .= " AND YEAR(a.date_public) = '{$date[0]}' AND MONTH(a.date_public) = '{$date[1]}'";
		}
		
		if ((string) $search != null) {
			$match = "AND MATCH(name, content_min, meta_title, meta_description, meta_keys) AGAINST('$search')";
			$orderr_by = '';
		}
		else {
			if (User::isAdmin()) {
				$orderr_by = "ORDER BY a.sort_id ASC, date_add DESC";
			}
			else {
				$orderr_by = "ORDER BY a.sort_id ASC, date_public DESC";
			}
		}
		
		$articles = "SELECT a.article_id, name, content_min, date_public, IFNULL(url_full, 'none.jpg') AS url_full,
			(SELECT GROUP_CONCAT(categ_id) FROM `articles_categs` WHERE article_id = a.article_id ) AS categs,
			(SELECT GROUP_CONCAT(tag_id) FROM `articles_tags` WHERE article_id = a.article_id ) AS tags,
			(SELECT COUNT(*) FROM `articles_comments` WHERE article_id = a.article_id AND flag = 1) AS comments,
			(SELECT COUNT(*) FROM `articles_views` WHERE article_id = a.article_id) + views AS views,
			flag, flag_moder
			FROM `articles` AS a
			LEFT JOIN `articles_images` USING(image_id)
			WHERE flag_delete = 0 $where $match
			$orderr_by
			LIMIT $limit, $count";
		
		$articles = DB::DBObject()->query($articles);
		$articles->execute();
		
		while ($article = $articles->fetch(PDO::FETCH_ASSOC)) {
			$articles_list[] = array_merge($article, array(
				'categs'	=> DB::getAssocKey("SELECT categ_id, name FROM `categories_articles` WHERE categ_id IN({$article['categs']})"),
				'tags'		=> $article['tags'] != null ? DB::getAssocKey("SELECT tag_id, name FROM `tags` WHERE tag_id IN({$article['tags']})") : ''
			));
		}
		
		return array(
			'meta'	=> $meta,
			'list'	=> $articles_list
		);
	}
	
	public function getSubscribeItems($categs, $date) {
		if (is_array($categs)) {
			$query = "SELECT
				articles.article_id AS id,
				'article' AS type,
				name,
				content_min AS description,
				url_full AS image,
				date_public AS date
			FROM `articles`
			LEFT JOIN `articles_images` USING(image_id)
			WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND DATE(date_public) = '". $date ."'
				AND articles.article_id IN(SELECT article_id FROM `articles_categs` WHERE categ_id IN(" . implode(',', $categs) . "))
			ORDER BY date_public DESC";

			return DB::getAssocArray($query);
		}
	}
	
	public function getArticlesCount($categ_id = 0, $tag_id = 0, $date = null, $search = null) {
		if ($categ_id > 0) {
			$where 	= "AND article_id IN(SELECT article_id FROM `articles_categs` WHERE categ_id = $categ_id)";
		}
		
		if ($tag_id > 0) {
			$where 	= "AND article_id IN(SELECT article_id FROM `articles_tags` WHERE tag_id = $tag_id)";
		}
		
		if ($date != null) {
			$date = explode('-', $date);
			$where .= " AND YEAR(date_public) = '{$date[0]}' AND MONTH(date_public) = '{$date[1]}'";
		}
		
		if ((string) $search != null) {
			$match = " AND MATCH(name, content_min, meta_title, meta_description, meta_keys) AGAINST('$search')";
		}
		
		$count = "SELECT COUNT(*) FROM `articles` WHERE flag = 1 AND flag_delete = 0 AND date_public <= '". DB::now() ."' $where $match";
		
		return DB::getColumn($count);
	}
	
	public function getArticleFull($article_id) {
		
		if (!User::isAdmin()) {
			$where = "AND flag = 1";
		}
		
		$article = "SELECT a.article_id, name, content, date_public, url_full, author, source_name, source_link, video_link, image_id, url_full,
			(SELECT GROUP_CONCAT(categ_id) FROM `articles_categs` WHERE article_id = a.article_id ) AS categs,
			(SELECT GROUP_CONCAT(tag_id) FROM `articles_tags` WHERE article_id = a.article_id ) AS tags,
			(SELECT COUNT(*) FROM `articles_views` WHERE article_id = a.article_id) + views AS views,
			meta_title, meta_description, meta_keys, content_min, flag, flag_moder
			FROM `articles` AS a
			LEFT JOIN `articles_images` USING(image_id)
			WHERE a.article_id = $article_id $where AND flag_delete = 0";
		
		$article = DB::getAssocArray($article, 1);
		
		$article['categs'] 		= DB::getAssocKey("SELECT categ_id, name FROM `categories_articles` WHERE categ_id IN({$article['categs']})");
		$article['tags']		= DB::getAssocKey("SELECT tag_id, name FROM `tags` WHERE tag_id IN({$article['tags']})");
		$article['video_link']	= str_replace('watch?v=', '', end(explode('/',  $article['video_link'])));
		
		return (array) $article;
	}
	
	public function getArticleData($article_id) {
		$query = "SELECT *, DATE_FORMAT(date_public, '%Y-%m-%d %H:%i') AS date_public,
			(SELECT GROUP_CONCAT(categ_id) FROM `articles_categs` WHERE article_id = a.article_id) AS categs,
			(SELECT GROUP_CONCAT(tag_id) FROM `articles_tags` WHERE article_id = a.article_id) AS tags
			FROM `articles` AS a WHERE article_id = $article_id";

		$data = DB::getAssocArray($query, 1);
		
		return $data;
	}
	
	public function getArticlesImages($article_id) {
		$images = "SELECT image_id, url_full AS url_full, description
			FROM `articles_images` WHERE article_id = $article_id ORDER BY sort_id";
		
		return DB::getAssocArray($images);
	}
	
	public function delete($article_id) {
		DB::update('articles', array(
			'flag_delete'	=> 1
		), array(
			'article_id'	=> $article_id
		));

        News::deleteOfferOnNews(16, $article_id);
		
		return true;
	}
	
	public function editFlag($article_id, $flag = 0) {
		DB::update('articles', array(
			'flag'			=> $flag
		), array(
			'article_id'	=> $article_id
		));

        News::updateOfferOnNews(16, $article_id, array(
            'flag'	=> $flag
        ));
		
		return true;
	}
	
	public function editFlagModer($article_id, $flag_moder = 0) {
		DB::update('articles', array(
			'flag_moder'	=> $flag_moder
		), array(
			'article_id'	=> $article_id
		));

        News::updateOfferOnNews(16, $article_id, array(
            'flag_moder'	=> $flag_moder
        ));
		
		return true;
	}
	
	public function getArticleVote($article_id, $user_id = 0) {
		$vote = "SELECT vote_id, name FROM `articles_votes` WHERE article_id = $article_id";
		$vote = DB::getAssocArray($vote, 1);
		
		if (count($vote) > 0) {
			if ($user_id > 0) {
				if (ModelArticles::isUserVote($article_id, $user_id)) {
					return ModelArticles::getVoteResults($vote['vote_id']);
				}
				else {
					$versions = "SELECT version_id, name FROM `votes_versions` WHERE vote_id = {$vote['vote_id']} ORDER BY sort_id ASC, version_id ASC";
					$versions = DB::getAssocKey($versions);
					
					return array(
						'vote'		=> $vote,
						'versions'	=> $versions
					);
				}
			}
			else {
				return ModelArticles::getVoteResults($vote['vote_id']);
			}
		}
		else {
			return false;
		}
	}
	
	public function getVoteResults($vote_id) {
		$vote = "SELECT vote_id, name FROM `articles_votes` WHERE vote_id = $vote_id";
		$vote = DB::getAssocArray($vote, 1);
		
		$versions = "SELECT version_id, name,
			(SELECT COUNT(*) FROM `votes_results` WHERE version_id = votes_versions.version_id) AS count
			FROM `votes_versions`
			WHERE vote_id = $vote_id ORDER BY sort_id ASC, version_id ASC";
		
		$versions = DB::getAssocArray($versions);
		
		$sum = 0;
		
		for ($i = 0, $c = count($versions); $i < $c; $i++) {
			$sum += $versions[$i]['count'];
		}
		
		return array(
			'vote'		=> $vote,
			'versions'	=> $versions,
			'sum'		=> $sum
		);
	}
	
	public function setVoteResult($vote_id, $version_id, $user_id = 0) {
		$write = array(
			'vote_id'		=> $vote_id,
			'version_id'	=> $version_id,
			'user_id'		=> $user_id
		);
		
		DB::insert('votes_results', $write);
		
		return DB::lastInsertId();
	}
	
	public function isUserVote($article_id, $user_id) {
		$vote = "SELECT COUNT(*) FROM `votes_results` 
			WHERE vote_id = (SELECT vote_id FROM `articles_votes` WHERE article_id = $article_id) AND user_id = $user_id";
		
		return (bool) DB::getColumn($vote);
	}
	
	public function getArticleComments($article_id) {
		$comments = "SELECT articles_comments.comment, articles_comments.date_add, articles_comments.user_id,
	 	IF( char_length(articles_comments.user_name)>1 , articles_comments.user_name , users_info.name) AS name, 
			/* articles_comments.user_name as name, */
			IF(users_info.avatar, users_info.avatar, 'none.jpg') AS avatar
			FROM `articles_comments`
			LEFT JOIN `users_info` USING(user_id)
			WHERE articles_comments.flag = 1 AND articles_comments.article_id = $article_id
			ORDER BY articles_comments.date_add";
		  // Site::d($comments);
		$comments = DB::DBObject()->query($comments);
		$comments->execute();
		
		$regExp 	= "/\[quot(\d+)\](\n*\t*\s*.*\n*\t*\s*)\[\/quot(\d+)\]/u";
		$replace	= "<div class=\"response\">$2</div>";

		while ($array = $comments->fetch(PDO::FETCH_ASSOC)) {
			
			$result[] = array(
				'comment'	=> preg_replace($regExp, $replace, $array['comment']),
				'date_add'	=> $array['date_add'],
				'user_id'	=> $array['user_id'],
				'name'		=> $array['name'],
				'avatar'	=> $array['avatar']
			);
		}
		
		return  $result;
	}
	
	public function addComment($article_id, $user_id, $comment) {

		$write = array(
			'article_id'	=> $article_id,
			'user_id'		=> $user_id,
			'comment'		=> $comment,
			'date_add'		=> DB::now()
		);
		
		DB::insert('articles_comments', $write);
		
		return DB::lastInsertId();
	}
	
	public function getComment($comment_id) {
		$comment = "SELECT articles_comments.comment, articles_comments.date_add, articles_comments.user_id,
			users_info.name, users_info.avatar
			FROM `articles_comments`
			INNER JOIN `users_info` USING(user_id)
			WHERE comment_id = $comment_id";
		
		return DB::getAssocArray($comment, 1);
	}
	
	public function setViews($article_id, $user_id = 0) {
		if (Request::getCookie('article_view_' . $article_id, 'int') > 0) {
			return true;
		}
		else {
			$write = array(
				'article_id'	=> $article_id,
				'user_id'		=> $user_id,
				'date_view'		=> DB::now()
			);
			
			DB::insert('articles_views', $write);
			
			Request::setCookie('article_view_' . $article_id, 1);
		}
		
		return true;
	}
	
	public function getCategoriesFromSelect() {
		$categs = "SELECT categ_id, name FROM `categories_articles` ORDER BY sort_id, name";
		
		return DB::getAssocKey($categs);
	}
	
	public function getGallery($article_id, $image_id = 0) {
		$gallery = "SELECT url_full, description FROM `articles_images` WHERE article_id = $article_id AND image_id != $image_id ORDER BY sort_id";
		
		return DB::getAssocArray($gallery);
	}
	
	public function addArticle($name, $author, $source_name, $source_link, $categs, $content, $images, $video_link, $user_id, $user_comment, $flag_vip, $flag_moder) {
		$write = array(
			'user_id'		=> $user_id,
			'name'			=> $name,
			'author'		=> $author,
			'source_name'	=> $source_name,
			'source_link'	=> $source_link,
			'content'		=> $content,
			'video_link'	=> $video_link,
			'image_id'		=> $images[0],
			'user_comment'	=> $user_comment,
			'date_add'		=> DB::now(),
			'flag_vip_add'	=> $flag_vip,
			'flag_moder'	=> $flag_moder
		);
		
		if (DB::insert('articles', $write)) {
			$article_id = DB::lastInsertId();

            /*
             * Add offer to news
             * */
            News::addOfferToNews(16, $article_id, array(
                'name' => $name,
                'image' => ($images[0] ? DB::getColumn('SELECT url_full FROM `articles_images` WHERE image_id = ' . $images[0]) : ''),
                'description' => '',
                'date_add' => '0000-00-00',
                'flag_moder_view' => 1,
                'flag_moder' => $flag_moder
            ));
            // End add

			if (is_array($categs)) {
				for ($i = 0, $c = count($categs); $i < $c; $i++) {
					$write = array(
						'article_id'	=> $article_id,
						'categ_id'		=> $categs[$i]
					);
					
					DB::insert('articles_categs', $write);
				}
			}
			
			if (is_array($images)) {
				$images = @implode(',', $images);
				$update = "UPDATE `articles_images` SET article_id = $article_id WHERE image_id IN($images)";
				
				DB::query($update);
			}
			
			return $article_id;
		}
		
		return false;
	}
	
	public function addArticleAdmin($user_id, $data, $categs, $tags, $new_tags, $interview_name, $interview_versions, $images, $images_descr) {
		
		if ($new_tags != '') {
			$new_tags = @explode(',', $new_tags);
			
			for ($i = 0, $c = count($new_tags); $i < $c; $i++) {
				DB::insert('tags', array(
					'name'		=> $new_tags[$i],
					'date_add'	=> DB::now()
				));
				
				$tags[] = DB::lastInsertId();
			}
		}
		
		DB::insert('articles', array(
			'user_id'			=> $user_id,
			'name'				=> $data['name'],
			'author'			=> $data['author'],
			'source_name'		=> $data['source_name'],
			'source_link'		=> $data['source_link'],
			'content_min'		=> $data['content_min'],
			'content'			=> $data['content'],
			'video_link'		=> $data['video_link'],
			'image_id'			=> $images[0],
			'date_public'		=> $data['date_public'],
			'meta_title'		=> $data['meta_title'],
			'meta_description'	=> $data['meta_description'],
			'meta_keys'			=> $data['meta_keys'],
			'date_add'			=> DB::now(),
			'flag'				=> $data['flag'],
			'flag_moder'		=> $data['flag_moder']
		));
		
		$article_id = DB::lastInsertId();
		
		if ($interview_name != '') {
			DB::insert('articles_votes', array(
				'article_id'	=> $article_id,
				'name'			=> $interview_name,
				'date_add'		=> DB::now()
			));
			
			$vote_id = DB::lastInsertId();
			
			$versions = explode(';', $interview_versions);
			
			for ($i = 0, $c = count($versions); $i < $c; $i++) {
				DB::insert('votes_versions', array(
					'vote_id'	=> $vote_id,
					'name'		=> $versions[$i]
				));
			}
		}
		
		if (is_array($categs)) {
			for ($i = 0, $c = count($categs); $i < $c; $i++) {
				DB::insert('articles_categs', array(
					'article_id'	=> $article_id,
					'categ_id'		=> $categs[$i]
				));
			}
		}
		
		if (is_array($tags)) {
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

        /*
         * Add offer to news
         * */
        News::addOfferToNews(16, $article_id, array(
            'name' => $data['name'],
            'image' => $images[0] ? DB::getColumn('SELECT url_full FROM `articles_images` WHERE image_id = ' . $images[0]) : '',
            'description' => $data['content_min'],
            'date_add' => $data['date_public'],
            'flag_moder_view' => 1,
            'flag_moder' => $data['flag_moder'],
            'flag' => $data['flag']
        ));
        // End add
		
		return $article_id;
	}
	
	public function editArticle($article_id, $data, $categs, $images, $tags, $images_descr) {
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
			'meta_title'		=> $data['meta_title'],
			'meta_description'	=> $data['meta_description'],
			'meta_keys'			=> $data['meta_keys']
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
			DB::delete('articles_tags', array('article_id' => $article_id));

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

        /*
         * Update offer to news
         * */
        News::updateOfferOnNews(16, $article_id, array(
            'name' => $data['name'],
            'image' => $images[0] ? DB::getColumn('SELECT url_full FROM `articles_images` WHERE image_id = ' . $images[0]) : '',
            'description' => $data['content_min'],
            'date_add' => $data['date_public']
        ));
        // End
		
		return true;
	}
	
	public function getCategoryMetaTags($categ_id) {
		$query = "SELECT name, title, meta_title, meta_description, meta_keys FROM `categories_articles` WHERE categ_id = $categ_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function getTagMetaTags($tag_id) {
		$query = "SELECT name, meta_title, meta_description, meta_keys ,h1 FROM `tags` WHERE tag_id = $tag_id";
		
		return DB::getAssocArray($query, 1);
	}
	
	public function uploadImage() {
	
		$image_name = Str::get()->generate(20);
		
		$images = Site::resizeImageBg($_FILES['qqfile'], $image_name, array(
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
	
	public static function  remove(){
	 $query='SELECT article_id FROM articles  WHERE flag_delete=1 ' ;   
   	 $articles= DB::getAssocArray($query);
	    echo count($articles);
	   if(!count($articles))die(' not content     ');  
       	   
	 array_map(function($article){
		static::removeImages($article['article_id']);
		
	 DB::delete('articles', array('article_id' => $article['article_id']));
	  },$articles);
	  
	}
	
	public static function removeImages($article_id){
		$query="SELECT url_full FROM `articles_images` WHERE article_id = $article_id";
		$images=DB::getAssocArray($query);
		$path = UPLOADS.'/images/articles';
		
		if(!count($images))return 1;	
		
		array_map(function($image){
			@unlink($path .'/50x75/'.$image['url_full']);
			@unlink($path . '/100x150/' . $image['url_full']);
			@unlink($path . '/175x250/' . $image['url_full']);
			@unlink($path . '/full/'    . $image['url_full']);
		},$images);
	   DB::delete('articles_images', array('article_id' => $article_id));
	}
}

?>