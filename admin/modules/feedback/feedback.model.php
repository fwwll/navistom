<?php

class ModelFeedback {
	
	public function getUserFeedbackMess($mess_id = 0) {
		if ($mess_id > 0) {
			$query = "SELECT *, INET_NTOA(ip_address) AS ip_address FROM `users_feedback_mess` WHERE mess_id = $mess_id";
		
			return DB::getAssocArray($query, 1);
		}
		else {
			$query = "SELECT f.*, 
				INET_NTOA(f.ip_address) AS ip_address,
				a.message AS answer_mess,
				a.date_send AS answer_date,
				u.name AS answer_user
				FROM `users_feedback_mess` AS f 
				LEFT JOIN `feedback_answers` AS a ON a.mess_id = f.mess_id AND a.type = 1
				LEFT JOIN `users_info` AS u ON u.user_id = a.user_id
				ORDER BY date_add DESC";
		
			return DB::getAssocArray($query);
		}
	}
	
	public function viewMess($mess_id) {
		DB::update('users_feedback_mess', array(
			'flag_view'	=> 1
		), array(
			'mess_id'	=> $mess_id
		));
		
		return true;
	}

    public  function getAccessRequests() {
        $query = 'SELECT r.user_id, r.link, r.type, r.date_add, u.email, ui.contact_phones, ui.name
            FROM `user_access_requests` AS r
            LEFT JOIN `users` AS u ON u.user_id = r.user_id
            LEFT JOIN `users_info` AS ui ON ui.user_id = r.user_id ORDER BY r.date_add DESC ';

        return DB::getAssocArray($query);
    }

    public function deleteAccessRequest($userId) {
        return DB::delete('user_access_requests', array('user_id' => $userId));
    }
	
	public function deleteMess($mess_id) {
		DB::delete('users_feedback_mess', array(
			'mess_id'	=> $mess_id
		));
	}
	
	public function getUsersErrorsMess($mess_id = 0) {
		if ($mess_id > 0) {
			$query = "SELECT *, INET_NTOA(ip_address) AS ip_address FROM `users_errors_mess` WHERE mess_id = $mess_id";
			
			return DB::getAssocArray($query, 1);
		}
		else {
			$query = "SELECT f.*, 
				INET_NTOA(f.ip_address) AS ip_address,
				a.message AS answer_mess,
				a.date_send AS answer_date,
				u.name AS answer_user
				FROM `users_errors_mess` AS f 
				LEFT JOIN `feedback_answers` AS a ON a.mess_id = f.mess_id AND a.type = 2
				LEFT JOIN `users_info` AS u ON u.user_id = a.user_id
				ORDER BY date_add DESC";
			
			return DB::getAssocArray($query);
		}
	}
	
	public function view($mess_id) {
		DB::update('users_errors_mess', array(
			'flag_view'	=> 1
		), array(
			'mess_id'	=> $mess_id
		));
		
		return true;
	}
	
	public function delete($mess_id) {
		DB::delete('users_errors_mess', array(
			'mess_id'	=> $mess_id
		));
	}
	
	public function saveAnswer($mess_id, $user_id, $type = 1, $message) {
		DB::insert('feedback_answers', array(
			'mess_id'	=> $mess_id,
			'user_id'	=> $user_id,
			'type'		=> $type,
			'message'	=> $message,
			'date_send'	=> DB::now()
		));
		
		return true;
	}
	
	public function getMessTplsList() {
		$query = "SELECT m.*, s.name AS section_name
			FROM `feedback_mess_tpls` AS m
			INNER JOIN `sections` AS s USING(section_id)";
		
		return DB::getAssocArray($query);
	}
	
	public function getSectionsList() {
		$query = "SELECT section_id, name_sys
			FROM `sections`
			WHERE (flag = 1 OR section_id = 15) AND section_id != 2
			ORDER BY sort_id";
		
		return DB::getAssocKey($query);
	}
	
	public function getMessTplData($mess_id) {
		$query = "SELECT * FROM `feedback_mess_tpls` WHERE mess_id = $mess_id";
		
		return DB::getAssocArray($query, 1);
	}
}