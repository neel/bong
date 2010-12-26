<?php
function fseekline($resource, $lineNumber){
	fseek($resource, 0);
	$c = 0;
	while($c < $lineNumber){
		fgets($resource);
		++$c;
	}
}
function _fstrpos($resource, $str, $direction=1){
	$pos = ftell($resource);
	$buff = fgets($resource);
	fseek($resource, $pos);
	return $pos+($direction==1 ? strpos($buff, $str) : strrpos($buff, $str));
}
function fstrpos($resource, $str){
	return _fstrpos($resource, $str, 1);
}
function fstrrpos($resource, $str){
	return _fstrpos($resource, $str, 0);
}
?>
