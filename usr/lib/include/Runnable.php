<?php
interface Runnable{
	public function run();
}

interface EmbeddedRunnable{
	public function run($subject, $action='', $args=array());
}
?>