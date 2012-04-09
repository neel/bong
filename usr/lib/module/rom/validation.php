<?php
/**
 * @example
 * $uname = new Field('uname', $_POST['uname']);
 * $uname->setRequiredFlag(true);
 * $uname->addRuleSet(new RuleSet(Validation::String, 'Illegal UserName given must have at least one non digit Character'));
 * $uname->addRuleSet(new RuleSet(Validation::MaxLength, 'UserName must be less than 5 characters', 5));
 * $email = new Field('email', $_POST['email']);
 * $email->addRuleset(new RuleSet(Calidation::Email, 'Invalid Email address given'));
 * $form = new Form;
 * $form->addField($uname);
 * $form->addField($email);
 * $formValidation = $form->validate();
 * //$formValidation now Contains the report about the form validation. which is even more easier than handling boolean success Values
 * //see the Data Structure of FormValidation for more Info. the method names describes themselves 
 * @author neel
 */
namespace Validation;
class RuleSet{
	private $_rule = null;
	private $_arg = null;
	private $_msg = null;
	
	/**
	 * Create a RuleSet By Giving Rule as (Validation::*) Validation class holds all the rules as const
	 * @param String $rule
	 * @param String $msg
	 * @param String $arg
	 */
	public function __construct($rule, $msg, $arg=null){
		$this->_rule = $rule;
		$this->_arg = $arg;
		$this->_msg = $msg;
	}
	public function rule(){
		return $this->_rule;
	}
	public function msg(){
		return $this->_msg;
	}
	public function setRuleArgument($arg){
		$this->_arg = $arg;
	}
	public function arg(){
		return $this->_arg;
	}
}
class Field{
	private $_form = null;

	private $_required = false;
	/**
	 * Array of RuleSet applied to the form field
	 * @var RuleSet[]
	 */
	private $_rules = array();
	/**
	 * @var String
	 * Name of the Form Field
	 */
	private $_name = null;
	private $_default = null;
	private $_value = null;
	
	public function __construct($name, $value=null){
		$this->_name = $name;
		$this->_value = $value;
	}
	public function setForm($form){
		$this->_form = $form;
	}
	public function addRuleSet($ruleSet){
		$this->_rules[] = $ruleSet;
	}
	public function setValue($value){
		$this->_value = $value;
	}
	public function setDefaultValue($value){
		$this->_default = $value;
	}
	public function value(){
		if($this->_value)
			return $this->_value;
		else
			return ($this->_default ? $this->_default : null);
	}
	public function setRequiredFlag($flag=true){
		$this->_required = $flag;
	}
	public function required(){
		return $this->_required;
	}
	public function name(){
		return $this->_name;
	}
	/**
	 * returns a FieldValidation report regarding the field
	 * @return Validation\FieldValidation
	 */
	public function validate(){
		$report = new FieldValidation;
		foreach($this->_rules as $i => $rule){
			$report->name = $this->_name;
			$report->value = $this->_value;
			$status = Validation::validate($this->_form, $this->_value, $rule->rule(), $rule->arg());
			//echo '<pre>'.$this->_name.' > '.$this->_value.' > '.$i.'/'.count($this->_rules).' : '.$rule->rule()." ".$status."\n</pre>";
			if(!$status){
				$report->isValid = false;
				$report->error = $rule->msg();
				$report->unMatchedIndex = $i;
				$report->unMatchedCriteriaName = $rule->rule();
				return $report;
			}
		}
		$report->isValid = true;
		return $report;
	}
	/**
	 * returns boolean result
	 */
	public function isValid(){
		return $this->validate()->isValid;
	}
	public function isEmpty(){
		return empty($this->_value);
	}
}
class Form{
	private $_fields = array();
	
	public function __construct(){
		
	}
	/**
	 * 
	 * @param Field $field
	 */
	public function addField($field){
		$field->setForm($this);
		$this->_fields[] = $field;
	}
	public function fields(){
		return $this->_fields;
	}
	public function field($name){
		/*
		if(isset($this->_fields[$name])){
			return $this->_fields[$name];
		}
		*/
		foreach ($this->_fields as $i => $f) {
			if($f->name() == $name){
				return $f;
			}
		}
		return null;
	}
	/**
	 * validates all Filds and returns aForm Validation Report
	 * @return Validation\FormValidation
	 */
	public function validate(){
		$reports = array();
		foreach($this->_fields as $field){
			$reports[] = $field->validate();
		}
		$formValidation = new FormValidation($reports);
		return $formValidation;
	}
}
class FieldValidation{
	public $name = null;
	public $value = null;
	public $isValid = false;
	public $error = null;
	public $unMatchedIndex = null;
	public $unMatchedCriteriaName = null;

	public function isEmpty(){
		return empty($this->value);
	}
}
class FormValidation{
	private $_fieldValidations = array();
	
