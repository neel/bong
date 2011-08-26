<?php
class SpiritXDO extends AbstractXDO{
	protected $_spiritName;
	public $_uid = false;
	/**
	 * @var SpiritAbstractor
	 */
	private $_abstractor;
	
	
	public function setAbstractor(&$abstractor){
		//var_dump(">> set ". get_class($abstractor));
		$this->_abstractor = $abstractor;
	}
	protected function abstractor(){
		return $this->_abstractor;
	}
	public function setSpirit($spiritName){
		$this->_spiritName = $spiritName;
	}
	public function spiritName(){
		return $this->_spiritName;
	}
	public function setUID($uid){
		$this->_uid = $uid;
	}
	public function hasUID(){
		return is_string($this->_uid);
	}
	public function uid(){
		return $this->_uid;
	}
	protected function fileName(){
		$filePath = md5(
						($this->_abstractor->sessioned() == SpiritAbstractor::Sessioned ? $this->sessionId() : '').
						($this->_abstractor->feeder() == SpiritAbstractor::SelfFeeded ? '' : Mempool::instance()->get("bong.mvc.controller")).
						$this->_spiritName.
						($this->_abstractor->binding() == SpiritAbstractor::StaticBinding ? '' : $this->uid())
				    ).'.sxdo';
		
		/*{ Experimental*/
		if($this->_abstractor->feeder() == SpiritAbstractor::SpiritFeeded){
			return md5($this->_abstractor->controller->xdo->uName().$filePath).'.sxdo';
		}
		/*} */
		
		//if(!$this->_spiritName)
		//	debug_print_backtrace();
		return $filePath;
	}
	public static function unpack($spiritName, $controllerName=null, $instanceId=null){
		$spiritAbstractor = $spiritName.'Abstractor';
		$filePath = md5(
						($spiritAbstractor::sessioned() == SpiritAbstractor::Sessioned ? self::sessionId() : '').
						($spiritAbstractor::feeder() == SpiritAbstractor::SelfFeeded ? '' : $controllerName).
						$spiritName.
						($spiritAbstractor::binding() == SpiritAbstractor::StaticBinding ? '' : $instanceId)
						
				    ).'.sxdo';
		$ret = new SpiritXDO();
		$ret->load($filePath);
		return $ret;
	}
}
?>
