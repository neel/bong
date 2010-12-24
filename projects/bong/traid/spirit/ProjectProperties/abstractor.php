<?php
/**
 * \controller ProjectProperties
 */
class ProjectPropertiesAbstractor extends SpiritAbstractor implements StaticBound, MemoryXDO, ControllerFeeded, FloatingSpirit{
	public function main(){
		$this->data->project = $this->controller->data->project;
	}
	public function properties(){
		$this->data->name = $this->controller->data->project->name();
		$this->data->dir = $this->controller->data->project->dir();
	}
	public function components(){
		$this->data->layout = $this->controller->data->project->layout();
		$this->data->params = $this->controller->data->project->params();
		$this->data->view = $this->controller->data->project->view();
	}
}
?>
