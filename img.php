<?
define(LIBS, __DIR__ .'/libraries/');
require_once(LIBS . 'AcImage/AcImage.php');
$size=['h'=>560,'w'=>700];
$path= __DIR__ .'/60434465705978967230.jpg';
$path_save=__DIR__ .'/s/';
$img = AcImage::createImage($path);

  //die(  var_dump($img->getWidth())); 
			$img->cropCenter($size['w'], $size['h']);
			 /* $img->thumbnail(
							$size['w'],
							$size['h']
						   );  */
//$img->resizeByWidth( 700);
$img->saveAsJPG($path_save. basename($path));