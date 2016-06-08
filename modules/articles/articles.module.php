<?php

class Articles {
	
	public function index($categ_id = 0, $page = 0, $tag_id = 0, $search = null, $date = null) {

		Site::setSectionView(16, User::isUser());
		
		$search 	= Str::get($search)->filterString();
		
		$articles 	= ModelArticles::getArticlesList($categ_id, (int)$page, $tag_id, Registry::get('config')->itemsInPage, $search, $date);
		$categs		= ModelArticles::getCategoriesList();
		
		$count 		= ModelArticles::getArticlesCount($categ_id, $tag_id, $date, $search);
		$pagination	= Site::pagination(Registry::get('config')->itemsInPage, $count, $page);
		
		if ($categ_id > 0) {
			$meta_tags = ModelArticles::getCategoryMetaTags($categ_id);
			
			Header::SetTitle($meta_tags['meta_title']);
			
			Header::SetH1Tag($meta_tags['title']);
			
			Header::SetMetaTag('description', $meta_tags['meta_description']);
			Header::SetMetaTag('keywords', $meta_tags['meta_keys']);
		}
		
		if ($tag_id > 0) {
			$meta_tags = ModelArticles::getTagMetaTags($tag_id);
			
			Header::SetTitle($meta_tags['meta_title']);
			$h1=($meta_tags['h1'])? $meta_tags['h1']: $meta_tags['name'];
			Header::SetH1Tag($h1);
			
			Header::SetMetaTag('description', $meta_tags['meta_description']);
			Header::SetMetaTag('keywords', $meta_tags['meta_keys']);
		}
		
		if ($date != null) {
			$title = Header::GetTitle();
			 
			Header::SetTitle($title . ' - архив ' . Str::get($date)->getRusDate());
			
			Header::SetH1Tag('Архив ' . Str::get($date)->getRusDate());
			
			
		}
		
		if ($page > 0) {
			$title = Header::GetTitle();
			
			Header::SetTitle($title . ' - страница ' . $page);
		}
		
		$subscribe = ModelCabinet::getUserSubscribeCategs(User::isUser(), 0, 16);
			
		if ($categ_id > 0 and is_array($subscribe)) {
			$subscribe_status = in_array($categ_id, $subscribe);
		}
		else {
			$subscribe_status = count($subscribe) >= count($categs);
		}
		
		if($_GET['tp']=='new'){ 
			 $tpl_name='articles-new2.tpl';  
		}else{
			 /* $tpl_name='articles.tpl'; */
			 $tpl_name='articles-new2.tpl';	
		}
		    
		echo Registry::get('twig')->render($tpl_name, array(
			'categories'	=> $categs,
			'tags'			=> ModelArticles::getTagsList(),
			'articles'		=> $articles['list'],
			'vips'			=> ModelArticles::getVips(),
			'meta'			=> $articles['meta'],
			'pagination'	=> $pagination,
			'videos'		=> ModelArticles::getArticlesVideo(),
			'archive'		=> ModelArticles::getArticlesArchive(),
			'count'			=> $count,
			 'category_name'  => Site::categoryName(), 
			'subscribe_status'	=> $subscribe_status
		));
	}
	
	public function full($article_id) {
		$article 	= ModelArticles::getArticleFull($article_id);
		$comments	= ModelArticles::getArticleComments($article_id);
		
		$is_user	= User::isUser();
		$isUserVote = ModelArticles::isUserVote($article_id, $is_user);
		
		$vote		= ModelArticles::getArticleVote($article_id, $is_user);
		
		$gallery	= ModelArticles::getGallery($article_id, $article['image_id']);
		
		
		ModelArticles::setViews($article_id, (int) $is_user);
		
		
		if ($article['meta_title'] == '') {
			Header::SetTitle($article['name']);
		}
		else {
			Header::SetTitle($article['meta_title']);
		}
		
		Header::SetMetaTag('description', $article['content_min']);
		
		Header::SetMetaTag('keywords', $article['meta_keys']);
		
		Header::SetSocialTag('og:image', 'http://navistom.com/uploads/images/articles/100x150/' . $article['url_full']);

        if (!$article['article_id']) {
            Header::Location('/404');
        }
		
		if($_GET['tp']=='new'){ 
			 $tpl_name='article-new3-full.tpl';  
		}else{
			 //$tpl_name='article-full.tpl';
			 $tpl_name='article-new3-full.tpl'; 	
		}
		echo Registry::get('twig')->render($tpl_name, array(
			'article'		=> $article,
			'interview'		=> $vote,
			'comments'		=> $comments,
			'comm_count'	=> count($comments),
			'is_user'		=> $is_user,
			'is_user_vote'	=> $isUserVote,
			'gallery'		=> $gallery,
			'last_offers'	=> Site::getLastOffers()
		));
	}
	
