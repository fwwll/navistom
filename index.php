<?php

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('libraries/cashF5.php');
setcookie('history',$_SERVER['HTTP_REFERER']); 
$name_url=str_replace('/','',$_SERVER['REQUEST_URI']);
cashe_f5_start($name_url);

//header("Expires: Thu, 01 Jan 2016 00:00:01 GMT");

//header("Cache-control: pravet");
//header("Cache-Control: public, max-age=33600, must-revalidate");

if (get_magic_quotes_gpc()) {
    function magicQuotes_awStripslashes(&$value, $key) {$value = stripslashes($value);}
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
}

/**
 * Redirect by and ru users to 404 page
 * 301 redirect ua users to new page
 */
$countries = array(1 => 'ua', 2 => 'ru', 3 => 'by');
if (in_array($_GET['route'], $countries)) {
    if ($_GET['route'] == 'ua') {
        header('Location: /', true, 301);
    }
    else {
        header('Location: /404');
    }

    die();
}
elseif(preg_match('/^(\/?([ua|ru|by]{2})+\/)(.*$)/', $_GET['route'], $result)) {
    if ($result[2] == 'ua') {
        header('Location: /' . $result[3], true, 301);
    }
    else {
        header('Location: /404');
    }

    die();
}


define('TPL', 'templates/');
define('MODULES', 'modules/');
define('CLASSES', 'classes/');
define('CACHE', 'cache/');
define('CONFIG', 'config/');
define('LIBS', 'libraries/');
define('LANGS', 'langs/');
define('USERAGENT',$_SERVER['HTTP_USER_AGENT']);
define('FULLPATH', $_SERVER['DOCUMENT_ROOT']);
define('HOST', 'http://'.$_SERVER['HTTP_HOST']);
define('FULLURL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

define('UPLOADS', FULLPATH . DIRECTORY_SEPARATOR . 'uploads');

require_once(CONFIG . 'global.config.php');
require_once(CLASSES . 'core.class.php');


require_once(LIBS . 'phpmailer/class.phpmailer.php');
require_once(LIBS . 'phpmailer/class.smtp.php');
require_once(LIBS . 'phpmailer/language/phpmailer.lang-ru.php');

/**
 * Init autoloader classes
 */
Core::Autoload();

/**
 * Add global cofig to registry
 */
Registry::set('config', (object) $_config);

/**
 * Init router
 */
require_once(CLASSES.'routing.class.php');

$router = new routing(
	Registry::get('config')->route_xml_file,
	Registry::get('config')->routing_cache,
	Registry::get('config')->route_debug
);
 
Request::setGet('country', 1);

$_USER = Request::getSession('_USER');

$route = $router->get($_GET['route']);
//Site::d($route ,1);  
Registry::set('route', (object) $route);


//Site::d($route,1 );
DB::connect($_config);
if($_COOKIE["xxx"] and  $_COOKIE["volo"]){
	//Site::d($name_url,1); 
 }

/**
 * Init templating lib Twig
 */
require_once(LIBS."Twig/Autoloader.php");
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(TPL.Registry::get('config')->template);
$twig = new Twig_Environment($loader, array(
    'cache'       => Registry::get('config')->template_cache,
    'auto_reload' => Registry::get('config')->auto_reload
));
/* $twig ->addGlobal('userAgent',USERAGENT); */
$twig->addGlobal('tpl_dir', TPL.Registry::get('config')->template);
$twig->addGlobal('md5time', md5(time()));
$twig->addGlobal('md5css', md5_file(TPL . Registry::get('config')->template . '/styles/main.css'));
$twig->addGlobal('md5js', md5_file(TPL . Registry::get('config')->template . '/scripts/main.js'));
$twig->addGlobal('country', Request::get('country'));
$twig->addGlobal('country_url', array_search(Request::get('country'), Registry::get('config')->countries));
$twig->addGlobal('route', $route);

//$twig->addGlobal('geo_country', Site::getUserCountryDefault());
$twig->addGlobal('geo_country', 1);
Registry::set('country_url', array_search(Request::get('country'), Registry::get('config')->countries));
Registry::set('country_name', Registry::get('config')->countries_names[Request::get('country')]);

$twig->addGlobal('country_name', Registry::get('country_name'));

if ($route['controller'] == 'main' or $route['controller'] == 'products' or $route['controller'] == 'ads' or $route['controller'] == 'realty'or $route['controller'] == 'services' or $route['controller'] == 'all') {
	Registry::set('exchanges', User::getExchanges(Request::get('country')));
	
}






function compress($buffer) { 
    /* удалить комментарии */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer); 
    /* удалить табуляции, пробелы, символы новой строки и т.д. */
   // $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $buffer); 
   $buffer = str_replace(array('  ',"\n","\t","\r"), ' ', $buffer); 
    return $buffer; 
  } 

