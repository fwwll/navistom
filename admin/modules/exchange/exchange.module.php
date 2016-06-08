<?php

class Exchange {
	
	public function index($country_id) {
		
		if (Request::post('rate')) {
			ModelExchange::saveExchanges(Request::post('rate'));
			
			Header::Location('/admin/exchange-rates');
		}
		else {
			echo Registry::get('twig')->render('exchanges.tpl', array(
				'form'	=> array(
					'title'	=> 'Настройка курса валют по умолчанию'
				),
				'countries'			=> Registry::get('config')->countries_names,
				'currency'			=> ModelExchange::getCurrensyList(),
				'currency_default'	=> ModelExchange::getCurrensyListDefault(),
				'exchanges_default'	=> ModelExchange::getExchangeRatesDefault()
			));
		}
	}
}