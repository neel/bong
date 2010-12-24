<?php
//namespace Bong\Util;
class MemPool extends Singleton{
	private $pairs = array();
	
	public function set($key, $value){
		$this->pairs[$key] = $value;
	}
	public function get($key){
		if($this->exists($key)){
			return $this->pairs[$key];
		}
		return null;
	}
	public function exists($key){
		return array_key_exists($key, $this->pairs);
	}
}
?>