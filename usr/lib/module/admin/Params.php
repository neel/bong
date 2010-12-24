<?php
namespace Structs\Admin;
class Params extends CommonComponent{
	public function generate(){
		$data = <<<'TEMPLATESTR'
<?php
$params->title = "Page Title";
$params->js = array();
$params->css = array();
?>
TEMPLATESTR;
		return parent::generate($data);
	}
}
?>
