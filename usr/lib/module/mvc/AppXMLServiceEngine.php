<?php
class AppXMLServiceEngine extends ServiceEngine{
	public function __construct() {
		parent::__construct(new MVCEngine(), new XMLDeliveryTrait());
	}
}
EngineFactory::register('AppXMLServiceEngine');
?>