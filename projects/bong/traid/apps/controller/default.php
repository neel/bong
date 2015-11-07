<?php
class DefaultController extends BongAppController{
	public function main(){
		http::redirect("project/ls");
	}
}
?>
