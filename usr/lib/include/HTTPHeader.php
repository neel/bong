<?php
final class HTTPHeader{
	const Singleton = 0x001;
	
	private $_key;
	private $_value;
	
	public function __construct($key, $value=null){
		$this->_key = $key;
		$this->_value = $value;
	}
	public function toString(){
		return $this->_key.':'.$this->_value;
	}
	public function __toString(){
		return $this->toString();
	}
	public function key(){
		return $this->_key;
	}
	public function value(){
		return $this->_value;
	}
	public function setValue($value){
		if(HTTPHeaders::freezed())
			return;
		$this->_value = $value;
	}
}
?>