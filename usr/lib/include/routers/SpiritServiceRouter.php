<?php
final class SpiritServiceRouter extends ServiceRouter{
	public function __construct(){
		parent::__construct();
		//parent::__construct('AppJSONServiceEngine');
		$this->navigation = new stdClass;
	}
	public function buildNavigation_1($parts){
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
	public function buildNavigation_2($parts){
		/*
		 * /controller/+spirit.res/method/args
		 * /+spirit.res/method/args
		 */
		//print_r($parts);
		$itr = 0;
		if($parts[0][0] == '+'){//Shared Spirit
			$this->navigation->spiritName = substr($parts[0], 1);
			$itr = 1;
		}else{//Controller Attached Spirit
			$this->navigation->controllerName = $parts[0];
			$this->navigation->controllerMethodName = $parts[1];
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
		print_r($this->navigation);
	}
	public function buildNavigation($parts){
		$spiritNamePos = -1;
		$propertyPos = -1;
		foreach($parts as $i => $part){
			if($part[0] == '+'){
				if($spiritNamePos != -1){
					throw new MalformedUrlException(implode('/', $parts));
				}
				$spiritNamePos = $i;
			}
			if($part[0] == '-'){
				if($propertyPos != -1){
					throw new MalformedUrlException(implode('/', $parts));
				}
				$propertyPos = $i;
			}
		}
		
		if($spiritNamePos == -1)
			throw new MalformedUrlException(implode('/', $parts));
		$controllerParts = array_slice($parts, 0, $spiritNamePos);
		$spiritParts = array_slice($parts, $spiritNamePos, $propertyPos==-1 ? count($parts) : $propertyPos-$spiritNamePos);
		$propParts = $propertyPos==-1 ? array() : array_slice($parts, $propertyPos);
		/*
		print_r($controllerParts);
		print_r($spiritParts);
		print_r($propParts);
		*/
		$controllerName = null;
		$controllerMethodName = null;
		$controllerMethodArgs = array();
		if(count($controllerParts) > 0){
			$controllerName = $controllerParts[0];
			if(strpos($controllerParts[0], ".s") === false){
				throw new MalformedUrlException(implode('/', $parts));
			}else{
				$controllerName = substr($controllerParts[0], 0, -2);
			}		
			$controllerMethodName = null;
			$controllerMethodArgs = array();
			if(count($controllerParts) > 1){
				$controllerMethodName = $controllerParts[1];
				if(count($controllerParts) > 2){
					$controllerMethodArgs = array_slice($controllerParts, 2);
				}
			}
		}
		$spiritName = $spiritParts[0];
		$spiritExtension = "res";
		$spiritInstanceId = null;
		if(strpos($spiritParts[0], ".") === false){
			throw new MalformedUrlException(implode('/', $parts));
		}else{
			$spiritName = substr($spiritParts[0], 0, strpos($spiritParts[0], "."));
			$spiritExtension = substr($spiritParts[0], strpos($spiritParts[0], "."));
			if(strpos($spiritName, ':') !== false){
				$spiritInstanceId = substr($spiritName, strpos($spiritName, ":")+1);
				$spiritName = substr($spiritName, 0, strpos($spiritName, ":"));
			}
		}
		$spiritMethodName = null;
		$spiritMethodArgs = array();
		if(count($spiritParts) > 1){
			$spiritMethodName = $spiritParts[1];
			if(count($spiritParts) > 2){
				$spiritMethodArgs = array_slice($spiritParts, 2);
			}
		}
		
		$this->navigation->controllerName = $controllerName;
		$this->navigation->controllerMethodName = $controllerMethodName;
		$this->navigation->controllerMethodArgs = $controllerMethodArgs;
		$this->navigation->spiritName = substr($spiritName, 1);
		$this->navigation->methodName = $spiritMethodName;		
		$this->navigation->args = $spiritMethodArgs;
		$this->navigation->spiritExtension = substr($spiritExtension, 1);
		$this->navigation->spiritInstanceId = $spiritInstanceId;
		$this->navigation->propertyName = $propertyPos==-1 ? null : substr($propParts[0], 1);
		Mempool::instance()->set('bong.mvc.controller', $this->navigation->controllerName);
		MemPool::instance()->set('bong.mvc.method', $this->navigation->controllerMethodName);
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
