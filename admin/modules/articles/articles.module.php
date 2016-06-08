<?php

class articles {
	public function index($sort = 'all') {
		
		switch ($sort) {
			case 'removed':
				$where = 'flag_delete = 1';
			break;
			case 'vips':
				
			break;
			case 'moder':
				$where = 'articles.flag_moder = 0 AND flag_delete = 0';
			break;
			default:
				$where = 'flag_delete = 0';
			break;
		}
		
		$query = "SELECT articles.article_id, articles.user_id, articles.name, date_public, articles.flag, url_full, articles.flag_delete, articles.flag_moder,
			users_info.name AS user_name,
			users.email
			FROM `articles`
			LEFT JOIN `articles_images` USING(image_id)
			LEFT JOIN `users_info` ON users_info.user_id = articles.user_id
			LEFT JOIN `users` ON users.user_id = users_info.user_id
			WHERE $where
			ORDER BY articles.date_add DESC";
		
		$articles = DB::getAssocArray($query);
		
		echo Registry::get('twig')->render('articles.tpl', array(
			'title'		=> 'Все статьи',
			'articles'	=> $articles
		));
	}
	
	public function calendar() {
		echo Registry::get('twig')->render('articles-calendar.tpl', array(
			'title'		=> 'Календарь выхода статей'
		));
	}
	
	public function calendarAjax() {
		Header::ContentType("text/plain");
		
		$data = ModelArticles::getCalendarData();
		
		echo json_encode($data);
	}
	
	public function comments() {
		$comments = ModelArticles::getCommentsList();
		
		echo Registry::get('twig')->render('articles-comments.tpl', array(
			'title'		=> 'Все комментарии к статьям',
			'comments'	=> $comments
		));
	}
	
	public function statistic() {
		
		$content_counts = Statistic::getContentsCount();
		$month			= Statistic::getContentsCount(null, 30);
		$week			= Statistic::getContentsCount(null, 7);
		$views 			= Statistic::getSectionsStatistic();
		
		for ($i = 0, $c = count($views); $i < $c; $i++) {
			$all_views = $all_views + $views[$i]['views_section'];
		}
		
		echo Registry::get('twig')->render('articles-statistic.tpl', array(
			'all_count'		=> array_sum($content_counts),
			'current_count'	=> $content_counts['articles_count'],
			'month_count'	=> array_sum($month),
			'month_current'	=> $month['articles_count'],
			'week_count'	=> array_sum($week),
			'week_current'	=> $week['articles_count'],
			'all_views'		=> $all_views,
			'current_views'	=> $views[1]['views_section'],
			'popular_items'	=> ModelArticles::getPopular(10)
		));
	}
	
	public function commentEdit($comment_id) {
		$comment = ModelArticles::getComment($comment_id);
		
		$form = new Form();
		
		$form->create('textarea', 'comment', 'Комментарий');
		
		$form->setValues($comment);
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				ModelArticles::updateComment($comment_id, Request::post('comment', 'string'));
			}
			
