<?php
/**
 * @internal
 * @author neel
 * A Singleton Header Cannot be set Multiple Times. Setting it Twice will overwrite the previous Value 
 */
final class HTTPHeaderTrait{
	private $_dict = array();
	
	public function __construct(){
		$this->_dict['Content-Type'] = HTTPHeader::Singleton;
		$this->_dict['Content-Length'] = HTTPHeader::Singleton;
	}
	public function header($key, $value){
		if(isset($this->_dict[$key]) && $this->_dict[$key] == HTTPHeader::Singleton && !is_null(HTTPHeaders::header($key))){
			$header = HTTPHeaders::header($key);
			$header->setValue($value);
			return $header;
		}
		return new HTTPHeader($key, $value);
	}
}
?>