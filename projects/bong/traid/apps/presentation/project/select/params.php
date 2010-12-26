<?php
$params->title = "Page Title";
$params->js = array(
	Resource::js('select'),
	Resource::js('editor'),
	'/CodeMirror/js/codemirror.js'
);
$params->css = array(
	Resource::css('style'),
	Resource::css('select'),
	Resource::css('editor')
);
?>