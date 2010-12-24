<?php
//namespace Bong\Structure
class Project{
	public $name;
	public $location;
	public $isDefault;
	public $path;
	
	public function exists(){
		return (file_exists($this->path) && is_dir($this->path));
	}
}
//namespace Bong\Util;
final class Fstab extends ConfigurationAdapter{
	public function __construct(){
		parent::__construct(rtrim(getcwd(), "/")."/etc/fstab.xml");
	}
	private function __projectFromNode($projectNode){
		$project = new /*Bong\Structure\*/Project();
		$project->name = $projectNode->attributes->getNamedItem("name")->nodeValue;
		$project->location = $projectNode->attributes->getNamedItem("location")->nodeValue;
		$project->path = Path::instance()->evaluate(':'.$projectNode->attributes->getNamedItem("name")->nodeValue);
		$project->isDefault = $projectNode->attributes->getNamedItem("default")->nodeValue == 'true';
		return $project;
	}
	private function __validateDefault(){
		return $this->xpath->evaluate("count(//bong:reposetory/bong:project[@default='true'])") == 1;
	}
	public function defaultProjectName(){
		if($this->__validateDefault()){
			return $this->xpath->evaluate("string(//bong:reposetory/bong:project[@default='true']/@name)");
		}
	}
	/**
	 * return the project Object of the default Project
	 * @return Project
	 */
	public function defaultProject(){
		if($this->__validateDefault()){
			$projectNode = $this->xpath->query("//bong:reposetory/bong:project[@default='true']")->item(0);
			return $this->__projectFromNode($projectNode);
		}		
	}
	public function projectNames(){
		$projectNodes = $this->xpath->query("//bong:reposetory/bong:project/@name");
		$projects = array();
		foreach($projectNodes as $projectNode){
			array_push($projects, $projectNode->nodeValue);
		}
		return $projects;
	}
	/**
	 * return an array of project Objects
	 */
	public function projects(){
		$projectNodes = $this->xpath->query("//bong:reposetory/bong:project");
		$projects = array();
		foreach($projectNodes as $projectNode){
			$project = $this->__projectFromNode($projectNode);
			array_push($projects, $project);
		}
		return $projects;
	}
	public function projectExists($projectName){
		return ($this->xpath->evaluate("count(//bong:reposetory/bong:project[@name='$projectName'])")==1);
	}
	/**
	 * returns the Project's Directory Name Only
	 * @param unknown_type $projectName
	 */
	public function projectDirectory($projectName){
		if($this->projectExists($projectName)){
			return $this->xpath->evaluate("string(//bong:reposetory/bong:project[@name='$projectName']/@location)");
		}
		return false;
	}
	/**
	 * Returns the Project's Location Absolute Path
	 * @param unknown_type $projectName
	 */
	public function projectLocation($projectName){
		if($this->projectExists($projectName)){
			return Path::instance()->evaluate(":$projectName");
		}
		return false;
	}
	/**
	 * return a Project Object given a project name
	 * @param string $projectName
	 * @return Project
	 */
	public function project($projectName){
		if($this->projectExists($projectName)){
			$projectNode = $this->xpath->query("//bong:reposetory/bong:project[@name='$projectName']")->item(0);
			return $this->__projectFromNode($projectNode);
		}
		return false;
	}
	/**
	 * @return Fstab
	 */
	public static function instance(){
		return parent::instance();
	}
}
?>