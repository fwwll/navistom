<?php

class ModelReclama{
	
		public static function reclama($id=1){
			$query = "SELECT title, content,file,file_title
			FROM `reclama` where id= $id ";
			$result=DB::getAssocArray($query);

		    return $result[0];
		}
		
		
		
		
		
	
}