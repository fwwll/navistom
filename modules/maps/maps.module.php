<?php
class Maps {
	public function index(){
		Header::SetTitle('Карта Navistom'); 
	 $section= Site::getSectionsList(1);
	 $modelAll = ModelMaps::getMaps();
	 

	// Site::d( ModelMaps::getSitMaps());
	
	 
	echo Registry::get('twig')->render('maps.tpl', array(
	     'section'=>$section, 
	     'modelAll'=>$modelAll 
	    )); 
	 
		
	}
	
		
	
	
	
}