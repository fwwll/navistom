<?php
//header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (get_magic_quotes_gpc()) {
    function magicQuotes_awStripslashes(&$value, $key) {$value = stripslashes($value);}
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
}

session_start();

set_time_limit(0);

define('TPL', 'templates/');
define('MODULES', 'modules/');
define('CLASSES', 'classes/');
define('CACHE', 'cache/');
define('CONFIG', 'config/');
define('LIBS', 'libraries/');
define('LANGS', 'langs/');

define('FULLPATH', $_SERVER['DOCUMENT_ROOT']);
define('HOST', 'http://'.$_SERVER['HTTP_HOST']);
define('FULLURL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

define('UPLOADS', FULLPATH . DIRECTORY_SEPARATOR . 'uploads');

include_once(CONFIG.'global.config.php');

include_once(CLASSES.'core.class.php');

/**
 * Init autoloader classes
 */
Core::Autoload();

/**
 * Add global cofig to registry
 */
Registry::set('config', (object) $_config);

Request::setGet('country', 1);

/**
 * Init router
 */
include_once(CLASSES.'routing.class.php');

$router = new routing(
	Registry::get('config')->route_xml_file, 
	Registry::get('config')->routing_cache, 
	Registry::get('config')->route_debug
);


$route = $router->get($_GET['route']);

Registry::set('route', (object) $route);

DB::connect($_config);

/**
 * Init templating lib Twig
 */
include_once(LIBS."Twig/Autoloader.php");
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(TPL.Registry::get('config')->template);
$twig = new Twig_Environment($loader, array(
    'cache'       => Registry::get('config')->template_cache,
    'auto_reload' => Registry::get('config')->auto_reload
));
$breadcrumb =ModelMenu::breadcrumb($_GET['route']);
$links= ModelMenu::menu_link_users($breadcrumb);



$twig->addGlobal('tpl_dir', TPL.Registry::get('config')->template);
$twig->addGlobal('md5time', md5(time()));
$twig->addGlobal('coutry', Request::get('country'));
$twig->addGlobal('route', $route);
$twig->addGlobal('links', $links);
$twig->addGlobal('ajax', 1);
Registry::set('ajax', 1);

Site::loadBanners();
$twig->addGlobal('banner_footer_content', Site::getBanner(3));





function compress($buffer) { 
    /* удалить комментарии */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer); 
    /* удалить табуляции, пробелы, символы новой строки и т.д. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $buffer); 
    return $buffer; 
  } 

function renderCss( $f='all.min.css' ,$script='style'){
	   $type='';
	  if($script=='script'){
		  $type="type='text/javascript'";
		    return'<script>'.file_get_contents("/home/testnav1/navistom.com/www/templates/complete/".$f).' </script>';
		  }else{
			return"<style class='css'> ". compress(join('',file("/home/testnav1/navistom.com/www/templates/complete/".$f)))." </style>" ;
		 }
}

/* function isBigImg($img,$cat='products',$full='full'){
	  if(is_int($cat)){
		 $var ='http://navistom.com'.$img;
	  }else{
		$var ="http://navistom.com/uploads/images/$cat/$full/$img"; 
	  }
	      
	if(!@fopen($var,'r'))
	{
	  return false;	
	}
	return true;	
} */

function isBigImg($img,$cat='products',$full='full'){
	$path= '/home/testnav1/navistom.com/www';
	  if(is_int($cat)){
		 $var = $path.$img;
	  }else{
		$var =$path."/uploads/images/$cat/$full/$img"; 
	  }
	      
	if(@is_file($var))
	{
	  return true;	
	}
	return false;	
}

function rusFormat($string){
	
	  return date('d.m.Y',strtotime($string));
}

function rusDate($string) {
	return Str::get($string)->getRusDate();
}

function timeago($string) {
	$timeAgo = new TimeAgo();
	return $timeAgo->inWords($string) . ' назад';
}

function translit($string) {
	return Str::get($string)->truncate(60)->translitURL();
}

function getNameYears($string) {
	return Str::get($string)->getNameYears();
}

if ($route['controller'] == 'articles' and $route['action'] == 'full') {
	Registry::set('exchanges', User::getExchanges(1));
}

function getExchangeRates($price, $currency_id, $user_id) {
	$exhanges = Registry::get('exchanges');
	
	if ($exhanges[$user_id][$currency_id]) {
		return bcmul($price, $exhanges[$user_id][$currency_id], 2);
	}
	elseif ($exhanges[0][$currency_id]) {
		return bcmul($price, $exhanges[0][$currency_id], 2);
	}
	else {
		return $price;
	}
}

function getDatediff($now, $date) {
	$now 	= new DateTime($now);
	$date	= new DateTime($date);

	return $now->diff($date)->days;
}
$twig->addFunction('renderCss', new Twig_Function_Function('renderCss'));
$twig->addFunction('isBigImg', new Twig_Function_Function('isBigImg'));
$twig->addFilter('getExchangeRates', new Twig_Filter_Function('getExchangeRates'));
$twig->addFilter('rusFormat', new Twig_Filter_Function('rusFormat'));
$twig->addFilter('rusDate', new Twig_Filter_Function('rusDate'));
$twig->addFilter('timeago', new Twig_Filter_Function('timeago'));
$twig->addFilter('translit', new Twig_Filter_Function('translit'));
$twig->addFilter('getNameYears', new Twig_Filter_Function('getNameYears'));
$twig->addFilter('datediff', new Twig_Filter_Function('getDatediff'));
$twig->addFilter('getAccessLimit', new Twig_Filter_Function(function($sectionId, $key = 'add') {
    return User::getUserAccessLimits($sectionId, $key);
}));
$twig->addFilter('day', new Twig_Filter_Function(function($day) {
    return $day . ' ' .Str::get($day)->getVariantsByInt('дней', 'день', 'дня');
}));
$twig->addFilter('checkUserAccessRequest', new Twig_Filter_Function(function($userId) {
    if ($userId > 0) {
        return User::checkUserAccessRequest($userId);
    }
    return false;
}));

$sections = "SELECT name, link, controller, target, class, icon
	FROM `sections` WHERE flag = 1 ORDER BY sort_id";

$sections = DB::getAssocArray($sections);

$twig->addGlobal('sections', $sections);

$twig->addGlobal('currency', Site::getCountryCurrency(Request::get('country')));

/**
 * Add templating lib Twig to registry
 */
Registry::set('twig', $twig);

$user_id = User::isUser();

if (!User::isUserAuth($user_id)) {
	Request::removeSession('_USER');
	Request::removeSession('_ADMIN');
}
else {
	$twig->addGlobal('user_info', Request::getSession('_USER'));
	
	if (User::isAdmin()) {
		$twig->addGlobal('is_admin', User::isAdmin());
	}
}

/**
 * Modules loader init
 */

Core::AutoloadModClasses();

Core::Init(
	FULLPATH.DIRECTORY_SEPARATOR.'modules', 
	$route['controller'], 
	$route['action'], 
	$route['values']
);

Site::updateBannersViews();

DB::close();

?>