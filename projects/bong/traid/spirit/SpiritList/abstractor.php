<?php
/**
 * \controller ControllerList
 */
class SpiritListAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, ControllerFeeded, FloatingSpirit{
	public function main(){
		$this->data->spirits = $this->controller->data->spirits;
	}
	public function addNew(){}
	public function addNewForm(){}
}
?>
