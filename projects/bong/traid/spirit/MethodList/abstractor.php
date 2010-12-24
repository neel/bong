<?php
/**
 * \controller MethodList
 */
class MethodListAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, ControllerFeeded, SessionedSpirit{
	public function main(){
		$this->data->controller = $this->controller->data->controller;
	}
	public function addNew(){
		
	}
	public function addNewView($methodName){
		$this->data->methodName = $methodName;
	}
}
?>
