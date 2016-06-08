<?php

class ModelPublications {
	public static function getPub() {
		
		$query = "SELECT * FROM  jurnal_public WHERE pub=0";
		
		$result=  DB::getAssocArray($query);
		
		return $result;
	}
	
}