<?php
namespace DB;
final class Connection{
	private $_name   = null;/*Connection Name*/
	private $_dsn    = null;
	private $_user   = null;
	private $_pass   = null;
	private $_params = array();

	private $_pdo = null;
	private $_connected = false;

	public function __construct($name, $dsn, $user, $pass, $params=array()){
		$this->_name   = $name;
		$this->_dsn    = $dsn;
		$this->_user   = $user;
		$this->_pass   = $pass;
		$this->_params = $params;
	}
	public function name(){
		return $this->_name;
	}
	public function dsn(){
		return $this->_dsn;
	}
	public function user(){
		return $this->_user;
	}
	public function pass(){
		return $this->_pass;
	}
	public function params(){
		return $this->_params;
	}
	public function addParam($connectionParam){
		$this->_params[] = $connectionParam;
	}
	public function connect(){
		if(!$this->_pdo){
			$this->_pdo = new \PDO($this->dsn(), $this->user(), $this->pass());
			foreach($this->_params as $param){
				$this->_pdo->setAttribute($param->key(), $param->value());
			}
			$this->_connected = true;
		}
	}
	public function disconnect(){
		$this->_pdo = null;
		$this->_connected = false;
	}
	public function connected(){
		return $this->_connected && $this->_pdo;
	}
	public function begin(){
		return $this->_pdo->beginTransaction();
	}
	public function commit(){
		return $this->_pdo->commit();
	}
	public function rollback(){
		return $this->_pdo->rollback();
	}
	public function inTransaction(){
		return $this->_pdo->inTransaction();
	}
	public function prepare($query){
        $stmt = $this->_pdo->prepare($query);
        return $stmt;
	}
	public function query(){
		return new \QueryBuilder($this->_pdo);
	}
	public function proc($name, $arguments=array()){
        $_params = array();
        if(count($arguments)>0) {
            for ($i=0; $i<count($arguments); $i++) {
                $_params[] = '?';
            }
        }
        $schemaName = $this->schema();
		$procName = $schemaName ? $schemaName.'.'.$name : $name;
        $stmt = $this->_pdo->prepare("SELECT * FROM {$procName}(" .
            implode(', ', $_params) .  ")");
        $status = $stmt->execute($arguments);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($status)
        	return $stmt;
        return $status;
	}
}
?>