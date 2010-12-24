<?php
class _Backend{
	private $_locks = array();
	
	public function registerLock($lockFileName){
		$this->_locks[] = $lockFileName;
	}
	public function unregisterLock($lockFileName){
		$index = array_search($lockFileName, $this->_locks);
		if($index !== false && $index >= 0){
			$this->_locks[$index] = null;
		}
	}
	public function __destruct(){
		foreach($this->_locks as $lockFile){
			if($lockFile && file_exists($lockFile)){
				unlink($lockFile);
			}
		}
	}
}
class Backend{
	const BACKEND = '.backend';
	const LOCK = '.lock';
	
	private static $_autoReleaser = null;
	public static function _init(){
		self::$_autoReleaser = new _Backend;
	}
	
	private static function acquireLock($lockFile){
		if(!file_exists($lockFile)){
			touch($lockFile);
		}
		self::$_autoReleaser->registerLock($lockFile);
	}
	private static function releaseLock($lockFile){
		if(file_exists($lockFile)){
			unlink($lockFile);
		}
		self::$_autoReleaser->unregisterLock($lockFile);
	}
	public static function SaveSessioned($name, $o){
		$filePath = self::_filePath($name, true);
		file_put_contents($filePath.Backend::BACKEND, base64_encode(serialize($o)));
		self::releaseLock($filePath.Backend::LOCK);
	}
	public static function LoadSessioned($name){
		$filePath = self::_filePath($name, true);
		self::acquireLock($filePath.Backend::LOCK);
		return unserialize(base64_decode(file_get_contents($filePath.Backend::BACKEND)));
	}
	public static function SaveUnSessioned($name, $o){
		$filePath = self::_filePath($name, false);
		file_put_contents($filePath.Backend::BACKEND, base64_encode(serialize($o)));
		self::releaseLock($filePath.Backend::LOCK);
	}
	public static function LoadUnSessioned($name){
		$filePath = self::_filePath($name, false);
		self::acquireLock($filePath.Backend::LOCK);
		return unserialize(base64_decode(file_get_contents($filePath.Backend::BACKEND)));
	}
	public static function ExistsSessioned($name){
		return file_exists(self::_filePath($name, true).Backend::BACKEND);
	}
	public static function ExistsUnSessioned($name){
		return file_exists(self::_filePath($name, false).Backend::BACKEND);
	}
	public static function BusySessioned($name){
		return file_exists(self::_filePath($name, true).Backend::LOCK);
	}
	public static function BusyUnSessioned($name){
		return file_exists(self::_filePath($name, false).Backend::LOCK);
	}	
	private static function _filePath($name, $sessioned){
		return rtrim(Path::instance()->currentProject('backend'), "/").'/'.md5(($sessioned ? session_id() : '').$name);
	}
}
Backend::_init();
?>