function renderCss( $f='all.min.css' ,$script='style'){
	   $type='';
	  if($script=='script'){
		  $type="type='text/javascript'";
		    return'<script>'.file_get_contents("templates/complete/".$f).' </script>';
		  }else{
			return"<style class='css'> ". compress(join('',file("templates/complete/".$f)))." </style>" ;
		 }
}

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
function rusDate($string) {
	return Str::get($string)->getRusDate();
}
function rusFormat($string){
	
	  return date('d.m.Y',strtotime($string));
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

function getExchangeRates($price, $currency_id, $user_id) {
	$exhanges = Registry::get('exchanges');
	  //Site::d($exhanges[0][$currency_id]);
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

$twig->addFilter('getExchangeRates', new Twig_Filter_Function('getExchangeRates'));
$twig->addGlobal('default_currency', $_config['default_currency'][Request::get('country')]);
$twig->addGlobal('sections', Site::getSectionsList());
$twig->addGlobal('sections_min', Site::getSectionsList(0,1));
$twig->addGlobal('currency', Site::getCountryCurrency(1));
$twig->addGlobal('countArticles', ModelMenu::countArticles());
$breadcrumb =ModelMenu::breadcrumb($_GET['route']);

$links= ModelMenu::menu_link_users($breadcrumb);

$twig->addGlobal('url',$_SERVER['REQUEST_URI']);
$twig->addGlobal('breadcrumb', $breadcrumb);
$twig->addGlobal('links', $links);
$twig->addGlobal('json_breadcrumb',	ModelMenu::json_breadcrumb($breadcrumb));
$canonical= end($breadcrumb)['url']; 


//Site::d($route);
if($route['controller'] == 'products' or $route['controller'] == 'ads' or $route['controller'] == 'realty'or $route['controller'] == 'services' or $route['controller']=='work' or $route['controller']=='labs' ) {
	
	//Site::d($route);
	
 //Site::redirect301($canonical ,$route);

} 

$twig->addGlobal('canonical',$canonical);
//Site::d( end($breadcrumb)['url'] ,1);
/**
 * statistic
 */

if (!Request::getCookie('user_visit')) {
	$browser = Site::getUserBrowserInfo();

	DB::insert('statistic_sessions', array(
		'sess_id'			=> session_id(),
		'ip_address'		=> Str::get(Site::getRealIP())->ip2mysql(),
		'browser_name'		=> $browser['ua_family'],
		'browser_version'	=> $browser['ua_version'],
		'os_name'			=> $browser['os_name'],
		'referer_url'		=> $_SERVER["HTTP_REFERER"] == null ? 0 : $_SERVER["HTTP_REFERER"]
	), 1);

	Request::setCookie('user_visit', 1, null);
}

/**
 * Add templating lib Twig to registry
 */
Registry::set('twig', $twig);

/**
 * Modules loader init
 */

$user_id = User::isUser();

Registry::set('ajax', -1);

if (!User::isUserAuth($user_id)) {
	Request::removeSession('_USER');
	Request::removeSession('_ADMIN');
}
else {
	
	$user_info=  Request::getSession('_USER');
	$twig->addGlobal('user_info', $user_info);
	// Site::d(Site::isPayAlert($user_info["user_id"]));
    $twig->addGlobal('alert_pay', Site::isPayAlert($user_info["user_id"]));
	
	  if(Request::getCookie('alert')){
		 $twig->addGlobal('alert',1);
	  }
	  
	if (User::isAdmin()) {
		$twig->addGlobal('is_admin', User::isAdmin());
	}

    if (User::isDeveloper()) {
        $twig->addGlobal('isDeveloper', User::isDeveloper());
    }
	
	
	

    $twig->addGlobal('turnSubscribe', Registry::get('config')->subscribe ? (Registry::get('config')->subscribe == 4 ? true : User::isDeveloper() || Registry::get('config')->subscribe == 3 && User::isAdmin()) : false);
}


Site::loadBanners();


$twig->addGlobal('banner', Site::getBanner());
$twig->addGlobal('banner_listing', Site::getBanner(2));
$twig->addGlobal('banner_listing_2', Site::getBanner(2));
$twig->addGlobal('banner_top', Site::getBanner(4));
$twig->addGlobal('banner_bg', Site::getBanner(5));
$twig->addGlobal('banner_footer_content', Site::getBanner(3));

if ($route['controller'] == 'main' or $route['controller'] == 'products' or $route['controller'] == 'ads' or $route['controller'] == 'realty' or ($route['controller'] == 'articles' and $route['action'] == 'full')) {
	Registry::set('exchanges', User::getExchanges(1));
}

//$twig->addGlobal('contents_count', Site::getContentsCount());
$twig->addGlobal('contents_count', Site::all_count());
//Site::d(Site::all_count(),1);

if (User::isAdmin()) {
	$twig->addGlobal('new_materials_count', array_sum(Site::getContentsCount(1)));
	$twig->addGlobal('moder_materials_count', array_sum(Site::getContentsCount(0, 0, null, 1, 0)));
	$twig->addGlobal('vip_materials_count', array_sum(Site::getContentsCount(0, 0, null, 0, 1)));
    $twig->addGlobal('hide_materials_count', array_sum(Site::getContentsCount(0, 0, null, 0, 0, 1)));
	$twig->addGlobal('nopay_by',  Site::no_pay_count());
	$twig->addGlobal('nopay_new',  Site::no_pay_new_count());
    $twig->addGlobal('adv_country', Request::getSession('adv_country'));
	
	 
}

/**
 * Set default meta tags
 */

$meta_tags = Site::getDefaultMetaTags($route['controller']);

Header::SetH1Tag($meta_tags['title']);
Header::SetTitle($meta_tags['meta_title']);
Header::SetMetaTag('description', $meta_tags['meta_description']);
Header::SetMetaTag('keywords', $meta_tags['meta_keys']);


Core::AutoloadModClasses();

if (User::isDeveloper()) {
    //$name = '123123';
    //\Subscribe\Subscribe::clear();
    //var_dump(empty($name));
    //die();
    var_dump(FULLPATH);
}

Core::Init(
	FULLPATH . DIRECTORY_SEPARATOR . 'modules',
	$route['controller'],
	$route['action'],
	$route['values']
);

if (User::isDeveloper() and Debug::$logs) {
    echo Debug::$logs;
}

if (User::isDeveloper() and Registry::get('config')->debug) {
    $logs = Debug::getLog('SQL');

    for($i = 0, $c = count($logs); $i < $c; $i++) {
        $tmp[] = $logs[$i]['time'];
    }

    echo $twig->render('debug.tpl', array(
        'logs' => $logs,
        'SQLTime' => round(array_sum($tmp), 3)
    ));
}
Site::print_page();

Site::updateBannersViews();
DB::close();
cashe_f5_end($name_url);
