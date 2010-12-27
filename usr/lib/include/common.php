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
function recurse_copy($src,$dst){
    $dir = @opendir($src);
    if(!$dir){
    	return false;
    }
    if(@mkdir($dst, 0777)){
		while(false !== ($file = readdir($dir))){ 
			if(($file != '.' ) && ( $file != '..' )){ 
				if(is_dir($src . '/' . $file)){ 
					recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				}else{ 
					if(!@copy($src . '/' . $file,$dst . '/' . $file)){
						return false;
					}
				}
			} 
		}
	}else{
		return false;
	}
    closedir($dir);
    return true;
}
?>
