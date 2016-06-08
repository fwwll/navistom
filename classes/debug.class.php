<?php

class Debug {
    private static $dumps = array();
    public  static $logs = null;

	public static function setError($key, $message, $code = 0, $file = null, $line = null) {
		$_SESSION['_ERRORS'][$key][] = array(
			'code' 		=> $code,
			'message'	=> $message,
			'file'		=> $file,
			'line'		=> $line
		);
		
		return true;
	}
	
	public static function getErrors($key = 0) {
		if ($key > 0) {
			$errors = $_SESSION['_ERRORS'][$key];
			unset($_SESSION['_ERRORS'][$key]);
		}
		else {
			$errors = $_SESSION['_ERRORS'];
			unset($_SESSION['_ERRORS']);
		}
		
		return $errors;
	}
	
	public static function setStatus($key, $message) {
		$_SESSION['_STATUS'][$key] = $message;
		
		return true;
	}
	
	public static function getStatus($key = 0) {
		if ($key != null) {
			$status = $_SESSION['_STATUS'][$key];
			unset($_SESSION['_STATUS'][$key]);
		}
		else {
			$status = $_SESSION['_STATUS'];
			unset($_SESSION['_STATUS']);
		}
		
		return $status;
	}

    public function setLog($key, $data) {
        $_SESSION['_LOGS'][$key][] = $data;

        return true;
    }

    public function getLog($key = 0) {
        if ($key) {
            $log = $_SESSION['_LOGS'][$key];
            unset($_SESSION['_LOGS'][$key]);
        }
        else {
            $log = $_SESSION['_LOGS'];
            unset($_SESSION['_LOGS']);
        }

        return $log;
    }

    public function dump($data) {
        self::$dumps[] = $data;

        return true;
    }

    public function getDumps() {
        return self::$dumps;
    }
	
	public function setLogSQL($query, $start_time, $end_time) {
        if (!Registry::get('config')->loggedQueries or !Registry::get('config')->loggedQueriesTime) {
            return false;
        }

		$time = round($end_time - $start_time, 4);

        if (Registry::get('config')->debug) {
            self::setLog('SQL', array(
                'query'	=> $query,
                'time'	=> $time,
                'date'	=> date("Y-m-d H:i:s"),
                'ip'	=> Site::getRealIP()
            ));
        }
		
		if ($time >= Registry::get('config')->loggedQueriesTime) {
			$filename = date("Y-m-d");
			
			$data = array(
				'query'	=> $query,
				'time'	=> $time,
				'date'	=> date("Y-m-d H:i:s"),
				'ip'	=> Site::getRealIP()
			);

			@file_put_contents(FULLPATH . '/logs/' . $filename . '.log', var_export($data, true), FILE_APPEND | LOCK_EX);

            return true;
		}
	}

    public static function log() {
        if (!User::isDeveloper()) {
            return false;
        }

        $args = func_get_args();
        self::$logs .= '<script>';
        foreach ($args as $value) {
            self::$logs .= ' console.log(' . json_encode($value) . '); ';
        }
        self::$logs .= '</script>';
    }
}

?>