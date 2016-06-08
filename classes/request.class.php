<?php
class Request {
	public static function post($key, $type = null) {
		if ($type != null)
			return self::filter($_POST[$key], $type);
		
		return $_POST[$key];
	}
	
	public static function get($key, $type = null) {
		if ($type != null)
			return self::filter($_GET[$key], $type);
		
		return $_GET[$key];
	}
	
	public static function all($key, $type = null) {
		if ($type != null)
			return self::filter($_REQUEST[$key], $type);
		
		return $_REQUEST[$key];
	}
	
	public static function cookie($key, $type = null) {
		if ($type != null)
			return self::filter($_COOKIE[$key], $type);
		
		return $_COOKIE[$key];
	}
	
	public static function setPost($key, $value, $type = null) {
		if ($type != null)
			$value = self::filter($value, $type);
		
		$_POST[$key] = $value;
		
		return true;
	}
	
	public static function setGet($key, $value, $type = null) {
		if ($type != null)
			$value = self::filter($value, $type);
		
		$_GET[$key] = $value;
		
		return true;
	}
	
	public static function setCookie($key, $value, $time = null, $path = "/", $type = null) {
		if ($type != null)
			$value = self::filter($value, $type);
		
		setcookie($key, $value, $time, $path);
	}
	
	public static function getCookie($key, $type = null) {
		if ($type != null) {
			return self::filter($_COOKIE[$key], $type);
		}
		
		return $_COOKIE[$key];
	}
	
	public static function removeCookie($key) {
		setcookie($key, "");
		return true;
	}
	
	public function file($key) {
        if ($_FILES[$key]['name'] != null) {
            return $_FILES[$key];
        }

        return false;
	}
	
	public function setSession($key, $value) {
		$_SESSION[$key] = $value;
		
		return true;
	}
	
	public function getSession($key) {
		return $_SESSION[$key];
	}
	
	public function removeSession($key) {
		unset($_SESSION[$key]);
	}
	
	public function PostIsNull() {
		$args = func_get_args();
		
		for ($i = 0, $c = count($args); $i < $c; $i++) {
			if (Str::get(Request::post($args[$i]))->isNull()) {
				continue;
			}
			else {
				return false;
			}
		}
		
		return true;
	}
	
	public function isPost() {
		return count($_POST) > 0 ? true : false;
	}
	
	private static function filter($str, $type) {
		
		switch ($type) {
			case 'int':
				return (int) $str;
			break;
			case 'float':
				return (float) $str;
			break;
			case 'string':
				return (string) Str::get($str)->filterString();
			break;
			case 'url':
				return (string) Str::get($str)->filterURL()->addScheme();
			break;
			case 'email':
				return (string) Str::get($str)->filterEmail();
			break;
			case 'translit': 
				return (string) Str::get($str)->translit();
			break;
			case 'translitURL':
				return (string) Str::get($str)->translitURL();
			break;
			case 'hash':
				return md5(md5($str));
			break;
		}
		
		return $str;
	}
}