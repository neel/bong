<?php
class BongAppModel extends \Singleton{
	/**
	 * override connectionName in your Model Class and return a connection name to use
	 * if null is returned the default connection will be used
	 */
	public function __construct(){
		//echo ">> BongAppModel::__construct()\n";
	}
	/*virtual*/public function connectionName(){
		return null;
	}
	final public function connection(){
		return \DB\DatabaseConfig::connection($this->connectionName());
	}
	public function begin(){
		return $this->connection()->begin();
	}
	public function commit(){
		return $this->connection()->commit();
	}
	public function rollback(){
		return $this->connection()->rollback();
	}
	public function inTransaction(){
		return $this->connection()->inTransaction();
	}
	public function prepare($query){
		return $this->connection()->prepare($query);
	}
	public function query(){
		$conn = $this->connection();
		if(!$conn){
			\DatabaseConfig::instance();
			//print_r(\DB\Config::instance());
		}
		return $conn->query(); 
	}
	public function proc($name, $arguments=array()){
		return $this->connection()->proc($name, $arguments);
	}
}
?>
