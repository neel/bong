<?php
abstract class ServiceRouter extends AbstractContentRouter{
	public function __construct($engineName=null){
		Runtime::loadModule('mvc');
		if($engineName)
			parent::__construct($engineName);
	}
}

?>