	public function add() {
		Header::SetTitle('Добавить статью' . ' - ' . Header::GetTitle());
		Header::SetMetaTag('description', 'Добавить статью');
		Header::SetMetaTag('keywords', 'Добавить статью');
		
		if (Request::post('name') != null) {
			if (Request::PostIsNull('name', 'content', 'source_name') and count(Request::post('images')) > 0) {
				if (User::isAdmin()) {
					$article_id = ModelArticles::addArticleAdmin(User::isUser(), array(
						'name'				=> Request::post('name', 'string'),
						'author'			=> Request::post('author', 'string'),
						'source_name'		=> Request::post('source_name', 'string'),
						'source_link'		=> Request::post('source_link', 'url'),
						'content_min'		=> Request::post('content_min', 'string'),
						'content'			=> Request::post('content'),
						'video_link'		=> Request::post('video_link', 'url'),
						'date_public'		=> Request::post('date_public', 'string'),
						'flag'				=> 1,
						'flag_moder'		=> 1,
						'meta_title'		=> Request::post('meta_title', 'string'),
						'meta_description'	=> Request::post('meta_description', 'string'),
						'meta_keys'			=> Request::post('meta_keys', 'string')
					),
						Request::post('categs'),
						Request::post('tags'),
						Request::post('new_tags'),
						Request::post('interview_name'),
						Request::post('interview_versions'),
						Request::post('images'),
						Request::post('image_description')
					);
					
					if ($article_id > 0) {
						$result = array(
							'success'	=> true,
							'message'	=> 'Статья успешно добавлена'
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
					if (User::isUserAccess(16)) {
						$article_id = ModelArticles::addArticle(
							Request::post('name', 'string'),
							Request::post('author', 'string'),
							Request::post('source', 'string'),
							Request::post('source_link', 'string'),
							Request::post('categs'),
							Request::post('content'),
							Request::post('images'),
							Request::post('video_link', 'string'),
							User::isUser(),
							Request::post('user_comment', 'string'),
							Request::post('submit', 'string') == 'vip' ? 1 : 0,
							User::isPostModeration(1) ? 1 : 0
						);
						
						$result = array(
							'success'	=> true,
							'message'	=> User::isPostModeration(1) ? 'Статья успешно добавлена' : 'Статья успешно добавлена на модерацию'
						);
					}
					else {
						$result = array(
							'success'	=> false,
							'message'	=> 'У Вас нет прав для добавления материала в этот раздел'
						);
					}
				}
			}
			else {
				$result = array(
					'success'	=> false,
					'message'	=> 'Не все обязательные поля заполнены'
				);
			}
			
			Header::ContentType("text/plain");
			
			echo json_encode($result);
		}
		else {
			$categs = ModelArticles::getCategoriesFromSelect();
			
			if (User::isAdmin()) {
				
				echo Registry::get('twig')->render('article-add-admin.tpl', array(
					'categs'		=> $categs,
					'tags'			=> ModelArticles::getTagsList()
				));
			}
			else {
				echo Registry::get('twig')->render('article-add.tpl', array(
					'categs'		=> $categs,
					'is_add_access'	=> User::isUserAccess(16)
				));
			}
		}
	}
	
	public function edit($article_id) {
		$data 	= ModelArticles::getArticleData($article_id);
		$images	= ModelArticles::getArticlesImages($article_id);
		
		echo Registry::get('twig')->render('article-edit.tpl', array(
			'data'			=> $data,
			'images'		=> $images,
			'categs'		=> ModelArticles::getCategoriesFromSelect(),
			'tags'			=> ModelArticles::getTagsList(),
			'images_count'	=> 7 - count($images)
		));
	}
	
	public function editAjax($article_id) {
		Header::ContentType("text/plain");
		
		if (User::isAdmin()) {
			if (Request::PostIsNull('name', 'content', 'content_min', 'source_name')) {
				
				ModelArticles::editArticle($article_id, array(
					'name'				=> Request::post('name', 'string'),
					'author'			=> Request::post('author', 'string'),
					'source_name'		=> Request::post('source_name', 'string'),
					'source_link'		=> Request::post('source_link', 'url'),
					'content_min'		=> Request::post('content_min', 'string'),
					'content'			=> Request::post('content'),
					'video_link'		=> Request::post('video_link', 'url'),
					'date_public'		=> Request::post('date_public', 'string'),
					'flag'				=> 1,
					'flag_moder'		=> 1,
					'meta_title'		=> Request::post('meta_title', 'string'),
					'meta_description'	=> Request::post('meta_description', 'string'),
					'meta_keys'			=> Request::post('meta_keys', 'string')
				), 
					Request::post('categs'), 
					Request::post('images'),
					Request::post('tags'),
					Request::post('image_description')
				);
					
				$result = array(
					'success'	=> true,
					'message'	=> 'Редактирование прошло успешно'
				);
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
				'message'	=> 'Ты не пройдешь!'
			);
		}
			
		echo json_encode($result);
	}
	
	public function delete($article_id) {
		if (User::isAdmin()) {
			ModelArticles::delete($article_id);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flag($article_id, $flag = 0) {
		if (User::isAdmin()) {
			ModelArticles::editFlag($article_id, $flag);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function flagModer($article_id, $flag_moder = 0) {
		if (User::isAdmin()) {
			ModelArticles::editFlagModer($article_id, $flag_moder);
		}
		
		Header::Location($_SERVER['HTTP_REFERER']);
	}
	
	public function commentAdd() {
		if (Request::post('comment', 'string') != null and Request::post('article_id', 'int') > 0 and User::isUser()) {
			
			$comment_id = ModelArticles::addComment(
				Request::post('article_id', 'int'),
				User::isUser(),
				Request::post('comment', 'string')
			);
			
			$comment = ModelArticles::getComment($comment_id);
			
			echo Registry::get('twig')->render('article-comment.tpl', $comment);
		}
	}
	
	public function voteResultAdd() {
		if ($user_id = User::isUser()) {
			ModelArticles::setVoteResult(
				Request::post('vote_id', 'int'),
				Request::post('version_id', 'int'),
				$user_id
			);
			
			$results = ModelArticles::getVoteResults( 
				Request::post('vote_id', 'int') 
			);
			
			echo Registry::get('twig')->render('article-vote-results.tpl', $results);
		}
		
		return false;
	}
	
	public function uploadimage() {
		
		Header::ContentType("text/plain");
		
		$image = ModelArticles::uploadImage();
		
		echo json_encode($image);
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
	
	public function remove(){
		if(User::isAdmin())
		    ModelArticles::remove();
	}
	
}

?>