<?php
class _XMLType{
	private $_type;
	
	public function __construct($typeName){
		$this->_type = $typeName;
	}
	public function isScaler(){
		return in_array($this->_type, array('integer', 'string', 'null', 'boolean'));
	}
	public function type(){
		return $this->_type;
	}
	public function __toString(){
		return $this->type();
	}
	public function isObject(){
		return ($this->_type == 'object');
	}
	public function isSequence(){
		return ($this->_type == 'array' || $this->_type == 'sequence');
	}
	public function isRecursive(){
		return ($this->_type == 'recursion');
	}
	public function isMeta(){
		return ($this->_type == 'meta');
	}
}

function XMLType($nodeType){
	static $_typeLib = array('array' => 'sequence', 'NULL' => 'null');
	$typeName = gettype($nodeType);
	if(array_key_exists($typeName, $_typeLib))
		return new _XMLType($_typeLib[$typeName]);
	else
		return new _XMLType($typeName);
}
function XMLTypeFromTypeName($typename){
	static $_typeLib = array('array' => 'sequence', 'NULL' => 'null');
	$typeName = $typename;
	if(array_key_exists($typeName, $_typeLib))
		return new _XMLType($_typeLib[$typeName]);
	else
		return new _XMLType($typeName);
}
?>