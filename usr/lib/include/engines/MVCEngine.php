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
	
	//private $_methodReturn = null;
		
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
		$models = $this->model();
		$this->modelInclude($models);
		while(!end($models)){
			array_pop($models);
		}
		$last_model = end($models);
		require_once($this->controller());
		$model = null;
		if($last_model->path && $last_model->className){
			$modelName = $last_model->className;/*Figure out model name*/
			$modelReflection = new ReflectionClass($modelName);/*Instantiate The Model*/
			$model = $modelReflection->newInstance();
		}
		$controllerName = ucfirst($this->navigation->controllerName.'Controller');
		$controllerReflection = new ReflectionClass($controllerName);
		$controller = $controllerReflection->newInstanceArgs(array($model));
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
				if(count($this->navigation->args) < $methodReflection->getNumberOfRequiredParameters()){
					throw new ArgumentNotGivenException($this->navigation->methodName, $this->navigation->controllerName, $this->projectName);
				}else{
					$this->_methodReturn = $methodReflection->invokeArgs($controller, $this->navigation->args);
					$this->_actionParams = $controller->params();
					$controller->flushParams();
				}
			}
		}
		$controller->setParams($this->_arrangedParams);
		/*This check will be required in future. cause In future it will be possible to have a controler with no XDO attached to it*/
		if($controller->xdo){
			$this->storeXDO($controller->xdo);
		}
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
		$data        = $controller->data;
		if(isset($controller->xdo))
			$xdo     = $controller->xdo;
		if(isset($controller->session))
			$session = $controller->session;
		$meta        = $controller->meta;
		//}
		//{ Load The View
		if(ControllerTray::instance()->renderView){
			if(isset($this->_methodReturn) && is_int($this->_methodReturn) && $this->_methodReturn == -1){
				return;
			}
			$__viewName = $this->view();
			ob_start();
			$scope = function() use($__viewName, $controller, $data, $session, $xdo, $meta){
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
					}
				}));
				$parser->setNsFocus('bong');
				$parser->setText($this->viewContents);
				$this->viewContents = $parser->parse();
			}
			if($this->_systemView){
				$controller->dumpStrap();
			}
		}
		//}		
		if(ControllerTray::instance()->renderView && ControllerTray::instance()->renderLayout){/// < Layout cannot be rendered If no View is rendered
			$this->mergeParams();
			$params = $this->_arrangedParams;
			ob_start();
			require($this->layout());
			$this->responseBuffer = ob_get_contents();
			ob_end_clean();
		}else{
			if(ControllerTray::instance()->renderView){
				$this->responseBuffer = ControllerTray::instance()->trim ? trim($this->viewContents) : $this->viewContents;
			}else{
				if(isset($this->_methodReturn)){
					switch(ControllerTray::instance()->responseType){
						case 'scrap/xml':
							http::contentType('text/xml');
							$packer = new XMLPacker($this->_methodReturn);
							$this->responseBuffer = $packer->toXML()->saveXML();
						break;
						case 'scrap/json':
							http::contentType('application/json');
							$this->responseBuffer = json_encode($this->_methodReturn);
						break;
						case 'scrap/plain':
						default:
							http::contentType('text/plain');
							$this->responseBuffer = $this->_methodReturn;
						
					}
				}
			}
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
	/**
	 * Searches for model(s) that exists
	 * returns an array of existing models and a require call must be done from the top of the array
	 * top most is the BongAppModel and file exists
	 * bottom of that will be 0 or more models located in /projectDir/usr/local/common/models/*
	 * name of these models are not predefined and can have any arbitrary name
	 * However its a good Practice to end the class names with Model
	 * these models should include BongAppModel
	 * Controller Model appears after that
	 * Controller Models must have the name <ControllerName>Model
	 * Controller Models may inherit any of the project level models
	 * ControllerMethod Model is not supported as its thought that such deep model hierarchy is not required at all
	 * All Models must have BongAppModel in its inheritance chain
	 * trait functionility cannot be used as its in 5.4 and we are using 5.3 stable
	 * If a controller Model is found its set to $this->model otherwise $this->model is set to null
	 */
	private function model(){
		/*
		$models = array(
			//Path::instance()->currentProject('apps.model.+&controller.@&method.php'),//Application Model
			Path::instance()->currentProject('apps.model.+@&controller.php'),//Controller Model
			Path::instance()->currentProject('common.model'),//Project Model
			Path::instance()->evaluate('share.apps.@model.php')//Bong Model
		);
		*/
		$models = array();
		$bongModel            = new stdClass;
		$bongModel->path      = Path::instance()->evaluate('share.apps.@model.php');
		$bongModel->name      = 'bong';
		$bongModel->className = 'BongAppModel';
		array_push($models, $bongModel);
		foreach(glob(Path::instance()->currentProject('common.model').'/*.php') as $modelFile){
			$projectModel            = new stdClass;
			$projectModel->path      = $modelFile;
			$projectModel->name      = basename($modelFile, '.php');
			$projectModel->className = '';
			array_push($models, $projectModel);
		}
		$appModel            = new stdClass;
		$appModel->path      = Path::instance()->currentProject('apps.model.+@&controller.php');
		$appModel->name      = MemPool::instance()->get('bong.mvc.controller');
		$appModel->className = MemPool::instance()->get('bong.mvc.controller').'Model';
		array_push($models, $appModel);
		foreach($models as $i => $model){
			if(!file_exists($model->path)){
				$models[$i] = false;
			}
		}
		return $models;
	}
	private function modelInclude($models){
		foreach($models as $model){
			if($model)
				require_once($model->path);
		}
	}
	private function modelName(){
		$index = -1;
		$modelPath = $this->model($index);
		switch($index){
			case 0:
				$modelName = ucfirst($this->navigation->controllerName).ucfirst($this->navigation->methodName).'Model'; 
			break;
			case 1:
				$modelName = ucfirst($this->navigation->controllerName).'Model'; 
			break;
			case 2:
				$modelName = ucfirst(Runtime::currentProject()->name).'Model'; 
			break;
			case 3:
				$modelName = 'BongAppModel';
			break;
			default:
				//TODO Handle Error
		}
		return $modelName;
	}
	private function controller(){
		$controllerPath = Path::instance()->currentProject('apps.+&controller.@&controller.php');
		if(!$controllerPath || !file_exists($controllerPath)){
			throw new ControllerNotFoundException(MemPool::instance()->get('bong.mvc.controller'), $controllerPath);
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
