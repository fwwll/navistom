<?php
//User::isAdmin() == 2492

class ModelMenu { 
	
	public static function  getMenuScrol($url, $parentId=0){
		  $url_all=$url;
         static::getCashMenu( $url_all) ;
		$r='#categ-(\d+)#';
		if(preg_match($r,$url,$sub_id)){
		    $parentId=$sub_id[1];    
		}

		
		 $url=trim(parse_url($url)['path'],'/');
		 $url= explode('/',$url);
		 $table=Site::table_categ( $url[0]);
		  
		
		
		if($table=='categories'){
			if($url[0]=='ads'){
				$result=  self::getCategoriesFromAds($parentId);
			}else{
				$result= self::getCategoriesFromNew($parentId,(($url[1]=='filter-stocks')?1:0) );
			}

		}else{
			  
			switch($url[0]){
				case 'work': return self::getCategoriesFromWork(1 ,(($url[1]=='vacancy')?1:0),$parentId); 
				case 'articles': $result=self::getCategoriesFromArticle($parentId); break;
				case 'demand': return self::getCategoriesFromDemand();
				case'services': return self::getCategoriesFromServices($parentId);
				case'activity': return self::getCategoriesFromActivity($parentId);
				case'labs':return self::getCategoriesFromLabs($parentId);
				case'realty':return self::getCategoriesFromRealty($parentId);
				default:$query='SELECT 
					categ_id,
					name, 
					(SELECT COUNT(*) FROM '.$url[0] .' WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND '.($parentId > 0 ? 'sub_categ_id' : 'categ_id').' = c.categ_id '. ($userId > 0 ? 'AND user_id = ' . $userId : '') .') AS count
					from '.$table.' AS c';
					
					$result=  DB::getAssocArray($query);					
			}
			
			 
					
		}
		
		
		if($parentId){
		  if($url[0]=='work'){
			  $path_cat='categ';
		  }else{
			 $path_cat='sub_categ';
		  }	
		}else{
			$path_cat='categ';
		}
		
		if($url[0]=='work'){
			$url[0]=$url[0].'/'.$url[1];
		}
		if($url[1]=='filter-stocks'){
			$url[0]=$url[0].'/'.$url[1];
		}  
		
		  
		foreach ($result as $k=>$v){
				$result[$k]['url']= '/'.$url[0].'/'.$path_cat.'-'.$result[$k]["categ_id"].'-'. self::translitURL($result[$k]["name"]);
			}	

     		
		
		return $result;
	}
	
    public static function setCashMenu( $arr, $url  ){
	   $name= 'menu-cashe-'.md5($url).'.php';	 
	  $path=	FULLPATH .'/'.CACHE.'/menu/'.$name;
	   file_put_contents($path,$arr);
	  // Site::d($path);
	}
	
	public static function getCashMenu($url  ){
		$cache_time=86400;
		 $name= 'menu-cashe-'.md5($url).'.php';	 
	     $path=	FULLPATH .'/'.CACHE.'/menu/'.$name;
		 
		 if(is_file($path)){
		    if ((time() - $cache_time) < filemtime($path)) {
		      //echo file_get_contents($path);
			  include $path;
			  die;
		    }
		 }
		 
			 
		
	}
	
	
	public static function getMenuScrolSub($url){
		$r='#categ-(\d)#';
		preg_match($r,$url,$sub_id);
		$sub_id=$sub_id[1];
		
		$url=trim(parse_url($url)['path'],'/');
		$url= explode('/',$url);
		$table=Site::table_categ( $url[0]);
	}
	
	
	
	
	public static function getCategoriesFromAds($parentId = 0, $userId = 0) {
		
        $query = 'SELECT categ_id, name, name_min,
					(SELECT COUNT(*) FROM ads WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND '.($parentId > 0 ? 'sub_categ_id' : 'categ_id').' = c.categ_id '. ($userId > 0 ? 'AND user_id = ' . $userId : '') .') AS count
					FROM categories AS c
					WHERE parent_id = ' . $parentId . ' AND flag_no_ads = 1
					HAVING count > 0
					ORDER BY sort_id';

        return DB::getAssocArray($query);
    }
	
	
	public static function getCategoriesFromNew($parentId = 0, $flagStock = false, $hideZero = true, $userId = 0) {
        if ($flagStock) {
            $date = DB::now(1);
            $count = '(SELECT SQL_CACHE COUNT(*) FROM products_new WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND '. ($parentId > 0 ? 'sub_categ_id' : 'categ_id') .' = c.categ_id '. ($userId > 0 ? 'AND user_id = ' . $userId : '') .' AND
            (SELECT stock_id FROM stocks WHERE product_new_id = products_new.product_new_id AND flag = 1 AND flag_moder = 1 AND DATE_SUB(date_start, INTERVAL 1 DAY) < "' . $date . '" AND date_end > "' . $date .'") > 0) AS count';
        }
        else {
            $count = '(SELECT SQL_CACHE  COUNT(*) FROM products_new WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND flag_show = 1 AND '. ($parentId > 0 ? 'sub_categ_id' : 'categ_id') .' = c.categ_id '. ($userId > 0 ? 'AND user_id = ' . $userId : '') .') AS count';
        }

