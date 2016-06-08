<?php

class ModelPrice {
  public static function  getPrice(){
	 $price =DB::getAssocArray('SELECT
								price_cat.section_id,
								price_cat.1,
								price_cat.2,
								price_cat.3,
								price_cat.1konc,
								price_cat.2konc,
								price_cat.3konc,
								price_cat.color_yellow,
								price_cat.urgently,
								price_cat.update_date,
								price_cat.jurnal,
								ch.top as top_c,
								ch.kon as kon_c,
								ch.urgently as urgently_c,
								ch.color_yellow as color_yellow_c,
								ch.jurnal as jurnal_c,
								ch.update_date as update_date_c
								FROM price_cat INNER JOIN price_cat_checkbox AS ch ON ch.section_id=price_cat.section_id ');
	
	 
	// Site::d($price_che);
     $paceAll=array();
	 
	 
    foreach($price as $p){
	  $p['name']= Site::getNameID($p['section_id']);
	  
	  $paceAll[]=$p;
	} 
	  
	 //Site::d($paceAll) ;
	  
	 return  $paceAll;
	  
  }
	
	public  static function update($where,$col,$checkbox){
		//Site::d($col);
		$str='';
		DB::update('price_cat_checkbox',$checkbox,$where);
		// DB::update('price_cat', $col,$where);
		  foreach($col as $k =>$v){
			  $str .=",`$k`=$v "; 
		  }
		 DB::exec('update price_cat SET '. trim($str,',') .' WHERE section_id='.$where['section_id']);
	} 
}	
	