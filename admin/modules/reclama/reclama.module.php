<?php
class Reclama {
	
	public  function index(){
		
		$reclama= ModelReclama::reclama();
	    
		 
	    if(!$_POST['content']){
			echo Registry::get('twig')->render('reclama.tpl',$reclama); 
		}else{
			  
			ModelReclama::save(); 
		}
	}
	
	public function edit(){
		$form = new Form();
		$form->createTab('reclama-default', '�������� ����������');
		$form->create('text', 'name', '��������� �������', null, 'reclama-default');
		
		
		
	}
}
