<?php
class SpiritResponseServiceEngine extends ServiceEngine {
	public function __construct() {
		parent::__construct(new SpiritEngineProxy(), new ResponseDeliveryTrait());
	}
}
EngineFactory::register('SpiritResponseServiceEngine');
?>