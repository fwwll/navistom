<?php
class Payment{

	 public function index(){
	 
			$values=array(
				'data'		=> Request::post('data', 'string'),
				'signature'	=> Request::post('signature', 'string'),
				'portmone'	=> json_decode(Request::post('portmone', 'json') ,true)
			);
			
			echo Registry::get('twig')->render('liqpay.tpl', $values);
		
		}
	
	

	
		public function getliqpay(){


			$str= json_encode($_POST);
			$data=Request::post('data', 'string');
			 if(!$data){
				Header::Location($_SERVER['HTTP_REFERER']);
				die;
			} 

			$data=json_decode(base64_decode($data),true);
			ModelPayment::statusLiqpay($data) ;	
		}
	
		public function getportmane(){
		
			$data=Request::post('SHOPORDERNUMBER','string');
			$str3= json_encode($data);
			
			if(!$data){
				Header::Location('/');
			} 
			
			  $result=ModelPayment::statusPortmone($data) ;  
			if($result){
				Header::Location($result);
			}
		
	}
	
	
	
	public function update_top(){
		$vip=Request::post('vip', 'int');
		$vip or Header::location('/') or die;
		$resource_id =Request::post('resource_id', 'int');
		$section_id=Request::post('section_id', 'int');



		$link = Request::post('link', 'string');
		$url = parse_url($link);
		$url= explode('/' ,trim($url['path'],'/'));

		$data=ModelPayment::startPayments($resource_id , $section_id);       
		$values=array(

				'signature'	=> $data['send_data']['signature'], 
				 'portmone'=> json_decode($data['portmone'],true),
				 'data'=>$data['send_data']['data'] 
			); 
			
		echo Registry::get('twig')->render('liqpay.tpl',$values );
	}
	
	public function success($order_id){
		//4352-4
		
		  
		$section= explode('-',$order_id);
		if( !$section[1]) Header::Location('/404');
		
		
		
		$result= ModelPayment::is_option($section);
		 $img_data= Site::get_jurnal_image($section[1],$section[0]); 
		 $tel= explode(',', $img_data['phones']);
		 $img_data['phones']= preg_replace('#\D#','',$tel[0]);
		$result['img_j']=$img_data;
		$price =Site::getPriceCategoriy($section[1]);
		// Site::d($img_data);
		$price_json=Site::dataJsoneString ($price);
		$chekbox= Site::getPriceCategoriyCheked($section[1]);
		if(!$chekbox) Header::Location('/404');
		
		
		$result['price']=$price;
		$result['checkbox']=$chekbox;
		$result['price_json']=$price_json;
		
		$result['count_tpl']=0;
		$flags= Request::post('description','string' );
	    if( !empty($flags)){
              
			$result['count_tpl']=1;
			
			$result['fields']= ModelPayment::getFields();
			//Site::d($result['fields']); 
		}
		 
		
		  
		 
		switch($section[2]){
			case 'add': $status='Ваше объявление ДОБАВЛЕНО на NaviStom';  break; 
			case 'up': $status='Отредактировано';  break;
			case 'payment': $status='Ваша оплата  принята';  break;
			case 'adddate': $status='Сейчас выше всех в списке';  break;
			case 'top': $status='Рекламировать';  break;
			case 'extend': $status='Продлить на 50 дней';  break;
			default: Header::Location('/404');
		 
		 
		 }
		
		$name= $result['add']['product_name'];
		$resurs= $result['add']['name'];
		$result['pay']=1; 
		$result['section']=$section[1];
		 
		   
	     if(($section[1]==4 or $section[1]==3 ) and Site::pay($section[0],$section[1])==false){
			 $name_lab=($section[1]==4)? 'Б/У':'Новое'; 
			$status="Рекламировать | Разместить в Продам $name_lab на 50 дней";
			$label2 ="<div class='informer'>
			Это объявление видите только Вы, как автор.
			Посетители увидят его после оплаты продвижения
			<span>Минимальная оплата 28 грн, максимальная - на Ваше усмотрение.</span>
			</div>";
			
			$result['pay']=0; 
			$result['label2'] =$label2;
		 }
		$label= $status .'</br><span class="res_l">'.$name.', '.$resurs.'</span></br>'; 
		
        $result['label'] =$label;
		$result['count']= array_sum(Statistic::getContentsCount(null, null, Request::get('country'), 1));
	 
  		   if($_GET['tp']=='new'){
	            
			 $tpl_name='success-new2.tpl';  
		   }else{
			 $tpl_name='success-new2.tpl';   
		   }
		 
		 //Site::d($tpl_name);
		echo Registry::get('twig')->render($tpl_name,$result);
		
	}


	public  function  vipRequest($section_id, $resource_id){
	  
		$url='/success-'.$resource_id.'-'.$section_id.'-top';
		Header::Location($url);
	  
	  
	  
	  
	}
	
	
	public  function form_success(){
		$resource_id=Request::post('resource_id','int');
		$section_id=Request::post('section_id','int');
		$result=ModelPayment::startPayments($resource_id,$section_id);
		echo json_encode($result);
	

	} 
 public function color(){
	 if (User::isAdmin()){
		echo  ModelPayment::isColor();
	 }
	 
 } 
	
}