<?php
class SpiritEngine extends AbstractMicroEngine implements EmbeddedRunnable{
	/**
	 * Reference to The Current Active Controller
	 * @var BongAppController
	 */
	private $_controller;
	/**
	 * std:map of SpiritController's
	 * std:pair<spiritName:string, SpiritController*>
	 * @var array
	 */
	private $spirits = array();
	private $instances = array();
	
	private $_activeInstanceId = null;
	
	public function setActiveInstance($instanceId){
		$this->_activeInstanceId = $instanceId;
	}
	public function activeInstance(){
		return $this->_activeInstanceId;
	}
	public function __construct(&$controller){
		$this->_controller = $controller;
	}
	public function &currentController(){
		return $this->_controller;
	}
	public function executeLogic($spiritName, $methodName, $args){
		$controller = $this->spirit($spiritName);
		$method = $this->method($controller, $methodName);
		if($method){
			$controller->setCurrentMethodName($methodName);
			if(count($args) < $method->getNumberOfRequiredParameters()){
				throw new ArgumentNotGivenException($methodName, $spiritName, $this->projectName);
			}else{
				$method->invokeArgs($controller, $args);
			}
		}else{
			assert("/*Exception Not Handled Spirit Method $spiritName::$methodName not found*/");
		}
		return $controller;
	}
	/**
	 * <pre>
	 * Instanciates the Spirit
	 * 		Calls the methodName method with the given args
	 * 		render the view of that Method
	 * returns the output as string
	 * </pre>
	 * @note SpiritController* is reused if already instantiated once. 
	 * @note e.g. one SpiritController is not instanciated more than once
	 * @warning Throws Exception if Tried to Call non Existing method
	 * @warning If View for that Specific method is not found general View of that Controler will be Called  
	 * @param string $spiritName
	 * @param string $methodName='main'
	 * @param array $args=array()
	 */
	public function run($spiritName, $methodName='main', $args=array()){
		$controller = $this->executeLogic($spiritName, $methodName, $args);
		$this->storeXDO($controller->xdo);
		//{ Make Controller properties Accessible through View
		$meta = $controller->meta;
		$xdo = $controller->xdo;
		$data = $controller->data;
		//}
		$controller->serialize();
		//{ Run the Coordinator
		$params = null;
		$coordinatorPath = $this->coordinator($spiritName);
		if(file_exists($coordinatorPath)){
			require($coordinatorPath);
			$coordinatorClassName = ucfirst($spiritName).'Coordinator';
			if(class_exists($coordinatorClassName)){
				$params = new $coordinatorClassName;
				$coordinatorReflection = new ReflectionObject($params);
				try{
					$method = $coordinatorReflection->getMethod($methodName);
					$method->invoke($params);//TODO No Arguments ? Think Again
				}catch(ReflectionException $ex){
					//TODO Call the dafult method
					assert("/*Cannot get method  $coordinatorClassName::$methodName*/");
				}
			}else{
				assert("/*No Class  $coordinatorClassName found*/");
			}
		}else{
			//Coordinator is Optional
		}
		//}
		ob_start();
		$__viewName = $this->view($spiritName, $methodName);
		$scope = function() use($__viewName, $controller, $data, $xdo, $meta){
			require($__viewName);
		};
		$scope();
		$this->viewContents = ob_get_contents();
		ob_end_clean();
		if(ControllerTray::instance()->bongParsing){
			$parser = new \SuSAX\Parser(new BongParser(function($spiritName, $methodName, $arguments, $tagName, $instanceId=null) use($controller){
				switch($tagName){
					case 'spirit':
						return !$instanceId ? $controller->spirit($spiritName)->call($methodName, $arguments) : $controller->spirit($spiritName)->instance($instanceId)->call($methodName, $arguments);
					break;
					case 'embed':
						return !$instanceId ? $controller->embed($spiritName)->call($methodName, $arguments) : $controller->embed($spiritName)->instance($instanceId)->call($methodName, $arguments);
					break;
				}
			}));
			$parser->setNsFocus('bong');
			$parser->setText($this->viewContents);
			$this->viewContents = $parser->parse();
		}
		if(ControllerTray::instance()->renderLayout){
			ob_start();
			require($this->layout($spiritName, $methodName));
			$this->responseBuffer = ob_get_contents();
			ob_end_clean();
		}else{
			$this->responseBuffer = ControllerTray::instance()->trim ? trim($this->viewContents) : $this->viewContents;
		}
		//echo $this->viewContents;
		//echo $this->responseBuffer;
	}
	/**
	 * Checks whether or not the spirit is loaded
	 * @param string $spiritName
	 * @return bool
	 */
	private function spiritLoaded($spiritName){
		return array_key_exists($spiritName, $this->spirits);
	}

