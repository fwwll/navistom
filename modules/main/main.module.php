<?php

class Main {
	
	public function index($page = 1, $user_id = 0, $flag_no_view = 0, $flag_no_moder = false, $flag_vip_add = 0, $error = false, $flag_no_show = 0) {
		Site::setSectionView(1, User::isUser());
		
		$all_count 	= array_sum(Site::getContentsCount(0, $user_id, null, 0, $flag_vip_add, $flag_no_show));
		$limit 		= 100;
		
		if ($flag_no_view > 0) {
			$all_count 	= 100;
			$limit 		= 100;
		}

        Debug::log($all_count);
		
		$pagination	= Site::pagination($limit, $all_count, $page);

        //$news = ModelMain::getNews($page, $limit, $user_id, null, $flag_no_view, ($flag_no_moder ? 0 : 1), $flag_vip_add, ($flag_no_show ? 0 : 1));
		$news = ModelMain::getNews($page, $limit, $user_id, null, $flag_no_view, ($flag_no_moder ? 0 : 1), $flag_vip_add, ($flag_no_show ? 0 : 1));
		
		//Site::d($news,1);
	
		  
		if ($flag_vip_add) {
			$vipRequests = ModelMain::getVipRequests();
			
			for ($i = 0, $c = count($news); $i < $c; $i++) {
				$news[$i]['vip'] = $vipRequests[$news[$i]['section_id']][$news[$i]['content_id']];
			}
			
			
		}

        if ($error) {
            header('HTTP/1.0 404 Not Found');
            Header::SetTitle('Ошибка 404 страница не найдена');
            Header::SetMetaTag('description', '404 Ошибка. Запрашиваемая страница не найдена');
            Header::SetMetaTag('keywords', '404 ошибка, страница не найдена');
        }

        if ($user_id > 0) {
            Header::SetTitle($news[0]['user_name'] . ' объявления для стоматологов и зубных техников');
            Header::SetMetaTag('description', $news[0]['user_name'] . ' Украина объявления для стоматологов и зубных техников на стоматологическом портале Navistom.com');
            Header::SetMetaTag('keywords', $news[0]['user_name'] . ', объявления, стоматология');
        }
              
            if($_GET['tp']=='new'){
	            
			 $tpl_name='main_new2.tpl';  
		   }else{
		
			 $tpl_name='main_new2.tpl';			 
		   }
		   
		echo Registry::get('twig')->render($tpl_name, array(
			'articles'		=> ModelMain::getArticlesList(),
			'news'			=> $news,
			'count_f'       => count($news),
			'pagination'	=> $pagination,
            'error'         => $error,
            'topProviders'  => ModelMain::getTopProviders() 
		));
	}
	
	public function search($q, $page = 1) {
		$q = (string) Str::get($q)->trim()->strToLower()->removeSymbols();
		
		$all_count 	= array_sum(Site::getContentsCount(0, 0, $q));
		$pagination	= Site::pagination(10, $all_count, $page);

        $result = ModelMain::getNews($page, 10, 0, $q);

		echo Registry::get('twig')->render('search-new.tpl', array(
			'q'					=> $q,
			'categs'			=> ModelMain::searchInCategs($q),
			'search_result'		=> $result,
			'pagination'		=> $pagination
		));
	}
	
	public function journals() {
		Site::setSectionView(17, User::isUser());
		
		$meta = Site::getDefaultMetaTags('journals');
			
		Header::SetTitle($meta['meta_title']);
		
		Header::SetH1Tag($meta['title']);
		
		Header::SetMetaTag('description', $meta['meta_description']);
		Header::SetMetaTag('keywords', $meta['meta_keys']);
		
		echo Registry::get('twig')->render('journals.tpl', array(
			'journals'	=> ModelMain::getJournalsList()
		));
	}
	
	public function getJournalPages($journal_id) {
		header("Content-Type: text/plain");
		
		$result = ModelMain::getJournalPages($journal_id);
		
		echo json_encode($result);
	}
	
