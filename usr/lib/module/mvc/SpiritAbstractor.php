<?php
abstract class SpiritAbstractor extends BongController{
	private static $InstanceBindingInterface = "InstanceBound";
	private static $StaticBindingInterface = "StaticBound";
	
	private static $SerializableXDOInterface = "SerializableXDO";
	private static $MemoryXDOInterface = "MemoryXDO";
	
	private static $ControllerFeededInterface = "ControllerFeeded";
	private static $SelfFeededInterface = "SelfFeeded";
	private static $SpiritFeededInterface = "SpiritFeeded";
	
	private static $SessionedSpiritInterface = "SessionedSpirit";
	private static $FloatingSpiritInterface = "FloatingSpirit";
	/**
	 * @var BongAppController
	 */
	public $controller;
	
	const InstanceBinding = 0xA001;
	const StaticBinding = 0xA002;
	
	const SerializableXDO = 0xA004;
	const MemoryXDO = 0xA008;
	
	const ControllerFeeded = 0xA010;
	const SelfFeeded = 0xA020;
	const SpiritFeeded = 0xA040;
	
	const Sessioned = 0xA080;
	const Floating = 0xA0FF;
	
	private $_instanceId = null;
	
	/**
	 * @var SpiritEngine
	 */
	private $_engine;
	
	public function __construct(&$engine, $spiritName, $instanceId=null){
		if(!static::iCheck()){
			assert("/*Some Interface Not Implemented Properly Check Previous Errors*/");
			return false;
		}
		parent::__construct();
		$this->_engine = $engine;
		$this->meta = new SpiritMeta($spiritName);
		if(static::serializable() == SpiritAbstractor::SerializableXDO)
			$this->xdo = new SpiritXDO();
		else
			$this->xdo = new SpiritMemoryXDO();//TODO should be new MemoryXDO() Instead.
		$this->xdo->setAbstractor($this);
		$this->xdo->setSpirit($spiritName);
		if(static::feeder() == SpiritAbstractor::ControllerFeeded){
			$this->controller = $engine->currentController();
		}
		/*{ Experimental*/
		elseif(static::feeder() == SpiritAbstractor::SpiritFeeded){
			$this->controller = $engine->abstractor();
		}
		/*} */
		if(static::binding() == SpiritAbstractor::InstanceBinding){
			$this->_instanceId = $instanceId;
			$this->xdo->setUID($this->uid());
		}
		if($this->xdo->serialized())
			$this->xdo->unserialize();
		$this->xdo->setSpirit($spiritName);
		$this->ctor();
	}
	/**
	 * meant to be Called from SpiritEngine Only
	 * @friend SpiritEngine::render
	 */
	final public function setCurrentMethodName($methodName){
		$this->meta->spiritMethod = $methodName;
	}
	final private function uid(){
		return '0x'. (static::feeder()==SpiritAbstractor::ControllerFeeded ? strtoupper(MemPool::instance()->get('bong.mvc.controller')) : '').$this->_instanceId;
	}
	/*virtual*/ public function ctor(){}
	
	final public function engine(){
		return $this->_engine;
	}
	/**
	 * 
	 * @param string $spiritName
	 * @return SpiritAdapter
	 */
	public function spirit($spiritName){
		return new SpiritAdapter($spiritName, $this->_engine);
	}
	public function embed($spiritName){
		return new SpiritAdapter($spiritName, new EmbeddedSpiritEngine($this->_engine, $this));
	}
	
	static public function binding(){
		$abstractorClassname = get_called_class();
		if(in_array(self::$InstanceBindingInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::InstanceBinding;
		elseif(in_array(self::$StaticBindingInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::StaticBinding;
		else
			return 0x000;
	}
	static public function feeder(){
		$abstractorClassname = get_called_class();
		if(in_array(self::$ControllerFeededInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::ControllerFeeded;
		elseif(in_array(self::$SelfFeededInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::SelfFeeded;
		elseif(in_array(self::$SpiritFeededInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::SpiritFeeded;
		else
			return 0x000;
	}
	static public function serializable(){
		$abstractorClassname = get_called_class();
		if(in_array(self::$SerializableXDOInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::SerializableXDO;
		elseif(in_array(self::$MemoryXDOInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::MemoryXDO;
		else
			return 0x000;
	}
	static public function sessioned(){
		$abstractorClassname = get_called_class();
		if(in_array(self::$SessionedSpiritInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::Sessioned;
		elseif(in_array(self::$FloatingSpiritInterface, class_implements($abstractorClassname)))
			return SpiritAbstractor::Floating;
		else
			return 0x000;
	}
	/**
	 * Check's wheather or not proper Interfaces are Provided
	 */
	static private function iCheck(){
		$badFlag = true;
		if(static::binding() == 0x000){
			assert("/*No Binding Interface Implemented*/");
			$badFlag = false;
		}
		if(static::feeder() == 0x000){
			assert("/*No Feeder Interface Implemented*/");
			$badFlag = false;
		}
		if(static::serializable() == 0x000){
			assert("/*No Serialization Interface Implemented*/");
			$badFlag = false;
		}
		if(static::sessioned() == 0x000){
			assert("/*No Session Interface Implemented*/");
			$badFlag = false;
		}
		return $badFlag;
	}
}
?>
