<?php
class AppJSONServiceEngine extends ServiceEngine{
	public function __construct() {
		parent::__construct(new MVCEngine(), new JSONDeliveryTrait());
	}
}
EngineFactory::register('AppJSONServiceEngine');
?>