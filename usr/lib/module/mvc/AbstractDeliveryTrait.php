<?php
abstract class AbstractDeliveryTrait{
	/**
	 * @var ContentEngine
	 */
	protected $engine;
	
	final public function setEngine($engine){
		$this->engine = $engine;
	}
	abstract public function execute();
}
?>