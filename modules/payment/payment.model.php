<?php
include_once MODULES.'/users/users.module.php';
include_once MODULES.'/users/users.model.php';

class ModelPayment{

	public static function startPayments($resource_id,$section_id){
	
		$curr_prace =Site::getPriceCategoriy((int)$section_id);
	   
		$vip=Request::post('vip', 'int');
		
		$day =array('1'=>'30','2'=>'20','3'=>'10','1konc'=>'30','2konc'=>'20','3konc'=>'10');
		     
		if($vip){
			$description=array(1=>'Топ на '.($day[ $vip]).' дней');
			
		}else{
			$description=array();
			$vip=0;
		}
		
		
		$fields='';
		//if($vip)$fields= $vip.'|'; 
        if(Request::post('color_yellow','int')){
			$add_op['color_yellow'] =$curr_prace['color_yellow'];
			$description['color_yellow']='+Золотой';
			//$fields .='yellow|';
			
		}
			
		if(Request::post('urgently','int')){
			$add_op['urgently']=$curr_prace['urgently'];
			$description['urgently']='+Cрочно';
			//$fields .='urgently|';
		}
		
		if( Request::post('show_competitor')){
			$su=trim(Request::post('show_competitor'));
			$add_op['show_competitor']=$curr_prace[$su];
			$description['show_competitor']='+ В конкурентах на '.($day[ $su]).' дней';
		    $conku=$su;

		}else{
			$conku=0;
		}
		
		if( Request::post('update_date','int')){
			 
			$add_op['update_date']=$curr_prace['update_date'];
			$description['update_date']='+ Поднять вверх';
			

		}
		
		$p_jurnal=Request::post('jurnal');
	//	$jurnal=array('jurnal1'=>98,'jurnal2'=>800,'jurnal3'=>1350,'jurnal4'=>2250,'jurnal5'=>3500);
		
		if( $p_jurnal ){
               
			$jurnal_label='Объявление с фото';
			$add_op['jurnal_cat'] =1;					
			$add_op['jurnal']= $curr_prace['jurnal'];
			$description['jurnal']='+'.$jurnal_label;
			

		}else{
			$add_op['jurnal']=0;
		}
		

		$liqpay = new MyLiqPay;
		  
		$order_id = array(0=>$resource_id,1=>$section_id,2=> date("d/m/Y_H:i:s"),3=>$vip ,4=>$conku);
		$description =implode("|",$description);
		$order_id = implode('~', $order_id);
		$status= ($vip)?'-add':'-payment';
		$url= '/success-'.$resource_id .'-'.$section_id.$status;
           
		$price=($curr_prace[$vip])+($add_op['show_competitor'])+($add_op['urgently'])+($add_op['color_yellow']) + ($add_op['update_date'])+($add_op['jurnal']);

		$play_t= array(
		  'version'		=> '3',
		  'amount'		=> $price,
		  'currency'	=> 'UAH',
		  'description'	=> $description,
		  'order_id'	=> $order_id,
		  'result_url'	=> HOST.$url, 
		  'server_url'  => HOST.'/getliqpay',
		 'pay_way'		=>'card,delayed, privat24', 
		 );
		
		if($_COOKIE["volo"]){
			$play_t['sandbox']=1;
		}
		
		$send_data = $liqpay->get_data_liqpay($play_t);
		
		if($_COOKIE["volo"]){
			$price=1;
		}
						 
						  
		$portmone=json_encode(array(
			'payee_id'         	=> Registry::get('config')->portmone_id,
			'shop_order_number'	=> $order_id,
			'bill_amount'      	=>  $price, 
			'description'		=> $description,
			'success_url'		=> HOST.'/getportmane',
			'failure_url'		=> $_SERVER['HTTP_REFERER'],
			'lang'				=> 'ru',
			'encoding'			=> 'UTF-8'
			
		)); 			 
					  
		if($vip){			 
			DB::insert('liqpay_status', array(
				'ads_id'         => $resource_id,
				'price'          => $curr_prace[ $vip],
				'status'     	 => null ,
				'order_id'		 => $order_id,
				'section_id' 	 => $section_id,
			));	
		}


		$insert['price'] = $curr_prace[ $vip];
		$insert['order_id'] = $order_id;
		$insert['status'] = $data['status'];
		$insert['section_id'] = $section_id;
		$insert['ads_id'] = $resource_id;
		$insert['color_yellow']= $add_op['color_yellow'];
		$insert['urgently']= $add_op['urgently'];
		$insert['show_competitor']= $add_op['show_competitor'];
		$insert['update_date']= $add_op['update_date'];
		$insert['jurnal']= $add_op['jurnal'];
		$insert['jurnal_cat']=$add_op['jurnal_cat'];
		
		DB::insert('payment_all', $insert);		

        if($vip){
			$table= Site::getSectionsTable($section_id);
			$column= Site::getSectionsTableIdName($section_id);		
		}
	
		return ['send_data'=>$send_data,'portmone'=>$portmone];
	}
	
	
	
	
	public static function statusliqpay($data){
		
	    $id_data =explode('~',$data['order_id']);
	    $up=Request::post('up', 'int'); 
		$where= array(
			'ads_id' =>$id_data[0],
			'section_id'=>$id_data[1]
		);
		
	
		if(($data['status']=='success' or $data['status']=='wait_accept' or $data['status']=='sandbox')and !empty($data['order_id']))
		{    
			
		   DB::update('liqpay_status',  array('status' => trim($data['status']),'service_payment'=>'liqpay','section_id'=>$id_data[1]),$where);	
		   self::log_payment($id_data,$data,'liqpay');
			
			if((int)$id_data[3])
			{   
				$time=Site::setTimeOut($id_data[3] , array($id_data[0],$id_data[1])); 
				$curr_time=$time['curr_time'];
				$time_end= $time['time_end'];
				
				Users::addToTopMainPayment(array( 
				  'section_id'	=> $id_data[1],
				  'resource_id'	=> $id_data[0],
				  'curr_time'	=> $curr_time,
				  'time_end'    => $time_end
				));
			}								
	self:: flagModer($id_data[0] ,$id_data[1]);
		return true;
		}
			  
	}
	



