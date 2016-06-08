<?php

class ModelVacancy {
	
	public function getVacancyList($type = null) {
		switch ($type) {
			case 'moderation':
				$where = 'flag_delete = 0 AND flag-moder = 0';
			break;
			case 'removed':
				$where = 'flag_delete = 1';
			break;
			default:
				$where = 'flag_delete = 0';
			break;
		}
		
		$query = "SELECT *, 
			(SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = vacancies.vacancy_id)) AS categs,
			(SELECT name FROM `users_info` WHERE user_id = vacancies.user_id) AS user
			FROM `vacancies` WHERE $where
			ORDER BY flag_moder, date_add DESC";
		
		return DB::getAssocArray($query);
	}
}