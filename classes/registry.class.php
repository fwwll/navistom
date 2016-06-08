<?php

class Registry {
    /**
     * @name $data
     * @var array
     */
	private static $data = array();

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
	public static function set($key, $value) {
		self::$data[$key] = $value;
		
		return true;
	}

    /**
     * @param string $key
     * @return $data|null
     */
	public static function get($key) {
		return isset(self::$data[$key]) ? self::$data[$key] : null;
	}

    /**
     * @param string $key
     * @return bool
     */
	public static function remove($key) {
		if (isset(self::$data[$key]))
			unset(self::$data[$key]);
		
		return true;
	}
}