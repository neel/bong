<?php
$params->title = "Page Title";
/*
See Issue #7
https://github.com/neel/bong/issues/7
*/
$params->js = array(SysResource::js("jquery"), SysResource::js("dump"), SysResource::js("bong.bootstrap"));
$params->css = array(SysResource::css('bong'), SysResource::css("dump"));
?>