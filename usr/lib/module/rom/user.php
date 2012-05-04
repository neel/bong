<?php
namespace ROM;
final class BongXDODescriptor{
	const ControllerXDO = 0x0012;
	const SpiritXDO = 0x0014;
	
	public $type = null;
	public $name = null;
	public $file = null;
	//public $uid = null;
	
	
	public function __construct($type, $name, $fileName){
		$this->type = $type;
		$this->name = $name;
		$this->file = $fileName;
	}
	public function __construct1($type, $name, $file ,$uid = null){
		$this->type = $type;
		$this->name = $name;
		$this->file = $file;
		$this->uid = $uid;
		
	}
	/**
	 * returns the xdo Object
	 */
	public function xdo(){
		$xmlUnPacker = new XMLUnPacker($this->fileName());
		return $xmlUnPacker->unpack();
	}
	public function fileName(){
		return $this->file;
	}
	public function ToString(){
		return $this->fileName();
	}
}
namespace ROM;
final class BongUserData{
	public $ClientSignature;
	public $IpAddress;
	public $SessionId;
	public $State;
	public $xdos = array();
	public $LastAccess;
	public $requestHistory = array();
	
	public function load($fileName){
		$sessionDir = \Path::instance()->currentProject('run');
		$buff = unserialize(file_get_contents($sessionDir.'/'.$fileName));
		$this->ClientSignature = $buff->c;
		$this->IpAddress = $buff->i;
		$this->SessionId = $buff->s;
		$this->State = $buff->t;
		$this->xdos = $buff->x;
		$this->LastAccess = $buff->l;
		$this->requestHistory = $buff->h;
	}
}
/**
 * BongCurrentUserData::instance() is used in the Application to get the track record of the current User
 * It Dumps all info's in a File named session_id().user in /var/run in your Project Directory when the normal execution of the application ends
 * It loads all those infos' back from that file when that application starts
 * But the above one requires that User to have the same sesion Id and same Browser Signature and the Same IP Address
 * If any of these 3 things are Changed the User is no longer believed as the Same User
 * This is a Part of Internal Auto Authentication that Makes Sesion Hijacking even Hearder
 */
namespace ROM;
final class UrlRequest{
	public $ts = array();
	public $sessionIds = array();
	public $url;
	
	public function __construct($ts, $sess, $url){
		$this->ts[0] = $ts;
		$this->sessionIds[0] = $sess;
		$this->url = $url;
	}
}
final class BongCurrentUserData extends \Singleton{
	public $ClientSignature;
	public $IpAddress;
	public $SessionId;
	public $State;
	public $xdos = array();
	public $LastAccess;
	public $requestHistory = array();
	public $store = null;
	public $csrf_token;
	public $csrf_rand;
	
