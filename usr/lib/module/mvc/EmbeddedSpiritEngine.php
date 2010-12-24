<?php
class EmbeddedSpiritEngine extends SpiritEngine{
	private $_abstractor = null;
	
	public function __construct(&$spiritEngine, &$abstractor){
		$this->_controller =& $spiritEngine->currentController();
		$this->spirits =& $spiritEngine->_spirits();
		$this->instances =& $spiritEngine->_instances();
		$this->_abstractor = $abstractor;
	}
	public function abstractor(){
		return $this->_abstractor;
	}
}
?>