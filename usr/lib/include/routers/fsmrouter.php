<?php
class FSMRouter extends AbstractContentRouter{
	public function __construct(/*$engineName*/){
		parent::__construct("FSMEngine");
	}
	public function buildNavigation(/*$path*/){
		
	}
}	
RouterFactory::register('FSMRouter');
?>
