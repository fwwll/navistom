<?php

class ModelStocks {
	
	public function addStock($product_new_id, $currency_id, $price, $price_descr, $content, $date_start, $date_end, $flag, $flag_moder) {
		
	}
	
	public function getProductsNewFromSelect() {
		$products = "SELECT product_new_id, product_name FROM `products_new` WHERE flag = 1 AND flag_moder = 1";
	}
}