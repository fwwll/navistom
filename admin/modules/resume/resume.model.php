<?php

class ModelResume {
	public function getResumeList($filter = null) {
		switch ($filter) {
			case 'moderation':
				$where = 'flag_delete = 0 AND flag_moder = 0';
			break;
			case 'removed':
				$where = 'flag_delete = 1';
			break;
			default:
				$where = 'flag_delete = 0';
			break;
		}
		
		$resume = "SELECT work_id, user_name, user_surname, user_firstname, flag, flag_moder, date_add,
			(SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = work.work_id)) AS categs,
			(SELECT name FROM `users_info` WHERE user_id = work.user_id) AS user
			FROM `work`
			WHERE $where";
		
		return DB::getAssocArray($resume);
	}
}