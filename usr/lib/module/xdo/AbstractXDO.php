<?php
/**
 * TODO Not Implemented Yet
 * @author Neel Basu
 */
abstract class AbstractXDO{
	private $__rawUName = null;
	
	/**
	 * Converts the DataObject to XML DOM
	 * @return DomDocument
	 */
	public function toXML(){
		$xmlSerializer = new XMLPacker($this);
		return $xmlSerializer->toXML();
	}
	
	/**
	 * Checks wheather there exists a Buffer Object ofthe Same Controller already
	 * @internal for Controllers File Name Should be md5($this->sessionId().':'.$controllerName).'.cxdo'
	 * @internal Makes HTTP Stateful
	 */
	public function serialized(){
		return (file_exists($this->__sessionFilePath()));
	}
	
	/**
	 * Serializes the XML Output of the DataObject
	 */
	public function serialize(){
		//echo ">> ".$this->__sessionFilePath()."\n";
		//debug_print_backtrace();
		$this->toXML()->save($this->__sessionFilePath());
	}
	
	/**
	 * Unserializes the Data Object
	 */
	public function unserialize(){
		if($this->serialized()){
			$xmlUnPacker = new XMLUnPacker($this->__sessionFilePath());
			foreach($xmlUnPacker->unpack() as $key => $value){
				$this->{$key} = $value;
			}
			$this->timestamp = time();
		}
	}
	
	/**
	 * 
	 */
	protected static function sessionId(){
		return session_id();
	}
	
	/**
	 * Will return the FileName of the XDO
	 */
	abstract protected function fileName();
	
	/**
	 * age of the Current Data Object (if unserialized from Cache)
	 * time() - time Extracted from xdo Meta <bong:meta ts="?">
	 * TODO Not Implemented
	 */
	public function age(){
		
	}
	
	/**
	 * Flush only the data in Current Object but do not delete the xdo
	 */
	public function flushData(){
		foreach($this as $key => &$value){
			$this->{$key} = is_array($value) ? array() : null;
		}
	}
	
	/**
	 * Flush the data and delete the xdo
	 */
	public function flush(){
		$this->flushData();
		return unlink($this->__sessionFilePath());
	}
	
	private function __sessionFilePath(){
		$sessionDir = \Path::instance()->currentProject('run');
		return ($sessionDir.'/'.$this->uName());
	}
	public function uName(){
		return $this->__rawUName ? $this->__rawUName : $this->fileName();
	}
	public function storage(){
		return $this->__sessionFilePath();
	}
	/**
	 * When the Path to XDO File is Known
	 * @param string $filePath
	 */
	public function load($filePath){
		$this->__rawUName = $filePath;
	}
	public function sessionFilePath(){
		return $this->__sessionFilePath();
	}
}
?>
