<?php
abstract class AbstractContentRouter{
	protected /* AbstractBongEngine*  */ $engine = null;
	protected $navigation;
	protected $projectName = null;
	protected $_engine = null;
	private $_enginePrepared = false;
	
	public function __construct($engineName){
		$this->navigation = new StdClass();
		$this->_engine = EngineFactory::produce($engineName);
	}
	final public function setProjectName($projectName){
		$this->projectName = $projectName;
	}
	final public function engine(){
		return $this->_engine;
	}
	public function prepareEngine(){
		$this->_engine->setProjectName($this->projectName);
		$this->_engine->setNavigation($this->navigation);
		$this->_engine->flagAsReady();//Flag the Engine as Prepared e.g. Ready to Run
		$this->_enginePrepared = true;
	}
	final public function enginePrepared(){
		return $this->_enginePrepared;
	}
	abstract public function buildNavigation($path);
}
?>