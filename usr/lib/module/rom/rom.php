<?php
namespace ROM;
class Request{
	const GET = 0xFBC02;
	const POST = 0xFBC04;
	const UPLOAD = 0xFBC08;
	const COOKIE = 0xFBC0F;
	const SESSION = 0xFBC0C;
	
	private $_method = null;
	
	public function __construct($method){
		$this->_method = $method;
	}
	public function method(){
		return $this->_method;
	}
	private function _items(){
		switch($this->_method){
			case GET:
				return $_GET;
				break;
			case POST:
				return $_POST;
				break;
			case UPLOAD:
				return $_FILES;
			case COOKIE:
				return $_COOKIES;
				break;
			case SESION:
				return $_SESSION;
				break;
		}
	}
	public function items(){
		return $this->items();
	}
	public function exists($name){
		return array_key_exists($name, $this->items());
	}
	public function key($name){
		if($this->exists($name)){
			$data = $this->items();
			return $data[$name];
		}
	}
	public function isEmpty(){
		return $this->length() > 0;
	}
	public function length(){
		return count($this->items);
	}
	public function validate($conf){
		\Validation\Validation::parse($conf, $this->items());
	}
}
class Client{
	public $browser='';
	public $userAgent='';
	public $host='';
	public $accept='';
	public $acceptLanguage='';
	public $acceptCharSet='';
	public $acceptEncoding='';
	public $connection='';
	public $remotePort='';
	public $remoteAddr='';
	public $requestMethod='';
	public $request='';
	public $version='';
	public $platform='';
	
