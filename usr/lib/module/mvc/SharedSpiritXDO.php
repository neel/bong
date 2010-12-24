<?php
/**
 * Do NOT Need
 * Delete
 * @author neel
 *
 */
final class SharedSpiritXDO extends SpiritXDO{
	/**
	 * return md5($this->sessionId().':'.$spiritName).'.sxdo'
	 */
	protected function fileName(){
		return md5($this->sessionId().':'.$this->_spiritName.($this->hasUID() ? '#'.$this->uid() : '')).'.sxdo';
	}
}
?>