	public function __construct(){
		$this->SesionId = session_id();
		$this->csrf_rand = @$_COOKIE['bong_csrf_rand'];
		$this->csrf_token = @$_COOKIE['bong_csrf_token'];
		$this->store = new \stdClass();
		$this->load();
		$this->ClientSignature = \ROM\Client::instance()->userAgent;
		$this->IpAddress = \ROM\Client::instance()->remoteAddr;
		$this->LastAccess = time();
	}
	public function addXDO($descriptor){
		if(!in_array($descriptor, $this->xdos)){
			$this->xdos[] = $descriptor;
		}
	}
	public function addUrlRequest($urlRequest){
		$lastRequest = count($this->requestHistory);
		if($lastRequest > 0){
			if($this->requestHistory[$lastRequest-1]->url == $urlRequest->url){
				$this->requestHistory[$lastRequest-1]->ts[] = $urlRequest->ts[0];
				$this->requestHistory[$lastRequest-1]->sessionIds[] = $urlRequest->sessionIds[0];
			}else{
				if($lastRequest >= 10){
					array_shift($this->requestHistory);
				}
				$this->requestHistory[] = $urlRequest;
			}
		}else{
			if($lastRequest >= 10){
				array_shift($this->requestHistory);
			}
			$this->requestHistory[] = $urlRequest;
		}
	}
	public function dump(){
		$buff = new \stdClass;
		$buff->c = $this->ClientSignature;
		$buff->i = $this->IpAddress;
		$buff->s = $this->SessionId;
		$buff->t = $this->State;
		$buff->x = $this->xdos;
		$buff->l = $this->LastAccess;
		$buff->h = $this->requestHistory;
		$buff->r = $this->store;
		$buff->csrf_rand = $this->csrf_rand;
		$buff->csrf_token = $this->csrf_token;
		$sessionDir = \Path::instance()->currentProject('run');
		return file_put_contents($sessionDir."/".session_id().".usr", serialize($buff));
	}
	public function load(){
		$sessionDir = \Path::instance()->currentProject('run');
		$filePath = $sessionDir."/".session_id().".usr";
		if(file_exists($filePath) && is_readable($filePath)){
			$buff = unserialize(file_get_contents($filePath));
			$this->ClientSignature = $buff->c;
			$this->IpAddress = $buff->i;
			$this->SessionId = $buff->s;
			$this->State = $buff->t;
			$this->xdos = $buff->x;
			$this->LastAccess = $buff->l;
			$this->requestHistory = $buff->h;
			$this->store = ($buff->r);
			$this->csrf_rand = $buff->csrf_rand;
			$this->csrf_token = $buff->csrf_token;
			return true;
		}
		return false;
	}
	public function identical(){		
		return (
			$this->ClientSignature == \ROM\Client::instance()->userAgent &&
			$this->IpAddress == \ROM\Client::instance()->remoteAddr
		);
	}
	public function csrf_identical(){
		return (
			$this->ClientSignature == \ROM\Client::instance()->userAgent &&
			$this->IpAddress == \ROM\Client::instance()->remoteAddr &&
			$this->csrf_rand == @$_COOKIE['bong_csrf_rand'] &&
			$this->csrf_token == @$_COOKIE['bong_csrf_token'] &&
			$this->csrf_token == sha1(session_id().'bong'.$this->csrf_rand)
		);
	}
	public static function reset(){		
		/*
		$_SESSION = array();
		if(ini_get("session.use_cookies")){
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
		//self::startSession(true);
		$installation_path = \MemPool::instance()->get('bong.url.base');
		//session_name('bong_user_token');
		$currentCookieParams = session_get_cookie_params();
		$currentCookieParams["path"] = $installation_path;
		$currentCookieParams["httponly"] = true;
		session_set_cookie_params( 
			$currentCookieParams["lifetime"], 
			$currentCookieParams["path"], 
			$currentCookieParams["domain"], 
			$currentCookieParams["secure"], 
			$currentCookieParams["httponly"] 
		);
		session_regenerate_id(true);
		*/

		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(),'',time()-3600,'/bong');
		session_regenerate_id(true);
	}
	public static function startSession($reset=false){
		$installation_path = \MemPool::instance()->get('bong.url.base');
		//session_name('bong_user_token');
		$currentCookieParams = session_get_cookie_params();
		$currentCookieParams["path"] = $installation_path;
		//$currentCookieParams["httponly"] = true;
		session_set_cookie_params( 
			$currentCookieParams["lifetime"], 
			$currentCookieParams["path"], 
			$currentCookieParams["domain"], 
			$currentCookieParams["secure"], 
			$currentCookieParams["httponly"] 
		);
		if(!session_id()){
			session_start();
		}
	}
	public function csrf_send(){
		$installation_path = \MemPool::instance()->get('bong.url.base');
		$rand = mt_rand().time().'';
		$rand_token = trim(str_shuffle($rand), 0);
		$hash_token = sha1(session_id().'bong'.$rand_token);
		setcookie('bong_csrf_token', $hash_token, 0, $installation_path);
		setcookie('bong_csrf_rand', $rand_token, 0, $installation_path);
		$_COOKIE['bong_csrf_token'] = $hash_token;
		$_COOKIE['bong_csrf_rand'] = $rand_token;
	}
	public function csrf_resend(){
		$installation_path = \MemPool::instance()->get('bong.url.base');
		$rand = mt_rand().time().'';
		$rand_token = trim(str_shuffle($rand), 0);
		$hash_token = sha1(session_id().'bong'.$rand_token);
		$_COOKIE['bong_csrf_token'] = $hash_token;
		$_COOKIE['bong_csrf_rand'] = $rand_token;
	}
	public function csrf_sent(){
		return (array_key_exists('bong_csrf_rand', $_COOKIE) && array_key_exists('bong_csrf_token', $_COOKIE));
	}
}
?>
