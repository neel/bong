<?php
namespace FSM;
class State{
	private static $_counter = 0;
	/**
	 * Request Method
	 * \Rom\Request::GET or \Rom\Request::POST
	 */
	private $_method;
	private $_url;
	/**
	 * every State would have an auto incremented Id
	 */
	private $_id;

	/**
	 * array of States that must be visited before visiting this State
	 */
	private $_parents = array();
	/**
	 * array of States that can be visited after visiting this State
	 */
	private $_children = array();	

	public function id(){
		return $this->_id;
	}	
	public function __construct($method, $url){
		$this->_method = $method;
		$this->_url = $url;
		$this->_id = self::$_counter++;
	}
	public function addParent(&$parent){
		$this->_parents[] = $parent;
	}
	public function addChild(&$child){
		$this->_children[] = $child;
	}
	/**
	 * Checks whether the given url matches with the State Url
	 */
	public function match($url, $method){
		if($method != $this->_method){
			return false;
		}
		$path = parse_url(trim($url, "/"), PHP_URL_PATH);
		$parts = explode('/', $path);
		$stateParts = explode('/', trim($this->_url, '/'));
		foreach($stateParts as $i => $statePart){
			if($statePart != $parts[$i]){
				return false;
			}
		}
		return true;
	}
	public function url(){
		return $this->_url;
	}
	public function method(){
		return $this->_method;
	}
	public function hash(){
		return self::genHash($this->_url, $this->_method);
	}
	public static function genHash($url, $method){
		return md5($url.':'.$method);
	}
	public function trim(){
		unset($this->_parents);
		unset($this->_children);
	}
	public function to($url, $method){
		foreach($this->_children as $state){
			if($state->match($url, $method)){
				return true;
			}
			return false;
		}
	}
	public function from($url, $method){
		foreach($this->_parents as $state){
			if($state->match($url, $method)){
				return true;
			}
			return false;
		}
	}
	public function children(){
		return $this->_children;
	}
	public function outDegree(){
		return count($this->_children);
	}
	public function inDegree(){
		return count($this->_parents);
	}
	public function degree(){
		return ($this->inDegree()+$this->outDegree());
	}
}
class Engine extends \Singleton{
	private $_states = array();
	/**
	 * Current State
	 */
	private $_current = null;
	/**
	 * The Initial State
	 */
	private $_root = null;
	
	public function __construct(){
		$this->_root = new State(\ROM\Request::GET, null);
	}
	public function parseTransition($conf){
		$confPath = \Path::instance()->currentProject('etc.conf.@'.$conf.'.fsm');
		if(!file_exists($confPath)){
			return false;
		}
		$handle = fopen($confPath, 'r');
		if($handle){
			while(($buffer = fgets($handle)) !== false){
				$states = preg_split('~\s*\>\s*~', $buffer);
				if(count($states) <= 1)
					continue;
				$prev = null;
				foreach($states as $i => &$state){
					preg_match('~\s*\(\s*(\w+)\s+([\w\d\/]+)\s*\)\s*~', $state, $m);
					$url = $m[2];
					if(strtolower($m[1]) == 'post'){
						$method = \ROM\Request::POST;
					}else{
						$method = \ROM\Request::GET;
					}
					$state = null;
					if($this->stateExists($url, $method)){
						$state = $this->getState($url, $method);
					}else{
						$state = new State($method, $url);
						$this->_states[$state->hash()] = $state;
					}
					if($i > 0){
						$prev->addChild(&$state);
						$state->addParent(&$prev);
					}
					$prev =& $state;
				}
			}
		}
		fclose($handle);
	}
	public static function parse($conf){
		self::instance()->parseTransition($conf);
		return self::instance()->states();
	}
	public function stateExists($url, $method){
		$hash = State::genHash($url, $method);
		return array_key_exists($hash, $this->_states);
	}
	public function getState($url, $method){
		$hash = State::genHash($url, $method);
		if(array_key_exists($hash, $this->_states)){
			return $this->_states[$hash];
		}
		return false;
	}
	public function states(){
		return $this->_states;
	}
	
}
class GraphStore extends \Singleton{
	private $_dict;
	
	public function __construct(){
		$this->_dict = new stdClass;
		$this->_dict->_current = null;
		$this->_dict->_states = array();
		if($this->exists()){
			$this->load();
		}else{
			$this->store();
		}
	}
	public function dump($states){
		foreach($states as $state){
			$state->trim();
			$this->_dict->_states[$state->hash()] = $state;
		}
	}
	public function stateExists($url, $method){
		$hash = State::genHash($url, $method);
		return array_key_exists($hash, $this->_dict->_states);
	}
	public function getState($url, $method){
		$hash = State::genHash($url, $method);
		if(array_key_exists($hash, $this->_dict->_states)){
			return $this->_dict->_states[$hash];
		}
		return false;
	}
	public function states(){
		return $this->_dict->_states;
	}
	public function setCurrent($url, $method){
		if($this->stateExists($url, $method)){
			$this->_dict->_current = $this->getState($url, $method);			
		}
	}
	private function filePath(){
		return \Path::instance()->currentProject('run').'/fsm';
	}
	public function store(){
		$filePath = $this->filePath();
		file_put_contents($filePath, base64_encode(serialize($this->_dict)));
	}
	public function load(){
		$filePath = $this->filePath();
		$this->_dict = unserialize(base64_decode(file_get_contents($filePath)));
	}
	public function exists(){
		return file_exists($this->filePath());
	}
	public function current(){
		return $this->_dict->_current;
	}
	public function hasCurrent(){
		return ($this->current() != null);
	}
	public function edgeValid($url, $method){
		if($this->hasCurrent()){
			if(Engine::instance()->hasState($url, $method)){
				$current = $this->current();
				$requested = Engine::instance()->getState($url, $method);
				return $requested->from($current->url(), $current->method());
			}
			return false;
		}
		return false;
	}
}
?>
