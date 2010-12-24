<?php
final class AppServiceRouter extends ServiceRouter{
	public function __construct(){
		parent::__construct();
		//parent::__construct('AppJSONServiceEngine');
	}
	public function buildNavigation($parts){
		$__ = explode('.', $parts[0]);
		$this->navigation->controllerName = $__[0];
		$this->navigation->methodName = null;
		$this->navigation->controllerExtension = $__[1];
		$this->navigation->args = array();
		for($i=1;$i<count($parts);++$i){
			$part = $parts[$i];
			switch($i){
				case 1://MethodName Comes Now
					if($part[0] == '-'){
						$this->navigation->propertyName = substr($part, 1);//Remove the - sign
						break;//Property NameShould be the last One 
					}else{
						$this->navigation->methodName = $part;
					}
					break;
				default://May be Argument or property Name
					if($part[0] == '-'){
						$this->navigation->propertyName = substr($part, 1);//Remove the - sign
						break;//Property NameShould be the last One 
					}else{
						$this->navigation->args[] = $part;
					}
			}
		}
		MemPool::instance()->set('bong.mvc.controller', $this->navigation->controllerName);
		MemPool::instance()->set('bong.mvc.method', $this->navigation->methodName);
	}
	public function prepareEngine(){
		switch($this->navigation->controllerExtension){
			case 'json':
				$this->_engine = EngineFactory::produce('AppJSONServiceEngine');
				break;
			case 'xml':
				$this->_engine = EngineFactory::produce('AppXMLServiceEngine');
				break;
			case 'prop':
				$this->_engine = EngineFactory::produce('AppPropertyServiceEngine');
				break;
			case 'res':
				$this->_engine = EngineFactory::produce('AppResponseServiceEngine');
				break;
		}
		parent::prepareEngine();
	}
}
RouterFactory::register('AppServiceRouter');
?>