<?php

class Menu {
	
	public function index() {
		
		 $url=Request::post('url', 'string');
		 if($url){
			 $menu=ModelMenu::getMenuScrol($url);
			 $menu= json_encode($menu);
			 ModelMenu::setCashMenu($menu,$url);
			//Site::d($menu);
			 echo $menu;
		 }else{
			 header('Location: /404');
		 }
	}
	
	
}