	public static function statusPortmone($data){
	   
		
		if(!empty($data))
		{ 
			$id_data =explode('~',$data);
			
			$where= array(
				'ads_id'    => $id_data[0],
				'section_id'=> $id_data[1]
			);
			
			$status = self::parser_status(array(
				'method' 	=> 'result',
				'payee_id' 	=> Registry::get('config')->portmone_id,
				'login'  	=> Registry::get('config')->portmone_login,
				'password'	=> Registry::get('config')->portmone_pass,
				'lang'		=>'ru',
				'server_output' => 'UTF-8',
				'shop_order_number' => trim($data)

			));
			  
			if('REJECTED'==trim($status->status))die('REJECTED');
			
			 
			$ob= DB::update('liqpay_status',  array('status' => $status->status,'service_payment'=>'portmone'),$where);
			
			self::log_payment($id_data, array('status' => $status->status, 'order_id'=>trim($data)),'portmone');

			
			if((int)$id_data[3]){				
			$time=Site::setTimeOut($id_data[3],array( $id_data[0],$id_data[1]));
			
			$curr_time=$time['curr_time'];
			$time_end= $time['time_end'];

			Users::addToTopMainPayment(array(
				  'section_id'	=> $id_data[1],
				  'resource_id'	=> $id_data[0],
				  'curr_time'	=> $curr_time,
				  'time_end'    => $time_end
			));
			}
			self:: flagModer($id_data[0] ,$id_data[1]);
			$st= ($id_data[3])?'-add':'-payment';
			$url= '/success-'.$id_data[0] .'-'.$id_data[1].$st;
			return $url;
			
	}
  }
  
  
	public  static function parser_status( $data ){
	
		$url="https://www.portmone.com.ua/gateway/"; 
		$postfields  = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $server_output = curl_exec($ch);
        curl_close($ch);
		
		
		  
		  
		$xml=simplexml_load_string($server_output); 
		
		return $xml->orders->order;
	  
	}
	
	
	public static function  insertAdmin($resource_id,$section_id, $vip=4){
		
		$order_id = array(0=>$resource_id,1=>$section_id,2=> date("d/m/Y_H:i:s"),3=>$vip);
		$order_id = implode('~', $order_id);
		$where= array(
			'ads_id' =>$resource_id,
			'section_id'=>$section_id
		 );
	
		$count=DB::getTableCount("liqpay_status",$where);
		if($count){ 
			DB::update('liqpay_status',  array('status' => 'success','service_payment'=>'admin','price'=>0),$where);
        }else{
	
			DB::insert('liqpay_status', array(
				'ads_id'     => $resource_id,
				'price'      => 0,
				'status'     => 'success',
				'order_id'	 => $order_id,
				'section_id' => $section_id,
				'service_payment'=>'admin'
			));	 		 
			
		}
		
		self::updateDateAdd($resource_id ,$section_id);
		
	
	}
	
	
	public  static function log_payment($id_data,$data ,$service_payment){
		
		if(!empty($id_data[4])){
			$time =Site::setTimePayment($id_data[4],array( $id_data[0],$id_data[1]));
			$update['start_competitor']=$time['start_competitor'];
			$update['end_competitor']=$time['end_competitor'];
		}
		
		$update['status'] = $data['status'];
		$update['date_add']=DB::now(1);
		$update['service_payment']=$service_payment;
		$order_id =trim($data['order_id']);
		$where= array(
			'order_id'	=>$order_id 	
		);
			
		DB::update('payment_all',$update ,$where);

		$select= DB::getAssocArray('SELECT 
									color_yellow,
									price,
									urgently,
									show_competitor,
									start_competitor ,
									end_competitor,
									service_payment,
									update_date,
									jurnal
									FROM  `payment_all`  
									 WHERE order_id="'.$order_id.'"'  ,1);
		$liq=array();
    $add_jurnal=1;		
		foreach($select as $k=>$v ){
			if($v){
			  if($k!=='jurnal'){
					if($k=='update_date'){
						self::updateDateAdd($id_data[0] ,$id_data[1]);
					}else{
						$liq[$k]=$v;
					}
					
					if($k=='price' and $add_jurnal and $v ){
						 Site::add_jurnal_public($id_data[0] ,$id_data[1]);
					    $add_jurnal=1;
					}
					
			   }else{
				  if($data['status']!=='admin' and $add_jurnal  ){
					  Site::add_jurnal_public($id_data[0] ,$id_data[1]);
					  $add_jurnal=1;
				    }  
			   }		
			}
					
		}
		
		 self::updateDateAdd($id_data[0] ,$id_data[1]);
		   
					
			$where=array(
						'ads_id'    => $id_data[0],
						'section_id'=> $id_data[1]
									);
		$count=DB::getTableCount("liqpay_status",$where);
		if($count){
			
			$flag= DB::update('liqpay_status',  $liq , $where);
		}else{
			
	        $liq['ads_id'] =$id_data[0];
			$liq['order_id'] =$order_id;
			$liq['section_id'] =$id_data[1];
			DB::insert('liqpay_status',$liq);
			 		
		}

        if($id_data[1]==4){
			DB::update('ads',  array('pay'=>1) , array('ads_id'=>$id_data[0]));
		}
		
		if($id_data[1]==3){
			DB::update('products_new',  array('pay'=>1) , array('product_new_id'=>$id_data[0]));
		}  		
      
	}
	
	
 public static	function is_status($status){
	
    switch($status){
		case 'success': return true;
		case 'wait_accept': return true;
		case 'sandbox': return true;
		case 'PAYED': return true;
		case 'CREATED': return true;
		default: return false;
	} 

 } 

	public static function is_option( $section ,$add=1){
        $all=array();
		$select= DB::getAssocArray('SELECT
									liq.ads_id,	
									liq.color_yellow,
									liq.urgently,
									liq.show_competitor,
									liq.start_competitor ,
									liq.end_competitor,
									li.date_end
									FROM  liqpay_status liq LEFT JOIN light_content li ON  liq.ads_id =li.resource_id AND liq.section_id=li.section_id
									WHERE  liq.ads_id='.$section[0]. '
									and liq.section_id="'.$section[1].'"'  ,1); 
									
		$name_id=Site::getSectionsTableIdName($section[1]);
		$all['link']=Site::getSectionsUrlByType($name_id);		  
		 
		$table =Site::getSectionsTable($section[1]);
			
		$query='SELECT
				resource_id, 
				tabl.product_name,
				tabl.section_id,
				sections.link ,
				sections.name
					
					FROM (
						(
						 SELECT
							 prod.product_new_id  AS resource_id,
							 prod.product_name as content,
							 prod.user_id ,
							  prod.product_name,
							  
							 3 as section_id
							 FROM products_new AS prod
						)UNION(
						
						 SELECT 
							ads.ads_id ,
							ads.content,
							ads.user_id,
							ads.product_name,
							4 as section_id
							FROM ads
								
							
								
					  )UNION(
						SELECT
							activity.activity_id,
							activity.name,
							activity.user_id,
							activity.name,
							5 as section_id
							FROM activity
							
							
					  )UNION(
						
						SELECT
							lab.lab_id,
							lab.content,
							lab.user_id,
							lab.name,
							7 as section_id
							FROM labs AS lab
					  )UNION(
						SELECT
							 realty.realty_id,
							 realty.name,
							 realty.user_id,
							 realty.name,
							8 as section_id
							 FROM realty 	
					  )UNION(
						SELECT
							 services.service_id,
							 services.name,
							 services.user_id,
							 services.name,
							 9 as section_id
							 FROM services
			
					  )UNION(
					  
						SELECT
							demand.demand_id,
							demand.name,
							demand.user_id,
							demand.name,
							11 as section_id
							FROM demand
					  
					  

					)UNION(
					  
						SELECT
							vacancies.vacancy_id,
							vacancies.search_name,
							vacancies.user_id,
							CONCAT(vacancies.city_name, ",",vacancies.search_name ) as name ,
							15 as section_id
							FROM vacancies
					)UNION(
					  
						SELECT
							work.work_id,
							work.user_name,
							work.user_id,
							CONCAT(work.name,", ", work.user_name," ", work.user_surname) as name ,
							6 as section_id
							FROM work
					  )
					  

					) as tabl 
					LEFT JOIN  sections  ON tabl.section_id =sections.section_id 
					WHERE resource_id="'.$section[0].'" and  tabl.section_id="'.$section[1].'"
			';
			

		$all['add']=DB::getAssocArray($query,1);
			
		$curr_date=DB::now(1);
		$month =Site::month(); 
	
		if($select['date_end']< $curr_date){
			$select['date_end']=0; 
		}else{
			    // site::d(Site::remaining_time($select['date_end']));
				
			$date=explode('-',Site::date_format($select['date_end']));
			//$date[1]= $month[$date[1]];
		    $select['date_t']= $date[2].'.' ;
			$select['date_t'].=' '.$date[1].'.'; 
			$select['date_t'].=' '.$date[0];
		    //$select['date_t']= Site::remaining_time($select['date_end']);
		} 
		
		 
		
		if($select['end_competitor']< $curr_date){
			$select['show_competitor']=0; 
		}else{
			
			$date=explode('-',Site::date_format($select['end_competitor']));
			
		    $select['show_comp']= $date[2].'.' ;
			$select['show_comp'].=' '.$date[1].'.'; 
			$select['show_comp'].=' '.$date[0];
		
		} 
	
			
		if($redirect){
			Header::location($all['add']['link']);
		}
        if($all['add']['link']=='/products'){
		   $all['add']['link']='/product';  
		 }
		 if($all['add']['link']=='/services'){
		   $all['add']['link']='/service';  
		 }
		if($all['add']['link']=='/labs'){
		   $all['add']['link']='/lab';  
		 } 
		
	   $all['select']=$select;

	   return $all ;
	}
	
	public static function updateDateAdd($resource_id ,$section_id) {
		
			$table=Site::getSectionsTable($section_id);
			$resource_id_name=Site::getSectionsTableIdName($section_id); 

			if($table == 'products_new') {
				DB::update('stocks', array(
					'date_add'			=> DB::now()
				), array(
					'product_new_id'	=> $resource_id
				));
			}
			
			DB::update($table, array(
				'date_add'			=> DB::now(),
				'is_update'			=> 0
			), array(
				$resource_id_name	=> $resource_id
			));

	}
	
	
	public static function flagModer($resource_id ,$section_id){
		
		
		$table=Site::getSectionsTable($section_id);
			$resource_id_name=Site::getSectionsTableIdName($section_id); 

			if($table == 'products_new') {
				DB::update('products_new', array(
					'flag_moder'	=>1,
					'flag'=>1
				), array(
					'product_new_id'	=> $resource_id
				));
			}
			 
			DB::update($table, array(
				'flag_moder_view'	=>0,
				'flag'=>1
			), array(
				$resource_id_name	=> $resource_id
			));
		
	}  
	
	public static function isColor(){
		
		$section_id=  Request::post( 'section_id','int' );
		$resource_id=Request::post( 'resource_id','int' );

		$update=Request::post( 'update','int' );
		if($update){
			$color_yellow=Request::post( 'color_yellow','int' );
			$urgently=Request::post( 'urgently','int' );
			$show_competitor=Request::post( 'show_competitor','int' );
			$start_competitor=Request::post( 'start_competitor','string');
			$end_competitor=Request::post( 'end_competitor','string');
			$pay=Request::post( 'pay','int' );
			
			if($section_id==4 ){
				$set_pay=array(); 
				$set_pay['pay']=$pay;
				
				 DB::update('ads',  $set_pay ,array('ads_id'=>$resource_id));
			} 
			
			
			if($section_id==3 ){
				$set_pay=array(); 
				$set_pay['pay']=$pay;
				
				 DB::update('products_new',  $set_pay ,array('product_new_id'=>$resource_id));
			} 
			
			
			 $update=array();
			if($color_yellow ){
				$update['color_yellow']=$color_yellow;
			}else{
				$update['color_yellow']=0;
			}
			if($urgently ){
				$update['urgently']=$urgently;
			}else{
				$update['urgently']=0;
			}
			if($show_competitor ){
				$update['show_competitor']=$show_competitor;
				
				if(!$start_competitor){
				        $time= Site::setTimePayment(1);
			             $update['start_competitor']=$time['start_competitor'];
						 $update['end_competitor']=$time['end_competitor'];	
			    }
				
				
			}else{
				$update['show_competitor']=0;
			}
			
			
			if($start_competitor){
			  $update['start_competitor']=$start_competitor;
			
			}
			
			if($end_competitor){
			  $update['end_competitor']=$end_competitor;
			
			}
			
			
			 $update['status']='success';
			$where=array('section_id'=>$section_id,'resource_id'=>$resource_id);
	
			$order_id = array(0=>$resource_id,1=>$section_id,2=> date("d/m/Y_H:i:s"),3=>$vip);
			$order_id = implode('~', $order_id);
			$where= array(
				'ads_id' =>$resource_id,
				'section_id'=>$section_id
			);
			$ins =$update;
			$ins['ads_id']=$resource_id;
			$ins['section_id']=$section_id;
			$ins['order_id']=$order_id;
			$ins['service_payment']='admin';
			$count=DB::getTableCount("liqpay_status",$where);
			
			
	
			
			DB::insert('payment_all', $ins);	 		 
			
			
			
			
			
			if($count){ 
				$f= DB::update('liqpay_status',  $update ,$where);
			}else{

				DB::insert('liqpay_status', array(
					'ads_id'     => $resource_id,
					'price'      => 0,
					'status'     => 'success',
					'order_id'	 => $order_id,
					'section_id' => $section_id,
					'service_payment'=>'admin'
				));	 		 
				$f= DB::update('liqpay_status',  $update ,$where);
			}
	
	
	    //var_dump($update) ; die;
			
		}else{  
			$sql='SELECT color_yellow, urgently, show_competitor,start_competitor, end_competitor FROM liqpay_status WHERE section_id='.$section_id .' AND  ads_id='.$resource_id ;	
			$result= DB::getAssocArray( $sql) ;
			$result=$result[0];
			
			
			if(count($result)){
				$result['success']=1;
			}else{
				$result['success']=0;
			}
			
			if($section_id==4){
				$ads= DB::getAssocArray("SELECT pay FROM ads WHERE ads_id=$resource_id") ;
				$ads=$ads[0];
				$result['pay']=$ads['pay'];
				
			}
			
			
			if($section_id==3){
				$ads= DB::getAssocArray("SELECT pay FROM products_new WHERE product_new_id=$resource_id") ;
				$ads=$ads[0];
				$result['pay']=$ads['pay'];
				
			}
			
			
			
			
			return json_encode($result);
		} 
		  
		self::updateDateAdd($resource_id ,$section_id);
	}


	

 public static function getFields(){
	 
	 $order= Request::post( 'shop_order_number','string');
	 
	// Site::d($order);
	 if(empty($order)) return 0; 
	 $sql='SELECT  price ,color_yellow, urgently, show_competitor,jurnal_cat, update_date FROM payment_all WHERE order_id="'.$order.'"';
	 $result= DB::getAssocArray( $sql);
	 //Site::d( $result);
	
	$result=$result[0]; 
	 foreach((array)$result as $k=>$v){
		if($v<1){
		  $result[$k]=0; 			
		}
	 }
	   
	  return  $result;
 }
}