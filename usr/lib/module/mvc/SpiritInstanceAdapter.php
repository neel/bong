<?php
final class SpiritInstanceAdapter extends AbstractSpiritAdapter{
	private $_spiritName;
	private $_instanceId;
	/**
	 * @var SpiritAadpter
	 */
	private $_adapter;
	
	public function __construct($adapter, $spiritName, $instanceId){
		$this->_adapter = $adapter;
		$this->_spiritName = $spiritName;
		$this->_instanceId = $instanceId;
	}
	public function call($methodName, $args=array()){
		$this->_adapter->_setActiveInstance($this->_instanceId);
		$buff = $this->_adapter->call($methodName, $args);
		$this->_adapter->_clearActiveInstance();
		return $buff;
	}
}
?>