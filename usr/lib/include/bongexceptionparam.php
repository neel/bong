<?php
final class BongExceptionParam{
	private $_name;
	private $_value;
	private $_mandatory = false;
	private $_defaultValue;
	private $_description = null;
	
	public function __construct($name, $description=null, $mandatory=false, $defaultValue=null){
		$this->_name = $name;
		$this->_mandatory = $mandatory;
		$this->_defaultValue = $defaultValue;
		$this->_description = $description;
	}
	public function valid(){
		return !($this->_mandatory && empty($this->_value));
	}
	public function name(){
		return $this->_name;
	}
	public function value(){
		return $this->_value;
	}
	public function setValue($value){
		return $this->_value = $value;
	}
	public function mandatory(){
		return $this->_mandatory;
	}
	public function optional(){
		return !$this->_mandatory;
	}
	public function hasDefaultValue(){
		return !empty($this->_defaultValue);
	}
	public function defaultValue(){
		return $this->_defaultValue;
	}
	public function description(){
		return $this->_description;
	}
	public function __toString(){
		return $this->value();
	}
}
?>
