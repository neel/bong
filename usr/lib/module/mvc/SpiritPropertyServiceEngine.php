<?php
class SpiritPropertyServiceEngine extends ServiceEngine {
	public function __construct() {
		parent::__construct(new SpiritEngineProxy(), new PropertyDeliveryTrait());
	}
}
EngineFactory::register('SpiritPropertyServiceEngine');
?>