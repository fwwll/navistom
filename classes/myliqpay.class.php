<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/libraries/LiqPay/LiqPay.php';
 class MyLiqPay extends LiqPay{
	public $data;
	   protected $_public_key ='i17646855564';
       protected $_protected_key='CdnzHZyqMXJtvI5FJ6kFcm21secDo0O9epNtl5qd';
	  
	//public $path='';
	public function __construct(){
		parent::__construct($this->_public_key ,$this->_protected_key );
	}
	

	
	 public function get_data_liqpay ($params)
    {        

         $language = 'ru';
        if (isset($params['language']) && $params['language'] == 'en') {
            $language = 'en';
        }

        $params    = $this->cnb_params($params);
        $data      =''.base64_encode( json_encode($params) );
        $signature =''.$this->cnb_signature($params);
		
		
        
        return  array(
           'data'  => $data,
           'signature' => $signature
        );
             
		
	}	
	
  
	
	
	 
 }