

<?
die;
function closing_html($str) 
	{
		preg_match_all('#<([a-z]+)?(?<![/|/ ])>#iU', $str, $result);
		$openedtags = $result[1];
		var_dump($openedtags);
		preg_match_all('#</([a-z]+)>#iU', $str, $result);
		$closedtags = $result[1];
		$lenOpened = count($openedtags);
		if (count($closedtags) == $lenOpened)
			return true;
		$openedtags = array_reverse($openedtags);
		$countTags = 0;
		for ($i = 0; $i < $lenOpened; $i++)
			if (!in_array($openedtags[$i], $closedtags))
				$countTags++;
		return ($countTags) ? false : true;
	}  

	
	




$t="<p>55</p><i>";

 if(closing_html($t)){
	 echo'ok';
 }else{
	  echo'on';
 } 
 die;
 
$t="<i><p>55</p></i>ggg<i></i>";
$r="/(<(i|b|strong)>.*<p>)/isU";

//$r="#(?<=<i>|<b>|<strong>).*?<p>.*?<\/p>.*?(?=<\/i>|<\/b>|<\/strong>)#iU";

$r="#.*?<i>.*?(?!</i>)#";
preg_match_all($r ,$t ,$res);

echo'<pre>';
var_dump( $res);




 // die(ini_get('upload_max_filesize'));
//echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==');
//echo base64_decode("aHR0cDovL2xpbmsucG9ybmNyb3Aub3JnL2dldGxpbmsucGhwP3NpZD0x") ;


die;
    $now   = new DateTime($now); 
	$date  = new DateTime('2016-10-13');
	echo $now->diff($date)->days;




   die();
 // die(var_dump($interval->format('%R% дней')));
 $path= '/home/testnav1/navistom.com/www/uploads/images/offers/142x195/94890834484478354030dd.jpg';

echo'<pre>';
 $path= explode('/', $path);
 
 print_r( $path);
 
  print_r(array_filter(  $path, 'mb_strlen')); 
   die;




function isBigImg($img,$cat='products',$full='full'){
	$path= '/home/testnav1/navistom.com/www';
	  if(is_int($cat)){
		 $var = $path.$img;
	  }else{
		$var =$path."/uploads/images/$cat/$full/$img"; 
	  }
	      
	if(file_exists($var))
	{
	  return true;	
	}
	return false;	
}

//http://navistom.com



/* if( isBigImg('/uploads/images/offers/142x195/94890834484478354030.jpg',1)){
	echo'ok';
}else{
	echo'nobe';
} */


//'/uploads/images/offers/142x195/94890834484478354030.jpg'


  /*  function compress_page($buffer)
    {
        $search = array('/>[^S ]+/s','/[^S ]+</s','/(s)+/s');
        $replace = array('>','<','1');
        return preg_replace($search, $replace, $buffer);
    }
	
	function display( $tpl ){
	  
	  if(file_exists($tpl)){
	  return compress_page( file_get_contents( $tpl));
	  }else{
		  die('not file');
	  }
	}
	
    $path= __DIR__ .'/templates/Navistom/products-new-new2.tpl';	 */
	
