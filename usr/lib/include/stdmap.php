<?php
class StdMap{
	private $elems = array();
	
	public function set($key, $value){
		$this->elems[$key] = $value;
	}
	public function exists($key){
		return array_key_exists($key, $this->elems);
	}
	public function get($key){
		if($this->exists($key))
			return $this->elems[$key];
		return null;
	}
	
	public function __set($key, $value){
		return $this->set($key, $value);
	}
	public function __get($key){
		return $this->get($key);
	}
}
?>