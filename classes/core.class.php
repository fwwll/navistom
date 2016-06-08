<?php
function sd(){
	$err = error_get_last();
	if( $err['type'] == E_ERROR ){
		die(var_dump( $GLOBALS['files'] ));
	}
}

register_shutdown_function('sd');

$GLOBALS['files'] = array();

class Core {
	public static function Autoload() {
		
		function autoloadBaseClasses($className) {
			$fileName = CLASSES.strtolower($className).'.class.php';
            $fileName = str_replace("\\", "/", $fileName);
			
			if (is_file($fileName)) {
				require_once($fileName);
			}
			else {
				$class 	= strtolower(str_replace('Model', '', $className));
				$file 	= MODULES . $class . DIRECTORY_SEPARATOR . $class .'.model.php';
				
				if (is_file($file)) {
					require_once($file);
				}
			}
		}
		
		spl_autoload_register('autoloadBaseClasses');
		
		return true;
	}
	
	public static function AutoloadModClasses() {
		
		function autoloadModClasses($className) {
			$module = Registry::get('route')->controller;
			$fileName = MODULES . $module . DIRECTORY_SEPARATOR . $module .'.model.php';
			
			if (is_file($fileName)) {
				require_once($fileName);
			}
			else {
				//$files = glob($module .'.model.php');
			}
		}
		
		spl_autoload_register('autoloadModClasses');
		
		return true;
	}
	
	public static function Init($mods_dir, $module, $method, $options) {
		if ($module != null) {
			$file = $mods_dir.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$module.'.module.php';
			
			if (is_file($file)) {
				include_once($file);

				if (class_exists($module)) {
					$modClass = new $module();
					
					if ($method != null and method_exists($modClass, $method)) {
						
						$reflection 	= new ReflectionMethod($modClass, $method);
						$methodParams 	= (array) $reflection->getParameters();
						
						$args = array();
						
						if (count($methodParams)) {
							foreach ($methodParams as $key => $val) {
								if (isset($options[$val->name]))
									$args[] = $options[$val->name];
								else {
									if ($val->isOptional())
										$args[] = $val->getDefaultValue();
									else 
										$args[] = null;
								}
							}
						}
						
						call_user_func_array(array($modClass, $method), $args);
					}
					else {
						if (method_exists($modClass, 'index')) {
							$modClass->index();
						}
					}
				}
				else {
					
				}
			}
			else {
				
			}
		} 
		else {
			
		}
	}
	
}