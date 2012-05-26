<?php
namespace DB;
final class ConnectionParam{
	private $_key_name   = null;/*PDO Attribute as string*/
	private $_value_name = null;/*PDO Attribute value as string*/
	private $_key        = null;
	private $_value      = null;

	public function __construct($key_name, $value_name){
		$this->_key_name   = $key_name;
		$this->_value_name = $value_name;

		$this->_key   = constant($key_name);
		$this->_value = constant($value_name);
	}
	public function keyName(){
		return $this->_key_name;
	}
	public function valueName(){
		return $thi->_value_name;
	}
	public function key(){
		return $this->_key;
	}
	public function value(){
		return $this->_value;
	}
}
?>