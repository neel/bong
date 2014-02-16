<?php
/**
 * Decides which Type of ContentRouter should be used to handle this type of Content
 * 
 * @author Neel Basu
 */
class URLAnalyzer extends Singleton implements Decider{
	private $url;
	
	private $sync = 'sync'; 
	private $standalone = 'standalone'; 
	private $xml = 'xml';
	private $projectChar = '-'; 
	
	public function __construct(){
		$this->url = MemPool::instance()->get('bong.url.path');
	}
	/**
	 * Returns a Suitable ContentRouter Object based upon the URL Pattern
	 * The Caller needs to extract the engine from the Router through the appropiate Getter
	 * the Validate the Engine then Run the Engine
	 * $engine = $router->engine();
	 * $engine->check(); or $engine->validate();
	 * $engine->run();
	 */
	public function decide(){
		$projectName = null;
		// issue #31 https://github.com/neel/bong/issues/31
		$urlparts = strlen(trim($this->url, "/")) ? explode('/', trim($this->url, "/")) : array();//Slash The Url
		$reqUrlParts = explode('/', trim($_SERVER['REQUEST_URI'], "/"));
		$urlroot = '/'.implode('/', array_slice($reqUrlParts, 0, (count($reqUrlParts)-count($urlparts))) );
		MemPool::instance()->set("bong.url.root", $urlroot);

		/**
		 * URL with Default Project:
		 * controllername
		 * controllername/methodname
		 * controllerName.extension/methodName
		 * image.png
		 * The Extension could be anything like dbg, mc, static, sync etc..
		 * 
		 * URL with default / non default Project Name
		 * ~projectName/controllername
		 * ~projectName/controllername/methodname
		 * ~projectName/controllerName.extension/methodName
		 * ~projectName/image.png
		 */
		$projectExt = null;
		if(count($urlparts) && substr_count($urlparts[0], '~') == 1){
			$projectName = substr(array_shift($urlparts), 1);
			if(substr_count($projectName, '.') == 1){
				$parts = explode('.', $projectName);
				$projectName = $parts[0];
				$projectExt = $parts[1];
			}
		}elseif(count($urlparts) && substr_count($urlparts[0], '~') > 1){
			throw new MalformedUrlException();
		}else{
			$projectName = Fstab::instance()->defaultProjectName();
		}
		
		if(Fstab::instance()->projectExists($projectName)){
			$projectLocation = Fstab::instance()->projectLocation($projectName);
			if($projectLocation && is_dir($projectLocation)){
				if(is_readable($projectLocation)){
					MemPool::instance()->set("bong.project.current", $projectName);
					Runtime::setCurrentProject($projectName);
				}else{
					throw new ProjectDirNotReadableException($projectName.'');
				}
			}else{
				throw new ProjectDirNotFoundException($projectName.'');
			}
		}else{
			throw new ProjectNotFoundException($projectName.'');
		}

		$urlbase = '/'.implode('/', (array_slice($reqUrlParts, 0, (count($reqUrlParts)-count($urlparts)))));
		MemPool::instance()->set("bong.url.base", $urlbase);

		$urlExtracted = '/'.implode('/',$urlparts);
		//{ Start FSM Handling 
		/**
		 * Now if invoked /~project.fsm
		 * load the fsm display
		 */
		if($projectExt == 'fsm'){
			return RouterFactory::produce('FSMRouter');
		}
		//} end FSM Handling
		$patterns = array(
			'resource.local' => Conf::instance()->evaluate('urlpatterns.resource.local'),
			'resource.sys' => Conf::instance()->evaluate('urlpatterns.resource.sys'),
			'service.app' => Conf::instance()->evaluate('urlpatterns.service.app'),
			'service.spirit' => Conf::instance()->evaluate('urlpatterns.service.spirit')
		);
		$type = 'mvc';
		//var_dump($urlExtracted);
		foreach($patterns as $key => $pattern){
			//echo ">> {$urlExtracted} => $pattern\n";
			assert('!empty($pattern)'."/*$key => $pattern*/");
			if(preg_match($pattern, $urlExtracted, $m)){
				//echo ">>\tMatched!\n";
				$type = $key;
				break;
			}
		}
		
		MemPool::instance()->set("bong.router.pattern", $type);
		/* AbstractContentRouter*  */ $router = null;
		switch($type){
			case 'resource.local':
			case 'resource.sys':
				$router = RouterFactory::produce('ResourceRouter');
				break;
			case 'service.app':
				$router = RouterFactory::produce('AppServiceRouter');
				break;
			case 'service.spirit':
				$router = RouterFactory::produce('SpiritServiceRouter');
				break;
			case 'mvc':
				$router = RouterFactory::produce('MVCRouter');
				break;
		}
		/**
		 * $router = RouterFactory::produce($key);
		 * $router->route();
		 * -- in Router --
		 * AbstractContentEngine* $this->engine = $this->engine();
		 * -- in AbstractContentRouter::engine() --
		 * return EngineFactory::produce($key);
		 * -- Another Style --
		 * Factory::Router::produce()
		 * Factory::Engine::produce()
		 */
		$router->setProjectName($projectName);
		$router->buildNavigation($urlparts);
		$router->prepareEngine();
		return $router;
	}
}
?>
