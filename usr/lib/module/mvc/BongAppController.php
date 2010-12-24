<?php
abstract class BongAppController extends BongController{
	protected $spiritEngine;
	protected $_params = array();
	
	public function __construct(){
		parent::__construct();
		$this->meta = new ControllerMeta();
		$this->xdo = new ControllerXDO();
		if($this->xdo->serialized())
			$this->xdo->unserialize();
		$this->spiritEngine = EngineFactory::produce("SpiritEngine", array(&$this));
		$this->ctor();
	}
	/*virtual*/ public function ctor(){}
	public function switchView($viewName){
		ControllerTray::instance()->alternateView = $viewName;
	}
	/**
	 * 
	 * @param string $spiritName
	 * @return SpiritAdapter
	 */
	public function spirit($spiritName){
		return new SpiritAdapter($spiritName, $this->spiritEngine);
	}
	public function renderViewAsXSLT(){
		ControllerTray::instance()->xsltView = true;
	}
	public function setParam($key, $value){
		$this->_params[$key] = $value;
	}
	public function addParam($key, $value){
		$this->_params[$key][] = $value;
	}
	public function params(){
		return $this->_params;
	}
	public function javascript($source){
		$this->addParam('js', $source);
	}
	public function stylesheet($source){
		$this->addParam('css', $source);
	}
	public function bootStrapJs(){
		$this->javascript(MemPool::instance()->get("bong.url.base").'/sys/rc/js/bong.bootstrap');
		$this->stylesheet(MemPool::instance()->get("bong.url.base").'/sys/rc/css/bong');
	}
	public function jquery(){
		$this->javascript(MemPool::instance()->get("bong.url.base").'/sys/rc/js/jquery');
	}
	public function dumpStrap(){
		$this->javascript(MemPool::instance()->get("bong.url.base").'/sys/rc/js/jquery');
		$this->javascript(MemPool::instance()->get("bong.url.base").'/sys/rc/js/dump');
		$this->stylesheet(MemPool::instance()->get("bong.url.base").'/sys/rc/css/dump');
	}
}

?>
