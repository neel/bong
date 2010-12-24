<?php
class AppResponseServiceEngine extends ServiceEngine{
	public function __construct() {
		parent::__construct(new MVCEngine(), new ResponseDeliveryTrait());
	}
}
EngineFactory::register('AppResponseServiceEngine');
?>