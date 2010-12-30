<?php
class ProjectController extends BongAppController{
	public function ctor(){
		Runtime::loadModule('admin');
		$this->dumpStrap();
		$this->bootStrapJs();
		$this->jquery();
	}
	public function ls(){

	}
	public function createProject($projectName, $projectDir){
		ControllerTray::instance()->renderLayout = false;
		$this->data->success = false;
		$this->data->projectName = $projectName;
		if(!\Fstab::instance()->addProject($projectName, $projectDir)){
			return false;
		}
		\Fstab::instance()->save();
		$commonProject = \Path::instance()->evaluate("common.template.prj");
		$projectPath = \Path::instance()->evaluate(":$projectName.root")."$projectDir";
		if(!is_dir($projectPath)){
			if(!recurse_copy($commonProject, $projectPath)){
				$this->data->success = false;
			}else{
				$this->data->success = true;
			}
		}
	}
	public function select($projectName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$projectName)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$projectName);
		}else{
			$explorer = Structs\Admin\Project::create($projectName);/*Create From reflection*/
		}
		$this->data->explorer = $explorer;
		$this->xdo->project = Fstab::instance()->project($projectName);
		$this->data->project = $explorer;
		$this->data->controllers = $explorer->controllers();
		$this->data->spirits = $explorer->spirits();
		//Backend::saveUnSessioned('explorer.'.$projectName, $explorer);
	}
	public function controller($controllerName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->explorer = $explorer;
		$this->data->controller = $explorer->controllerByName($controllerName);
		$this->xdo->controllerName = $controllerName;
		$this->data->controllers = $explorer->controllers();
		$this->data->spirits = $explorer->spirits();
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function sspirit($spiritName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->explorer = $explorer;
		$this->data->controller = $explorer->spiritByName($spiritName);
		$this->xdo->spiritName = $spiritName;
		$this->data->controllers = $explorer->controllers();
		$this->data->spirits = $explorer->spirits();
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function addController($name){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		ControllerTray::instance()->renderLayout = false;
		$this->data->name = $name;
		$controller = Structs\Admin\AppController::create($explorer, $name);
		$explorer->addController($controller);
		$controller->generate();
		$this->data->controller = $controller;
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function addSpirit(){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		ControllerTray::instance()->renderLayout = false;
		$this->data->name = $_POST['name'];
		$spirit = Structs\Admin\SpiritController::create($explorer, $this->data->name);
		$spirit->setBinding($_POST['binding']);
		$spirit->setSerialization($_POST['serialization']);
		$spirit->setFeeder($_POST['feeder']);
		$spirit->setSession($_POST['session']);
		$explorer->addSpirit($spirit);
		$spirit->generate();
		$this->data->spirit = $spirit;
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function addControllerMethod($methodName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->controllerName = $this->xdo->controllerName;
		$controller = $explorer->controllerByName($this->xdo->controllerName);
		$this->data->controller = $controller;
		ControllerTray::instance()->renderLayout = false;
		$method = Structs\Admin\ControllerMethod::create($controller, $methodName);
		$controller->addMethod($method);
		$method->generate();
		$this->data->method = $method;
		$this->data->methodName = $methodName;
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function addSpiritMethod($methodName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->spiritName = $this->xdo->spiritName;
		$controller = $explorer->spiritByName($this->xdo->spiritName);
		$this->data->controller = $controller;
		ControllerTray::instance()->renderLayout = false;
		$method = Structs\Admin\SpiritMethod::create($controller, $methodName);
		$controller->addMethod($method);
		$method->generate();
		$this->data->method = $method;
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function addControllerView($methodName, $viewName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->controllerName = $this->xdo->controllerName;
		$controller = $explorer->controllerByName($this->xdo->controllerName);
		$this->data->controller = $controller;
		ControllerTray::instance()->renderLayout = false;
		http::contentType('application/json');
		$method = $controller->methodByName($methodName);
		$view = Structs\Admin\ControllerView::create($method, $viewName);
		$method->addView($view);
		$this->data->success = false;
		$this->data->success = $view->generate();
		$this->data->method = $method;
		$this->data->view = $view;
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function addSpiritMethodView($methodName, $viewName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->spiritName = $this->xdo->spiritName;
		$controller = $explorer->spiritByName($this->xdo->spiritName);
		$this->data->controller = $controller;
		ControllerTray::instance()->renderLayout = false;
		http::contentType('application/json');
		$method = $controller->methodByName($methodName);
		$view = Structs\Admin\SpiritView::create($method, $viewName);
		$method->addView($view);
		$this->data->success = false;
		$this->data->success = $view->generate();
		$this->data->method = $method;
		$this->data->view = $view;
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function createControllerMethodLayout($methodName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->controllerName = $this->xdo->controllerName;
		$controller = $explorer->controllerByName($this->xdo->controllerName);
		$this->data->controller = $controller;
		ControllerTray::instance()->renderLayout = false;
		http::contentType('application/json');
		$method = $controller->methodByName($methodName);
		$this->data->methodName = $methodName;
		$this->data->success = false;
		$this->data->success = $method->genLayout();
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function createControllerMethodParams($methodName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->controllerName = $this->xdo->controllerName;
		$controller = $explorer->controllerByName($this->xdo->controllerName);
		$this->data->controller = $controller;
		ControllerTray::instance()->renderLayout = false;
		http::contentType('application/json');
		$method = $controller->methodByName($methodName);
		$this->data->methodName = $methodName;
		$this->data->success = false;
		$this->data->success = $method->genParams();
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function createControllerLayout(){
		
	}
	public function createControllerParams(){
		
	}
	public function createControllerView(){
		
	}
	public function methodLink($methodName){
		$backend = null;
		if(Backend::ExistsUnSessioned('explorer.'.$this->xdo->project->name)){
			$explorer = Backend::LoadUnSessioned('explorer.'.$this->xdo->project->name);
		}else{
			$explorer = Structs\Admin\Project::create($this->xdo->project->name);/*Create From reflection*/
		}
		$this->data->controllerName = $this->xdo->controllerName;
		$controller = $explorer->controllerByName($this->xdo->controllerName);
		$this->data->controller = $controller;
		ControllerTray::instance()->renderLayout = false;
		$method = $controller->methodByName($methodName);
		$this->data->method = $method;
		$this->data->methodName = $methodName;
		$this->data->arguments = $method->arguments();
		//Backend::saveUnSessioned('explorer.'.$this->xdo->project->name, $explorer);
	}
	public function fnc(){

	}
}
