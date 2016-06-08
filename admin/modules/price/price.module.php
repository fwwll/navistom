<?php
//error_reporting(E_ALL);
class Price {
	public function index() {
	   echo Registry::get('twig')->render('price.tpl', array(
		'price'=>ModelPrice::getPrice()));
	   
	}
	
	
	public function update() {
		
		$section_id= Request::post('section_id', 'int');
		$name= Request::post('name');
		$pric= Request::post('pric', 'int');
		$checked= Request::post('checked', 'int');
		
	
		$where=array('section_id'=>$section_id);
		$col=array( $name =>$pric);
		
		 if($name==='1konc' or $name==='2konc' or $name==='3konc'){
			$name='kon';
		 } 
		 
		 if($name==='1' or $name==='2' or $name==='3'){
			$name='top';
		 } 
		 
		$checkbox=array($name=>$checked);
		 ModelPrice::update($where, $col,$checkbox);
		
	   
	}
	
}