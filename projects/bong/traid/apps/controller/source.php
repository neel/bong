<?php
class SourceController extends BongAppController{
	private $_xdo = null;
	private $_backend = null;
	private $_controllerName = null;
	
	public function ctor(){
		Runtime::loadModule('admin');
		ControllerTray::instance()->renderLayout = false;
		$this->_xdo = ControllerXDO::unpack('project');
		$this->_xdo->unserialize();
		if(Backend::ExistsUnSessioned('explorer.'.$this->_xdo->project->name)){
			$this->_backend = Backend::LoadUnSessioned('explorer.'.$this->_xdo->project->name);
		}else{
			$this->_backend = Structs\Admin\Project::create($this->_xdo->project->name);/*Create From reflection*/
		}
		if(isset($this->_xdo->controllerName))
			$this->_controllerName = $this->_xdo->controllerName;
	}
	private function source($component){
		$this->data->exists = false;
		$this->data->sourceRequested = false;
		if($component){
			$this->data->file = $component->filePath();
			$this->data->exists = true;
			$this->data->source = $component->source();
		}
		if($this->data->exists && isset($_GET['source'])){
			$this->data->sourceRequested = true;
		}
	}
	public function projectLayout(){
		$this->data->title = 'Project Layout';
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->layout(), $_POST['contents']);
		}else{
			return $this->source($this->_backend->layout());
		}
	}
	public function projectParams(){
		$this->data->title = 'Project Params';
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->params(), $_POST['contents']);
		}else{
			return $this->source($this->_backend->params());
		}
	}
	public function projectView(){
		$this->data->title = 'Project Common View';
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->view(), $_POST['contents']);
		}else{
			return $this->source($this->_backend->view());
		}
	}
	public function controller(){
		$this->data->title = "Controller {$this->_controllerName}";
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->controllerByName($this->_controllerName), $_POST['contents']);
		}else{
			return $this->source($this->_backend->controllerByName($this->_controllerName));
		}
	}
	public function controllerLayout(){
		$this->data->title = "Controller {$this->_controllerName}\'s Layout";
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->controllerByName($this->_controllerName)->layout(), $_POST['contents']);
		}else{
			return $this->source($this->_backend->controllerByName($this->_controllerName)->layout());
		}
	}
	public function controllerParams(){
		$this->data->title = "Controller {$this->_controllerName}\'s Params";
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->controllerByName($this->_controllerName)->params(), $_POST['contents']);
		}else{
			return $this->source($this->_backend->controllerByName($this->_controllerName)->params());
		}
	}
	public function controllerView(){
		$this->data->title = "Controller {$this->_controllerName}\'s View";
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->controllerByName($this->_controllerName)->view(), $_POST['contents']);
		}else{
			return $this->source($this->_backend->controllerByName($this->_controllerName)->view());
		}
	}
	public function controllerMethodLayout($methodName){
		$this->data->exists = false;
		$method = $this->_backend->controllerByName($this->_controllerName)->methodByName($methodName);
		$this->data->title = "{$this->_controllerName}:{$methodName}() \'s Layout";
		if($method->hasLayout()){
			$this->data->exists = true;
			if(isset($_POST['contents'])){
				return $this->save($method->layout(), $_POST['contents']);
			}else{
				return $this->source($method->layout());
			}
		}
	}
	public function controllerMethodParams($methodName){
		$this->data->exists = false;
		$method = $this->_backend->controllerByName($this->_controllerName)->methodByName($methodName);
		$this->data->title = "{$this->_controllerName}:{$methodName}() \'s Params";
		if($method->hasParams()){
			$this->data->exists = true;
			if(isset($_POST['contents'])){
				return $this->save($method->params(), $_POST['contents']);
			}else{
				return $this->source($method->params());
			}
		}
	}
	public function view($methodName, $viewName='view'){
		$this->data->title = "{$this->_controllerName}:{$methodName}() \'s View $viewName";
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->controllerByName($this->_controllerName)->methodByName($methodName)->viewByName($viewName), $_POST['contents']);
		}else{
			return $this->source($this->_backend->controllerByName($this->_controllerName)->methodByName($methodName)->viewByName($viewName));
		}
	}
	public function sspirit(){
		$this->data->title = "Spirit {$this->_xdo->spiritName}";
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->spiritByName($this->_xdo->spiritName), $_POST['contents']);
		}else{
			return $this->source($this->_backend->spiritByName($this->_xdo->spiritName));
		}		
	}
	public function spiritView($methodName, $viewName='view'){
		if(isset($_POST['contents'])){
			return $this->save($this->_backend->spiritByName($this->_xdo->spiritName)->methodByName($methodName)->viewByName($viewName), $_POST['contents']);
		}else{
			return $this->source($this->_backend->spiritByName($this->_xdo->spiritName)->methodByName($methodName)->viewByName($viewName));
		}	
	}
	public function controllerMethod($methodName){
		$this->data->phpDoc = true;
		$this->data->exists = false;
		$this->data->sourceRequested = false;
		$this->data->file = $this->_backend->controllerByName($this->_controllerName)->filePath();
		$method = $this->_backend->controllerByName($this->_controllerName)->methodByName($methodName);
		if($method){
			$this->data->exists = true;
			$this->data->source = $method->code();
		}		
		if($this->data->exists && isset($_GET['source'])){
			$this->data->sourceRequested = true;
		}
	}
	public function spiritMethod($methodName){
		$this->data->phpDoc = true;
		$this->data->exists = false;
		$this->data->sourceRequested = false;
		$this->data->file = $this->_backend->spiritByName($this->_xdo->spiritName)->filePath();
		$method = $this->_backend->spiritByName($this->_xdo->spiritName)->methodByName($methodName);
		if($method){
			$this->data->exists = true;
			$this->data->source = $method->code();
		}		
		if($this->data->exists && isset($_GET['source'])){
			$this->data->sourceRequested = true;
		}
	}
	public function save($component, $contents){
		$this->data->filePath = $component->filePath();
		$this->data->saveSuccess = false;
		if(is_writable($component->filePath())){
			$this->data->saveSuccess = true;
			return file_put_contents($component->filePath(), $contents);
		}
		return false;
	}
}
?>
