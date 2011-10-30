<?php
class FSMEngine extends ContentEngine{
	protected function validate(){}
	public function executeLogic(){}
	public function run(){
		$fsm = \FSM\Engine::parse('site');
		$path = Path::instance()->evaluate('common').'/fsm.php';
		ob_start();
		require($path);
		$this->responseBuffer = ob_get_contents();
		ob_end_clean();
	}
}
EngineFactory::register("FSMEngine");
?>
