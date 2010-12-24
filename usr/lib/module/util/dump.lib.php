<?php
class Dump{
	public static function css(){
		$p = pathinfo(__FILE__);
		include($p['dirname']."/wrapper.css");
	}
	public static function js(){
		$p = pathinfo(__FILE__);
		include($p['dirname']."/wrapper.js");
	}
	public static function r($v, $return = false){
		$search = array(
									"~\[(\d+)\]\s*\=\>\s*([Aa]rray)[\r\n]\s+\(~",
									"~\[(\d+)\]\s*\=\>\s*([\\\\\w]+ [Oo]bject)[\r\n]\s+\(~",
									"~\[(\d+)\]\s*\=\>([^\r\n]*)[\r\n]\s+\(~",
									"~\[(\d+)\]\s*\=\>([^\r\n]*)[\r\n]\s+\*RECURSION\*[\r\n]~",
									"~\[(\d+)\]\s*\=\>([^\r\n]*)[\r\n]~",
									
									"~\[(.+private)\]\s*\=\>\s*([Aa]rray)[\r\n]\s+\(~",
									"~\[(.+private)\]\s*\=\>\s*([\\\\\w]+ [Oo]bject)[\r\n]\s+\(~",
									"~\[(.+private)\]\s*\=\>([^\r\n]*)[\r\n]\s+\(~",
									"~\[(.+private)\]\s*\=\>([^\r\n]*)[\r\n]\s+\*RECURSION\*[\r\n]~",
									"~\[(.+private)\]\s*\=\>([^\r\n]*)[\r\n]~",
									
									"~\[(.+protected)\]\s*\=\>\s*([Aa]rray)[\r\n]\s+\(~",
									"~\[(.+protected)\]\s*\=\>\s*([\\\\\w]+ [Oo]bject)[\r\n]\s+\(~",
									"~\[(.+protected)\]\s*\=\>([^\r\n]*)[\r\n]\s+\(~",
									"~\[(.+protected)\]\s*\=\>([^\r\n]*)[\r\n]\s+\*RECURSION\*[\r\n]~",
									"~\[(.+protected)\]\s*\=\>([^\r\n]*)[\r\n]~",
									
									"~\[(.+)\]\s*\=\>\s*([Aa]rray)[\r\n]\s+\(~",
									"~\[(.+)\]\s*\=\>\s*([\\\\\w]+ [Oo]bject)[\r\n]\s+\(~",
									"~\[(.+)\]\s*\=\>([^\r\n]*)[\r\n]\s+\(~",
									"~\[(.+)\]\s*\=\>([^\r\n]*)[\r\n]\s+\*RECURSION\*[\r\n]~",
									"~\[(.+)\]\s*\=\>([^\r\n]*)[\r\n]~",
									
									"~\)~",
									"~\*RECURSION\*~",
									"~([\w\s\\\]+)\s*[\r\n]+\s*\(~",
									
									"~[\r\n\t]+~"/*,
									"~(.+)~"*/
								);
		$replacement = array(
											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='index [$1]' class='key lite'>[$1]</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='index [$1]' class='key lite'>[$1]</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='line'> <span title='index [$1]' class='key lite'>[$1]</span> => <span class='value'>$2</span> </a> \n",
											"<li class='node'> <a class='line'> <span title='index [$1]' class='key lite'>[$1]</span> => <span class='recursion'>recursive</span> </a> </li>",
											"<li class='node'> <a class='line'> <span title='index [$1]' class='key lite'>[$1]</span> => <span class='value'>$2</span> </a> </li>\n",

											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='$1' class='key private'>$1</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='$1' class='key private'>$1</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='line'> <span title='$1' class='key private'>$1</span> => <span class='value'>$2</span> </a> \n",
											"<li class='node'> <a class='line'> <span title='$1' class='key private'>$1</span> => <span class='recursion'>recursive</span> </a> </li>",
											"<li class='node'> <a class='line'> <span title='$1' class='key private'>$1</span> => <span class='value'>$2</span> </a> </li>\n",
											
											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='$1' class='key protected'>$1</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='$1' class='key protected'>$1</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='line'> <span title='$1' class='key protected'>$1</span> => <span class='value'>$2</span> </a> \n",
											"<li class='node'> <a class='line'> <span title='$1' class='key protected'>$1</span> => <span class='recursion'>recursive</span> </a> </li>",
											"<li class='node'> <a class='line'> <span title='$1' class='key protected'>$1</span> => <span class='value'>$2</span> </a> </li>\n",

											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='$1' class='key'>$1</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='fold'>+</a> <a class='line'> <span title='$1' class='key'>$1</span> => <span class='value'>$2</span> <span class='elem'></span> </a> \n",
											"<ul class='node'> <a class='line'> <span title='$1' class='key'>$1</span> => <span class='value'>$2</span> </a> \n",
											"<li class='node'> <a class='line'> <span title='$1' class='key'>$1</span> => <span class='recursion'>recursive</span> </a> </li>",
											"<li class='node'> <a class='line'> <span title='$1' class='key'>$1</span> => <span class='value'>$2</span> </a> </li>\n",
											
											"</ul>",
											"<li class='node'> <a class='line'> <span title='recursive' class='recursion'>recursive</span> </a> </li>",
											"<ul class='node'> <a class='line'> <span class='value'>$1</span> </a> \n",
											
											""/*,
											"<pre>$1</pre>"*/
										);
		$res = "<div class='dumpWindow'><div class='dumpWindowTitle'><span class='dumpWindowControl'><a class='dumpWindowFloat'>^</a><a class='dumpWindowMax'>></a></span><span class='dumpWindowTitleText'>Dump Window</span></div><div class='dumpArea'>";
		$res .= preg_replace($search, $replacement, print_r($v, true));
		$res .= "</div></div>";
		if($return)
			return $res;
		else
			echo $res;
	}
}
?>