	public function __construct(){
		$this->browser = "Unknown";
		$agent = @$_SERVER['HTTP_USER_AGENT'];
		$this->userAgent = @$agent;
		$this->host = @$_SERVER['HTTP_HOST'];
		$this->accept = @$_SERVER['HTTP_ACCEPT'];
		$this->acceptLanguage = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$this->acceptCharSet = @$_SERVER['HTTP_ACCEPT_CHARSET'];
		$this->acceptEncoding = @$_SERVER['HTTP_ACCEPT_ENCODING'];
		$this->connection = @$_SERVER['HTTP_CONNECTION'];
		$this->remotePort = @$_SERVER['REMOTE_PORT'];
		$this->remoteAddr = @$_SERVER['REMOTE_ADDR'];
		$this->requestMethod = @$_SERVER['REQUEST_METHOD'];
		$this->request = @$_SERVER['REQUEST_METHOD']." ".@$_SERVER['REQUEST_URI']." ".@$_SERVER['SERVER_PROTOCOL'];
		$this->version = "Unknown";
		$this->platform = "Unknown";
	/**
	 * Some of the Following Codes have been borrowed from http://apptools.com/phptools/browser/source.php
	 *
	 * File name: browser.php
     * Author: Gary White
     * Last modified: November 10, 2003
     *
     * **************************************************************
     *
     * Copyright (C) 2003  Gary White
     *
     * This program is free software; you can redistribute it and/or
     * modify it under the terms of the GNU General Public License
     * as published by the Free Software Foundation; either version 2
     * of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details at:
     * http://www.gnu.org/copyleft/gpl.html
     * *************************************************************
     *  For browsers, it should correctly identify all versions of:
     *  Amaya
     *  Galeon
     *  iCab
     *  Internet Explorer
     *  Konqueror
     *  Lynx
     *  Mozilla
     *  Netscape Navigator/Communicator
     *  OmniWeb
     *  Opera
     *  Pocket Internet Explorer for handhelds
     *  Safari
     *  WebTV
		 */
		switch(true){
			case (eregi("win", $agent)):
	      $this->platform = "Windows";
	      if(!defined('IS_WIN'))define('IS_WIN', "Windows");
	      if(!defined('IS_WINDOWS'))define('IS_WINDOWS', "Windows");
	      break;
			case (eregi("mac", $agent)):
	      $this->platform = "MacIntosh";
	      if(!defined('IS_MAC'))define('IS_MAC', "MacIntosh");
	      if(!defined('IS_MACINTOSH'))define('IS_MACINTOSH', "MacIntosh");
	      break;
			case (eregi("linux", $agent)):
	      $this->platform = "Linux";
	      if(!defined('IS_LINUX'))define('IS_LINUX', "Linux");
	      break;
			case (eregi("OS/2", $agent)):
	      $this->platform = "OS/2";
	      if(!defined('IS_OS2'))define('IS_OS2', "OS/2");
	      break;
			case (eregi("BeOS", $agent)):
	      $this->platform = "BeOS";
	      if(!defined('IS_BEOS'))define('IS_BEOS', "BeOS");
	      break;
		}
    if(!defined('CLIENT_OS'))define('CLIENT_OS', $this->platform);
    if(!defined('IS_WIN'))define('IS_WIN', false);
    if(!defined('IS_WINDOWS'))define('IS_WINDOWS', false);
    if(!defined('IS_MAC'))define('IS_MAC', false);
    if(!defined('IS_MACINTOSH'))define('IS_MACINTOSH', false);
    if(!defined('IS_LINUX'))define('IS_LINUX', false);
    if(!defined('IS_OS2'))define('IS_OS2', false);
    if(!defined('IS_BEOS'))define('IS_BEOS', false);
		switch(true){
			case eregi("opera",$agent):
				if(!defined('IS_OPERA'))define('IS_OPERA', "opera");
				$val = stristr($agent, "opera");
        if (eregi("/", $val)){
	        $val = explode("/",$val);
	        $this->browser = "opera";
	        $val = explode(" ",$val[1]);
	        $this->version = $val[0];
        }else{
          $val = explode(" ",stristr($val,"opera"));
          $this->browser = "opera";
          $this->version = $val[1];
        }
				break;
			case eregi("webtv",$agent):
				if(!defined('IS_WEBTV'))define('IS_WEBTV', "webtv");
        $val = explode("/",stristr($agent,"webtv"));
        $this->browser = "webtv";
        $this->version = $val[1];
				break;
			case eregi("microsoft internet explorer", $agent):
				if(!defined('IS_IE'))define('IS_IE', "microsoft internet explorer");
        $this->browser = "microsoft internet explorer";
        $this->version = "1.0";
        $var = stristr($agent, "/");
        if (ereg("308|425|426|474|0b1", $var)){
        	$this->version = "1.5";
        }
				break;
			case eregi("NetPositive", $agent):
				if(!defined('IS_NETPOSITIVE'))define('IS_NETPOSITIVE', "NetPositive");
        $val = explode("/",stristr($agent,"NetPositive"));
        $this->browser = "NetPositive";
        $this->version = $val[1];
				break;
			case (eregi("msie",$agent) && !eregi("opera",$agent)):
				if(!defined('IS_MSIE'))define('IS_MSIE', "msie");
        $val = explode(" ",stristr($agent,"msie"));
        $this->browser = "msie";
        $this->version = $val[1];
				break;
			case (eregi("mspie",$agent) || eregi('pocket', $agent)):
				if(!defined('IS_MSPIE'))define('IS_MSPIE', "mspie");
        $val = explode(" ",stristr($agent,"mspie"));
        $this->browser = "mspie";
        $this->version = "WindowsCE";
        if(eregi("mspie", $agent))
					$this->version = $val[1];
        else{
          $val = explode("/",$agent);
          $this->version = $val[1];
        }
				break;
			case eregi("galeon",$agent):
				if(!defined('IS_GALEON'))define('IS_GALEON', "galeon");
        $val = explode(" ",stristr($agent,"galeon"));
        $val = explode("/",$val[0]);
        $this->browser = "galeon";
        $this->version = $val[1];
				break;
			case eregi("Konqueror",$agent):
				if(!defined('IS_KONQUEROR'))define('IS_KONQUEROR', "Konqueror");
        $val = explode(" ",stristr($agent,"Konqueror"));
        $val = explode("/",$val[0]);
        $this->browser = "Konqueror";
        $this->version = $val[1];
				break;
			case eregi("icab",$agent):
				if(!defined('IS_ICAB'))define('IS_ICAB', "icab");
        $val = explode(" ",stristr($agent,"icab"));
        $this->browser = 'icab';
        $this->version = $val[1];
				break;
			case eregi("omniweb",$agent):
				if(!defined('IS_OMNIWEB'))define('IS_OMNIWEB', "omniweb");
        $val = explode("/",stristr($agent,"omniweb"));
        $this->browser = "omniweb";
        $this->version = $val[1];
				break;
			case eregi("Phoenix", $agent):
				if(!defined('IS_PHOENIX'))define('IS_PHOENIX', "Phoenix");
        $this->browser = "Phoenix";
        $val = explode("/", stristr($agent,"Phoenix/"));
        $this->version = $val[1];
				break;
			case eregi("firebird", $agent):
				if(!defined('IS_FIREBIRD'))define('IS_FIREBIRD', "Firebird");
        $this->browser="Firebird";
        $val = stristr($agent, "Firebird");
        $val = explode("/",$val);
        $this->version = $val[1];
				break;
			case eregi("Firefox", $agent):
				if(!defined('IS_FIREFOX'))define('IS_FIREFOX', "Firefox");
        $this->browser="Firefox";
        $val = stristr($agent, "Firefox");
        $val = explode("/",$val);
        $this->version = $val[1];
				break;
			case (eregi("mozilla",$agent) && eregi("rv:[0-9].[0-9][a-b]",$agent) && !eregi("netscape",$agent)):
				if(!defined('IS_MOZILLA'))define('IS_MOZILLA', "Mozilla");
        $this->browser = "Mozilla";
        $val = explode(" ",stristr($agent,"rv:"));
        eregi("rv:[0-9].[0-9][a-b]",$agent,$val);
        $this->version = str_replace("rv:","",$val[0]);
				break;
			case (eregi("mozilla",$agent) && eregi("rv:[0-9]\.[0-9]",$agent) && !eregi("netscape",$agent)):
				if(!defined('IS_MOZILLA'))define('IS_MOZILLA', "Mozilla");
        $this->browser = "Mozilla";
        $val = explode(" ",stristr($agent,"rv:"));
        eregi("rv:[0-9]\.[0-9]\.[0-9]",$agent,$val);
        $this->version = str_replace("rv:","",$val[0]);
				break;
			case eregi("libwww", $agent):
	      if(eregi("amaya", $agent)){
	        $val = explode("/",stristr($agent,"amaya"));
  				if(!defined('IS_AMAYA'))define('IS_AMAYA', "Amaya");
	        $this->browser = "Amaya";
	        $val = explode(" ", $val[1]);
	        $this->version = $val[0];
	      }else{
	        $val = explode("/",$agent);
  				if(!defined('IS_LYNX'))define('IS_LYNX', "Lynx");
	        $this->browser = "Lynx";
	        $this->version = $val[1];
	      }
				break;
			case eregi("safari", $agent):
				if(!defined('IS_SAFARI'))define('IS_SAFARI', "Safari");
        $this->browser = "Safari";
        $this->version = "";
				break;
			case eregi("netscape",$agent):
        $val = explode(" ",stristr($agent,"netscape"));
        $val = explode("/",$val[0]);
				if(!defined('IS_NETSCAPE'))define('IS_NETSCAPE', "netscape");
        $this->browser = "netscape";
        $this->version = $val[1];
				break;
			case (eregi("mozilla",$agent) && !eregi("rv:[0-9]\.[0-9]\.[0-9]",$agent)):
        $val = explode(" ",stristr($agent,"mozilla"));
        $val = explode("/",$val[0]);
				if(!defined('IS_NETSCAPE'))define('IS_NETSCAPE', "netscape");
        $this->browser = "netscape";
        $this->version = $val[1];
				break;
		}
		if(!defined('CLIENT_BROWSER'))define('CLIENT_BROWSER', $this->browser);
		if(!defined('IS_OPERA'))define('IS_OPERA', false);
		if(!defined('IS_WEBTV'))define('IS_WEBTV', false);
		if(!defined('IS_IE'))define('IS_IE', false);
		if(!defined('IS_NETPOSITIVE'))define('IS_NETPOSITIVE', false);
		if(!defined('IS_MSIE'))define('IS_MSIE', false);
		if(!defined('IS_MSPIE'))define('IS_MSPIE', false);
		if(!defined('IS_GALEON'))define('IS_GALEON', false);
		if(!defined('IS_KONQUEROR'))define('IS_KONQUEROR', false);
		if(!defined('IS_ICAB'))define('IS_ICAB', false);
		if(!defined('IS_OMNIWEB'))define('IS_OMNIWEB', false);
		if(!defined('IS_PHOENIX'))define('IS_PHOENIX', false);
		if(!defined('IS_FIREBIRD'))define('IS_FIREBIRD', false);
		if(!defined('IS_FIREFOX'))define('IS_FIREFOX', false);
		if(!defined('IS_MOZILLA'))define('IS_MOZILLA', false);
		if(!defined('IS_AMAYA'))define('IS_AMAYA', false);
		if(!defined('IS_LYNX'))define('IS_LYNX', false);
		if(!defined('IS_SAFARI'))define('IS_SAFARI', false);
		if(!defined('IS_NETSCAPE'))define('IS_NETSCAPE', false);		
	}
}
class Server{
	public $software;
	public $signature;
	public $port;
	public $name;
	public $address;
	public $admin;
	public $protocol;
	