	/**
	 * Load's a Spirit Given the name of the spirit.
	 * include's the Spirit's Controller
	 * If The Spirit Have Static Binding it can only be loaded once
	 * return's the SpiritController* Object given a SpiritName
	 * @param String $spiritName
	 */
	public function &spirit($spiritName){
		//{ Include the Controller
		$spiritClassName = ucfirst($spiritName).'Abstractor';
		if(!class_exists($spiritClassName))
			require($this->controller($spiritName));
		//}
		$spiritAbstractor = null;
		if($this->instanceRequested($spiritClassName)){
			$instance = $this->spiritInstance($spiritName, $this->_activeInstanceId);
			$this->clearActiveInstance();
			$spiritAbstractor =  $instance;
		}else{
			$spiritAbstractor = $this->staticSpirit($spiritName);
		}
		$this->storeXDO($spiritAbstractor->xdo);
		return $spiritAbstractor;
	}
	private function instanceRequested($spiritAbstractorClassName){
		$binding = $spiritAbstractorClassName::binding();
		if($binding == SpiritAbstractor::InstanceBinding){
			assert(!is_null($this->_activeInstanceId));
			return true;
		}
		return false;
	}
	private function instanceLoaded($spiritName, $instanceId){
		$key = $spiritName.':'.$instanceId;
		return array_key_exists($key, $this->instances);
	}
	private function loadInstance($spiritName, $instanceId){
		if(!$this->instanceLoaded($spiritName, $instanceId)){
			$spiritClassName = ucfirst($spiritName).'Abstractor';
			$spiritInstance = new $spiritClassName($this, $spiritName, $instanceId);
			//$spiritInstance->setInstanceId($instanceId);
			$key = $spiritName.':'.$instanceId;
			$this->instances[$key] = $spiritInstance;
		}
	}
	private function &spiritInstance($spiritName, $instanceId){
		if(!$this->instanceLoaded($spiritName, $instanceId)){
			$this->loadInstance($spiritName, $instanceId);
		}
		$key = $spiritName.':'.$instanceId;
		return $this->instances[$key];
	}
	private function clearActiveInstance(){
		$this->_activeInstanceId = null;
	}
	private function &staticSpirit($spiritName){
		if(!$this->spiritLoaded($spiritName)){
			$this->loadSpirit($spiritName);
		}
		return $this->spirits[$spiritName];
	}
	private function loadSpirit($spiritName){
		if(!$this->spiritLoaded($spiritName)){
			$spiritClassName = ucfirst($spiritName).'Abstractor';
			$spiritAbstractor = new $spiritClassName($this, $spiritName);
			$this->spirits[$spiritName] = $spiritAbstractor;
		}
	}
	/**
	 * return's ReflectionMethods of the Given method of the Given spirit if exists
	 * otherwise return's false
	 * @param SpiritController* $spiritController
	 * @param string $methodName
	 * @return ReflectionMethod
	 */
	private function method(&$spiritController, $methodName){
		$reflection = new ReflectionObject($spiritController);
		try{
			$method = $reflection->getMethod($methodName);
		}catch(ReflectionException $ex){
			assert("/*Method `$methodName` for Found TODO throw Exception*/");
		}
		return $method ? $method : false;
	}
	/**
	 * return's the path to the View given a methodName
	 * searches for the view of the specified method
	 * If not Found throw's Exception
	 * otherwise return's that path  
	 * @param string $spiritName
	 * @param string $methodName
	 * @return string
	 */
	private function view($spiritName, $methodName){
		$views = array(
			Path::instance()->currentProject("*$spiritName.view.-$methodName.@view.php")
		);
		foreach($views as $view){
			if(file_exists($view)){
				return $view;
			}
		}
		//TODO throw Exception as no View found Existing
		assert('/*Throw SpiritViewNotFound Exception*/');
	}
	/**
	 * return's the Path to the Controller
	 * @param string $spiritName
	 * @return string
	 */
	private function controller($spiritName){
		$controller = Path::instance()->currentProject("*$spiritName.@abstractor.php");
		if(file_exists($controller)){
			return $controller;
		}
		//TODO throw Exception as Controller not Existing
		assert('/*Throw SpiritAbstractorNotFound Exception*/');
	}
	private function layout($spiritName, $methodName){
		$layouts = array(
			Path::instance()->currentProject("*$spiritName.view.-$methodName.@layout.php"),
			Path::instance()->currentProject("*$spiritName.view.@layout.php"),
			Path::instance()->currentProject("*common.view.@layout.php")
		);
		foreach($layouts as $layout){
			if(file_exists($layout)){
				return $layout;
			}
		}
		//TODO throw Exception as no Layout found Existing
		assert('/*Throw SpiritLayoutNotFound Exception*/');
	}
	private function coordinator($spiritName){
		return Path::instance()->currentProject("*$spiritName.@coordinator.php");
	}

	public function &_spirits(){
		return $this->spirits;
	}
	public function &_instances(){
		return $this->instances;
	}
}
EngineFactory::register("SpiritEngine");
?>
