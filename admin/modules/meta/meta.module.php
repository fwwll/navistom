<?php
class Meta{
	
	public  function index($page_name){
		
		      if(Request::post('pages', 'string')){
				  
				 $this->saveMeta();  
			  }
			  
	    	 $meta= Site::get_meta(trim($page_name),1);
			 
			
	       
			echo Registry::get('twig')->render('meta.tpl', compact('meta')); 
				
	
	}
	
	public function edit(){
		$form = new Form();
		$form->createTab('reclama-default', 'Îñíîâíàÿ èíôîğìàöèÿ');
		$form->create('text', 'name', 'Çàãîëîâîê ğåêëàìû', null, 'reclama-default');
		
		
		
	}
	
	
	private  function saveMeta(){
		 
      $description=Request::post('description', 'string') ; 
	  $keywords=Request::post('keywords', 'string') ; 
	  $keywords=Request::post('title', 'string') ; 
	  $h1=Request::post('h1', 'string') ; 
	  $title=Request::post('title', 'string') ;
	  $pages=Request::post('pages', 'string') ;
	   
	  Site::set_meta([
		'description'=>$description,
		'keywords'=>$keywords,
		'title'=>$title,
		'h1'=>$h1,
		'pages'=>$pages
	  ],$pages);
	  
    } 
	
}
