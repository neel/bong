<?php
final class SpiritAdapter extends AbstractSpiritAdapter{
	/**
	 * SpiritInstanceName -1-------1- SpiritInstanceAdapter
	 */
	private $_instances = array();
	private $spiritName;
	/**
	 * @var SpiritEngine
	 */
	private $spiritEngine = null;
	
	private $_activeInstanceId = null;
	
	public function __construct($spiritName, &$spiritEngine){
		$this->spiritName = $spiritName;
		$this->spiritEngine = $spiritEngine;
	}
	/**
	 * \virtual
	 * @param string $methodName
	 * @param array $args
	 */
	public function call($methodName, $args=array()){
		$this->spiritEngine->setActiveInstance($this->_activeInstanceId);
		http::freeze();//a Spirit Cannot send any HTTP Header unless It is used as ResponseService. as the spirit output is embedded in the app output. so its the app that sends the header. not the spirit
		$this->spiritEngine->run($this->spiritName, $methodName, $args);
		http::release();
		//Engine Automatically Clear's the Active Instance Id So no need to call clear Explecitely 
		return $this->spiritEngine->response();
	}
	public function instance($instanceId){
		if(!array_key_exists($instanceId, $this->_instances)){
			$instance = new SpiritInstanceAdapter($this, $this->spiritName, $instanceId);
			$this->_instances[$instanceId] = &$instance;
		}
		return $this->_instances[$instanceId];
	}
	public function _setActiveInstance($instanceId){
		$this->_activeInstanceId = $instanceId;
	}
	public function _clearActiveInstance(){
		$this->_activeInstanceId = null;
	}
}

?>
