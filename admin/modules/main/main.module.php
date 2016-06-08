<?php

class main {
	public function index() {
		$vipAds = ModelMain::getVipAds();
		$vipAdsCounts = array('ua' => 0, 'ru' => 0, 'by' => 0);
		
		foreach ((array)$vipAds as $key => $value) {
			for ($i = 0, $c = count($value); $i < $c; $i++) {
				if ($value[$i]['days_left'] <= 7) {
					$vipAdsCounts[$key]++;
				}
			}
		}
		
		echo Registry::get('twig')->render('main.tpl', array(
			'title'     => 'Панель управления NaviStom.com',
            'items'     => ModelMain::getVipRequests(),
            'vipads'    => $vipAds,
            'count'		=> $vipAdsCounts
		));
	}

    public function test() {
        print __METHOD__;
    }

	public function logout() {
		Request::removeSession('_USER');
		Request::removeSession('_ADMIN');
		
		Request::setCookie('_aut_key', '');
		
		Header::Location('/');
	}
	
	
	public function payment(){
		User::isAdmin() or die;
		
		$month= [		
		'1'  => 'Январь',
		'2'  => 'Февраль',
		'3'  => 'Март',
		'4'  => 'Апрель',
		'5'  => 'Май',
		'6'  => 'Июнь',
		'7'  => 'Июль',
		'8'  => 'Август',
		'9'  => 'Сентябрь',
		'10' => 'Октябрь',
		'11' => 'Ноябрь',
		'12' => 'Декабрь'
		];

        $result_t=ModelMain::getCountPayment(  ); 
		
		if(Request::post('admin') and !Request::post('portmone')  and !Request::post('liqpay'))
		{
			$result_t[0]["all_sum"]=0;
	    }
		 //$c= ModelMain::getPayment();
	    $count=count($result_t) ; 
	
        $page= Request::get('page');
		$flag=0;
		if(Request::get('user')){
			$flag=1;
		}
		if($page==1){
			 Header::location('/admin/payment/');
		 }
		//$page=($page)? $page:1; 
		
		 
		
		$limit =40;
		$pagination	= Site::pagination( $limit, $count, $page);
		//Site::d($pagination);
		if($page>1){ 
		  $start = ($page*$limit);
		}else{
		  $start=0;	
		}
		
		$page_count =0;
	
		
		 if($limit< $count){
			$page_count =(int)($count /$limit) ;
		 } 
        $result=array();   
	    $result = ModelMain::getPayment($start ,$limit);
	
	
		
		$archive=Request::post('archive','int');	 
		  foreach($result as $k=>$v){
			  $result[$k]['add_data']=  date('d.m.Y' ,strtotime($result[$k]['add_data']));
			  $result[$k]['d']= Site::remaining_time( $result[$k]["date_end"]);
			  $result[$k]['co']= Site::remaining_time($result[$k]['end_competitor']);
			  $result[$k]['section']= Site::getNameID($result[$k]['section_id']);
			  $result[$k]['list_price'] = array_flip(Site::getPriceCategoriy($result[$k]['section_id']));
			
			//  Site::d($result[$k]['list_price'],1);
			    if(!is_null($result[$k]["date_end"])){
			   // $result[$k]['d']= Site::remaining_time( $result[$k]["date_end"]);
				}else{
				//$result[$k]['d']='stop';	
				}
			   if($v["link"]=='/labs'){
				   $result[$k]["link"]='/lab';
			   } 
			   if($v["link"]=='/products'){
				   $result[$k]["link"]='/product';
			   } 
			   if($v["link"]=='/services'){
				   $result[$k]["link"]='/service';
			   } 
			   
			   
			    if($archive and !$v['price'] ){
				 
					// unset($result[$k]);
				}
			   
			   
		  }
        //   Site::d($result);
		echo Registry::get('twig')->render('payment.tpl', [ 'all'=>$result ,'month'=>$month, 'sum'=>$result_t,'pagination'=>$pagination,'flag'=>$flag] );
	
	
	}
	
	
	/* public function paymentIndex (){
	    $filter= Request::post('filter', 'string');
		$filter= parse_str($filter);
		var_dump($filter);die;
	    $result = ModelMain::getPayment();
		
		echo Registry::get('twig')->render('payment.tpl',$result );
	
	
	} */
	
}

?>