<?php

class Validator {
	public function isInt($str) {
		if (filter_var($str, FILTER_VALIDATE_INT))
			return true;
				
		return false;
	}
	
	public function isFloat($str) {
		if (filter_var($str, FILTER_VALIDATE_FLOAT))
			return true;
				
		return false;
	}
	
	public function isBool($str) {
		if (filter_var($str, FILTER_VALIDATE_BOOLEAN))
			return true;
				
		return false;
	}
	
	public function isIP($str) {
		if (filter_var($str, FILTER_VALIDATE_IP))
			return true;
				
		return false;
	}
	
	public function isEmail($str) {
		$regExp = '/^((([a-z]|\d|[!#$%&\'*+\-\/=?\^_`{|}~]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])+(\.([a-z]|\d|[!#$%&\'*+\-\/=?\^_`{|}~]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(\\\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(([a-z]|\d|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])*([a-z]|\d|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])))\.)+(([a-z]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(([a-z]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])*([a-z]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])))\.?$/iu';
		
		if (preg_match($regExp, $str) > 0)
			return true;
			
		return false;
	}
	
	public function isURL($str) {
		if (filter_var($str, FILTER_VALIDATE_URL))
			return true;
				
		return false;
	}
	
	public function isPhone($str) {
		$regExp = '/^([\+][0-9]{1,3}[ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/';
		
		if (preg_match($regExp, $str) > 0)
			return true;
			
		return false;
	}
	
	public function isNull($str) {
		return mb_strlen($str, 'UTF-8') > 0 ? true : false;
	}
}