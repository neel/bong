<?php
final class SessionXDO extends AbstractXDO{
	/**
	 * return md5($this->sessionId().':'.$controllerName').'.csdo'
	 */
	protected function fileName(){
		$controllerName = Mempool::instance()->get("bong.mvc.controller");
		return md5($this->sessionId().':'.$controllerName).'.csdo';
	}
	public static function unpack($controllerName){
		$ret = new SessionXDO();
		$ret->load(md5(self::sessionId().':'.$controllerName).'.csdo');
		return $ret;
	}
}

?>
