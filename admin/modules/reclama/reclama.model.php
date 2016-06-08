<?php

class ModelReclama{
	
		public static function reclama($id=1){
			$query = "SELECT title, content,file,file_title
			FROM `reclama` where id= $id ";
			$result=DB::getAssocArray($query);
	 	
		   return $result[0];
		}
		
		
		
		public static function save($id=1){
			//  var_dump($_POST); die;
			  
			$title= Request::post('title', 'string');
			$file_title=  Request::post('file_title', 'string');
			$content= Request::post('content', 'html');
			
			$uploaddir = '/home/testnav1/navistom.com/www/file/';
			$uploadfile = $uploaddir . basename($_FILES['file']['name']);
            $filename=basename($_FILES['file']['name']);
			
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
				
				DB::update('reclama', array('file'=> $filename), array('id' => 1));
				
				
			} else {
				/* echo "Возможная атака с помощью файловой загрузки!\n"; */
			}

			DB::update('reclama', array('title'=> $title,'file_title'=>$file_title,'content'=>$content), array('id' => 1));
			
			
			
			Header::location('/admin/reclama/');	
			 
			
		}
		
	
}