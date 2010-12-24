<?php
class ResponseDeliveryTrait extends AbstractDeliveryTrait{
	public function execute(){
		ControllerTray::instance()->renderLayout = false;
		$this->engine->run();
		return $this->engine->response();
	}
}
?>