<?php
class ModelMaps{
	public static function sub($arr, $section){
	   $all_s=[];
	   foreach($arr as $v){
		   $all_s[$v['categ_id']] = Site::getCategoriesFromSelect($v['categ_id'], $section);
	   }
	   
	   return $all_s; 
   }
	
	public static function  getMaps() {
		
	
		
	  
      $ModelAll['4']=Site::getCategoriesFromSelect(0,4);
	//Site::d(Site::getCategoriesFromSelect(0,4));
	   $ModelAll['4']['sub_categs']=static::sub($ModelAll['4'], 4 );
	   
	   $ModelAll['3']=Site::getCategoriesFromSelect(0,3);
	   $ModelAll['3']['sub_categs']= static::sub($ModelAll['3'], 3 );
	   
	   $ModelAll['2']=Site::getCategoriesFromSelect(0,2);
	   $ModelAll['2']['sub_categs']=static::sub($ModelAll['2'], 2 );
	   
	   $ModelAll['9']=ModelServices::getCategoriesFromSelect(1);
	   $ModelAll['11']=0;
	   $ModelAll['5']=ModelActivity::getCategoriesFromSelect(1);
	   $ModelAll['6']=ModelWork::getCategoriesFromSelect(true);
       $ModelAll['15']= ModelWork::getCategoriesFromSelect(true, true);
	   $ModelAll['7']= ModelLabs::getCategoriesFromSelect(1);
	   $ModelAll['8']=  ModelRealty::getCategoriesFromSelect(1); 
	   // Site::d($ModelAll['3']['sub_categs']);
	 $ModelAll['16'] = ModelArticles::getCategoriesList();
	   return $ModelAll;
	 
    }

   public static function translitURL( $name){
	   
	   return Str::get($name)->truncate(60)->translitURL(); 	   
	}
   
   public static function getSitMaps(){
	 $domain='http://navistom.com/';
	 $d='/';
	 
	
	 
	 $section=array(
			2=>array('url'=>$domain. Site::getSectionUrlById(2).$d), 
			3=>array('url'=>$domain. Site::getSectionUrlById(3).$d), 
		    4=>array('url'=>$domain. Site::getSectionUrlById(4).$d),
			5=>array('url'=>$domain. Site::getSectionUrlById(5).$d),
			6=>array('url'=>$domain. Site::getSectionUrlById(6).$d),
			7=>array('url'=>$domain. Site::getSectionUrlById(7).$d),
			8=>array('url'=>$domain. Site::getSectionUrlById(8).$d),
			9=>array('url'=>$domain. Site::getSectionUrlById(9).$d),
			11=>array('url'=>$domain. Site::getSectionUrlById(11).$d),
			15=>array('url'=>$domain. Site::getSectionUrlById(15).$d),
			16=>array('url'=>$domain. Site::getSectionUrlById(16).$d) 	
		);
		
	$all=[];	
	$ModelAll= static::getMaps();
	  
	  
	  
	  
	  
    foreach($section as $key=>$val){
		  
	
		if( is_array($ModelAll[$key])){
			foreach($ModelAll[$key] as $k=>$v ){
			  if($v["count"]){
				  $all[]= [
						'loc'=> $val['url'].'categ-'.$v["categ_id"].'-'.self::translitURL($v['name']),
						'lastmod'	=> DB::now(1), 
						'priority'	=> "0.8"
						];
			  }					  
			}
		  }
		  
		if( is_array($ModelAll[$key]['sub_categs'])){
			foreach($ModelAll[$key]['sub_categs'] as $k=>$va ){

				foreach($va as $k=>$v){
					if($v["count"]){
						$all[]= [
							'loc'=> $val['url'].'categ-'.$v["categ_id"].'-'.self::translitURL($v['name']),
							'lastmod'	=> DB::now(1), 
							'priority'	=> "0.8"
						  ];
			  }				
				}
			} 
		}  
		  
	  }
	  
	  
	  return $all; 
	  
	  
	   
   }
   
   
	
}	
			


