<?php

class Form {
	private $url;
	
	private $doc;
	private $form		= array();
	
	private $inputs 	= array();
	private $selects	= array();
	private $attrs		= array();
	private $values 	= array();
	private $tabs		= array();
	private $tabs_tmp	= array();
	private $hidden		= array();
	private $groups		= array();
	
	private $elems_tmp	= array();
	private $values_tmp	= array();
	
	private $req		= array();
	private $req_types	= array();
	
	private $errors		= array();
	private $flag_error = 0;
	private $flag_send 	= false;
	private $flag_file	= 0;
	
	public $error_text	= 'Не все обязательные поля заполнены!';
	
	public function __construct($url = null, $name = null, $method = 'POST', $class = null) {
		
		$this->url = $url != null ? $url : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		$this->doc = new DOMDocument('1.0', 'UTF-8');

		$this->form = array(
			'method'	=> $method != null ? $method : 'POST',
			'action'	=> $this->url,
			'name'		=> $name != null ? $name : 'ad-form',
			'class'		=> $class != null ? $class : 'ad-form a-clear validation'
		);
		
	}
	
	public function create($type, $name, $title, $value = null, $tab = null) {
		$this->inputs[$name] = array(
			'type'	=> $type,
			'title'	=> $title,
			'value' => $value,
			'tab'	=> $tab
		);
		
		$this->flag_file > 0 ? '' : ($this->flag_file = $type == 'file' ? 1 : 0);
		
		return $this;
	}
	
	public function group($name, $elems) {
		$this->groups[$name] = $elems;
		
		return $this;
	}
	
	public function hide($array) {
		$this->hidden = array_merge($this->hidden, $array);
		return true;
	}
	
	public function attr($name, $key, $value) {
		$this->attrs[$name][] = array(
			'key'	=> $key,
			'value'	=> $value
		);
		
		return $this;
	}
	
	public function setValues($array) {
		$this->values = array_merge($this->values, $array);
		
		return true;
	}
	
	public function required() {
		$this->req = func_get_args();
		
		return true;
	}
	
	public function requiredType($name, $type) {
		$this->req_types[$name] = $type;
		
		return true;
	}
	
	public function createTab($name, $title, $class = null) {
		$this->tabs[$name] = array(
			'title'	=> $title,
			'class' => $class
		);
	}
	
