<?php

class Statistic {
	
	public function index() {
		$content_count_by_day 		= ModelStatistic::getContentsCount(DB::now(1));
		
		$content_count_by_yesterday = ModelStatistic::getContentsCount(date('Y-m-d', strtotime('-1 day')));
		$content_count_by_week 		= ModelStatistic::getContentsCount(null, null, 7);
		$content_count_by_month		= ModelStatistic::getContentsCount(null, null, 30);
		
		echo Registry::get('twig')->render('statistic.tpl', array(
			'sessions_count'			=> ModelStatistic::getSessinsCount(DB::now(1)),
			'users_count'				=> ModelStatistic::getUsersCount(),
			'section_stat'				=> ModelStatistic::getSectionsStatistic(),
			'registration_count'		=> ModelStatistic::getRegistrationCount(DB::now(1)),
			'content_count'				=> ModelStatistic::getContentsCount(),
			'content_count_by_day'		=> $content_count_by_day,
			'content_count_by_day_sum'	=> array_sum($content_count_by_day),
			'sections_content_views'	=> ModelStatistic::getSectionsContentViews(),
			'sections_views_by_month'	=> ModelStatistic::getSectionsViewsByMonth(),
			'browsers_statistic'		=> ModelStatistic::getUsersBrowsers(),
			'platform_statistic'		=> ModelStatistic::getUsersPlatform(),
			'statistic_by_date'			=> array(
				'sessions'		=> array(
					'yesterday'		=> 	ModelStatistic::getSessinsCount(date('Y-m-d', strtotime('-1 day'))),
					'week'			=> 	ModelStatistic::getSessinsCount(null, null, 7),
					'month'			=> 	ModelStatistic::getSessinsCount(null, null, 30)
				),
				'registrations'	=> array(
					'yesterday'		=> ModelStatistic::getRegistrationCount(date('Y-m-d', strtotime('-1 day'))),
					'week'			=> ModelStatistic::getRegistrationCount(null, null, 7),
					'month'			=> ModelStatistic::getRegistrationCount(null, null, 30),
				),
				'contents'		=> array(
					'yesterday'		=> array_sum($content_count_by_yesterday),
					'week'			=> array_sum($content_count_by_week),
					'month'			=> array_sum($content_count_by_month)
				)
			)
		));
	}
	
	public function subscribe() {
		echo Registry::get('twig')->render('statistic-subscribe.tpl', array(
			'all_count'			=> ModelStatistic::getSubscribeUsersCount(),
			'active_count'		=> ModelStatistic::getSubscribeActiveUsersCount(),
			'count_by_sections'	=> ModelStatistic::getSubscribeUsersCountBySections()
		));
	}
}