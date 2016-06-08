<?php

class ModelExchange {
	
	public function getCurrensyList() {
		$query = "SELECT country_id, name, currency_id FROM `currency` WHERE is_default = 0";
		
		return DB::getAssocGroup($query);
	}
	
	public function getCurrensyListDefault() {
		$query = "SELECT country_id, name_min FROM `currency` WHERE is_default = 1";
		
		return DB::getAssocGroup($query);
	}
	
	public function getExchangeRatesDefault() {
		$query 	= "SELECT * FROM `exchange_rates_default`";
		$data 	= DB::getAssocArray($query);
		
		for ($i = 0, $c = count($data); $i < $c; $i++) {
			$result[$data[$i]['country_id']][$data[$i]['currency_id']] = $data[$i]['currency_rates'];
		}
		
		return $result;
	}
	
	public function saveExchanges($rates) {
		foreach ($rates as $country_id => $val) {
			foreach ($val as $currency_id => $rate) {
				DB::insert('exchange_rates_default', array(
					'country_id'		=> $country_id,
					'currency_id'		=> $currency_id,
					'currency_rates'	=> $rate
				), 1);
			}
		}
	}
}