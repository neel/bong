<?php
class SpiritJSONServiceEngine extends ServiceEngine {
	public function __construct() {
		parent::__construct(new SpiritEngineProxy(), new JSONDeliveryTrait());
	}
}
EngineFactory::register('SpiritJSONServiceEngine');
?>