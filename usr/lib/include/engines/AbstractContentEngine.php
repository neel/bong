<?php
abstract class AbstractContentEngine{
	protected $navigation;
	protected $projectName = null;
	private $_ready = null;
	/**
	 * Hold's the Response as String
	 * @var stringstream
	 */
	protected $responseBuffer;
	/**
	 * @var AbstractXDO
	 */
	private $xdoStore;
	
	final public function navigation(){
		return $this->navigation;
	}
	final public function setProjectName($projectName){
		$this->projectName = $projectName;
	}
	final public function setNavigation($navigation){
		$this->navigation = $navigation;
	}
	final public function getNavigation(){
		return $this->navigation;
	}
	final public function flagAsReady(){
		$this->_ready = true;
	}
	final public function ready(){
		return $this->_ready;
	}
	/**
	 * \overridable
	 */
	protected function validate(){
		return true;
	}
	/**
	 * \deprecated
	 */
	final public function check(){
		return $this->validate();
	}
	/**
	 * 
	 * @param $xdo AbstractXDO
	 */
	final public function storeXDO(&$xdo){
		$this->xdoStore = $xdo;
	}
	/**
	 * @return AbstractXDO
	 */
	public function xdo(){
		return $this->xdoStore;
	}
	public function response(){
		return $this->responseBuffer;
	}
	public function writeResponse(){
		echo $this->response();
	}
}
?>
