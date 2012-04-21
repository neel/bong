<?php
class MVCRouter extends AbstractContentRouter{
	public function __construct(/*$engineName*/){
		parent::__construct("MVCEngine");
	}
	
	public function buildNavigation($path){
		//var_dump($path);
		//http://localhost/bong/index.php/controllerName/methodName/arg1/arg2/+spiritName/methodName/spiritArg1/spiritArg2/+spiritName2/methodName/args
		$this->navigation->controllerName =  null;
		$this->navigation->methodName = null;
		$this->navigation->args = array();
		$this->navigation->spirits = array();
		$racks = array();
		foreach($path as $i => $urlSection){
			if(strlen($urlSection) > 0 && $urlSection[0] == '+'){
				$racks[] = array();
			}
			$racks[(count($racks) == 0 ? count($racks) : count($racks) -1)][] = $urlSection;
		}
		foreach($racks as $i => &$rack){
			if($i == 0){
				$this->navigation->controllerName = isset($rack[0]) && !empty($rack[0]) ? $rack[0] : Conf::instance()->evaluate('default.controller');
				$this->navigation->methodName = isset($rack[1]) && !empty($rack[1]) ? $rack[1] : Conf::instance()->evaluate('default.method');
				for($i=2;$i<count($rack);++$i){
					$this->navigation->args[] = $rack[$i];
				}
			}else{
				if(count($rack) > 1){//Ignore if no MethodName is provided
					$spirit = new stdClass;
					$spirit->spiritName = substr($rack[0], 1);//remove the + sign
					$spirit->methodName = $rack[1];
					$spirit->args = array();
					for($i=2;$i<count($rack);++$i){
						$spirit->args[] = $rack[$i];
					}
					$this->navigation->spirits[] = $spirit;
				}
			}
		}
		if(is_null($this->navigation->controllerName))
			$this->navigation->controllerName = 'default';
		if(is_null($this->navigation->methodName))
			$this->navigation->methodName = 'main';
		MemPool::instance()->set('bong.mvc.controller', $this->navigation->controllerName);
		MemPool::instance()->set('bong.mvc.method', $this->navigation->methodName);
		Runtime::loadModule('mvc');
		//print_r($this->navigation);
	}
}
RouterFactory::register('MVCRouter');
?>
