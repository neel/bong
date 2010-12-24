<?php
class SpiritMemoryXDO extends SpiritXDO {
	protected function fileName(){}
	public function serialized(){
		return false;
	}
	public function serialize(){}
	//public function __sessionFilePath(){
	//	return null;
	//}
	public function unserialize(){}
	public function flush(){}
}

?>