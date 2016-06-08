<?php

class Vacancy {
	public function index($filter = null) {
		
		if ($filter == 'moderation') {
			$title = 'Вакансии на модерации';
		}
		else {
			$title = 'Все вакансии';
		}
		
		echo Registry::get('twig')->render('vacancy-list.tpl', array(
			'vacancies'	=> ModelVacancy::getVacancyList($filter),
			'title'		=> 'Работа - ' . $title 
		)); 
	}
}