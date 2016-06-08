<?php

class Filter {
	public function filterInt($str) {
		return (int) $str;
	}
	
	public function filterFloat($str) {
		return (float) $str;
	}
	
	public function filterString($str) {
		return filter_var($str, FILTER_SANITIZE_STRING);
	}
	
	public function filterURL($str) {
		return filter_var($str, FILTER_SANITIZE_URL);
	}
	
	public function filterEmail($str) {
		return filter_var($str, FILTER_SANITIZE_EMAIL);
	}
}