	public function display() {
		
		$form = $this->doc->createElement('form');
		$form->setAttribute('method', $this->form['method']);
		$form->setAttribute('action', $this->form['action']);
		$form->setAttribute('name', $this->form['name']);
		$form->setAttribute('class', $this->form['class']);
		
		if ($this->flag_file > 0)
			$form->setAttribute('enctype', 'multipart/form-data');
		
		if ($this->flag_error > 0) {
			$error = $this->doc->createElement('div');
			$error->setAttribute('class', 'ad-form-error');
			
			$b = $this->doc->createElement('b', $this->error_text);
			$error->appendChild($b);
			$error->appendChild($this->_createErrors());
			
			$this->setValues($this->values_tmp);
		}
		
		foreach ($this->inputs as $key => $val) {
			switch ($val['type']) {
				case 'text':
					$elem = $this->_inputTypeText($key);
				break;
				case 'password':
					$elem = $this->_inputTypePassw($key);
				break;
				case 'pgenerate':
					$this->attr($key, 'class', 'pgenerate');
					$elem = $this->_inputTypePassw($key, 1);
				break;
				case 'hidden':
					$elem = $this->_inputTypeHidden($key);
				break;
				case 'spinner':
					$this->attr($key, 'class', 'spinner');
					$elem = $this->_inputTypeText($key);
				break;
				case 'file':
					$elem = $this->_inputTypeFile($key);
				break;
				case 'select':
					$elem = $this->_select($key);
				break;
				case 'multiple':
					$this->attr($key, 'multiple', 'multiple');
					$elem = $this->_select($key);
				break;
				case 'checkbox':
					$elem = $this->_inputTypeCheckbox($key);
				break;
				case 'switch':
					$this->attr($key, 'class', 'switch-checkbox');
					$elem = $this->_inputTypeCheckbox($key, 1);
				break;
				case 'radio':
					$elem = $this->_inputTypeRadio($key);
				break;
				case 'radiobuttons':
					$this->attr($key, 'class', 'icheck');
					$elem = $this->_inputTypeRadio($key, 1);
				break;
				case 'textarea':
					$elem = $this->_textarea($key);
				break;
				case 'editor':
					$this->attr($key, 'class', 'editor');
					$elem = $this->_textarea($key);
				break;
				case 'code':
					$this->attr($key, 'class', 'code-editor');
					$elem = $this->_textarea($key);
				break;
				case 'date':
					$this->attr($key, 'class', 'datepicker');
					$elem = $this->_inputTypeText($key, 'a-icon-calendar');
				break;
				case 'daterange':
					$elem = $this->_dateRange($key, 'a-icon-calendar');
				break;
				case 'time':
					$this->attr($key, 'class', 'timepicker');
					$elem = $this->_inputTypeText($key, 'a-icon-time');
				break;
				case 'datetime':
					$this->attr($key, 'class', 'datetimepicker');
					$elem = $this->_inputTypeText($key, 'a-icon-calendar');
				break;
				case 'title':
					$elem = $this->doc->createElement('h4');
					$b = $this->doc->createElement('b', $val['title']);
					$elem->appendChild($b);
					
					$elem->setAttribute('id', $key);
					$elem->setAttribute('class', 'ad-form-title');
				break;
				case 'uploader':
					$elem = $this->_uploader($key);
				break;
				default:
					$elem = $this->doc->createElement($val['type'], $val['value']);
					$elem->setAttribute('id', $key);
					$elem->setAttribute('class', $val['title']);
					
					foreach ($this->attrs[$key] as $val) {
						$elem->setAttribute($val['key'], $val['value']);
					}
				break;
			}
			
			if ($val['tab'] != null) {
				$this->tabs_tmp[$val['tab']][] = $elem;
			} 
			else {
				$form->appendChild($elem);
			}
		}
		
		if (count($this->tabs) > 0) {
			$tabs = $this->_tab();
		
			$b = 0;
			foreach ($this->tabs as $key => $val) {
				$class = $b == 0 ? 'visible' : '';
					
				$box = $this->_tabsBox($class);
				
				if ($b == 0 and $this->flag_error > 0)
					$box->appendChild($error);
				
				for ($i = 0, $c = count($this->tabs_tmp[$key]); $i < $c; $i++) {
					$box->appendChild($this->tabs_tmp[$key][$i]);
				}
				
				$tabs->appendChild($box);
				$b++;
			}
			
			$form->appendChild($tabs);
		}
		
		return $this->doc->saveHTML($form);
	}
	
	public function destroy($link_save, $link_apply) {
		if ($this->flag_send) {
			$url = $this->flag_send == 1 ? $link_save : $link_apply;
			
			header("Location: {$url}");
			die();
		}
		
		return false;
	}
	
	public function isSend() {
		if (Request::post('form-save') != null)
			$this->flag_send = 1;
		elseif (Request::post('form-apply') != null)
			$this->flag_send = 2;
		else 
			$this->flag_send = false;
			
		return $this->flag_send;
	}
	
	public function checkForm() {
		for ($i = 0, $c = count($this->req); $i < $c; $i++) {
			if (Str::get(Request::post($this->req[$i]))->isNull())
				continue;
			else {
				$this->values_tmp = $this->form['method'] == 'POST' ? $_POST : $_GET;
				$this->errors[] = array(
					'name'	=> $this->req[$i],
					'type'	=> 1,
					'title'	=> $this->inputs[$this->req[$i]]['title']
				);
				
				$this->flag_error = 1;
			}
		}
		
		return $this->flag_error > 0 ? false : true;
	}
	
	private function _createErrors() {
		$ul = $this->doc->createElement('ul');
		
		for ($i = 0, $c = count($this->errors); $i < $c; $i++) {
			$li = $this->doc->createElement('li', $this->errors[$i]['title']);
			$ul->appendChild($li);
		}
		
		return $ul;
	}
	
	private function _tab() {
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', 'section');
		
		$ul = $this->doc->createElement('ul');
		$ul->setAttribute('class', 'tabs a-clear');
		
		$i = 0;
		foreach ($this->tabs as $key => $val) {
			$li = $this->doc->createElement('li', $val['title']);
			if ($i == 0)
				$li->setAttribute('class', 'current');
				
			$ul->appendChild($li);
			$i++;
		}
		
		$parent->appendChild($ul);
		
		return $parent;
	}
	
	private function _tabsBox($class = null) {
		$parent = $this->doc->createElement('div');
		if ($class != null)
			$parent->setAttribute('class', 'box '.$class);
		else 
			$parent->setAttribute('class', 'box');
		
		return $parent;
	}
	
