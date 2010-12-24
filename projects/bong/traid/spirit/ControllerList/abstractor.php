<?php
/**
 * \controller ControllerList
 */
class ControllerListAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, ControllerFeeded, FloatingSpirit{
	public function main(){
		$this->xdo->controllerName = isset($this->controller->xdo->controllerName) ? $this->controller->xdo->controllerName : null;
		$this->data->controllers = $this->controller->data->controllers;
	}
	public function addNew(){
		
	}
}
?>