        $query = 'SELECT SQL_CACHE  categ_id, name, name_min,
                  '. $count .'
                  FROM
                    categories AS c
                  WHERE
                    parent_id = ' . $parentId . ' AND flag_no_products = 0
                    '. ($hideZero ? 'HAVING count > 0' : '') .'
                  ORDER BY sort_id';
				 // Site::d($query);
                $result= DB::getAssocArray($query);
				
			
				
        return $result;
    }
	
	
	public static function getCategoriesFromWork($count = true, $vacancy = false,$parentId=0) {
		if($vacancy){
			$delim ='vacancy';	
		}else{
			$delim ='resume';	
		} 
		
        if (!$parentId) {
            if ($vacancy) {
                $count = '(SELECT SQL_CACHE  COUNT(*) FROM `vacancies` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND vacancy_id IN(SELECT vacancy_id FROM `vacancies_categs` WHERE categ_id = c.categ_id)) AS count';			
            }
            else {
                $count = '(SELECT SQL_CACHE  COUNT(*) FROM `work` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND work_id IN(SELECT work_id FROM `work_categs` WHERE categ_id = c.categ_id)) AS count';
            }

            $query = 'SELECT categ_id, name,
            '. $count .'
            FROM `categories_work` AS c
            HAVING count > 0
            ORDER BY sort_id';
			 
			$result=DB::getAssocArray($query);
			foreach ($result as $k=>$v){
			  $result[$k]['url']= '/work/'.$delim.'/categ-'.$result[$k]["categ_id"].'-'. self::translitURL($result[$k]["name"]);
			}		 

            return $result;
        }else{
			if ($vacancy) {
                $count = '(SELECT SQL_CACHE  COUNT(*) FROM `vacancies` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND vacancy_id IN(SELECT vacancy_id FROM `vacancies_categs` WHERE categ_id = '.$parentId .') AND city_id = cities.city_id ) AS count';			
            }
            else {
                $count = '(SELECT SQL_CACHE  COUNT(*) FROM `work` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND work_id IN(SELECT work_id FROM `work_categs` WHERE categ_id = '.$parentId .') AND city_id = cities.city_id ) AS count';
            }

            $query = "SELECT 
						city_id,
						name, 
						$count  
					FROM `cities`
					HAVING count > 0";
			
			$result=DB::getAssocArray($query);
			foreach ($result as $k=>$v){
				$result[$k]['url']= '/work/'.$delim.'/categ-'.$parentId.'/city-'.($result[$k]["city_id"]).'-'.self::translitURL($result[$k]["name"]);
			}		 

            return $result;
			
			
			
		}
		
	  }
		
	
	
	
	
