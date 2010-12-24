<?php
class JSONDeliveryTrait extends AbstractDeliveryTrait {
	public function execute(){
		http::freeze();//a Spirit Cannot send any HTTP Header unless It is used as ResponseService
		$this->engine->executeLogic();
		http::release();
		http::contentType('application/json');
		return json_encode($this->engine->xdo());
	}
}
?>