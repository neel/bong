<?php
$conf_path = \Path::instance()->currentProject('etc.conf.@database.xml');
if(file_exists($conf_path)){
	\DB\DatabaseConfig::instance();
}
?>