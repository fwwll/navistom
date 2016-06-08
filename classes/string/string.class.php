<?php

class String {
	
	public function translit($str) {
		$translate = array(
	        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
	        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
	        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
	        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
	        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
	        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
	        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
	        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
	        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
	        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
	        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
	        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
	        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya");
		
	    return strtr($str, $translate);
	}
	
	public function translitURL($str) {
        $str = self::strToLower($str);
        $str = preg_replace(array('/[^a-zа-яёъїіє0-9]/iu', '!\s+!'), ' ', $str);
        $str = self::trim($str);

        $translate = array(
	        "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
            "е"=>"e","ж"=>"j","з"=>"z","и"=>"i","й"=>"y",
            "к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o",
            "п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u",
            "ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh",
            "щ"=>"sch","ъ"=>"y","ы"=>"yi","ь"=>"","э"=>"e",
            "ю"=>"yu","я"=>"ya","і"=> "i","ї"=>"yi", " "=>"-");

        return strtr($str, $translate);
	}
	
	public function strToLower($str) {
		return mb_strtolower($str, 'UTF-8');
	}
	
	public function removeSymbols($str) {
		return preg_replace("/[^A-Za-zА-Яа-я0-9 \-]/Uui", "", $str);
	}
	
	public function truncate($str, $maxlength, $charset = null, $flag = 0) {
		$charset = $charset != null ? $charset : 'UTF-8';
		
		$length = mb_strlen($str, $charset) > $maxlength ? mb_strrpos(mb_substr($str, 0, $maxlength, $charset), ' ', null, $charset) : $maxlength;
		
		$truncateStr = preg_replace("/[^A-Za-zА-Яа-я0-9]{1,}$/Uui", "", mb_substr($str, 0, $length, $charset));
		
		return mb_strlen($str, $charset) > $maxlength ? $truncateStr.($flag > 0 ? '' : '...') : $truncateStr;
	}
	
	public function generate($str, $count, $type = 'num') {
		switch ($type){
			case 'num':
				$symbols = '0123456789';
			break;
			case 'low':
				$symbols = 'abcdefghijklmnopqrstuvwxyz';
			break;
			case 'up':
				$symbols = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
			default:
				$symbols = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		}
		
        for($i = 0; $i < $count; $i++)
        	$string .= $symbols{mt_rand(0, strlen($symbols)-1)};
        
        return $string;
	}
	
	public function getRusMonth($month) {
		$month_list =  array ('январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');
		
		return $month_list[$month - 1];
	}
	
	public function ucwords($str) {
		return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
	}
	
	public function getNameYears($years) {
		$yearend = substr($years, -1);
		
		if ($years == 11 or $years == 12 or $years == 13 or $years == 14) {
			return 'лет';
		}
		
		if ($yearend == 1) {
			return 'год';
		}
		elseif ($yearend == 2 or $yearend == 3 or $yearend == 4) {
			return 'года';
		}
		else {
			return 'лет';
		}
	}
	
	public function getVariantsByInt($int, $several, $one, $two) {
		$intend = substr($int, -1);
		
		if ($int == 11 or $int == 12 or $int == 13 or $int == 14) {
			return $several;
		}
		
		if ($intend == 1) {
			return $one;
		}
		elseif ($intend == 2 or $intend == 3 or $intend == 4) {
			return $two;
		}
		else {
			return $several;
		}
	}
	
	public function getRusDate($datetime) {
		$yy = (int) substr($datetime,0,4);
		$mm = (int) substr($datetime,5,2);
		$dd = (int) substr($datetime,8,2);
		
		$hours = substr($datetime,11,5);
		
		$month =  array ('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		
		return ($dd > 0 ? $dd . " " : '') . $month[$mm - 1]." ".$yy." г. " . $hours;
	}
	
	public function ip2mysql($str) {
		return sprintf("%u", ip2long($str));
	}
	
	public function mysql2ip($str) {
		return long2ip(sprintf("%d", $str));
	}
	
	public function count($str, $charset = 'UTF-8') {
		return mb_strlen($str, $charset);
	}
	
	public function trim($str) {
		return trim($str);
	}
	
	public function addScheme($str) {
		if ($str != '') {
			return parse_url($str, PHP_URL_SCHEME) === null ? 'http://' . $str : $str;
		}
	}

    public function dateDiff($dateEnd, $dateStart = null) {
        $dateStart  = $dateStart ? new DateTime($dateStart) : new DateTime('now');
        $dateEnd    = new DateTime($dateEnd);
        $diff       = $dateStart->diff($dateEnd);

        return $diff->days * ($diff->invert ? -1 : 1);
    }
	
	public function getDateAgo($date) {
		
	}
}