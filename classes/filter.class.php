<?php
/**
 * Static class filters and validation data
 *
 * @author Xss
 * @version 1.0
 */
class Filter {
	
	/**
	 * Static protected method
	 * validation string on filters
	 *
	 * @param array $array
	 * @param constant $filter
	 * @return bool
	 */
	static protected function dataValidation($array, $filter) {
		for ($i=0, $c=count($array); $i<$c; $i++) {
			if (is_array($array[$i])) {
				foreach ($array[$i] as $val) 
					if (!filter_var($val, $filter)) return false;
			}
			else {
				if (filter_var($array[$i], $filter)) continue;
				else return false;
			}
		}
		return true;
	}
	
	/**
	 * Static protected method
	 * filters string on filters
	 *
	 * @param array $array
	 * @param constant $filter
	 * @return array
	 */
	static public function dataFilter($array, $filter) {
		
		if (count($array) == 1) 
			return filter_var($array[0], $filter);
		
		for ($i=0, $c=count($array); $i<$c; $i++) {
			if (is_array($array[$i])) {
				$array[$i] = filter_var_array($array[$i], $filter);
			}
			else {
				$array[$i] = filter_var($array[$i], $filter);
			}
		}
		return $array;
	}
	
	static public function postIsNull() {
		$args = func_get_args();
		
		for ($i=0, $c = count($args); $i<$c; $i++) {
			if ($_POST[$args[$i]] != null) continue;
			else return false;
		}
		
		return true;
	}
	
	static public function validationRegExp($var, $regExp) {
		
	}
	
	static public function filterRegExp($var, $pattern, $replacement) {
		
	}
	
	/**
	 * Validation vars on int value
	 *
	 * @return bool
	 */
	static public function validInt() {
		return Filter::dataValidation(func_get_args(), FILTER_VALIDATE_INT);
	}
	
	/**
	 * Validation vars on float value
	 *
	 * @return bool
	 */
	static public function validFloat() {
		return Filter::dataValidation(func_get_args(), FILTER_VALIDATE_FLOAT);
	}
	
	/**
	 * Validation vars on boolean value
	 *
	 * @return bool
	 */
	static public function validBool() {
		return Filter::dataValidation(func_get_args(), FILTER_VALIDATE_BOOLEAN);
	}
	
	/**
	 * Validation vars on IP address
	 *
	 * @return bool
	 */
	static public function validIP() {
		return Filter::dataValidation(func_get_args(), FILTER_VALIDATE_IP);
	}
	
	/**
	 * Validation vars on email address
	 *
	 * @return bool
	 */
	static public function validEmail() {
		return Filter::dataValidation(func_get_args(), FILTER_VALIDATE_EMAIL);
	} 
	
	/**
	 * Validation vars on URL addres
	 *
	 * @return bool
	 */
	static public function validURL() {
		return Filter::dataValidation(func_get_args(), FILTER_VALIDATE_URL);
	} 
	
	/**
	 * Filter vars on int values
	 *
	 * @return var
	 */
	static public function filterInt() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_NUMBER_INT);
	}
	
	/**
	 * Filter vars on float values
	 *
	 * @return var
	 */
	static public function filterFloat() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_NUMBER_FLOAT);
	}
	
	/**
	 * Filter vars encoded special symbols for URL values
	 *
	 * @return var
	 */
	static public function filterEncoded() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_ENCODED);
	}
	
	/**
	 * Filter vars magic quotes
	 *
	 * @return var
	 */
	static public function filterMagicQuotes() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_MAGIC_QUOTES);
	}
	
	/**
	 * Filter vars encode special chars
	 *
	 * @return var
	 */
	static public function filterSpecialChars() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_SPECIAL_CHARS);
	}
	
	/**
	 * Filter vars encode full special chars
	 *
	 * @return var
	 */
	static public function filterFullSpecialChars() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	
	/**
	 * Filter vars strip HTML tags and special chars
	 *
	 * @return var
	 */
	static public function filterString() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_STRING);
	}
	
	/**
	 * Filter vars encode special chars for URL
	 *
	 * @return var
	 */
	static public function filterURL() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_URL);
	}
	
	/**
	 * Filter vars delete "!#$%&'*+-/=?^_`{|}~@.[]" for string
	 *
	 * @return var
	 */
	static public function filterEmail() {
		return Filter::dataFilter(func_get_args(), FILTER_SANITIZE_EMAIL);
	}
}