<?php
abstract class BongAppController extends BongController{
	protected $spiritEngine;
	protected $_params = array();
	protected $model;
	
	public function __construct($model = null){
		parent::__construct();
		if($model){
			$this->model = $model;
			$this->model->connect();
		}
		$this->meta = new ControllerMeta();
		/*{ TODO Having an XDO should be optional not all app asks for an XDO*/
		$this->xdo = new ControllerXDO();
		if($this->xdo->serialized())
			$this->xdo->unserialize();
		/*}*/
		/*{ TODO Save applies here too. not all app need a session Storage some wants to work real Stateless too*/
		$this->session = new SessionXDO();
		if($this->session->serialized())
			$this->session->unserialize();
		/**/
			
		$controllerName = get_class($this);
		$desc = new \ROM\BongXDODescriptor(\ROM\BongXDODescriptor::ControllerXDO, $controllerName, $this->xdo->sessionFilePath());
		\ROM\BongCurrentUserData::instance()->addXDO($desc);
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
	public function flushParams(){
		$this->_params = array();
	}
	/**
	 * @internal
	 * Intended for Intended Use only.
	 * set's the Whole $this->_params object
	 */
	public function setParams($params){
		$this->_params = $params;
	}
}

?>
