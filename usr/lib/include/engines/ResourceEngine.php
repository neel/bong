<?php
class ResourceEngine extends ContentEngine{
	/**
	 * Must have a ProjectName
	 * Must Have a contentName
	 */
	protected function validate(){
		return ($this->projectName && $this->navigation->contentName);
	}
	public function executeLogic(){
		
	}
	public function run(){
		if(Fstab::instance()->projectExists($this->projectName)){
			$projectDir = Path::instance()->evaluate(':'.$this->projectName);
			if(is_dir($projectDir)){
				$evPath = $this->navigation->systemResource ? '' : ":{$this->projectName}.";
				$evPath .= "share.";
				switch($this->navigation->resourceType){
					case 'img':
						$evPath .= 'image';
						break;
					case 'css':
						$evPath .= 'css';
						break;
					case 'js':
						$evPath .= 'javascript';
						break;
					case 'xslt':
						$evPath .= 'xslt';
						break;
					case 'scrap':
						$evPath .= 'scrap';
						break;
					default:
				}
				$evPath .= ".@{$this->navigation->estimatedContentName}";
				$resourcePath = Path::instance()->evaluate($evPath);
				if(is_file($resourcePath)){
					$mime = mime_content_type($resourcePath);
					switch($this->navigation->resourceType){
						case 'img':
							http::contentType($mime);
							break;
						case 'css':
							http::contentType('text/css');
							break;
						case 'js':
							http::contentType('text/javascript');
							break;
						case 'xslt':
							http::contentType('text/xml+xslt');
							break;
						case 'scrap':
							http::contentType('text/plain');
							break;
						default:
					}
					switch($this->navigation->resourceType){
						case 'img':
							$this->responseBuffer = file_get_contents($resourcePath);
							break;
						default:
							{
								ob_start();
								require($resourcePath);
								$this->responseBuffer = ob_get_contents();
								ob_end_clean();
							}
					}
					
				}else{
					throw new FileNotFoundException($resourcePath ? $resourcePath : 'null <Resource Path Could not be Resolved> ');
				}
			}else{
				throw new ProjectDirNotFoundException($this->projectName);
			}
		}else{
			throw new ProjectNotFoundException($this->projectName);
		}
	}
}
EngineFactory::register('ResourceEngine');
?>
