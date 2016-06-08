<?php

class Stocks {
	public function index() {
		
		echo Registry::get('twig')->render('stocks.tpl', array(
			'title'		=> 'Все акции'
		));
	}
	
	public function add() {
		$form = new Form();
		
		$form->create('select', 'product_new_id', 'Товар', array());
		$form->create('text', 'price', 'Акционная цена');
		$form->create('select', 'currency_id', 'Валюта', array());
		$form->create('textarea', 'content', 'Описание акции');
		$form->create('daterange', 'date_range', 'Период действия акции');
		
		echo Admin::displayFormTPL(
			$form->display(),
			'Добавить акционное предложение',
			'Добавить акционное предложение'
		);
	}
}