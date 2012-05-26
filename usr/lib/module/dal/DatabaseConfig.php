<?php
namespace DB;
final class DatabaseConfig extends \ConfigurationAdapter{
	private $collection = null;

	public function __construct(){
		parent::__construct(\Path::instance()->currentProject('etc.conf.@database.xml'));
		$this->collection = new \DB\Config();
		$config = $this->dom;

		$connection_nodes = $config->getElementsByTagNameNS('http://lab.zigmoyd.net/xmlns/bong', 'connection');
		$connections = array();
		foreach($connection_nodes as $connection_node){
			/*{ Connection attributes*/
			$name = $connection_node->getAttribute('name');
			$dsn  = $connection_node->getAttribute('dsn');
			$user = $connection_node->getAttribute('user');
			$pass = $connection_node->getAttribute('pass');
			$connection = new \DB\Connection($name, $dsn, $user, $pass);
			/*}*/
			/*{ PDO Params*/
			$params = array();
			$param_nodes = $connection_node->getElementsByTagNameNS('http://lab.zigmoyd.net/xmlns/bong', 'param');
			foreach($param_nodes as $param_node){
				$name  = $param_node->getAttribute('name');
				$value = $param_node->getAttribute('value');
				$param = new \DB\ConnectionParam($name, $value);
				$connection->addParam($param);
			}
			/*}*/
			$this->collection->addConnection($connection);
		}

		$model_nodes = $config->getElementsByTagNameNS('http://lab.zigmoyd.net/xmlns/bong', 'model');
		$model_node = $model_nodes->item(0);
		$default_connection = $model_node->getAttribute('default');
		$autoconnect = constant($model_node->getAttribute('autoconnect'));
		$this->collection->setDefault($default_connection);
		$this->collection->setAutoconnect($autoconnect);
		//echo ">> DatabaseConfig::__construct()\n";
	}
	public function conn($name=null){
		return $this->collection->conn($name);
	}
	/**
	 * use \DB\Config::connection() to get the default connection
	 * use \DB\Config::connection('con_name') to get other connections
	 */
	public static function connection($name=null){
		return self::instance()->conn($name);
	}
}
?>