<?php
class PropertyDeliveryTrait extends AbstractDeliveryTrait{
	public function execute(){
		http::freeze();//a Spirit Cannot send any HTTP Header unless It is used as ResponseService
		$this->engine->run();
		http::release();
		$xdo = $this->engine->xdo();
		$navigation = $this->engine->getNavigation();
		if(isset($xdo->{$navigation->propertyName})){
			$value = $xdo->{$navigation->propertyName};
			if(is_array($value) || is_object($value)){
				http::contentType('application/json');
				return json_encode($value);
			}else{
				http::contentType('text/plain');
				return $value;
			}
		}
		return null;
	}
}
?>