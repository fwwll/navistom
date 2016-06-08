<?php
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");

error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (get_magic_quotes_gpc()) {
    function magicQuotes_awStripslashes(&$value, $key) {$value = stripslashes($value);}
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
}

session_start();

define('TPL', 'template/');
define('TPL_PATH', 'template');
define('MODULES', 'modules/');
define('CLASSES', '../classes/');
define('CACHE', '../cache/');
define('CONFIG', '../config/');
define('LIBS', '../libraries/');
define('LANGS', '../langs/');

define('FULLPATH', $_SERVER['DOCUMENT_ROOT']);
define('HOST', $_SERVER['HTTP_HOST']);
define('FULLURL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

define('UPLOADS', FULLPATH . DIRECTORY_SEPARATOR . 'uploads');

require_once(CONFIG.'global.config.php');

require_once(CLASSES.'core.class.php');

require_once(LIBS.'upload/upload.class.php');

require_once(LIBS . 'AcImage/AcImage.php');

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

$router = new routing(
	Registry::get('config')->route_admin_file, 
	Registry::get('config')->routing_cache, 
	true
);

$route = $router->get(Request::get('route', 'string'));

Registry::set('route', (object) $route);

DB::connect($_config);

/**
 * Init templating lib Twig
 */
require_once(LIBS."Twig/Autoloader.php");
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(TPL_PATH);
$twig = new Twig_Environment($loader, array(
    'cache'       => Registry::get('config')->template_cache,
    'auto_reload' => Registry::get('config')->auto_reload
));

 if($_GET['page']){
   $twig->addGlobal('page', ((int)$_GET['page']));
 }
$twig->addGlobal('tpl_dir', Registry::get('config')->admin_template);
$twig->addGlobal('ajax', 1);

function rusDate($string) {
	return Str::get($string)->getRusDate();
}

$twig->addFilter('rusDate', new Twig_Filter_Function('rusDate'));

/**
 * Add templating lib Twig to registry
 */
Registry::set('twig', $twig);

Core::AutoloadModClasses();

/**
 * Modules loader init
 */
Core::Init(
	FULLPATH.DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR .'modules', 
	$route['controller'], 
	$route['action'], 
	$route['values']
);

DB::close();

?>