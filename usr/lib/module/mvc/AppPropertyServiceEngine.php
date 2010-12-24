<?php
class AppPropertyServiceEngine extends ServiceEngine{
	public function __construct() {
		parent::__construct(new MVCEngine(), new PropertyDeliveryTrait());
	}
}
EngineFactory::register('AppPropertyServiceEngine');
?>