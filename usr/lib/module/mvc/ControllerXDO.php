<?php
use Structs\Admin;
final class ControllerXDO extends AbstractXDO{
	/**
	 * return md5($this->sessionId().':'.$controllerName').'.cxdo'
	 */
	protected function fileName(){
		$controllerName = Mempool::instance()->get("bong.mvc.controller");
		return md5($this->sessionId().':'.$controllerName).'.cxdo';
	}
	public static function unpack($controllerName){
		$ret = new ControllerXDO();
		$ret->load(md5(self::sessionId().':'.$controllerName).'.cxdo');
		return $ret;
	}
}

?>