	public function getBanner() {
		echo json_encode(Site::getBanner());
	}
	
	public function getMessTpl($mess_id) {
		header("Content-Type: text/plain");
		
		echo json_encode(array(
			'message' => ModelMain::getMessTpl($mess_id)
		));
	}
	
	public function vipRequest($section_id, $resource_id) {
	
		/* $curr_prace =Site::getPriceCategoriy($section_id);
		if (Request::get('query')) {
			$params = Request::get('query');
		}
		else {
			$url = parse_url('http://navistom.com' . $_SERVER['REQUEST_URI']);
			parse_str($url['query'], $params);
		}
		     //var_dump($params) ;die; 
		echo Registry::get('twig')->render('vip-request.tpl', array(
			'params'		=> $params,
			'section_id'	=> $section_id,
			'resource_id'	=> $resource_id,
			'curr_prace'	=> $curr_prace,
			'count'			=> array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1))
		)); */
		
		
	}
	
	public function vipRequestConfirm($section_id, $resource_id, $type = 1) {
		if ($section_id > 0 and $resource_id > 0) {
			$table 			= Site::getSectionsTable($section_id);
			$primary_key 	= Site::getSectionsTableIdName($section_id);
			
			ModelMain::updateFlagVipAdd($table, $primary_key, $resource_id);
			ModelMain::updateVipRequest($section_id, $resource_id, $type);
			
			echo Registry::get('twig')->render('vip-request-complete.tpl');
		}
	}
	
	public function vipRequestDelete($section_id, $resource_id) {
		$table 			= Site::getSectionsTable($section_id);
		$primary_key 	= Site::getSectionsTableIdName($section_id);
			
		ModelMain::updateFlagVipAdd($table, $primary_key, $resource_id, 0);
		
		ModelMain::updateVipRequest($section_id, $resource_id, 0, 1);
		
		Header::Location();
	}

    public function statistic($cron = 0) {
        $fileName = CACHE . 'statistic/' . DB::now(1) . '.html';
        
        if (is_file($fileName)) {
			  //Site::d($fileName,1);
            if (Registry::get('ajax') == 1) {
                echo file_get_contents($fileName);
            }
            else {
                Header::SetTitle('Статистика портала NaviStom.com');
                Header::SetMetaTag('description', 'Статистика портала NaviStom.com');
                Header::SetMetaTag('keywords', 'статистика navistom, статистика navistom.com, статистика навигатор стоматологии');

                $document = new DOMDocument;
                @$document->loadHTML('<?xml encoding="utf-8" ?>' . file_get_contents($fileName));
                $document = $document->saveXML($document->getElementById('global-statistic'), LIBXML_NOEMPTYTAG);

                echo Registry::get('twig')->render('statistic-static.tpl', array(
                    'statistic' => str_replace(array('<![CDATA[', ']]>'), '', $document)
                ));
            }
        }
        else {
            echo Statistic::createStatisticCache();
        }
    }
	
