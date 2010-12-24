<?php
namespace Structs\Admin;
final class SpiritView extends View{
	public function filePath(){
		return \Path::instance()->evaluate(':'.$this->method()->controller()->project()->name().'.*'.$this->method()->controller()->name().'.view.-'.$this->method()->name().'.@'.$this->name().'.php');
	}
}
?>
