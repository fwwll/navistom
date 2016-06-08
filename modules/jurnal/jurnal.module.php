<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/libraries/dom/simple_html_dom.php';
class Jurnal {
	
	public function index() {
		/*Header::SetTitle("Журнал Cтоматологічні Оголошення");
		Header::SetH1Tag("Журналы"); */
		Site::get_meta("journals");
	
	 $html = new simple_html_dom(); 
	 $html->load($this->get_web_page('http://zooble.com.ua/archive.php'));
	  
	  $str='<div class="row-jurnal">';
	  $i=2;
     foreach($html->find('div[class=journal]') as $element) {
      if($i>3){	 
         $str.= $element.'</div><div class="row-jurnal">' ;
		 $i=1;
	  }else{
		 $str.= $element; 
	  }
	   $i++;
     }

	
		
	
		
		
		echo Registry::get('twig')->render('jurnal.tpl',array('divs'=>$str ));
	}
	
	
	
	
function get_web_page( $url )
{
  $uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";

  $ch = curl_init( $url );

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
  curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
  curl_setopt($ch, CURLOPT_ENCODING, "");        // обрабатывает все кодировки
  curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
  $content = curl_exec( $ch );
  curl_close( $ch );

  return $content;
}

	
	
}