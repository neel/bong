<?php
class HTTPHeaders{
	private static $_trait = null;
	private static $_headers = array();
	private static $_freezed = false;
	private static $_status = null;
	private static $_msg = '';
	
	public static function init(){
		self::$_trait = new HTTPHeaderTrait();
	}
	public static function setResponseCode($code, $msg = ''){
		self::$_status = $code;
		self::$_msg = $msg;
	}
	public static function responseCode(){
		return $this->_status;
	}
	public static function addHeader($key, $value){
		if(self::freezed())
			return;
		self::$_headers[] = self::$_trait->header($key, $value);
	}
	public static function header($key){
		foreach(self::$_headers as &$header){
			if($header->key() == $key){
				return $header;
			}
		}
		return null;
	}
	public static function send(){
		if(self::$_status){
			header($_SERVER['SERVER_PROTOCOL'].' '.self::$_status.' '.self::$_msg);
		}
		foreach(self::$_headers as &$header){
			header($header->toString());
		}
	}
	public static function contentType($mimeType){
		self::addHeader('Content-Type', $mimeType);
	}
	/**
	 * Once freezed No Headers can be sent unless its released
	 */
	public static function freeze(){
		self::$_freezed = true;
	}
	public static function freezed(){
		return self::$_freezed;
	}
	public static function release(){
		self::$_freezed = false;
	}
}
final class http extends HTTPHeaders{}
HTTPHeaders::init();
?>
