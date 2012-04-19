<?php
abstract class BongAppModel extends \Singleton{
	protected $_pdo = null;
	
	abstract protected function dsn();
	abstract protected function user();
	abstract protected function password();
	/*virtual*/public function autoconnect(){
		return true;
	}
	/*virtual*/protected function options(){return array();}
	/*virtual*/protected function schema(){return 0;}

	public function connect(){
		$this->_pdo = new PDO($this->dsn(), $this->user(), $this->password(), $this->options());
		$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
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
        $status = $stmt->execute($arguments);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($status)
        	return $stmt;
        return $status;
	}
	/**
	 * return's $row_num'th row from the result set
	 */
	public function result_row($name, $arguments=array(), $row_num=0){
		$rows = $this->proc($name, $arguments)->fetch(PDO::FETCH_NUM);
		return $rows[$row_num];
	}
	public static function hstore($assoc=array()){
		$rets = array();
		foreach($assoc as $key => $val){
			if(is_string($key)){
				$first_char = $key[0];
				if(!is_numeric($first_char)){
					$rets[] = "{$key}=>\"{$val}\"";
				}
			}
		}
		return implode(',', $rets);
	}
	public function query(){
		return new QueryBuilder($this->_pdo);
	}
}
?>
