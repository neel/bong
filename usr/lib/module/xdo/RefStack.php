<?php
class RefStack{
	private $_storage = array();
	
	public function push(&$elem){
		$this->_storage[] =& $elem;
	}
	public function pop(){
		return array_pop($this->_storage);
	}
	public function &top(){
		return $this->_storage[count($this->_storage)-1];
	}
	public function length(){
		return count($this->_storage);
	}
	public function isEmpty(){
		return ($this->length() == 0);
	}
}
?>
