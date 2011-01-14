<?php
/**
 * \controller Sidebar
 */
class SidebarAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, ControllerFeeded, FloatingSpirit{
	public function main(){
		$this->data->project = $this->controller->data->explorer;
		$this->xdo->controllerName = isset($this->controller->xdo->controllerName) ? $this->controller->xdo->controllerName : null;
		$this->data->controllers = $this->controller->data->controllers;
		$this->data->spirits = $this->controller->data->spirits;
	}
}
?>