	public function __construct($fieldValidation){
		$this->_fieldValidations = $fieldValidation;
	}
	/**
	 * returns an array of Validation FieldValidation reports of invalid fields only
	 */
	public function inValids(){
		$ret = array();
		foreach($this->_fieldValidations as $f){
			if(!$f->isValid){
				$ret[] = $f;
			}
		}
		return $ret;
	}
	public function isValid(){
		return count($this->inValids()) == 0;
	}
	public function firstInvalid(){
		foreach($this->_fieldValidations as $f){
			if(!$f->isValid){
				return $f;
			}
		}
	}
	public function valids(){
		$ret = array();
		foreach($this->_fieldValidations as $f){
			if($f->isValid){
				$ret[] = $f;
			}
		}
		return $ret;		
	}
	public function fields(){
		return $this->_fieldValidations;
	}
	/**
	 * returns FieldValidationReport of the given Form Field
	 * @param String $name
	 * @return Validation\FieldValidation
	 */
	public function field($name){
		foreach($this->_fieldValidations as $f){
			if($f->name == $name){
				return $f;
			}
		}
	}
	/**
	 * returns an array of strings Containing Error Messages of only Invalid Fields
	 */
	public function errors(){
		$errors = array();
		foreach($this->inValids() as $report){
			$errors[] = $report->error;
		}
		return $errors;
	}
}
class Validation{
	const String   = 'Type::String';
	const Int      = 'Type::Int';
	const Float    = 'Type::Float';
	const Real     = 'Type::Real';
	const Presence = 'Type::Presence';
	
	const Length    = 'String::Length';
	const MaxLength = 'String::Max';
	const MinLength = 'String::Min';
	const Repeat    = 'String::Repeat';//Retype Password or re-enter Email Field
	const Equals    = 'String::Equals';
	const AlphaSpace = 'String::AlphaSpace';
	
	const Max = 'Real::Max';
	const Min = 'Real::Min';
	
	const Regex = 'String::Pattern';
	
	const Url = 'String::Pattern::Url';
	const Email = 'String::Pattern::Email';
	const Ip = 'String::Pattern::Email';
	
	const Callback = 'Callback';
	
	public static function validate($form, $value, $rule, $arg=null){
		//echo $rule.': '.$value."\n";
		switch($rule){
			case Validation::String:
				return preg_match('~^\D$~', $value);
				break;
			case Validation::AlphaSpace:
				return preg_match('~^[a-zA-Z\ ]+$~', $value);
				break;
			case Validation::Int:
				return preg_match('~^\d+$~', $value);
				break;
			case Validation::Float:
				return preg_match('~^\d+\.\d+$~', $value);
				break;
			case Validation::Real:
				return preg_match('~^\d+(?:\.\d+)?$~', $value);
				break;
			case Validation::Length:
				return strlen($value) == $arg;
				break;
			case Validation::MaxLength:
				return strlen($value) <= $arg;
				break;
			case Validation::MinLength:
				return strlen($value) >= $arg;
				break;
			case Validation::Presence:
				return self::validate($form, $value, self::MinLength, 1);
				break;
			case Validation::Max:
				return self::validate($form, $value, self::Int) && $value <= $arg;
				break;
			case Validation::Min:
				return self::validate($form, $value, self::Int) && $value >= $arg;
				break;
			case Validation::Regex:
				return preg_match($arg, $value);
				break;
			case Validation::Url:
				return preg_match('~(?:\w+)\:\/\/[\w\d\_\-]+(?:\.[\w\d\_\-]+)*~', $value);
				break;
			case Validation::Email:
				return preg_match('~[\w\d\_\-]+\@[\w\d\_\-]+(?:\.[\w\d\_\-]+)~', $value);
				break;
			case Validation::Ip:
				return preg_match('~\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}~', $value);
				break;
			case Validation::Repeat:
				return $form->field($arg) && $form->field($arg)->value() == $value;
				break;
			case Validation::Equals:
				return $value == $arg;
				break;
			case Validation::Callback:
				return $arg($value);
				break;
			default:
				throw new \RuntimeException("Invalid Validation RuleName ".$rule);
		}
	}
	public static function parse($conf, $inputs){
		$confPath = \Path::instance()->currentProject('etc.conf.@'.$conf.'.conf.xml');
		if(!file_exists($confPath)){
			return false;
		}
		$dom = new \DOMDocument("1.0", "utf-8");
		$dom->load($confPath);
		$xpath = new \DOMXPath($dom);
		$xpath->registerNamespace('bong', 'http://lab.zigmoyd.net/xmlns/bong');
		$fieldNodes = $xpath->query("//bong:form/bong:field");
		$form = new Form;
		foreach($fieldNodes as $fieldNode){
			$field = null;
			$name = self::__attrValue($fieldNode, "name");
			$default = self::__attrValue($fieldNode, "default");
			if($name){
				$field = new Field($name);
				$required = self::__attrValue($fieldNode, "required", 'false');
				if($required == 'true'){
					$field->setRequiredFlag(true);
				}
				if(isset($inputs[$name])){
					$field->setValue(strlen($inputs[$name]) > 0 ? $inputs[$name] : ($default ? $default : '') );
				}
				$ruleNodes = $xpath->query("bong:criteria", $fieldNode);
				foreach($ruleNodes as $ruleNode){
					$ruleName = self::__attrValue($ruleNode, 'name');
					if($ruleName){
						$arg = self::__attrValue($ruleNode, 'value');
						$error = self::__attrValue($ruleNode, 'msg');
						$rule = new RuleSet($ruleName, $error, $arg);
						$field->addRuleSet($rule);
					}
				}
			}
			$form->addField($field);
		}
		return $form;
	}
	private static function __attrValue($node, $attrName, $defaultValue=null){
		$attrNode = $node->attributes->getNamedItem($attrName);
		if($attrNode){
			return $attrNode->nodeValue;
		}
		return $defaultValue;
	}
}
?>
