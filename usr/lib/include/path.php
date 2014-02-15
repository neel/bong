<?php
//namespace Bong\Util;
final class Path extends ConfigurationAdapter implements XPathConfig{
	public function __construct(){
		parent::__construct(rtrim(getcwd(), "/")."/etc/path.xml");
	}
	private function directiveToXPath($path, &$projectDir="", &$controllerName="", &$methodName="", &$spiritName="", &$terminal=""){
		$projectName = "";
		$branches = explode('.', $path);
		$nullPos = -1;
		$i = 0;
		/**
		 * +controllerName
		 * -methodName
		 * *spiritName
		 * @arbitraryFileName
		 */
		$branches = array_map(function($value) use(&$projectName, &$controllerName, &$methodName, &$spiritName, &$terminal, &$i, &$nullPos){
			++$i;
			if($nullPos > -1)return $value;
			if(preg_match("~\:(\w+)~", $value, $m) > 0){
				$projectName = $m[1];
				return "bong:path[@name='project']";
			}elseif(preg_match("~^\+([\&\w]+)~", $value, $m) > 0){
				$controllerName = $m[1][0] == '&' ? MemPool::instance()->get('bong.mvc.controller') : $m[1];
				return "bong:path[@name='controller']";
			}elseif(preg_match("~^\-([\&\w]+)~", $value, $m) > 0){
				$methodName = $m[1][0] == '&' ? MemPool::instance()->get('bong.mvc.method') : $m[1];
				return "bong:path[@name='method']";
			}elseif(preg_match("~^\*([\&\w]+)~", $value, $m) > 0){
				$spiritName = $m[1];
				return "bong:path[@name='spirit']";
			}elseif(preg_match("~^\@([\&\w\S]+)~", $value, $m) > 0){
				if($m[1][0] == '&'){
					$terminal = in_array($m[1][1], array('c', 'C')) ? 
									MemPool::instance()->get('bong.mvc.controller') : 
									((in_array($m[1][1], array('m', 'M')) ? 
										MemPool::instance()->get('bong.mvc.method') : 
										substr($m[1], 1)));
				}else{
					$terminal = $m[1];
				}
				$nullPos = $i-1;
				return null;
			}
			return "bong:path[@name='$value']";
		}, $branches);
		if($nullPos > -1){
			$termExprs = array();
			$len = count($branches);
			for($i=$nullPos;$i<$len;++$i){
				$termExprs[] = array_pop($branches);
			}
			$terminal .= implode('.', array_reverse($termExprs));
		}
		if($branches[count($branches)-1] == "bong:path[@name='project']"){
			$branches[] = "bong:path[@name='self']";
		}
		$projectDir = Fstab::instance()->projectDirectory($projectName);
		return "//bong:reposetory/".implode('/', $branches);
	}
	/**
	 * FIXME return's null When $path is invalid. throw an exception
	 * @param String $path
	 * @return string
	 */
	public function evaluate($path){
		$projectDir = "";
		$controllerName = "";
		$methodName = "";
		$spiritName = "";
		$terminal = "";
		
		$xpath = $this->directiveToXPath($path, $projectDir, $controllerName, $methodName, $spiritName, $terminal);
		$nodes = $this->xpath->query($xpath);
		$pathElems = array();
		if($nodes->length > 0){
			$node = $nodes->item(0);
			while($node->nodeName == 'bong:path'){
				array_push($pathElems, $node->attributes->getNamedItem("location")->nodeValue);
				$node = $node->parentNode;
			}
		}
		$pathElems = array_reverse($pathElems);
		$retPath = implode("/", $pathElems);
		if(!empty($projectDir)){
			$retPath = (str_replace('$projectDir', $projectDir, $retPath));
		}
		if(!empty($controllerName)){
			$retPath = (str_replace('$controller', $controllerName, $retPath));
		}
		if(!empty($methodName)){
			$retPath = (str_replace('$method', $methodName, $retPath));
		}
		if(!empty($spiritName)){
			$retPath = (str_replace('$spirit', $spiritName, $retPath));
		}
		if(!empty($retPath) && !empty($terminal)){
			$retPath .= '/'.$terminal;
		}

		if($retPath)
			return Path::toAbsolutePath($retPath);
		else
			return null;
	}
	
	/**
	 * evaluate path in Current Project scope
	 * @internal concats current project Name with : and then appends $path to it. Then treat it as usual Path 
	 * @param string $path
	 * @return string
	 */
	public function currentProject($path){
		if(!Runtime::currentProject())/*Project not Set Yet Who is Calling ?*/
			debug_print_backtrace();
		return $this->evaluate(':'.Runtime::currentProject()->name.'.'.$path);
	}
	/**
	 * Just prepends the Bong Installation Directory Full Path to It
	 * @param string $relativePath
	 */
	static public function toAbsolutePath($relativePath){
		return rtrim(MemPool::instance()->get("bong.root"), "/").'/'.ltrim($relativePath, "/");
	}
	/**
	 * @return Path
	 */
	public static function instance(){
		return parent::instance();
	}
}
?>
