


<?php



/* if(preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/", 'volodarik@@list.ru')){
	echo'ok';
}else{
	
	echo'dfd';
}
 */

die();
session_start() ;
$name=str_replace('/','',$_SERVER['REQUEST_URI']) ; 

function  cashe_f5_start($name){
	session_start() ;
	 $ts = time();

	$time_ob_user= ( empty($_SESSION["time"]))?0: $_SESSION["time"] ;
	
	$s_url = (empty($_SESSION["URI"]))?0: $_SESSION["URI"] ;
	 
	$_SESSION["URI"]= $name;
	$_SESSION["time"] = $ts;
	
	 if($s_url != $name){
		 unset($_SESSION[$s_url]);
	 }
	 
	if ($ts - $time_ob_user < 2) {
	
      		
		if(!empty($_SESSION[$name])){
		  	
		 echo$_SESSION[$name]; 
		 die;
		 
		}else{
		    $_SESSION['ob_start']=1;
			ob_start();
		}
  }
    
}


function cashe_f5_end($name){
	session_start() ;
 if(!empty($_SESSION['ob_start'])  )
 {   
	$_SESSION['ob_start']=0;
	
	$tpl=     ob_get_clean(); 
    $_SESSION[$name] =$tpl;
 
   

 }	
} 

cashe_f5_start($name);

echo file_get_contents('http://navistom.com/');

cashe_f5_end($name);














/*
// раздел настроек, которые вы можете менять
$settings_cachedir = '/home/username/public_html/cache_files/';
$settings_cachetime = 3600; //время жизни кэша (1 час)



  session_start(); // Начинаем сессию
  $ts = time(); // Получаем текущее время
  $url_new= $_SERVER['REQUEST_URI'];
  $s_time = (empty($_SESSION["time"]))? 0: $_SESSION["time"]; // Если пользователь обращается к скрипту впервые, то устанавливаем значение 0, иначе берём его из сессии
   $s_url = (empty($_SESSION["URI"]))? 0: $_SESSION["URI"];
  $_SESSION["URI"]= $url_new;
  $_SESSION["time"] = $ts; // Обновляем значение сессии
  if ($ts - $s_time < 0.9 and $s_url==$url_new ) {
	 $f= $url_new.'-cache.html'; 
	 $f= str_replace('/','-',$f);
    if( file_exists($f) and   (time()- $settings_cachetime < filemtime($f)) ){
		echo file_get_contents($f);
	}else{
		$ob_start=true;
		ob_start();
		 //ob_get_clean()
		}
       
    //die('Хватит постоянно обновлять страницу!');
  }
  else{
	  echo "ok"; // Выводим сообщение об ошибке

  }
  
      
  if($ob_start){
	    $tpl_ce =ob_get_clean();
           
     
  }

function writeCache($content, $filename) {
  $fp = fopen('./cache/' . $filename, 'w');
  fwrite($fp, $content);
  fclose($fp);
}


function readCache($filename, $expiry) {
  if (file_exists('./cache/' . $filename)) {
    if ((time() - $expiry) > filemtime('./cache/' . $filename))
      return FALSE;
    $cache = file('./cache/' . $filename);
    return implode('', $cache);
  }
  return FALSE;
}
  
  
  


//setcookie ("xxx", "1", 0x7fffffff);
// var_dump( parse_url('/test/ddd')['path']);


/* //1449910079
 $tt= 12*24*60*60;
 //echo $tt;
  $t= time();
  $t+= 950400 +$tt;
 // echo $t;
echo date( 'Y-m-d', time() );
/* if('2015-10-28'<'2015-11-09'){
	echo '2015-11-09';
}
die;
 */
 
/* echo date( 'Y-m-d', time() );
 die;
 $datetime1 = date_create('2015-10-28');
$datetime2 = date_create('2015-10-29');
$interval = date_diff($datetime1, $datetime2);
 $r= $interval->format('%R%a');
 $rr= $interval->format('%a');
 //echo $r;
 if($r>0){
		echo $rr;
	 }else{
		echo'off';
	};
   */ 
/* $t= time();
 $t+=($r*24*60*60);
 
 echo date('Y-m-d',$t); */   
 
 
 
 

 /* echo $_COOKIE["volo"];
setcookie ("volo", "1", 0x7fffffff);   */

// Показать всю информацию, по умолчанию INFO_ALL
//phpinfo();

?>