	public function __construct(){
		$this->software = @$_SERVER['SERVER_SOFTWARE'];
		$this->signature = @$_SERVER['SERVER_SIGNATURE'];
		$this->port = @$_SERVER['SERVER_PORT'];
		$this->name = @$_SERVER['SERVER_NAME'];
		$this->address = @$_SERVER['SERVER_ADDR'];
		$this->admin = @$_SERVER['SERVER_ADMIN'];
		$this->protocol = @$_SERVER['SERVER_PROTOCOL'];		
	}
}
class Rom{
	public $get = null;
	public $post = null;
	public $upload = null;
	public $cookie = null;
	public $session = null;
	private $_method = null;
	private $_client = null;
	private $_server = null;
	
	public function __construct(){
		$this->get = new Request(Reuquest::GET);
		$this->post = new Request(Reuquest::POST);
		$this->upload = new Request(Reuquest::UPLOAD);
		$this->cookie = new Request(Reuquest::COOKIE);
		$this->session = new Request(Reuquest::SESSION);
		$method = $_SERVER["REQUEST_METHOD"];
		switch($method){
			case 'GET':
				$this->_method = Request::GET;
				break;
			case 'POST':
				$this->_method = Request::POST;
				break;
		}
		$this->_client = new Client();
		$this->_server = new Server();
	}
	public function requestMethod(){
		return $this->_method;
	}
	public function isPostRequest(){
		return ($this->_method == Reuquest::POST);
	}
	public function client(){
		$this->_client;
	}
	public function server(){
		$this->_server;
	}
}
?>
