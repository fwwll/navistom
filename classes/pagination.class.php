<?php

class Pagination {
	
	private $_result = array();
	
	public function __construct($total_count, $page = 1, $items_count = 10, $visible_count = 5) {
		$this->getPaginationList($total_count, $items_count, $page, $visible_count);
		$this->getURL();
		
		return $this;
	}
	
	public function get() {
		return $this->_result;
	}
	
	private function getPaginationList($total_count, $items_count, $page, $visible_count) {
		$total_pages	= (int) ceil($total_count / $items_count);
		$page			= $page > 0 ? (int) $page : 1;
		$page_next		= $page != $total_pages ? $page + 1 : $total_pages;
		$page_prev		= $page > 1 ? $page - 1 : 1;
		
		$limit			= ($page - 1) * $items_count;
		
		$this->_result +=  array(
			'total'		=> $total_pages,
			'current'	=> $page,
			'next'		=> $page_next,
			'prev'		=> $page_prev,
			'limit'		=> $limit . ', ' . $items_count,
			'limit_full'=> 'LIMIT ' . $limit . ', ' . $items_count,
			'pages'		=> $this->getPagesArray($page, $total_pages, $page_next, $visible_count)
		);
	}
	
	private function getURL() {
		$get = preg_replace("/[^\w+\d+_]$/i", "" , $_GET['route']);
		
		$this->_result += array(
			'get_vars'	=> $get,
			'no_page'	=> preg_replace("/(\/?page-\d+)/i", "" , $get)
		);
	}
	
	private function getPagesArray($page, $last_page, $page_next, $visible_count) {
		$array = array();
		
		if ($page == 1) {
			if ($page_next == $page) return array();
			
			for ($i = 0; $i < $visible_count; $i++) {
				if ($i == $last_page) break;
				array_push($array, $i + 1);
			}
			return $array;
		}

		if ($page == $last_page) {
			$start = $last_page - $visible_count;
			if ($start < 1) $start = 0;
			for ($i = $start; $i < $last_page; $i++)
			{
				array_push($array, $i + 1);
			}
			return $array;
		}

		$start = $page - $visible_count;
		if ($start < 1) $start = 0;
		for ($i = $start; $i < $page; $i++) {
			array_push($array, $i + 1);
		}
		for ($i = ($page + 1); $i < ($page + $visible_count); $i++) {
			if ($i == ($last_page + 1)) break;
			array_push($array, $i);
		}
		return $array;
	}
}