public static function translit($string){
	return Str::get($string)->truncate(60)->translitURL();
}	
	
 public static function  translitURL($str,$revers=0) 
	{
		/* $tr = array(
			"А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
			"Д"=>"d","Е"=>"e","Ё"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
			"Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
			"О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
			"У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
			"Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
			"Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
			"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"j",
			"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
			"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
			"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
			"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
			" -"=> "", ","=> "", " "=> "-", "."=> "", "/"=> "-", 
			"-"=> ""
		);
		 if($revers){  
			$tr2=array_flip($tr);
			$str=str_replace('-',' ',$str );
		 }
		return mb_strtolower(strtr($str,$tr)); */
		 return  static ::translit($str);
	}
    public static  function vilka($url){
		$firms='/firm-(\d+)-/i';
		$products='/product-(\d+)-/i';
		preg_match($products,$url,$product);
		preg_match($firms,$url,$firm);
		return array('product'=>$product[1],'firm'=>$firm[1]);		
	} 
	public static function breadcrumb($route){
		
		$filter='';
        $user='';		
		$bread=array();
		$REFERER=self::getRreferer();
		
		
		
		
		$router = new routing(
			Registry::get('config')->route_xml_file,
			Registry::get('config')->routing_cache,
			Registry::get('config')->route_debug
		);
		
        $vilka=self::vilka($route);   
        $route = $router->get($route);
		
		//Site::d($route,1);
      	
		$tab= Site::table_categ($route["controller"]);

		$sub_categ_id =  $route["values"]["sub_categ_id"];
		$bread[0]['url']='/'.$route["controller"];
		$bread[0]['name']=Site::getName($route["controller"]);
		
		if($route["values"]["filter"]=='stocks'){
			   $filter ='/filter-stocks';
		}
		
		if($route["values"]["user_id"]){
			$query='SELECT SQL_CACHE  name FROM users_info WHERE user_id='.$route["values"]["user_id"];
			$result =DB::getAssocArray($query);
			$name_user=$result[0]["name"]; 
			$user= '/user-'.($route["values"]["user_id"]).'-'.(self::translitURL($result[0]["name"]));
			 			   
		}		
		
		
		
		if($route["controller"]=='products'){
			 $bread[0]['url']='/products'.$filter;
			 $bread[0]['name']=(($filter=='/filter-stocks')?'Акции':'Продам новое');
			 $route["controller"]="products".$filter;
		}
		 
		if($route["action"]=='resumeIndex'){
			 $bread[0]['url']='/work/resume';
			 $bread[0]['name']='Резюме';
			 $route["controller"]='work/resume';
		} 

		if($route["values"]["sort_by"]=='coming'){
			$route["controller"]='activity/sort-by-coming';
			
		}
		/* ----- */
		if($route["action"]=='vacancyIndex'){
			 $bread[0]['url']='/work/vacancy';
			 $bread[0]['name']='Вакансии';
			 $route["controller"]="work/vacancy";
				
		}
		 
		if($route["action"]=='resumeIndex'){
			 $bread[0]['url']='/work/resume';
			 $bread[0]['name']='Резюме';
			 $route["controller"]='work/resume';
		} 
		
				
		if(!empty($route["values"]["categ_id"])){
			$query="SELECT SQL_CACHE  name FROM $tab WHERE categ_id=".$route["values"]["categ_id"];
			$result =DB::getAssocArray($query);
			$city='';
			 if($route["values"]["city_id"]){
				$city='/city-'.($route["values"]["city_id"]).'-'.($route["values"]["translit"]);
			 }else{
				$city='-'.($route["values"]["translit"]);
			 }
				 
			$bread[1]['url']='/'.($route["controller"]).$user.'/categ-'.($route["values"]["categ_id"]).'-'.self::translitURL($result[0]['name']);
			$bread[1]['name']=$result[0]['name'] .(($route['controller']=='ads')?' Б/У':'') ;
			
			//Site::d($bread,1);
		}
		
		 if( !empty($route["values"]["city_id"])){
			$query="SELECT SQL_CACHE  name FROM cities WHERE city_id=".$route["values"]["city_id"];
			$result =DB::getAssocArray($query);
		
			$bread[2]['url']= '/'.($route["controller"]).$user.'/categ-'.($route["values"]["categ_id"]).'/city-'.($route["values"]["city_id"]).'-'.(self::translitURL($result[0]['name'])).'';
			$bread[2]['name']=$result[0]['name'] .(($route['controller']=='ads')?' Б/У':'') ;;
			
		} 
          		
		if(!empty($route["values"]["sub_categ_id"])){
			$query="SELECT SQL_CACHE  * FROM $tab WHERE categ_id=".$route["values"]["sub_categ_id"];
			$result =DB::getAssocArray($query);
			//Site::d($result);
			$parent="SELECT SQL_CACHE  * FROM $tab WHERE categ_id=".$result[0]['parent_id'];
			$parent=DB::getAssocArray($parent);
			$bread[2]['url']= '/'.($route["controller"]).$user.'/categ-'.$result[0]['parent_id'].'-'.self::translitURL($parent[0]['name']);
			$bread[2]['name']=$parent[0]['name'] .(($route['controller']=='ads')?' Б/У':'') ;
			$bread[3]['url']= '/'.($route["controller"]).$user.'/sub_categ-'.($route["values"]["sub_categ_id"]).'-'.(self::translitURL($result[0]['name']));
			/* $bread[3]['url']= '/'.($route["controller"]).$user.'/sub_categ-'.($route["values"]["sub_categ_id"]).'-'.($route["values"]["translit"]); */
			$bread[3]['name']=$result[0]['name'] .(($route['controller']=='ads')?' Б/У':'') ;
			
		}
		
		if($route['controller']=='articles' AND $route["action"]=='full'){
			$query='SELECT  SQL_CACHE  * FROM articles WHERE article_id='.$route["values"]["article_id"];
			$result =DB::getAssocArray($query);
			
			$bread[1]['name']=$result[0]["name"];
			$bread[1]['url']='/article/'.($route["values"]["article_id"]).'-'.(  self::translit($result[0]["name"])); 

		}
		
		
		if($route['controller']=='ads' AND $route["action"]=='full'){
			
			$query='SELECT  SQL_CACHE 
			    product_name,
       			parent.name ,
				parent.categ_id as parent_id ,
				cat.name as cat,
				cat.categ_id as cat_id
				FROM ads 
				INNER JOIN  categories as parent ON parent.categ_id=ads.sub_categ_id 
				INNER JOIN  categories as cat  ON cat.categ_id= ads.categ_id 
				WHERE ads_id='.$route["values"]["ads_id"];
			 
			$result =DB::getAssocArray($query);

			$bread[1]['name']=$result[0]["cat"] .' Б/У';
			$bread[1]['url']='/ads'.$user.'/categ-'.($result[0]['cat_id']).'-'.(self::translitURL($result[0]["cat"])); 
			
			$bread[2]['name']=$result[0]["name"] .' Б/У';
			$bread[2]['url']='/ads'.$user.'/sub_categ-'.($result[0]['parent_id']).'-'.(self::translitURL($result[0]["name"])); 
			
			$bread[3]['name']=$result[0]["product_name"] .' Б/У' ;
			$bread[3]['url']='/ads'.$user.'/'.($route["values"]["ads_id"]).'-'.(self::translitURL($result[0]["product_name"])); 
			
			
			
			
		}
		
		if(($route['controller']=='products' OR $route['controller']=='ads' OR $route['controller']=='products/filter-stocks') AND !empty($vilka['firm'])){
			$query='SELECT SQL_CACHE  name FROM producers WHERE producer_id='.$vilka['firm'];
			$result =DB::getAssocArray($query);
			$x =count($bread);
			
			$bread[$x+1]['name'] = $result[0]["name"];
			$bread[$x+1]['url'] ='/'.($route['controller']).$user.'/sub_categ-'.($route["values"]["sub_categ_id"]).'/firm-'.($vilka['firm']).'-'.(self::translitURL($result[0]["name"]));
					
		}
		// Site::d($bread,1);
		
		if(($route['controller']=='products' OR $route['controller']=='ads' ) AND !empty($vilka['product'])){
		
		$query='SELECT  SQL_CACHE  p1.name ,
						p1.producer_id ,
						p2.name as p2_name 
					FROM products as p1 
					INNER JOIN  producers as p2 ON p1.producer_id=p2.producer_id
					WHERE p1.product_id='.$vilka['product'];
						
		$result =DB::getAssocArray($query);
		
		$x =count($bread);
		 
		$bread[($x+1)]['name'] = $result[0]["p2_name"];
		$bread[($x+1)]['url'] ='/'.($route['controller']).$user.'/sub_categ-'.($route["values"]["sub_categ_id"]).'/firm-'.($result[0]['producer_id']).'-'.(self::translitURL($result[0]["p2_name"]));

		$bread[($x+2)]['name'] = $result[0]["name"];
		$bread[($x+2)]['url'] ='/'.($route['controller']).$user.'/sub_categ-'.($route["values"]["sub_categ_id"]).'/product-'.($vilka['product']).'-'.(self::translitURL($result[0]["name"]));
					
		}
		
		
		 
		
		if($route['controller']=='products' AND $route["action"]=='full'){
			
			$query='SELECT  SQL_CACHE 
			    product_name,
       			parent.name ,
				parent.categ_id as parent_id ,
				cat.name as cat,
				cat.categ_id as cat_id
			FROM products_new 
			INNER JOIN  categories as parent ON parent.categ_id=products_new.sub_categ_id 
			INNER JOIN  categories as cat  ON cat.categ_id= products_new.categ_id 
			WHERE product_new_id='.$route["values"]["product_new_id"];
			$result =DB::getAssocArray($query);
			  
			 if(trim($_COOKIE['filter'])=='/filter-stocks'){ 
			    $bread[1]['name']=$bread[0]['name'];
				$bread[1]['url']=$bread[0]['url']; 
				  
				$bread[0]['name']='Акции';
				$bread[0]['url']='/products/filter-stocks'; 
				 
				$bread[2]['name']=$result[0]["cat"];
				$bread[2]['url']='/products/categ-'.($result[0]['cat_id']).'-'.(self::translitURL($result[0]["cat"])); 
				
				$bread[3]['name']=$result[0]["name"];
				$bread[3]['url']='/products/sub_categ-'.($result[0]['parent_id']).'-'.(self::translitURL($result[0]["name"])); 
				
				$bread[4]['name']=$result[0]["product_name"];
				$bread[4]['url']='/product/'.($route["values"]["product_new_id"]).'-'.(self::translitURL($result[0]["product_name"])); 
				
				
				
			 }else{
			 
	
				$bread[1]['name']=$result[0]["cat"];
				$bread[1]['url']='/products'.$user.'/categ-'.($result[0]['cat_id']).'-'.(self::translitURL($result[0]["cat"])); 
				
				$bread[2]['name']=$result[0]["name"];
				$bread[2]['url']='/products'.$user.'/sub_categ-'.($result[0]['parent_id']).'-'.(self::translitURL($result[0]["name"])); 
				
				$bread[3]['name']=$result[0]["product_name"];
				$bread[3]['url']='/product'.$user.'/'.($route["values"]["product_new_id"]).'-'.(self::translitURL($result[0]["product_name"]));	
				
				
			 }	
	    }
		
	
		
	        
	
		if($route["controller"]=='reclama'){
			$bread[0]['name']='Реклама на Navistom';
			$bread[0]['url']='/advertising';
		}	
		if($route["controller"]=='users' and $route["action"]=="feedback" ){
			$bread[0]['name']='Обратная связь';
			$bread[0]['url']='/feedback';
		}
		    
		if($route["values"]["user_id"]){
		  $x=count($bread);
		  if($x >1){	
			array_unshift($bread ,array('url'=>('/'.($route["controller"]).$user),'name'=>$name_user) );
			$set= $bread[1];
			$bread[1]=$bread[0]; 
			$bread[0]=$set;
		  }else{
			$query='SELECT SQL_CACHE  name FROM users_info WHERE user_id='.$route["values"]["user_id"];
			$result =DB::getAssocArray($query);  
			$bread[$x]['name']=$result[0]['name'];
			$bread[$x]['url']='/'.($route['controller']).'/user-'.($route["values"]["user_id"]).'-'.(self::translitURL($result[0]["name"]));
		  }
		  
		}
		  
		if(($route['action']=='resumeIndex' OR $route['action']=='vacancyIndex') AND ($route['values']["max"] or $route['values']["min"])){
			$max=($route['values']["max"]?$route['values']["max"]:0);
			$min=($route['values']["min"]?$route['values']["min"]:0);
			$name= "От $min - до $max";
			$x=count($bread);
			$bread[$x]['name']=$name;
			$bread[$x]['url']='/'.($route['controller']).'/price-'.$min.'-'.$max;
		} 
		
		if($route['action']=='resumeFull'){
			$bread[0]['name']='Резюме';
			$bread[0]['url']='/work/resume';
		  $x=count($bread);
		  
		  $query='SELECT SQL_CACHE 
					user_name,
					user_surname,
					c.name,
					work.work_id,
					cat.categ_id
				FROM work  
				INNER JOIN work_categs as cat ON cat.work_id=work.work_id 
				INNER JOIN categories_work as c ON cat.categ_id=c.categ_id
				WHERE work.work_id='.$route["values"]["work_id"];
						
						
		$result =DB::getAssocArray($query);
		 $full='/work/resume/'.($route["values"]["work_id"]).'-';
		 $user_name=''; 
		foreach($result as $k =>$v){
			$bread[$x]['name']=$v['name'] ;
            $name= self::translitURL($v['name']);			
			$bread[$x]['url']='/work/resume/categ-'.($v["categ_id"]).'-'. $name;
			$user_name= ($v['user_surname']).'-'.($v['user_name']);	
			$full.=$name.'-';
			$x++;
		}	
		  $bread[$x]['url']= trim($full,'-');
		  $bread[$x]['name']=$user_name;
	
		}
		
		//Site::d($route,1);	
		
		if($route['action']=='vacancyFull'){
			$bread[0]['name']=' Вакансии';
			$bread[0]['url']='/work/vacancy';
			$query='SELECT SQL_CACHE 
					vac.city_id,
					vac.city_name,
					c.categ_id,
					c.name,
					vac.search_name
				FROM vacancies as vac
				INNER JOIN vacancies_categs as cat ON  cat.vacancy_id =vac.vacancy_id
				INNER JOIN categories_work as c ON cat.categ_id=c.categ_id
				WHERE vac.vacancy_id='.$route["values"]["vacancy_id"];
					
				$result =DB::getAssocArray($query);
				$x=count($bread);
				$bread[$x]['name']=$result[0]['city_name'];
			    $bread[$x]['url']='/work/vacancy/city-'.($result[0]['city_id']).'-'.self::translitURL($result[0]['city_name']);
				$x=count($bread);
				foreach($result as  $k =>$v){
					$bread[$x]['name']=$v['name'];
					$bread[$x]['url']='/work/vacancy/categ-'.($v['categ_id']).'-'.self::translitURL($v['name']);
					$x++;
				}
				
				$bread[$x]['name']='Требуется '.$result[0]['search_name'];
				$bread[$x]['url']='/work/vacancy/'.($route["values"]["vacancy_id"]).'-'.self::translitURL($result[0]['search_name']);
				 
				
		}
		
			
		if($route['controller']=='labs' AND $route['action']=='full' ){
			$query="SELECT SQL_CACHE 
			    l.city_name,
			    l.city_id,
				l.name as lab_name,
				c.name,
				c.categ_id
			FROM labs as l
			INNER JOIN labs_categs as cat ON cat.lab_id=l.lab_id
			INNER JOIN categories_labs as c ON cat.categ_id= c.categ_id
			WHERE l.lab_id=".($route['values']['lab_id']);
			
			$result =DB::getAssocArray($query);
			$x=count($bread);
			
			$bread[$x]['name']=$result[0]["city_name"];
			$bread[$x]['url']='/labs/city-'.($result[0]["city_id"]).'-'.self::translitURL($result[0]["city_name"]);
			$x=count($bread);
			
			$lab_name='';
			$lab_url='';
			
			foreach($result as $k =>$v){
				$bread[$x]['name']=$v['name'];
				$bread[$x]['url']='/labs/categ-'.($v['categ_id']).'-'.self::translitURL($v['name']);
				$lab_name=$v['lab_name'];
				$lab_url='/lab/'.($route['values']['lab_id']).'-'.self::translitURL($lab_name);
				$x++;
			}
			
			$bread[$x]['name']=	$lab_name;
			$bread[$x]['url']=$lab_url;			

		}

		
		if($route['controller']=='realty' and $route["action"]=='full' ){
			$query='SELECT	SQL_CACHE 	
						city_id,
						city_name,
						r.name,
						cat.name as cat_name,
						cat.categ_id						
					FROM realty as r
					INNER JOIN categories_realty as cat ON r.categ_id =cat.categ_id 
               	    WHERE r.realty_id='.($route['values']['realty_id']);
				
			$result =DB::getAssocArray($query);
			$x=count($bread);
			$bread[$x]['name']=$result[0]["city_name"];
			$bread[$x]['url']='/realty/city-'.($result[0]['city_id']).'-'.self::translitURL($result[0]["city_name"]);
			$x++;
			$bread[$x]['name']=$result[0]["cat_name"];
			$bread[$x]['url']='/realty/categ-'.($result[0]['categ_id']).'-'.self::translitURL($result[0]["cat_name"]);
			$x++;
			$bread[$x]['name']=$result[0]["name"];
			$bread[$x]['url']='/realty/'.($route['values']['realty_id']).'-'.self::translitURL($result[0]["name"]);
		}
		
	
		if($route["controller"]=='services' and $route["action"] =='full' ){
			$query='SELECT SQL_CACHE 
					s.city_name,
					s.city_id,
					s.name,
					c.categ_id,
					c.name as cat_name
 			 FROM  services as s 
			 INNER JOIN services_categs as cat ON cat.service_id =s.service_id
			 INNER JOIN  categories_services as c  ON c.categ_id=cat.categ_id  	
			 WHERE s.service_id='.$route['values']['service_id'];
			 
			$result =DB::getAssocArray($query);
			$x=count($bread);
			$bread[$x]['name']=$result[0]["city_name"];
			$bread[$x]['url']='/services/city-'.($result[0]['city_id']).'-'.self::translitURL($result[0]["city_name"]);
			$x++;
			$bread[$x]['name']=$result[0]["cat_name"];
			$bread[$x]['url']='/services/categ-'.($result[0]['categ_id']).'-'.self::translitURL($result[0]["cat_name"]);
			$x++;
			$bread[$x]['name']=$result[0]["name"];
			$bread[$x]['url']='/service/'.($route['values']['service_id']).'-'.self::translitURL($result[0]["name"]);
		}
		
		if($route["controller"]=='demand' and $route["action"] =='full' ){
			$query='SELECT  SQL_CACHE  name FROM demand  
			        WHERE demand_id='.$route['values']['demand_id'];
			$result =DB::getAssocArray($query);		
			$x=count($bread);		
			$bread[$x]['name']=$result[0]["name"];
			$bread[$x]['url']='/demand/'.($route['values']['demand_id']).'-'.self::translitURL($result[0]["name"]);		
		}
		
		
		
		
		if($route["controller"]=='activity' and $route["action"] =='full' ){
			$query='SELECT SQL_CACHE 
						city_id,
						city_name,
						 ac.name as name_all,
						c.name,
						c.categ_id
						
					FROM activity as ac 
			        INNER JOIN activity_categs as cat ON  ac.activity_id= cat.activity_id
					INNER JOIN categories_activity as c ON c.categ_id=cat.categ_id	
					WHERE ac.activity_id='.$route['values']['activity_id'];
					
			$result =DB::getAssocArray($query);		
			$x=count($bread);		
			$bread[$x]['name']=$result[0]["city_name"];
			$bread[$x]['url']='/activity/city-'.($result[0]["city_id"]).'-'.self::translitURL($result[0]["city_name"]);
			
			 $x=count($bread);
			foreach($result as $k=>$v){
				$bread[$x]['name']=$v["name"];
				$bread[$x]['url']='/activity/categ-'.($v["categ_id"]).'-'. self::translitURL($v["name"]);
				$x++;
			}
			
			$bread[$x]['name']=$result[0]["name_all"];
			$bread[$x]['url']='/activity/'.($route['values']['activity_id']).'-'.self::translitURL($result[0]["name_all"]); 

		}
		
		if($route["controller"]=='articles' AND $route["values"] AND $route["values"]["date"] ){
			$x=count($bread);
			$bread[$x]['name']= 'Архив ' . Str::get($route["values"]["date"])->getRusDate();
			$bread[$x]['url'] =$bread[$x-1]['url'].'/archive-'.$route["values"]["date"];
			
			 
		}
	
	
	//$meta 	= DB::getAssocArray("SELECT meta_title, meta_description, meta_keys FROM `tags` WHERE tags_id = $tag_id", 1);
	//Site::d($route,1);
	
	   if( $route["controller"]=='articles' AND  $route["values"]["tag_id"]){
		  $x=count($bread); 
		  $bread[$x]['name']=DB::getAssocArray('SELECT name FROM `tags` WHERE tag_id='.$route["values"]["tag_id"],1)['name'];
		  $bread[$x]['url']=$bread[$x-1]['url'].'/tag-'.$route["values"]["tag_id"].'-'. self::translitURL($bread[$x]['name']); 
	   } 
	
		
		
		if($route["action"]=="allCategories"){
			$x=count($bread);
			$bread[$x]['name']='Рубрики';
			$bread[$x]['url'] = $bread[$x-1]['url'] .'/all-categories';
		}
		
		if($route["action"]=="allProducers"){
			$x=count($bread);
			$bread[$x]['name']='Производители';
			$bread[$x]['url'] = $bread[$x-1]['url'] .'/all-producers';
		}
		
		if($route["action"]=="allUsers"){
			$x=count($bread);
			$bread[$x]['name']='Все организаторы';
			$bread[$x]['url'] = $bread[$x-1]['url'] .'/all-users';
		}
		
		if($route["action"]=='allSalespeople'){
			$x=count($bread);
			$bread[$x]['name']='Все продавцы';
			$bread[$x]['url'] = $bread[$x-1]['url'] .'/all-salespeople';
			
		}
		 //Site::d($route["action"]); 
		  /*Site::d($route);/*llSalespeople */ 
		
			
		self::referer($bread);
		setcookie('filter', $filter ,time()+3600,'/' );
	
			  
     	return $bread;
	}
	public static function referer($bread){
		if($_COOKIE['REFERER']){
			 $REFERER=unserialize($_COOKIE['REFERER']);
			// Site::d($REFERER,1);
             $inc=($REFERER['inc']?$REFERER['inc']:0);		 
		}else{
			 $REFERER=array();
			 $inc=0; 
			 $REFERER['inc']=$inc;
		}
		 $set=0;
		 $x= ($inc >0)?($inc- 1):3;
		 if(!empty($REFERER['bread'][$x]) and (count($REFERER['bread'][$x])==count($bread ))){
			 foreach($REFERER['bread'][$x] as $k=>$v){
				if($v!=$bread[$k]){ 
					$set=1; 
				}	
			 }
			 
		 }else{
			$set=1; 
		 }
		 
		if($set){ 
			$REFERER['bread'][$inc]=$bread;
			$inc++;
			if($inc>3)$inc=0;
			$REFERER['inc']=$inc;
			setcookie('REFERER', serialize($REFERER) ,time()+3600,'/' );
		}
	
	}
	public static function getRreferer( $inc='curr'){
		$REFERER=unserialize($_COOKIE['REFERER']);
		if($inc=='curr'){
			$inc=$REFERER['inc'];
			if($inc==0){
				$inc=3;
				if(empty($REFERER['bread'][$inc])){
					 return $REFERER['bread'][$inc];
				}
				return $REFERER['bread'][0];
			}else{
				$x= $inc-1;
				if(empty($REFERER['bread'][$x])){
					 return $REFERER['bread'][$x];
				}
				return $REFERER['bread'][$inc];
			}
			
		}
        return $REFERER['bread'][$inc]; 		
	}
	
	public static function json_breadcrumb($arr){
		 if(!is_array($arr)) return false ; 
		 
		 $result='{"@context": "http://schema.org",
				"@type": "BreadcrumbList",
				"itemListElement":[';
				
				
		$result.='{
						  "@type": "ListItem",
						  "position": 1,
						  "item":{
							"@id": "/",
							"name": "Главная"
						  }
					   },';		
		 $i=1;
		 foreach($arr as $k=>$v){
			 $i++;
			 $result.='{
						  "@type": "ListItem",
						  "position": '.($i).',
						  "item":{
							"@id": "'.$v["url"].'",
							"name": "'.$v["name"].'"
						  }
					   },';
					   
		  	 if(!$v["name"]) return''; 
		 }
		    $result=trim($result,',');
		
		$result.=']}';
		//Site::d($result,1);
		return $result; 
		
		
	}
	
	
	public static  function menu_link_users( $links)
	{
		
		array_pop($links);
		return array_reverse($links, true);
	} 
	
	
	public static function getCategoriesFromArticle($i=1){
      if($i) return 0;     
	 $query='SELECT SQL_CACHE 
		 categ_id,
         name,
         (SELECT count(*) FROM articles_categs  WHERE categ_id=cat.categ_id  )AS count 
         FROM categories_articles as cat  ORDER BY sort_id 		        
		';	
     return DB::getAssocArray($query);		
	}
	
	
	public static function getCategoriesFromDemand(){

		 $query='SELECT SQL_CACHE  demand_id ,name , 1 as count from demand WHERE flag_delete=0 AND flag=1';
		
		 $result =DB::getAssocArray($query);
		
		 foreach ($result as $k=>$v){
				$result[$k]['url']= '/demand/'.$result[$k]['demand_id'].'-'. self::translitURL($result[$k]["name"]);
		        $result[$k]['no']=1;
		 }
		 
		 return $result;
	}
	
	public static function getCategoriesFromServices($parentId=0 ,$country_id=1) {
          if(!$parentId){
            $query = 'SELECT SQL_CACHE  categ_id, name,
                      (SELECT COUNT(*) FROM services AS s WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND categ_id IN(SELECT categ_id FROM services_categs WHERE service_id = s.service_id)) AS count
                      FROM categories_services AS c WHERE (SELECT COUNT(*) FROM services AS s WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND categ_id IN(SELECT categ_id FROM services_categs WHERE service_id = s.service_id)) >0
                      ORDER BY sort_id';
					  $result=DB::getAssocArray($query);
					  foreach ($result as $k=>$v){
							$result[$k]['url']= '/services/categ-'.$result[$k]["categ_id"].'-'. self::translitURL($result[$k]["name"]);
						}		
					  
					  return $result;
					  
					  
		  }else{
			  $query = 'SELECT SQL_CACHE  city_id, name,
                (SELECT COUNT(*) FROM services WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id'. ($parentId > 0 ? ' AND service_id IN(SELECT service_id FROM services_categs WHERE categ_id = ' . $parentId . ')' : '') .') AS count
                FROM cities
                WHERE city_id IN(SELECT DISTINCT city_id FROM services WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = ' . $country_id . ')
                HAVING count > 0
                ORDER BY sort_id, name';
				
			    $result = DB::getAssocArray($query);	
				
				foreach ($result as $k=>$v){
				  $result[$k]['url']= '/services/categ-'.$parentId.'/city-'.($result[$k]['city_id']).'-'. self::translitURL($result[$k]["name"]);
			    }
              return $result; 
		  }		  
             
            
		
	}
	
	
	public  static function getCategoriesFromActivity($parentId=0){
		 $date = DB::now(1);
		if(!$parentId){
			
			
			$count = " (SELECT COUNT(*) FROM `activity` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = 1  AND IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '$date', date_start > '$date'), 1) AND activity_id
			IN(SELECT activity_id FROM `activity_categs` WHERE categ_id = categories_activity.categ_id))";
			
			$query = "SELECT categ_id, name ,($count )AS count
			 FROM `categories_activity` WHERE $count > 0 ORDER BY sort_id, name";
			
			$result= DB::getAssocArray($query);
			
			foreach ($result as $k=>$v){
				$result[$k]['url']= '/activity/sort-by-coming/categ-'.$result[$k]["categ_id"].'-'. self::translitURL($result[$k]["name"]);
			}		
			 return $result;
		}else{
			

		$query = "SELECT SQL_CACHE  cities.city_id, cities.name, COUNT(activity_id) AS count
			FROM `activity`
			INNER JOIN `cities` USING(city_id)
			WHERE activity.flag = 1 AND activity.flag_moder = 1 AND activity.flag_delete = 0 AND activity.country_id = 1 AND
			IF(activity.date_start != '000-00-00', IF(activity.date_end != '000-00-00', activity.date_end > '$date', activity.date_start > '$date'), 1)
			AND activity_id IN(SELECT activity_id FROM `activity_categs` WHERE categ_id = $parentId)
			GROUP BY cities.city_id";
		    
			$result= DB::getAssocArray($query);
			foreach ($result as $k=>$v){
				$result[$k]['url']= '/activity/sort-by-coming/categ-'.$parentId.'/city-'.$result[$k]["city_id"].'-'. self::translitURL($result[$k]["name"]);
			}		
			
			 return $result;
			
		}
		
	}
	
	
	public static  function getCategoriesFromLabs($parentId=0){			
		if(!$parentId){
			
			$query = "SELECT SQL_CACHE  categ_id,
						name, 
					   (SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = 1 AND lab_id 
					   IN(SELECT lab_id FROM `labs_categs` WHERE categ_id = categories_labs.categ_id)) AS count
				   FROM `categories_labs`
				   WHERE (SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = 1 AND lab_id 
					   IN(SELECT lab_id FROM `labs_categs` WHERE categ_id = categories_labs.categ_id))>0 ORDER BY sort_id";
			 $result=DB::getAssocArray($query);
			 foreach($result as $k=>$v){
				$result[$k]['url']= '/labs/categ-'.$result[$k]["categ_id"].'-'. self::translitURL($result[$k]["name"]);
			 }		
					  
		}else{
			
			$query = "SELECT SQL_CACHE 
						city_id,
						name,
						(SELECT COUNT(*) FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id AND lab_id
				        IN(SELECT lab_id FROM `labs_categs` WHERE categ_id =  $parentId )) AS count
					FROM `cities`
					WHERE city_id IN(SELECT DISTINCT city_id FROM `labs` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = 1)
					HAVING count > 0
					ORDER BY sort_id, name";
	
			$result=DB::getAssocArray($query);
			foreach ($result as $k=>$v){
				$result[$k]['url']= '/labs/categ-'.$parentId.'/city-'.$result[$k]["city_id"].'-'. self::translitURL($result[$k]["name"]);
			}	
		}
		return $result;
	}
	
	public static function getCategoriesFromRealty($parentId=0){
		if(!$parentId){
		
			$query = "SELECT SQL_CACHE 
						categ_id,
						name,
						(SELECT COUNT(*) FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = 1 AND categ_id = categories_realty.categ_id) AS count
					  FROM `categories_realty` where (SELECT COUNT(*) FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = 1 AND categ_id = categories_realty.categ_id) >0  ORDER BY sort_id";
			 $result=DB::getAssocArray($query);
			
			 foreach($result as $k=>$v){
							$result[$k]['url']= '/realty/categ-'.$result[$k]["categ_id"].'-'. self::translitURL($result[$k]["name"]);
			 }		
			 
			return $result;
	    }else{
		 
		  
			$where = " AND categ_id = $parentId";
		    $query = "SELECT SQL_CACHE  city_id, name,
			(SELECT COUNT(*) FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND city_id = cities.city_id $where) AS count
			FROM `cities` 
			WHERE city_id IN(SELECT DISTINCT city_id FROM `realty` WHERE flag = 1 AND flag_moder = 1 AND flag_delete = 0 AND country_id = 1 $where)
			ORDER BY sort_id, name";
			  // Site::d($query);
			  $result =DB::getAssocArray($query);
			
			foreach ($result as $k=>$v){
				  $result[$k]['url']= '/realty/categ-'.$parentId.'/city-'.($result[$k]['city_id']).'-'. self::translitURL($result[$k]["name"]);
			    }
		
		   return $result;
		 	
		}	
		
	}
    public static function countArticles(){
		$query='SELECT  SQL_CACHE  count(*) FROM `articles` WHERE  flag_delete= 0 AND flag=1';
		return DB::getColumn($query);
	}
	
}