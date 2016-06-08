<?php
//error_reporting(E_ALL);
class Publications {
	public function index($filter = null) {
		
		$filter= ModelPublications::getPub();
		Site::d($filter,1);
		echo Registry::get('twig')->render('publicarions.tpl', array(
			'title' 	=> 'Объявления в журнал',
			'products'	=> ModelProducts::getProducts($filter)
		)); 
	}
	
	
}