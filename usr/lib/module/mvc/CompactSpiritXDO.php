<?php
/**
 * Dio Not Nweed
 * Delete
 * @author neel
 *
 */
final class CompactSpiritXDO extends SpiritXDO{
/**
	 * return md5($this->sessionId().':'.$controllerName'.':'.$spiritName).'.sxdo'
	 */
	protected function fileName(){
		md5($this->sessionId().':'.Mempool::instance()->get("bong.mvc.controller").':'.$this->_spiritName.($this->hasUID() ? '#'.$this->uid() : '')).'.sxdo';
	}	
}
?>
