<?php
abstract class BongException extends Exception{
	private $_hierarchy = null;
	private $_errCode = 0;
	protected $params;
	
	public function __construct($hierarchy, $errCode){
		$this->_hierarchy = $hierarchy;
		$this->_errCode = "B09G039".$errCode;
		parent::__construct("UnCaught Exception <<".$this->_hierarchy.">> Thrown", $errCode);
		$this->registerParam(new BongExceptionParam("date", "Date", true));
		$this->registerParam(new BongExceptionParam("time", "Time", true));
		$this->registerParam(new BongExceptionParam("tz", "Time Zone", true));
	}
	/**
	 * Register All Params required by the Derived Exceptin Object
	 * @param BongExceptionParam $param
	 */
	final protected function registerParam($param){
		$this->params[$param->name()] = $param;
	}
	/**
	 * Checks Wheather All Params given in the Exception are Valid
	 */
	final protected function valid(&$invalidParam=null){
		foreach($this->params as $param){
			if(!$param->valid()){
				$invalidParam = $param->name();
				return false;
			}
		}
		return true;
	}
	/**
	 * Set a Param. Will return false if trying to set non Existing Param. returns true Otherwise
	 * @param String $name
	 * @param Mixed $value
	 */
	final public function setParam($name, $value){
		if(isset($this->params[$name])){
			$this->params[$name]->setValue($value);
			return true;
		}
		return false;
	}
	final public function param($name){
		if(isset($this->params[$name])){
			return $this->params[$name]->value();
		}
		return null;//Should Throw an Exception in this Scenario
	}
	final public function params(){
		return $this->params;
	}
	final public function __set($name, $value){
		return $this->setParam($name, $value);
	}
	final public function __get($name){
		return $this->param($name);
	}
	public function paramsFlatterned(){
		$buff = '{';
		$i = 0;
		foreach($this->params as $name => $value){
			$buff .= ($i != 0 ? ',' : '')."\n\t".$name.': '.$value;
			++$i;
		}
		$buff .= "\n}";
		return $buff;
	}
	abstract protected function templatize();
	/**
	 * \overridable
	 * Put's Values on Registered Params
	 */
	public function prepare(){
		$this->setParam('date', @date("D F j Y"));
		$this->setParam('time', @date("H:i:s"));
		$this->setParam('tz', @date_default_timezone_get());
	}
	final public function message(){
		header('Content-Type: text/html');
		$this->prepare();
		$missing_param = "";
		if(!$this->valid($missing_param)){
			throw new InvalidExceptionException($this, $missing_param);
		}
		return $this->templatize();
	}
	final public function __toString(){
		return $this->message();
	}
	final public function code(){
		return $this->_errCode;
	}
	final public function hierarchy(){
		return $this->_hierarchy;
	}
	final protected function generalMessage(){
		return "\nUncaught Exception <<".$this->hierarchy().">> Thrown with Params\n".$this->paramsFlatterned()."\n";
	}
}
?>
