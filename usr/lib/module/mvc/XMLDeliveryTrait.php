<?php
class XMLDeliveryTrait extends AbstractDeliveryTrait{
	public function execute(){
		http::freeze();//a Spirit Cannot send any HTTP Header unless It is used as ResponseService
		$this->engine->executeLogic();
		http::release();
		http::contentType('text/xml');
		return $this->engine->xdo()->toXML()->saveXML();
	}
}
?>