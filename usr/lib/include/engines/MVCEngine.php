<?php
class MVCEngine extends ContentEngine{
	private $_systemView = false;
	
	private $_actionParams = null;
	private $_methodParams = null;
	private $_ctorParams = null;
	private $_appParams = null;
	private $_projectParams = null;
	private $_bongParams =null;
	
	private $_arrangedParams = null;
	
	protected function validate(){
		return (
			$this->projectName && 
			$this->navigation->controllerName && 
			$this->navigation->methodName && 
			is_array($this->navigation->args)
		);
	}
	public function executeLogic(){
		Runtime::loadModule('util');
		require($this->controller());
		$controllerName = ucfirst($this->navigation->controllerName.'Controller');
		$controllerReflection = new ReflectionClass($controllerName);
		$controller = $controllerReflection->newInstance();
		$this->_ctorParams = $controller->params();
		$controller->flushParams();
		if(isset($this->navigation->methodName)){
			$this->_alternateView = ControllerTray::instance()->alternateView;
			$controllerReflectionObject = new ReflectionObject($controller);
			try{
				$methodReflection = $controllerReflectionObject->getMethod($this->navigation->methodName);
			}catch(ReflectionException $ex){
				throw new MethodNotFoundException($this->navigation->methodName, $this->navigation->controllerName, $this->projectName);
			}
			if($methodReflection){
				$methodReflection->invokeArgs($controller, $this->navigation->args);
				$this->_actionParams = $controller->params();
				$controller->flushParams();
			}
		}
		$controller->setParams($this->_arrangedParams);
		$this->storeXDO($controller->xdo);
		$controller->serialize();
		return $controller;	
	}
	/**
	 * \algorithm
	 * layoutPath:
	 * 		projectLayout = Path::currentProject(apps.layout.+controller.@layout.php)
	 * 		if exists(projectLayout)
	 * 			return projectLayout
	 * 		else return default Layout
	 * 
	 * paramPath:
	 * 		projectParam = Path::currentProject(apps.layout.+controller.@param.php)
	 * 		if exists(projectParam)
	 * 			return projectParam
	 * 		else return default param
	 * 
	 * viewPath:
	 * 		return Path::currentProject(apps.layout.+controller.-method.@view.php)
	 * 
	 * controllerPath:
	 * 		return Path::currentProject(apps.+controller)
	 * 
	 * processView:
	 * 		require(viewPath())
	 * 
	 * main:
	 * 		require(paramPath())
	 * 		require(controllerPath())
	 * 		startOutputBuffering()
	 * 		$controller = new Controller();
	 * 		closure syncVars(&$controller){
	 * 			foreach($controller->data as $key => &$value){
	 * 				local $key = $value;
	 * 			}
	 * 			processView()
	 * 		}
	 * 		$this->viewContents = getOutputBufferContents()
	 * 		endOutputBuffering()
	 * 		startOutputBuffering()
	 * 		require(layoutPath())
	 * 		$response = getOutputBufferContents()
	 * 		endOutputBuffering()
	 * 
	 * \endalgorithm
	 */
	public function run(){
		$controller = $this->executeLogic();
		//$this->storeXDO($controller->xdo);
		//{ Make Variables Accessible to View
		$data = $controller->data;
		$xdo = $controller->xdo;
		$meta = $controller->meta;
		//}
		$__viewName = $this->view();
		ob_start();
		$scope = function() use($__viewName, $controller, $data, $xdo, $meta){
			require($__viewName);
		};
		$scope();
		$this->viewContents = ob_get_contents();
		ob_end_clean();
		if($this->_systemView){
			$controller->dumpStrap();
		}
		
		if(ControllerTray::instance()->renderLayout){
			$this->mergeParams();
			$params = $this->_arrangedParams;
			ob_start();
			require($this->layout());
			$this->responseBuffer = ob_get_contents();
			ob_end_clean();
		}else{
			$this->responseBuffer = ControllerTray::instance()->trim ? trim($this->viewContents) : $this->viewContents;
		}
		if(ControllerTray::instance()->xsltView){
			$this->processXSLView($controller->storage());
		}
		//echo $this->responseBuffer;
	}
	/**
	 * $params stdClass Params in the params file
	 * $controllerParams array Params set By Controller Action
	 * $this->_ctorParams array params set by ctor()
	 * 
	 * For Multivalue Parameters like JS or CSS first take the ctor()'s then take 
	 */
	private function mergeParams(){
		$this->params();
		$this->_arrangedParams = new StdClass;

		$arrangedParams =& $this->_arrangedParams;
		$buildParams = function($paramsDict) use(&$arrangedParams){
			foreach($paramsDict as $key => $value){
				if(is_array($value)){
					if(isset($arrangedParams->{$key})){
						foreach($value as $v){
							if(!in_array($v, $arrangedParams->{$key})){
								$arrangedParams->{$key}[] = $v;
							}
						}
					}else{
						$arrangedParams->{$key} = $value;
					}
				}else{
					$arrangedParams->{$key} = $value;
				}
			}
		};
		
		foreach(array($this->_bongParams, $this->_projectParams, $this->_appParams, $this->_ctorParams, $this->_methodParams, $this->_actionParams) as $paramsFile){
			if(is_string($paramsFile)){
				$params = new stdClass();
				require($paramsFile);
				$buildParams($params);
				$params = null;
			}else if(is_array($paramsFile) || is_object($paramsFile)){
				$buildParams($paramsFile);
			}
		}
	}
	private function layout(){
		$layouts = array(
			Path::instance()->currentProject('apps.view.+&controller.-&method.@layout.php'),//Method's Layout
			Path::instance()->currentProject('apps.layout.+&controller.@layout.php'),//Application Layout
			Path::instance()->currentProject('common.layout.@layout.php'),//Project Layout
			Path::instance()->evaluate('share.apps.layout.@layout.php')//Bong Layout
		);
		foreach($layouts as $layout){
			if(file_exists($layout)){
				return $layout;
			}
		}
	}
	private function params(){
		$params = array(
			Path::instance()->currentProject('apps.view.+&controller.-&method.@params.php'),//Method's Params
			Path::instance()->currentProject('apps.layout.+&controller.@params.php'),//Application Params
			Path::instance()->currentProject('common.layout.@params.php'),//Project Params
			Path::instance()->evaluate('share.apps.layout.@params.php')//Bong Params
		);
		foreach($params as $i => $param){
			if(file_exists($param)){
				switch($i){
					case 0:
						$this->_methodParams = $param;
						break;
					case 1:
						$this->_appParams = $param;
						break;
					case 2:
						$this->_projectParams = $param;
						break;
					case 3:
						$this->_bongParams = $param;
						break;
					default:
				}
			}
		}
	}
	private function view(){
		$views = array(
			Path::instance()->currentProject('apps.view.+&controller.-&method.@'.(ControllerTray::instance()->alternateView ? ControllerTray::instance()->alternateView : 'view').'.php'),//Application View
			Path::instance()->currentProject('apps.view.+&controller.@view.php'),//Controller View
			Path::instance()->currentProject('common.@view.php'),//Project View
			Path::instance()->evaluate('share.apps.@view.php')//Bong View
		);
		foreach($views as $i => $view){
			if(file_exists($view)){
				if(ControllerTray::instance()->alternateView && $i > 0){
					//TODO throw AlternateViewNotFoundException
				}
				if($i > 0){
					$this->_systemView = true;
				}
				return $view;
			}
		}
	}
	private function controller(){
		$controllerPath = Path::instance()->currentProject('apps.+&controller.@&controller.php');
		if(!$controllerPath || !file_exists($controllerPath)){
			throw new ControllerNotFoundException(MemPool::instance()->get('bong.mvc.controller'));
		}else{
			if(is_readable($controllerPath))
				return $controllerPath;
			else
				throw new ControllerNotReadableException(MemPool::instance()->get('bong.mvc.controller'));
		}
	}
	/**
	 * FIXME Exceptions Not Handled
	 * \algo
	 * $this->responceBuffer: The XSLT as String
	 * $xdoPath: Path to XML DataObject
	 * \endalgo
	 * @param string $xdoPath
	 */
	private function processXSLView($xdoPath){
		$xdo = new DOMDocument();
		$xdo->load($xdoPath);
		
		$xslt = new DOMDocument();
		if(!@$xslt->loadXML($this->responseBuffer)){//TODO Handle Exceptions
			echo $this->responseBuffer;
			assert("/*Response Malformed Not Parsable as XML TODO Throw Exception*/");
		}
		
		$transformer = new XSLTProcessor();
		if(!@$transformer->importStylesheet($xslt)){//TODO Handle Exceptions
			echo $transformer->saveXML();
			assert("/*Response Not XSL TODO Throw Exception*/");
		}
		
		$responseXML = $transformer->transformToDoc($xdo);//Response as a DOMDocument
		$this->responseBuffer = $responseXML->saveHTML();//TODO will it be HTML or XML should be decided based upon Content Mime Type	
	}
}
EngineFactory::register("MVCEngine");
?>
