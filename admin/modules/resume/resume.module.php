<?php

class Resume {
	public function index($filter) {
		echo Registry::get('twig')->render('resume-list.tpl', array(
			'resume'	=> ModelResume::getResumeList($filter),
			'title'		=> 'Работа - Все резюме' 
		)); 
	}
}