<?php
/**
 * \controller ControllerProperties
 */
class ControllerPropertiesAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, ControllerFeeded, FloatingSpirit{
	public function main(){
		$this->data->controller = $this->controller->data->controller;
	}
	public function components(){
		$this->data->layout = $this->controller->data->controller->layout();
		$this->data->params = $this->controller->data->controller->params();
		$this->data->view = $this->controller->data->controller->view();
	}
}
?>
