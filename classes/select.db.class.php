<?php

class DBObject {
	private $DBO;
	
	private $_from		= '';
	private $_cols 		= array();
	private $_where		= array();
	private $_having	= array();
	private $_limit		= array();
	private $_join		= array();
	private $_left		= array();
	private $_using		= array();
	
	public function __construct($name, $host, $user, $passw, $charset = 'utf8') {
		try {
			$this->DBO = new PDO("mysql:host={$host};dbname={$name}", $user, $passw, array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset}" ));
		
			return $this;
		}
		catch (PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public function Select() {
		$this->_cols = func_get_args();
		
		return $this;
	}
	
	public function From($table) {
		$this->_from = $table;
		
		return $this;
	}
	
	public function Where($where = array()) {
		$this->_where = $where;
		
		return $this;
	}
	
	public function Limit($from, $count) {
		$this->_limit = array($from, $count);
		
		return $this;
	}
	
	public function join($table) {
		$this->_join[] = $table;
		
		return $this;
	}
	
	public function Using($key) {
		$this->_using[] = $key;
		
		return $this;
	}
	
	public function fetchAll() {
		$query = 	'SELECT ' 	. implode(', ', $this->_cols) . 
					' FROM ' 	. $this->_from .
					' WHERE ' 	. $this->_whereReplace($this->_where);	
					
		$query = $this->DBO->query($query);
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	private function _whereReplace($where) {
		if ($where != null and is_array($where)) {
			foreach ($where as $key => $val)
				$_w[] = $key.' = '.$this->DBO->quote($val);
			$where = @implode(' AND ', $_w);
		}
		else 
			$where = 1;
			
		return $where;
	}
}