	public function rss($country_id = 1, $page = 1, $count = 10) {
		Header::ContentType('text/xml');
		
		error_reporting(0);
		
		$pDom = new DOMDocument('1.0', 'UTF-8');
		
		$pDom->formatOutput = true;
		$pDom->preserveWhiteSpace = false;
		
		$pRSS = $pDom->createElement('rss');
		$pRSS->setAttribute('version', '2.0');
		$pRSS->setAttribute('xmlns', 'http://navistom.com/rss');
		$pRSS->setAttribute('xmlns:yandex', 'http://news.yandex.ru');

		$pDom->appendChild($pRSS);

		$pChannel = $pDom->createElement('channel');
		
		$pRSS->appendChild($pChannel);

		$pTitle = $pDom->createElement('title', 'NaviStom - навигатор стоматологии - cтоматологический портал');
		$pLink  = $pDom->createElement('link', 'http://navistom.com');
		$pDesc  = $pDom->createElement('description', 'Стоматологический портал для стоматологов зубных техников. Объявления стоматологические, новости стоматологии. Стоматологическое оборудование расходные материалы инструменты товары услуги производители и поставщики стоматологической продукции');
		$pImage = $pDom->createElement('image');
		
		$pChannel->appendChild($pTitle);
		$pChannel->appendChild($pLink);
		$pChannel->appendChild($pDesc);
		$pChannel->appendChild($pImage);
		
		$pURL   = $pDom->createElement('url', 'http://navistom.com/templates/Navistom/images/logo.png'); 
		$pTitle = $pDom->createElement('title', 'NaviStom - навигатор стоматологии - cтоматологический портал');
		$pLink  = $pDom->createElement('link', 'http://navistom.com');
		
		$pImage->appendChild($pURL);
		$pImage->appendChild($pTitle);
		$pImage->appendChild($pLink);
		
		$news = ModelMain::getNewsList($country_id, $page, $count, 0, null);
		
		$country = array_keys(Registry::get('config')->countries, $country_id);
		
		for ($i = 0, $c = count($news); $i < $c; $i++) {
			$pItem  = $pDom->createElement('item');
		    
		    $pDate	= $pDom->createElement('pubDate', $news[$i]['date_add'].' +0200');
		    
		    $pImg	= $pDom->createElement('enclosure');
		    
		    switch ($news[$i]['type']) {
		    	case 'activity':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/activity/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		$pImg->setAttribute('url', 'http://navistom.com/uploads/images/activity/'.$news[$i]['image']);
		    		$pTitle = $pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'articles':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/article/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		$pImg->setAttribute('url', 'http://navistom.com/uploads/images/articles/100x150/'.$news[$i]['image']);
		    		$pTitle = $pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'ads':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/ads/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		if($news[$i]['image'] != '' and $news[$i]['image'] != 'products/80x100/') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		$pTitle = $pDom->createElement('title', htmlspecialchars($news[$i]['name']) . ', Б/У');
		    	break;
		    	case 'products_new':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/product/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/products/80x100/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		
		    		$pTitle = @$pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'services':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/service/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/services/80x100/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		$pTitle = $pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'demand':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/demand/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/demand/80x100/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		$pTitle = $pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'labs':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/lab/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/labs/80x100/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		$pTitle = $pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'realty':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/realty/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/realty/80x100/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		$pTitle = $pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'diagnostic':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/diagnostic/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/diagnostic/80x100/'. $news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		$pTitle = $pDom->createElement('title', $news[$i]['name']);
		    	break;
		    	case 'resume':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/work/resume/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/work/80x100/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		
		    		$pTitle = $pDom->createElement('title', 'Резюме ' . $news[$i]['name']);
		    	break;
		    	case 'vacancies':
		    		$pLink  = $pDom->createElement('link', 'http://navistom.com/' . $country[0] . '/work/vacancy/' . $news[$i]['content_id'] . '-' . Str::get($news[$i]['name'])->truncate(60)->translitURL());
		    		if($news[$i]['image'] != '') {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/work/80x100/'.$news[$i]['image']);
		    		}
		    		else {
		    			$pImg->setAttribute('url', 'http://navistom.com/uploads/images/100x80.jpg');
		    		}
		    		$pTitle = $pDom->createElement('title', 'Требуется ' . $news[$i]['name']);
		    	break;
		    }
		   
		    $pImg->setAttribute('type', 'image/jpeg');
		    
		    $pItem->appendChild($pTitle);
		    $pItem->appendChild($pLink);
		    $pItem->appendChild($pImg);
		    $pItem->appendChild($pDate);
		    
		    $pChannel->appendChild($pItem);
		}
		
		echo $pDom->saveXML();
		
		die();
	}
	
	
	

    public function error() {
        $this->index(null, null, null, null, null, true);
    }

    public function providerTransition($providerId, $link) {
        $link = str_replace('|', '/', $link);

        ModelMain::setProviderTransition($providerId);
        Header::Location($link);
    }
	
	public function topStop(){
	
		
	}
}