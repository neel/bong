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
		ControllerTray::instance()->bongParsing = false;
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
		$this->data->titleText = "{$methodName}:$viewName.php";
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
		$this->data->titleText = "{$methodName}:$viewName.php";
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
		$this->data->filePath = $this->data->file;
		$this->data->saveSuccess = false;
		$method = $this->_backend->controllerByName($this->_controllerName)->methodByName($methodName);
		if($method){
			$this->data->exists = true;
			$this->data->source = $method->code();
			$this->data->titleText = $this->_controllerName.'::'.$methodName;
		}
		if($this->data->exists && isset($_GET['source'])){
			$this->data->sourceRequested = true;
		}else if(isset($_POST['contents'])){
			$this->data->saveSuccess = false;
			$this->data->saveSuccess = $method->setCode($_POST['contents']);
		}
	}
	public function spiritMethod($methodName){
		$this->data->phpDoc = true;
		$this->data->exists = false;
		$this->data->sourceRequested = false;
		$this->data->file = $this->_backend->spiritByName($this->_xdo->spiritName)->filePath();
		$this->data->filePath = $this->data->file;
		$this->data->saveSuccess = false;
		$method = $this->_backend->spiritByName($this->_xdo->spiritName)->methodByName($methodName);
		if($method){
			$this->data->exists = true;
			$this->data->source = $method->code();
			$this->data->titleText = $this->_xdo->spiritName.'::'.$methodName;
		}
		if($this->data->exists && isset($_GET['source'])){
			$this->data->sourceRequested = true;
		}else if(isset($_POST['contents'])){
			$this->data->saveSuccess = false;
			$this->data->saveSuccess = $method->setCode($_POST['contents']);
		}
	}
	public function spaceToTab($text){
		return preg_replace("/\G {2}/","\t$1", $text);
	}
	public function save($component, $contents){
		$this->data->filePath = $component->filePath();
		$this->data->saveSuccess = false;
		if(is_writable($component->filePath())){
			$fd = @fopen($component->filePath(), 'w');
			if(!$fd)
				return false;
			$codeFd = fopen('data:text/plain,'.$contents, 'rb');
			while(($line = fgets($codeFd)) !== false){
				fwrite($fd, $this->spaceToTab($line));
			}
			fclose($fd);
			$this->data->saveSuccess = true;
			return true;
		}
		return false;
	}
}
?>