	private function _uploader($name, $count = 8) {
		$ul = $this->doc->createElement('ul');
		$ul->setAttribute('class', 'uploader');
		$ul->setAttribute('id', $name);
		
		$c = count($this->values[$name]) < 8 ? 8 : count($this->values[$name]);
		
		for ($i = 0; $i < $c; $i++) {
			$li = $this->doc->createElement('li', '');
			//$li->setAttribute('id', $i);
			
			if (count($this->values[$name]) > 0) {
				$img = $this->doc->createElement('img');
				$img->setAttribute('src', current($this->values[$name]));
				$img->setAttribute('alt', key($this->values[$name]));
				
				$input = $this->doc->createElement('input');
				$input->setAttribute('type', 'hidden');
				$input->setAttribute('value', key($this->values[$name]));
				$input->setAttribute('name', 'images[]');
				
				$li->setAttribute('class', 'image-added');
				$li->appendChild($input);
				$li->appendChild($img);
				
				unset($this->values[$name][key($this->values[$name])]);
			}
			
			$ul->appendChild($li);
		}
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$row_class = array_search($name, $this->hidden) !== false ? 'a-row a-row-hide' : 'a-row'; 
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', $row_class);
		
		$parent->appendChild($label);
		$parent->appendChild($ul);
		
		return $parent;
	}
	
	private function _dateRange($name, $icon = null) {
		$from = $this->doc->createElement('input');
		$from->setAttribute('name', 'start_'.$name);
		$from->setAttribute('id', 'start_'.$name);
		$from->setAttribute('type', 'text');
		$from->setAttribute('class', 'datepicker-from');
		$from->setAttribute('placeholder', 'дата начала');
		
		if ($this->values['start_'.$name] !== null) 
			$from->setAttribute('value', $this->values['start_'.$name]);
		
		$to = $this->doc->createElement('input');
		$to->setAttribute('name', 'end_'.$name);
		$to->setAttribute('id', 'end_'.$name);
		$to->setAttribute('type', 'text');
		$to->setAttribute('class', 'datepicker-to');
		$to->setAttribute('placeholder', 'дата окончания');
		
		if ($this->values['end_'.$name] !== null) 
			$to->setAttribute('value', $this->values['end_'.$name]);
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$row_class = array_search($name, $this->hidden) !== false ? 'a-row a-row-hide' : 'a-row'; 
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', $row_class);
		
		$parent->appendChild($label);
		$parent->appendChild($to);
		$parent->appendChild($from);
		
		if($icon != null) {
			$icon_elem = $this->doc->createElement('i', '');
			$icon_elem->setAttribute('class', $icon);
			
			$parent->appendChild($icon_elem);
		}
		
		return $parent;
	}
	
