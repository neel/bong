<?php
class SpiritXMLServiceEngine extends ServiceEngine {
	public function __construct() {
		parent::__construct(new SpiritEngineProxy(), new XMLDeliveryTrait());
	}
}
EngineFactory::register('SpiritXMLServiceEngine');
?>