			$form->destroy(
				'/admin/articles/comments', 
				'/admin/articles/comment/edit-'.$comment_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать комментарий',
			'Редактировать комментарий'
		);
	}
	
	public function commentDelete($comment_id){	
		ModelArticles::deleteComment($comment_id);
		Header::Location('/admin/articles/comments');
	}
		
	
	
	public function add() {
		$form = new Form();
		
		$form->createTab('article-default', 'Основная информация');
		$form->createTab('article-media', 'Фото / видео');
		$form->createTab('article-meta', 'Meta - описание');
		
		$form->create('text', 'name', 'Заголовок статьи', null, 'article-default');
		
		$categs = DB::DBObject()->query("SELECT categ_id, name FROM `categories_articles` ORDER BY sort_id");
		$categs = $categs->fetchAll(PDO::FETCH_KEY_PAIR);
		
		$tags = DB::DBObject()->query("SELECT tag_id, name FROM `tags` ORDER BY name");
		$tags = $tags->fetchAll(PDO::FETCH_KEY_PAIR);
		
		$form->create('multiple', 'categs[]', 'Рубрика', $categs, 'article-default');
		$form->create('multiple', 'tags[]', 'Метки', $tags, 'article-default');
		$form->create('datetime', 'date_public', 'Дата публикации', null, 'article-default');
		$form->create('switch', 'flag', 'Статья доступна к просмотру', 1, 'article-default');
		
		$form->create('text', 'author', 'Автор статьи', null, 'article-default');
		$form->create('text', 'source_name', 'Название источника', null, 'article-default');
		$form->create('text', 'source_link', 'Ссылка на источник', null, 'article-default');
		
		$form->create('textarea', 'content_min', 'Описание', null, 'article-default');
		$form->create('editor', 'content', 'Текст статьи', null, 'article-default');
		
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'article-media');
		
		$form->create('uploader', 'images', 'Фотографии', null, 'article-media');
		
		$form->create('textarea', 'meta_title', 'Meta title', null, 'article-meta');
		$form->create('textarea', 'meta_description', 'Meta description', null, 'article-meta');
		$form->create('textarea', 'meta_keys', 'Meta keywords', null, 'article-meta');
		
		$form->setValues(array('flag' => 1));
		
		$form->required('name', 'categs', 'date_public', 'content_min', 'source_name');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				
				$images = Request::post('images');
				$categs = Request::post('categs');
				$tags 	= Request::post('tags');
				
				$write = array(
					'user_id'			=> User::isUser(),
					'name'				=> Request::post('name', 'string'),
					'author'			=> Request::post('author', 'string'),
					'source_name'		=> Request::post('source_name', 'string'),
					'source_link'		=> Request::post('source_link', 'url'),
					'content_min'		=> Request::post('content_min', 'string'),
					'content'			=> Request::post('content'),
					'video_link'		=> Request::post('video_link', 'string'),
					'date_public'		=> Request::post('date_public', 'string'),
					'date_add'			=> DB::now(),
					'flag'				=> Request::post('flag', 'int'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string')
				);
				
				if (DB::insert('articles', $write)) {
					$article_id = DB::lastInsertId();
					
					$images = @implode(', ', $images);
					
					DB::query("UPDATE `articles_images` SET article_id = $article_id WHERE image_id IN($images)");
					
					
					
					for ($i = 0, $c = count($categs); $i < $c; $i++) {
						$write = array(
							'article_id'	=> $article_id,
							'categ_id'		=> $categs[$i] 
						);
						
						DB::insert('articles_categs', $write);
					}
					
					for ($i = 0, $c = count($tags); $i < $c; $i++) {
						$write = array(
							'article_id'	=> $article_id,
							'tag_id'		=> $tags[$i] 
						);
						
						DB::insert('articles_tags', $write);
					}
				}
				
			}
			
			$form->destroy(
				'/admin/articles', 
				'/admin/article/edit-'.$article_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить статью',
			'Добавить статью'
		);
	}
	
	public function edit($article_id) {
		$query = "SELECT *,
			(SELECT GROUP_CONCAT(categ_id) FROM `articles_categs` WHERE article_id = a.article_id) AS categs,
			(SELECT GROUP_CONCAT(tag_id) FROM `articles_tags` WHERE article_id = a.article_id) AS tags
			FROM `articles` AS a WHERE article_id = $article_id";
		
		$data = DB::getAssocArray($query, 1);
		$data['categs[]'] 	= explode(',', $data['categs']);
		$data['tags[]'] 	= explode(',', $data['tags']);
		
		$images = "SELECT image_id, CONCAT('/uploads/images/articles/100x150/', url_full) AS url_full FROM `articles_images` WHERE article_id = $article_id ORDER BY sort_id";
		$images = DB::DBObject()->query($images);
		$data['images'] = $images->fetchAll(PDO::FETCH_KEY_PAIR);
		
		$images_description = ModelArticles::getImagesDescription($article_id);
		
		//var_dump($images_description);
		
		$form = new Form();
		
		$form->createTab('article-default', 'Основная информация');
		$form->createTab('article-media', 'Фото / видео');
		$form->createTab('article-meta', 'Meta - описание');
		
		$form->create('text', 'name', 'Заголовок статьи', null, 'article-default');
		
		$categs = DB::DBObject()->query("SELECT categ_id, name FROM `categories_articles` ORDER BY sort_id");
		$categs = $categs->fetchAll(PDO::FETCH_KEY_PAIR);
		
		$tags = DB::DBObject()->query("SELECT tag_id, name FROM `tags` ORDER BY name");
		$tags = $tags->fetchAll(PDO::FETCH_KEY_PAIR);
		
		$form->create('multiple', 'categs[]', 'Рубрика', $categs, 'article-default');
		$form->create('multiple', 'tags[]', 'Метки', $tags, 'article-default');
		$form->create('datetime', 'date_public', 'Дата публикации', null, 'article-default');
		$form->create('switch', 'flag', 'Статья доступна к просмотру', 1, 'article-default');
		$form->create('switch', 'flag_video', 'Отображать в "Видео"', 1, 'article-default');
		
		$form->create('text', 'author', 'Автор статьи', null, 'article-default');
		$form->create('text', 'source_name', 'Название источника', null, 'article-default');
		$form->create('text', 'source_link', 'Ссылка на источник', null, 'article-default');
		
		$form->create('textarea', 'content_min', 'Описание', null, 'article-default');
		$form->create('editor', 'content', 'Текст статьи', null, 'article-default');
		
		
		
		$form->create('text', 'video_link', 'Ссылка на видео с YouTube', null, 'article-media');
		
		$form->create('uploader', 'images', 'Фотографии', null, 'article-media');
		
		for ($i = 0, $c = count($images_description); $i < $c; $i++) {
			$form->create('hidden', 'image_description[' . $images_description[$i]['image_id'] . ']', '', $images_description[$i]['description'], 'article-media');
			$form->attr('image_description[' . $images_description[$i]['image_id'] . ']', 'id', 'descr_' . $images_description[$i]['image_id']);
		}
		
		$form->create('textarea', 'meta_title', 'Meta title', null, 'article-meta');
		$form->create('textarea', 'meta_description', 'Meta description', null, 'article-meta');
		$form->create('textarea', 'meta_keys', 'Meta keywords', null, 'article-meta');
		
		$form->setValues($data);
		
		$form->required('name', 'categs', 'date_public', 'content_min', 'source_name');
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				ModelArticles::editArticle($article_id, array(
					'name'				=> Request::post('name', 'string'),
					'author'			=> Request::post('author', 'string'),
					'source_name'		=> Request::post('source_name', 'string'),
					'source_link'		=> Request::post('source_link', 'url'),
					'content_min'		=> Request::post('content_min', 'string'),
					'content'			=> Request::post('content'),
					'video_link'		=> Request::post('video_link', 'url'),
					'date_public'		=> Request::post('date_public', 'string'),
					'flag'				=> Request::post('flag', 'int'),
					'flag_moder'		=> 1,
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string'),
					'flag_video'		=> Request::post('flag_video', 'int')
				), 
				Request::post('categs'), 
				Request::post('images'),
				Request::post('tags'),
				Request::post('image_description'));
			}
			
			$form->destroy(
				'/admin/articles', 
				'/admin/article/edit-'.$article_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать статью',
			'Редактировать статью'
		);
	}
	
	public function vipAdd($article_id) {
		$form = new Form();
		
		$data = DB::getAssocArray("SELECT * FROM `articles_vip` WHERE article_id = $article_id", 1);
		
		$form->create('switch', 'flag_vip', 'Сделать статью VIP', 1);
		$form->create('spinner', 'sort_id', 'Позиция в списке VIP');
		$form->create('daterange', 'date_range', 'Период VIP размещения');
		
		if (count($data) > 0) {
			$form->setValues($data);
			$form->setValues(array(
				'flag_vip' 			=> 1,
				'start_date_range'	=> $data['date_start'],
				'end_date_range'	=> $data['date_end']
			));
		}
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				$write = array(
					'article_id'	=> $article_id,
					'sort_id'		=> Request::post('sort_id', 'int'),
					'date_start'	=> Request::post('start_date_range', 'string'),
					'date_end'		=> Request::post('end_date_range', 'string')
				);
				
				if (Request::post('flag_vip') > 0) {
					DB::insert('articles_vip', $write, 1);
				}
				else {
					DB::delete('articles_vip', array('article_id' => $article_id));
				}
				
				$form->destroy(
					'/admin/articles', 
					'/admin/article/vip-'.$article_id
				);
			}
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Сделать статью VIP',
			'Сделать статью VIP'
		);
	}
	
	public function delete($article_id) {
		DB::update('articles', array('flag_delete' => 1), array('article_id' => $article_id));
		
		Header::Location('/admin/articles');
	}
	
	public function reestablish($article_id) {
		DB::update('articles', array('flag_delete' => 0), array('article_id' => $article_id));
		Header::Location('/admin/articles/removed');
	}
	
	public function categories() {
		
		$categories = DB::getAssocArray("SELECT categ_id, name, date_add, date_edit FROM `categories_articles` ORDER BY sort_id");
		
		echo Registry::get('twig')->render('articles-categories.tpl', array(
			'title'			=> 'Все рубрики',
			'categories'	=> $categories
		));
	}
	
	public function categoryAdd() {
		$form = new Form();
		
		$form->create('text', 'name', 'Название');
		
		$form->create('text', 'title', 'Заголовок H1');
		
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				$write = array(
					'name'				=> Request::post('name', 'string'),
					'title'				=> Request::post('title', 'string'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string'),
					'date_add'			=> DB::now()
				);
				
				DB::insert('categories_articles', $write);
				$categ_id = DB::lastInsertId();
			}
			
			$form->destroy(
				'/admin/articles/categories', 
				'/admin/articles/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить рубрику',
			'Добавить рубрику'
		);
	}
	
	public function categoryEdit($categ_id) {
		
		$data = DB::getAssocArray("SELECT categ_id, name, title, meta_title, meta_description, meta_keys FROM `categories_articles` WHERE categ_id = $categ_id", 1);
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название');
		
		
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues($data);
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				$write = array(
					'name'				=> Request::post('name', 'string'),
				
					'title'				=> Request::post('title', 'string'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string')
				);
				 Site::d($write,1);
				DB::update('categories_articles', $write, array('categ_id' => $categ_id));
			}
			
			$form->destroy(
				'/admin/articles/categories', 
				'/admin/articles/category/edit-'.$categ_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать рубрику "'.$data['name'].'"',
			'Редактировать рубрику "'.$data['name'].'"'
		);
	}
	
	public function tags() {
		$tags = DB::getAssocArray("SELECT tag_id, name, date_add, date_edit FROM `tags` ORDER BY sort_id");
		
		echo Registry::get('twig')->render('articles-tags.tpl', array(
			'title'	=> 'Все метки',
			'tags'	=> $tags
		));
	}
	
	public function tagAdd() {
		$form = new Form();
		
		$form->create('text', 'name', 'Название метки');
		$form->create('text', 'h1', 'H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				$write = array(
					'name'				=> Request::post('name', 'string'),
					'h1'				=> Request::post('h1', 'string'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string'),
					'date_add'			=> DB::now()
				);
				
				DB::insert('tags', $write);
				$tag_id = DB::lastInsertId();
			}
			
			$form->destroy(
				'/admin/articles/tags', 
				'/admin/articles/tag/edit-'.$tag_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить метку',
			'Добавить метку'
		);
	}
	
	
	public function tagDelete($tag_id){
		
		DB::delete('tags', array('tag_id' => $tag_id));
		DB::delete('articles_tags', array('tag_id' => $tag_id));
		Header::Location('/admin/articles/tags');
	
	}
	
	public function tagEdit($tag_id) {
		$data = DB::getAssocArray("SELECT tag_id, name, meta_title, meta_description, meta_keys , h1 FROM `tags` WHERE tag_id = $tag_id", 1);
		
		$form = new Form();
		
		$form->create('text', 'name', 'Название метки');
		$form->create('text', 'h1', 'H1');
		$form->create('textarea', 'meta_title', 'Meta title');
		$form->create('textarea', 'meta_description', 'Meta description');
		$form->create('textarea', 'meta_keys', 'Meta keywords');
		
		$form->required('name');
		
		$form->setValues($data);
		
		if ($send = $form->isSend()) {
			if ($form->checkForm()) {
				$write = array(
					'name'				=> Request::post('name', 'string'),
					'h1'				=> Request::post('h1', 'string'),
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'H1')
				);
				
				DB::update('tags', $write, array('tag_id' => $tag_id));
			}
			
			$form->destroy(
				'/admin/articles/tags', 
				'/admin/articles/tag/edit-'.$tag_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать  МЕТКУ "'.$data['name'].'"',
			'Редактировать рубрику "'.$data['name'].'"'
		);
	}
	
	public function tagSorted() {
		parse_str($_GET['data'], $sort);
		
		$query = "UPDATE `tags` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['tag']); $i < $c; $i++) 
			$query .= " WHEN tag_id = " . (int)$sort['tag'][$i] . " THEN $i ";
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
		
		return true;
	}
	
	public function sorted() {
		parse_str($_GET['data'], $sort);
		
		$query = "UPDATE `categories_articles` SET sort_id = CASE ";
		
		for ($i = 0, $c = count($sort['categ']); $i < $c; $i++) 
			$query .= " WHEN categ_id = " . (int)$sort['categ'][$i] . " THEN $i ";
		
		$query .= "ELSE sort_id END";
		
		DB::query($query);
		
		return true;
	}
	
	public function interviews() {
		
		$interviews = "SELECT 
			articles_votes.vote_id, 
			articles_votes.article_id, 
			articles_votes.name, 
			articles_votes.date_add, 
			articles_votes.date_edit,
			articles.name AS article_name
			FROM `articles_votes` 
			INNER JOIN `articles` USING(article_id)
			ORDER BY articles_votes.date_add DESC";
		
		$interviews = DB::getAssocArray($interviews);
		
		echo Registry::get('twig')->render('articles-interviews.tpl', array(
			'title'			=> 'Опросы к статьям',
			'interviews'	=> $interviews
		));
	}
	
	public function interviewAdd() {
		$form = new Form();
		
		$articles = "SELECT article_id, name FROM `articles` WHERE flag = 1 AND flag_delete = 0 ORDER BY date_public DESC";
		$articles = DB::getAssocKey($articles);
		
		$form->create('select', 'article_id', 'Статья', $articles);
		$form->create('text', 'name', 'Название опроса');
		
		$form->create('text', 'versions', 'Варианты ответов');
		$form->attr('versions', 'class', 'tags-input');
		
		$form->required('article_id', 'name');
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				$write = array(
					'article_id'	=> Request::post('article_id', 'int'),
					'name'			=> Request::post('name', 'string'),
					'date_add'		=> DB::now()
				);
				
				DB::insert('articles_votes', $write);
				$vote_id = DB::lastInsertId();
				
				$versions = explode(';', Request::post('versions', 'string'));
				
				for ($i = 0, $c = count($versions); $i < $c; $i++) {
					$write = array(
						'vote_id'	=> $vote_id,
						'name'		=> $versions[$i]
					);
					
					DB::insert('votes_versions', $write);
				}
			}
			
			$form->destroy(
				'/admin/articles/interviews', 
				'/admin/articles/interview/edit-' . $vote_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить опрос к статье',
			'Добавить опрос к статье'
		);
	}
	
	public function interviewEdit($vote_id) {
		$form = new Form();
		
		$vote = "SELECT articles_votes.article_id, articles_votes.name,
			GROUP_CONCAT(votes_versions.name SEPARATOR ';') AS versions
			FROM `articles_votes`
			INNER JOIN `votes_versions` USING(vote_id)
			WHERE vote_id = $vote_id";
		
		$vote = DB::getAssocArray($vote, 1);
		
		$articles = "SELECT article_id, name FROM `articles` WHERE flag = 1 AND flag_delete = 0 ORDER BY date_public DESC";
		$articles = DB::getAssocKey($articles);
		
		$form->create('select', 'article_id', 'Статья', $articles);
		$form->create('text', 'name', 'Название опроса');
		
		$form->create('text', 'versions', 'Варианты ответов');
		$form->attr('versions', 'class', 'tags-input');
		
		$form->required('article_id', 'name');
		$form->setValues($vote);
		
		if ($form->isSend()) {
			if ($form->checkForm()) {
				
				$write = array(
					'article_id'	=> Request::post('article_id', 'int'),
					'name'			=> Request::post('name', 'string')
				);
				
				DB::update('articles_votes', $write, array('vote_id' => $vote_id));
				
				$versions = explode(';', Request::post('versions', 'string'));
				
				DB::delete('votes_versions', array('vote_id' => $vote_id));
				
				for ($i = 0, $c = count($versions); $i < $c; $i++) {
					$write = array(
						'vote_id'	=> $vote_id,
						'name'		=> $versions[$i]
					);
					
					DB::insert('votes_versions', $write);
				}
			}
			
			$form->destroy(
				'/admin/articles/interviews', 
				'/admin/articles/interview/edit-' . $vote_id
			);
		}
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Редактировать опрос к статье',
			'Редактировать опрос к статье'
		);
	}
	
	public function uploadImage() {
		header("Content-Type: text/plain");
		
		$result = ModelArticles::uploadArticleImages();
		
		echo json_encode($result);
	}
	
	public function deleteImage($image_id) {
		Header::ContentType("text/plain");
		
		if (ModelArticles::deleteImage($image_id)) {
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
}

?>