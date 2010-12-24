<?php
abstract class MemoryXDO extends AbstractXDO{
	public function serialized(){
		return false;
	}
	protected function fileName(){}
	public function serialize(){}
	public function __sessionFilePath(){
		return null;
	}
	public function unserialize(){}
	public function flush(){}
}
?>