	private function _inputTypeText($name, $icon = null) {
		$input = $this->doc->createElement('input');
		$input->setAttribute('name', $name);
		$input->setAttribute('id', $name);
		$input->setAttribute('type', 'text');
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				if ($this->attrs[$name][$i]['key'] == 'class')
					$classes[] = $this->attrs[$name][$i]['value'];
				else {
					$input->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
				}
			}
		}
		
		if ($this->values[$name] !== null) 
			$input->setAttribute('value', $this->values[$name]);
		
		$valid = $this->_createValidationClass($name);
		
		if ($valid) 
			$classes[] = $valid;
		
		if (count($classes) > 0) {
			$classes = @implode(' ', $classes);
			$input->setAttribute('class', $classes);
		}
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$row_class = array_search($name, $this->hidden) !== false ? 'a-row a-row-hide' : 'a-row'; 
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', $row_class);
		
		$parent->appendChild($label);
		$parent->appendChild($input);
		
		if($icon != null) {
			$icon_elem = $this->doc->createElement('i', '');
			$icon_elem->setAttribute('class', $icon);
			
			$parent->appendChild($icon_elem);
		}
		
		return $parent;
	}
	
	private function _inputTypeHidden($name) {
		$input = $this->doc->createElement('input');
		$input->setAttribute('name', $name);
		//$input->setAttribute('id', $name);
		$input->setAttribute('type', 'hidden');
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				$input->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
			}
		}
		
		$input->setAttribute('value', $this->inputs[$name]['value']);
		
		return $input;
	}
	
	private function _inputTypeFile($name) {
		$input = $this->doc->createElement('input');
		$input->setAttribute('name', $name);
		$input->setAttribute('id', $name);
		$input->setAttribute('type', 'file');
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				if ($this->attrs[$name][$i]['key'] == 'class')
					$classes[] = $this->attrs[$name][$i]['value'];
				else {
					$input->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
				}
			}
		}
		
		if ($this->values[$name] != null) {
			$input->setAttribute('value', $this->values[$name]);
			$input->setAttribute('title', $this->values[$name]);
		}
		
		$valid = $this->_createValidationClass($name);
		
		if ($valid) 
			$classes[] = $valid;
		
		if (count($classes) > 0) {
			$classes = @implode(' ', $classes);
			$input->setAttribute('class', $classes);
		}
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', 'a-row');
		
		$parent->appendChild($label);
		$parent->appendChild($input);
		
		return $parent;
	}
	
	private function _inputTypePassw($name, $flag_generate = 0) {
		$input = $this->doc->createElement('input');
		$input->setAttribute('name', $name);
		$input->setAttribute('id', $name);
		
		$type = $flag_generate > 0 ? 'text' : 'password';
		$input->setAttribute('type', $type);
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				if ($this->attrs[$name][$i]['key'] == 'class')
					$classes[] = $this->attrs[$name][$i]['value'];
				else {
					$input->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
				}
			}
		}
		
		if ($this->values[$name] != null) 
			$input->setAttribute('value', $this->values[$name]);
		
		$valid = $this->_createValidationClass($name);
		
		if ($valid) 
			$classes[] = $valid;
		
		if (count($classes) > 0) {
			$classes = @implode(' ', $classes);
			$input->setAttribute('class', $classes);
		}
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', 'a-row');
		
		$parent->appendChild($label);
		$parent->appendChild($input);
		
		if ($flag_generate > 0) {
			$link = $this->doc->createElement('a', 'Генерировать');
			$link->setAttribute('class', 'pass-generate');
			$link->setAttribute('href', '#');
			
			$parent->appendChild($link);
		}
		
		return $parent;
	}
	
	private function _inputTypeCheckbox($name, $flagLabel = 0) {
		$input = $this->doc->createElement('input');
		$input->setAttribute('name', $name);
		$input->setAttribute('id', $name);
		$input->setAttribute('type', 'checkbox');
		$input->setAttribute('value', $this->inputs[$name]['value']);
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				if ($this->attrs[$name][$i]['key'] == 'class')
					$classes[] = $this->attrs[$name][$i]['value'];
				else {
					$input->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
				}
			}
		}
		
		if ($this->values[$name] == $this->inputs[$name]['value']) 
			$input->setAttribute('checked', 'checked');
		
		$valid = $this->_createValidationClass($name);
		
		if ($valid) 
			$classes[] = $valid;
		
		if (count($classes) > 0) {
			$classes = @implode(' ', $classes);
			$input->setAttribute('class', $classes);
		}
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$row_class = array_search($name, $this->hidden) !== false ? 'a-row a-row-hide' : 'a-row'; 
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', $row_class);
		
		if ($flagLabel > 0) {
			$parent->appendChild($label);
			$parent->appendChild($input);
		}
		else {
			$label_emu = $this->doc->createElement('label', '&nbsp;');
			$parent->appendChild($label_emu);
			
			$parent->appendChild($input);
			$parent->appendChild($label);
		}
		
		return $parent;
	}
	
	private function _inputTypeRadio($name, $flagNoDiv) {
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				if ($this->attrs[$name][$i]['key'] == 'class')
					$classes[] = $this->attrs[$name][$i]['value'];
				else {
					$input->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
				}
			}
		}
		
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', 'a-row');
		
		$parent->appendChild($label);
		
		if (is_array($this->inputs[$name]['value'])) {
			$array = $this->inputs[$name]['value'];
			
			$valid = $this->_createValidationClass($name);
		
			if ($valid) 
				$classes[] = $valid;
			
			if (count($classes) > 0) {
				$classes = @implode(' ', $classes);
			}
			
			foreach ($array as $key => $val) {
				$radio = $this->doc->createElement('input');
				$radio->setAttribute('type', 'radio');
				$radio->setAttribute('name', $name);
				$radio->setAttribute('value', $key);
				$radio->setAttribute('id', $name.'-'.$key);
				$radio->setAttribute('class', $classes);
				
				if ($this->values[$name] === $key) 
					$radio->setAttribute('checked', 'checked');
				
				$label = $this->doc->createElement('label', $val);
				$label->setAttribute('class', 'radio-title');
				$label->setAttribute('for', $name.'-'.$key);
				
				if ($flagNoDiv > 0) {
					$parent->appendChild($radio);
					$parent->appendChild($label);
				}
				else {
					$row_class = array_search($name, $this->hidden) !== false ? 'a-row a-row-hide' : 'a-row'; 
					$parent = $this->doc->createElement('div');
					$parent->setAttribute('class', $row_class);
					
					$parent_r->appendChild($radio);
					$parent_r->appendChild($label);
					
					$parent->appendChild($parent_r);
				}
				
			}
			
			return $parent;
		}
	}
	
	private function _textarea($name) {
		$textarea = $this->doc->createElement('textarea', '');
		$textarea->setAttribute('name', $name);
		$textarea->setAttribute('id', $name);
		
		
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				if ($this->attrs[$name][$i]['key'] == 'class')
					$classes[] = $this->attrs[$name][$i]['value'];
				else {
					$textarea->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
				}
			}
		}
		
		if ($this->values[$name] != null) {
			$value = $this->doc->createTextNode($this->values[$name]);
			$textarea->appendChild($value);
		}
		
		$valid = $this->_createValidationClass($name);
		
		if ($valid) 
			$classes[] = $valid;
		
		if (count($classes) > 0) {
			$classes = @implode(' ', $classes);
			$textarea->setAttribute('class', $classes);
		}
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$row_class = array_search($name, $this->hidden) !== false ? 'a-row a-row-hide' : 'a-row'; 
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', $row_class);
		
		$parent->appendChild($label);
		$parent->appendChild($textarea);
		
		return $parent;
	}
	
	private function _select($name) {
		$select = $this->doc->createElement('select');
		$select->setAttribute('name', $name);
		$select->setAttribute('id', $name);
		$this->attr($name, 'class', 'select-2');
		
		if (count($this->attrs[$name]) > 0) {
			for ($i = 0, $c = count($this->attrs[$name]); $i < $c; $i++) {
				if ($this->attrs[$name][$i]['key'] == 'class')
					$classes[] = $this->attrs[$name][$i]['value'];
				else {
					$select->setAttribute($this->attrs[$name][$i]['key'], $this->attrs[$name][$i]['value']);
				}
			}
		}
		
		$valid = $this->_createValidationClass($name);
		
		if ($valid) 
			$classes[] = $valid;
		
		if (count($classes) > 0) {
			$classes = @implode(' ', $classes);
			$select->setAttribute('class', $classes);
		}
		
		foreach ($this->inputs[$name]['value'] as $key => $val) {
			$option = $this->doc->createElement('option', htmlspecialchars($val));
			$option->setAttribute('value', $key);

			if (is_array($this->values[$name])) {
				if (array_search($key, $this->values[$name]) !== false) 
					$option->setAttribute('selected', 'selected');
			}
			else {
				if ($this->values[$name] != null) {
					if ($this->values[$name] == $key) 
						$option->setAttribute('selected', 'selected');
				}
			}
			
			$select->appendChild($option);
		}
		
		$label = $this->doc->createElement('label');
		$label->setAttribute('for', $name);
		
		if (@array_search($name, $this->req) !== false) {
			$font = $this->doc->createElement('font', '*');
			$font->setAttribute('class', 'a-req');
			$label->appendChild($font);
		}
		
		$title = $this->doc->createTextNode($this->inputs[$name]['title']);
		$label->appendChild($title);
		
		$row_class = array_search($name, $this->hidden) !== false ? 'a-row a-row-hide' : 'a-row'; 
		$parent = $this->doc->createElement('div');
		$parent->setAttribute('class', $row_class);
		
		$parent->appendChild($label);
		$parent->appendChild($select);
		
		return $parent;
	}
	
	private function _createValidationClass($name) {
		if (@array_search($name, $this->req) !== false and $this->req_types[$name] == null) {
			return 'validate[required]';
		}
		elseif (@array_search($name, $this->req) === false and $this->req_types[$name] != null) {
			return 'validate[' . $this->req_types[$name] . ']';
		}
		elseif (@array_search($name, $this->req) !== false and $this->req_types[$name] != null) {
			return 'validate[required, ' . $this->req_types[$name] . ']';
		}
		
		return false;
	}
}