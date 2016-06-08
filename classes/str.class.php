<?php

class Str {
	private $_value 		= null,
			$_handlers 		= array();
	
	
	private function __construct($value) {
		$this->_value = $value;
		$this->_loadHandlers();
	}
	
	public function __call($name, $argv) {
		foreach( $this->_handlers as $handler ) {
			if( method_exists( $handler, $name ) ) {
				$result = call_user_func_array( 
					array($handler, $name), 
					array_merge(array($this->_value), $argv) 
				);
				if( is_bool( $result ) ) {
					return $result;
				}
				else {
					$this->_value = $result;
					return $this;
				}					
			}else{
				continue;
			}
		}
		throw new Exception('Метод '.$name.' не найден!');
	}
	
	public function __toString() {
		return (string) $this->_value;
	}
	
	private function _loadHandlers(){			
		$dir 		= dirname(__FILE__) . DIRECTORY_SEPARATOR . 'string';			
		$handlers	= glob( $dir . DIRECTORY_SEPARATOR . '*.class.php' );
		
		foreach($handlers as $file) {
			$chunks 	= explode( '.', basename( $file ) );
			array_pop( $chunks );
			
			$className = ucfirst( strtolower( $chunks[0] ) );
			
			include_once $file;
			
			if( ! class_exists( $className ) ){
				throw new Exception('Класс '.$className.' не найден');
			}
			
			$this->_handlers[] = new $className;				
		}			
	}
	
	static public function get($value = null) {
		return new self( trim((string) $value) );
	}
}