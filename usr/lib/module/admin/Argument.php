<?php
namespace Structs\Admin;
final class Argument extends Struct{
	const Type_Auto = 0xAB00;
	const Type_String = 0xAB01;
	const Type_Numeric = 0xAB02;
	
	private $_name;
	private $_type;
	private $_default;
	private $_value=null;
	private $_method = null;
	
	/**
	 * 
	 * @param Method $method
	 * @param [string|ReflectionProperty] $reflectionOrName
	 * @param string $type
	 * @param bool $default
	 * @param scaler $value
	 */
	public function instance($method, $reflectionOrName, $type=null, $default=null, $value=null){
		$this->_method = $method;
		if(is_string($reflectionOrName)){
			$name = $reflectionOrName;
			$this->_create($name, $type, $default, $value);
		}else if(is_object($reflectionOrName) && get_class($reflectionOrName) == 'ReflectionParameter'){
			$reflection = $reflectionOrName;
			$this->_createFromReflection($reflection);
		}
		//$this->_method->addArgument($this);
	}
	/**
	 * @load
	 * @param ReflectionParameter $reflection
	 */
	private function _createFromReflection($reflection){
		$this->_name = $reflection->getName();
		if($reflection->isDefaultValueAvailable()){
			$this->_default = true;
			$this->_value = $reflection->getDefaultValue();
		}
	}
	/**
	 * Doesn't Write the Argument to Code File. Rather its Used to Keep the Data Structure and Its written nby teh Method::_create()
	 * 
	 * @store
	 * @param string $name
	 * @param enum $type
	 * @param bool $default
	 * @param scaler $value
	 */
	private function _create($name, $type, $default, $value){
		$this->_name = $name;
		$this->_type = $type;
		$this->_default = $default;
		$this->_value = $value;
	}
	
	public function name(){
		return $this->_name;
	}
	public function type(){
		return $this->_type;
	}
	public function isDefault(){
		return $this->_default;
	}
	public function defaultValue(){
		return $this->_value;
	}
	public function method(){
		return $this->_method;
	}
	public function doc(){}
	public function stringify(){
		return ('$'.$this->_name.($this->_default ? "=".(is_string($this->_value) ? "'" : '').$this->_value.(is_string($this->_value) ? "'" : '') : ''));
	}
	public function toString(){
		return $this->stringify();
	}
}
?>