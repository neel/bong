<?php
$params->title = "Page Title";
$params->js = array(
	Resource::js('controller'),
	Resource::js('sspirit'),
	Resource::js('editor'),
	'/CodeMirror/js/codemirror.js'
);
$params->css = array(
	Resource::css('sspirit'),
	Resource::css('style'),
	Resource::css('controller'),
	Resource::css('editor')
);
?>