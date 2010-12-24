<?php
final class SpiritServiceRouter extends ServiceRouter{
	public function __construct(){
		parent::__construct();
		//parent::__construct('AppJSONServiceEngine');
	}
	public function buildNavigation($parts){
		/**
		 * /controller/method/args/+spirit.res/method/args
		 * /controller/+spirit.res/method/args
		 * /+spirit.res/method/args
		 * /+spirit:instanceId.res/method/args
		 * 
		 */
		/**
		 * navigation = {
		 * 		controllerName: '',
		 * 		controllerMethodName: '',
		 * 		controllerMethodArgs: '',
		 * 		spiritName: '',
		 * 		spiritExtension: (res|json|xml|prop),
		 * 		spiritInstanceId: '',
		 * 		methodName: '',
		 * 		args: '',
		 * 		propertyName: ''//Obviously Spirit's Property
		 * }
		 */
		$this->navigation->controllerName = null;
		$this->navigation->controllerMethodName = null;
		$this->navigation->controllerMethodArgs = array();
		$this->navigation->spiritName = null;
		$this->navigation->methodName = null;
		$this->navigation->args = array();
		$spiritStarted = false;
		$j = 0;
		foreach($parts as $i => $part){
			if(!$spiritStarted && $part[0] == '+'){
				$spiritStarted = true;
			}
			if(!$spiritStarted){
				switch($i){
					case 0:
						$this->navigation->controllerName = substr($part, 0, -2);
						Mempool::instance()->set('bong.mvc.controller', $this->navigation->controllerName);
						break;
					case 1:
						$this->navigation->controllerMethodName = $part;
						MemPool::instance()->set('bong.mvc.method', $this->navigation->controllerMethodName);
						break;
					default:
						$this->navigation->controllerMethodArgs[] = $part;
						break;
				}
			}else{
				switch($j){
					case 0:{
							$this->navigation->spiritName = substr($part, 1);
							$__ = explode('.', $this->navigation->spiritName);
							$this->navigation->spiritName = $__[0];
							$this->navigation->spiritExtension = $__[1];
							if(strpos($this->navigation->spiritName, ':') > 0){
								list(
									$this->navigation->spiritName,
									$this->navigation->spiritInstanceId
								) = explode(':', $this->navigation->spiritName);
							}
						}break;
					case 1:
						$this->navigation->methodName = $part;
						break;
					default:
						$this->navigation->args[] = $part;
						break;					
				}
				++$j;
			}
		}
		//print_r($this->navigation);
	}
	public function buildNavigation_1($parts){
		/*
		 * /controller/+spirit.res/method/args
		 * /+spirit.res/method/args
		 */
		$itr = 0;
		if($parts[0][0] == '+'){//Shared Spirit
			$this->navigation->spiritName = substr($parts[0], 1);
			$itr = 1;
		}else{//Controller Attached Spirit
			$this->navigation->controllerName = $parts[0];
			Mempool::instance()->set('bong.mvc.controller', $this->navigation->controllerName);
			MemPool::instance()->set('bong.mvc.method', $this->navigation->controllerMethodName);
			$this->navigation->spiritName = substr($parts[1], 1);
			$itr = 2;
		}
		$__ = explode('.', $this->navigation->spiritName);
		$this->navigation->spiritName = $__[0];
		$this->navigation->spiritExtension = $__[1];
		if(strpos($this->navigation->spiritName, ':') > 0){
			list(
				$this->navigation->spiritName,
				$this->navigation->spiritInstanceId
			) = explode(':', $this->navigation->spiritName);
		}
		$this->navigation->methodName = null;
		$this->navigation->args = array();
		if(count($parts) > $itr){
			$_part = $parts[$itr];
			if($_part[0] != '-'){
				$this->navigation->methodName = $_part;
				$itr++;
			}
			for($i=$itr;$i<count($parts);++$i){
				if($parts[$i][0] == '-'){//Property Name
					$this->navigation->propertyName = substr($parts[$i], 1);
					break;//Property Name Should be the Last One
				}else{//Arguments
					$this->navigation->args[] = $parts[$i];
				}
			}
		}
		//print_r($this->navigation);
	}
	public function prepareEngine(){
		switch($this->navigation->spiritExtension){
			case 'json':
				$this->_engine = EngineFactory::produce('SpiritJSONServiceEngine');
				break;
			case 'xml':
				$this->_engine = EngineFactory::produce('SpiritXMLServiceEngine');
				break;
			case 'prop':
				$this->_engine = EngineFactory::produce('SpiritPropertyServiceEngine');
				break;
			case 'res':
				$this->_engine = EngineFactory::produce('SpiritResponseServiceEngine');
				break;
		}
		parent::prepareEngine();
	}
	
}
RouterFactory::register('SpiritServiceRouter');
?>
