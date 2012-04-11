<?php
abstract class BongAppModel{
	protected $_pdo = null;
	
	abstract protected function dsn();
	abstract protected function user();
	abstract protected function password();
	/*virtual*/protected function options(){return array();}
	/*virtual*/protected function schema(){return 0;}

	public function connect(){
		$this->_pdo = new PDO($this->dsn(), $this->user(), $this->password(), $this->options());
	}
	public function connected(){
		return !is_null($this->_pdo);
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
        $stmt->execute($arguments);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt;
	}
	/**
	 * return's $row_num'th row from the result set
	 */
	public function result_row($name, $arguments=array(), $row_num=0){
		$rows = $this->proc($name, $arguments)->fetch(PDO::FETCH_NUM);
		return $rows[$row_num];
	}
}
?>
