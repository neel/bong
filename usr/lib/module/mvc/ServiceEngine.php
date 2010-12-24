<?php
abstract class ServiceEngine extends ContentEngine{
	/**
	 * @var ContentEngine
	 */
	protected $engine;
	protected $deliveryTrait;
	
	public function __construct(&$engine, &$deliveryTrait){
		$this->engine = $engine;
		$this->deliveryTrait = $deliveryTrait;
	}
	final private function prepareInternalEngine(){
		$this->engine->setProjectName($this->projectName);
		$this->engine->setNavigation($this->navigation);
		$this->engine->flagAsReady();//Flag the Engine as Prepared e.g. Ready to Run
	}
	final public function executeLogic(){
		$this->prepareInternalEngine();
		//$this->engine->executeLogic();
	}
	final public function run(){
		$this->executeLogic();
		$this->deliveryTrait->setEngine($this->engine);
		$this->engine->responseBuffer = $this->deliveryTrait->execute();
	}
	final public function response(){
		return $this->engine->response();
	}
}
?>