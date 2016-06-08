<?php

class Table {
	public $doc;
	
	private $data 		= array();
	private $name		= null;
	private $class		= null;
	
	private $cols		= array();
	private $options 	= array();
	
	public function __construct($data, $class = null) {
		
		$this->doc = new DOMDocument('1.0', 'UTF-8');
		$this->doc->formatOutput 		= true;
		$this->doc->preserveWhiteSpace 	= false;
		
		$this->data 	= $data;
		$this->class 	= $class;
		
		return true;
	}
	
	public function setCol($name, $title, $class = null, $type = null) {
		$this->cols[$name] 	= array(
			'title' => $title,
			'class' => $class,
			'type'	=> $type
		);
		
		return true;
	}
	
	public function setOption($link, $icon_class, $title = null) {
		$this->options[] = array(
			'link'	=> $link,
			'icon'	=> $icon_class,
			'title'	=> $title
		);
		
		return true;
	}
	
	public function display() {
		$table = $this->doc->createElement('table');
		$tbody = $this->doc->createElement('tbody');
		
		if ($this->class != null)
			$table->setAttribute('class', $this->class);
		
		$thead = $this->doc->createElement('thead');
		$tr = $this->doc->createElement('tr');
		
		foreach ($this->cols as $key => $val) {
			$th = $this->doc->createElement('th', $val['title']);
			$val['class'] != null ? $th->setAttribute('class', $val['class']) : '';
			
			$tr->appendChild($th);
		}
		
		$thead->appendChild($tr);
		$table->appendChild($thead);
		
		for ($i = 0, $c = count($this->data); $i < $c; $i++) {
			$tr = $this->_createTr($this->data[$i]);
			$tbody->appendChild($tr);
		}
		
		$table->appendChild($tbody);
		
		$table->setAttribute('cols', count($this->cols));
		
		return $this->doc->saveXML($table);
	}
	
	private function createOptions() {
		
	}

	
	private function _createTr($array) {
		$tr = $this->doc->createElement('tr');
		
		foreach ($array as $key => $val) {
			$td = $this->doc->createElement('td', $val);
			$tr->appendChild($td);
		}
		
		return $tr;
	}
}

?>