<?php

class Router {
	public static function Init() {
		
		$route = @explode('/', Request::get('route'));
		
		Request::setGet('lang', $route[0], 'string');
		
		if ($route[1] != null and self::_isModule($route[1])) {
			$module = $route[1];
		}
		else {
			$module = Registry::get('config')->default_module;
		}
		
		Request::setGet('module', $module);
		
		//$module	= $module != null ? $module : Registry::get('config')->default_module;
		
		/*$requestURI = @explode('/', $_SERVER['REQUEST_URI']);
		Request::setGet('module', $requestURI[1], 'string');
		Request::setGet('method', $requestURI[2], 'string');
		
		unset($requestURI[0], $requestURI[1], $requestURI[2]);
		
		if (count($requestURI) > 0 and count($requestURI)%2 == 0) {
			foreach ($requestURI as $key=>$value) {
				if ($key%2 == 0) 
					$getValues[] = $value;
				else  
					$getKeys[] = $value;
			}
			$options = @array_combine($getKeys, $getValues);
			
			Request::setGet('options', $options);
			
			$_GET = @array_merge($_GET, $options);
		}
		
		return true;*/
	}
	
	private static function _isModule($module) {
		$file = FULLPATH.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$module.'.module.php';
		
		return is_file($file) ? true : false;
	}
}