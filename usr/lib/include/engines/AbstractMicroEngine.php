<?php
abstract class AbstractMicroEngine extends AbstractContentEngine{
	abstract public function executeLogic($subject, $action, $args);
}
?>