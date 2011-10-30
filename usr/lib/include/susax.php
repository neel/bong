<?php
namespace SuSAX;
abstract class AbstractNode{}
abstract class Node extends AbstractNode{
	protected $_namespace = null;
	protected $_name = null;
	protected $_children = array();
	
	public function __construct($name, $ns=null){
		$this->_name = $name;
		$this->_namespace = $ns;
	}
	public function name(){
		return $this->_name;
	}
	public function ns(){
		return $this->_namespace;
	}
	public function children(){
		return $this->_children;
	}
}
class Tag extends Node{
	private $_arguments = array();
	
	public function __construct($name, $args, $ns=null){
		parent::__construct($name);
		$this->_arguments = $args;
		$this->_namespace = $ns;
	}
	public function attrByName($name){
		foreach($this->_arguments as $arg){
			if($arg->name() == $name){
				return $arg;
			}
		}
		return null;
	}
}
class Argument extends Node{
	public function __construct($name, $value=null){
		parent::__construct($name);
		if($value){
			$this->_children[] = new TextNode($value);
		}
	}
	public function setValue($value){
		$this->_children[] = (new TextNode($value));
	}
	public function value(){
		return count($this->_children) > 0 ? $this->_children[0] : null;
	}
}
class TextNode extends AbstractNode{
	private $_contents = null;
	
	public function __construct($text){
		$this->_contents = $text;
	}
	public function value(){
		return $this->_contents;
	}
	public function ToString(){
		return $this->value();
	}
}
interface ParserI{
	public function open($tag);
	public function close($tag);
	public function standalone($tag);
}
abstract class AbstractParser implements ParserI{
	private $_parseTree = array();
	private $_indentation = 0;
	
	public function push($tag){
		$this->_parseTree[] = $tag;
		++$this->_indentation;
	}
	public function pop($tag){
		$c = 1;
		$l = count($this->_parseTree)-1;
		for($i=$l;$i>$l;--$i){
			if($this->_parseTree[$i]->name() == $tag->name()){
				++$c;
				break;
			}
		}
		$this->_indentation = $c;
		while($c > 0){
			array_pop($this->_parseTree);
			--$c;
		}
	}
	public function indentation(){
		return $this->_indentation;
	}
	public function parseTree(){
		return $this->_parseTree;
	}
	public function parent(){
		if(count($this->_parseTree) > 0){
			return $this->_parseTree[count($this->_parseTree)-1];
		}
	}
}
class Parser{
	private $_nsFocus = null;
	private $_text = null;
	private $_handle;
	
	public function __construct($parserHandle){
		$this->_handle = $parserHandle;
	}
	public function setNsFocus($nsName){
		$this->_nsFocus = $nsName;
	}
	public function setText($text){
		$this->_text = $text;
	}
	public function parse(){
		$text = $this->_text;
		$handle = $this->_handle;
		$nsFocus = $this->_nsFocus;
		return preg_replace_callback('%<[/]?(\w+):(\w+)\s*([\w\d="\'\s]*)?/?>%s', function($m) use($handle, $text, $nsFocus){
			$nsName = $m[1];
			if($nsFocus && $nsName != $nsFocus){
				return $m[0];
			}
			$tagName = $m[2];
			$argNodes = array();
			if(count($m) > 3){
				$args = preg_split('%\s+%', $m[3]);
				foreach($args as &$arg){
					$argParts = preg_split('%\s*\=\s*%', $arg);
					$argNode = new \SuSAX\Argument($argParts[0]);
					if(count($argParts) > 1){
						$argNode->setValue(trim($argParts[1], ($argParts[1][0]== "'" ? "'" : '"')));
					}
					$argNodes[] = $argNode;
				}
			}
			$tagNode = new \SuSAX\Tag($tagName, $argNodes, $nsName);
			$return = null;
			if($m[0][1] == '/'){
				$return = $handle->close($tagNode);//Close
				$handle->pop($tagNode);
			}elseif($m[0][strlen($m[0])-2] == '/'){
				$return = $handle->standalone($tagNode);//Standalone
			}else{
				$return = $handle->open($tagNode);//Open
				$handle->push($tagNode);
			}
			if(!$return){
				return $m[0];
			}
			return $return;
		}, $text);
	}
}
?>
<?php
/*
error_reporting(255);
ini_set('display_errors','On');
header('Content-Type: text/plain');
class MyParser extends \SuSAX\AbstractParser{
	public function open($tag){
		echo ">> open ".$tag->ns().':'.$tag->name().'/'.$this->indentation().($this->parent() ? $this->parent()->name() : '')."\n";
		return "OO";
	}
	public function close($tag){
		echo ">> close ".$tag->ns().':'.$tag->name().'/'.$this->indentation()."\n";
	}
	public function standalone($tag){
		echo ">> standalone ".$tag->ns().':'.$tag->name().'/'.$this->indentation()."\n";
	}
}
$text = <<<TEXT
Hallo <b>W<html:i>o</html:i>rld</b>
<cnt:tag x="2" y="1">
<cnt:taga x="2" y="1"></cnt:taga>
</cnt:tag>
I am Here
TEXT;
$parser = new \SuSAX\Parser(new MyParser);
$parser->setNsFocus('cnt');
$parser->setText($text);
$text_ = $parser->parse();
var_dump($text_);
*/
?>
