<?php
class Reclama {
	
	public  function index(){
		Header::SetTitle("Размещение рекламы на стоматологическом портале NaviStom");
		$reclama= ModelReclama::reclama();
	    
		 
	    if(!$_POST['content']){
			echo Registry::get('twig')->render('reclama.tpl',$reclama); 
		}else{
			  
			ModelReclama::save(); 
		}
	}
	
}
