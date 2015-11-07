<?php
namespace DB;
final class Config{
	private $_connections = array();
	private $_default_connection_name = null;
	private $_autoconnect = false;

	public function addConnection($connection){
		$this->_connections[] = $connection;
	}
	/**
	 * returns connection with name `$name`
	 * returns the default connection if no name is specified;
	 */
	public function conn($name=null){
		if(!$name)
			$name = $this->defaultConnection();
		foreach($this->_connections as $connection){
			if($connection->name() == $name){
				return $connection;
			}
		}
		return null;
	}
	/**
	 * set's the dafault connection by name
	 * \internal not intended to be called from user's side
	 */
	public function setDefault($name){
		$this->_default_connection_name = $name;
	}
	/**
	 * return's the default connection name
	 */
	public function defaultConnection(){
		return $this->_default_connection_name;
	}
	/**
	 * sets wheather or not to connect automatically on start up.
	 * \internal not intended to be invoked by user
	 */
	public function setAutoConnect($flag = true){
		$this->_autoconnect = $flag;
		if($flag){
			$conn = $this->conn();
			if(!$conn->connected()){
				$conn->connect();
			}
		}
	}
	public function autoConnect(){
		return $this->_autoconnect;
	}
}
?>