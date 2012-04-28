<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo get_class($ex) ?></title>
		<script type="text/javascript" src="<?php echo SysResource::js('bong.bootstrap') ?>"></script>
		<script type="text/javascript" src="<?php echo SysResource::js('jquery') ?>"></script>
		<script type="text/javascript" src="<?php echo SysResource::js('dump') ?>"></script>
		<style type="text/css">
			@import url(<?php echo SysResource::css('dump') ?>);
			body{
				margin: 0px;
				padding: 0px;
				/*background-color: #FFF1EF;*/
			}
			.bong-exception{
				width: 100%;
				float: left;
				/*background-color: #FFF1EF;*/
			}
			.bong-exception-header, .bong-exception-main, .bong-exception-footer, .bong-exception-params, .bong-exception-traces{
				width: 100%;
				float: left;
			}
			.bong-exception-name, .bong-exception-code, .bong-exception-file, .bong-exception-line, .bong-exception-param-key, .bong-exception-param-value, .bong-exception-trace-file, .bong-exception-trace-line, .bong-exception-trace-source{
				/*float: left;*/
				display: inline-block;
			}
			.bong-exception-param{
				width: 100%;
				margin: auto;
			}
			.bong-exception-header{
				margin-top: 12px;
				height: 38px;
				font-size: 17px;
				font-family: Trebuchet MS,Liberation Sans,DejaVu Sans,sans-serif;
			}
			.bong-exception-name{
				font-size: 23px;
				color: #E42550;
			}
			.bong-exception-code{
				font-size: 10px;
			}
			.bong-exception-main{
				min-height: 30px;
				border-left: 5px solid #EE9E93;
				padding: 10px;
				background-color: #FFECEC;
			}
			.bong-exception-footer{
				color: #FFF1F1;
				background-color: #EE9E93;
			}
			.bong-exception-params{
				background: #FFFCFC;
				margin-left: 18px;
				width: 100%;
				display: table;
			}
			.bong-exception-param{
				border-bottom: 1px solid #fff;
				font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
				font-size: 12px;
				margin-left: 20px;
				display: table-row;
			}
			.bong-exception-param:hover{
				background-color: #FEF7F7;
			}
			.bong-exception-param-key, .bong-exception-param-value{
				display: table-cell;
			}
			.bong-exception-param-key{
				font-weight: bold;
				width: 120px;
			}
			.bong-exception-param-value{
				padding-left: 10px;
			}
			h4{
				margin: 0px;
				padding: 0px;
				border-bottom: 1px solid #EE9E93;
				font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
				font-size: 19px;
			}
			.bong-exception-trace-source{
				padding-left: 20px;
			}
			.bong-exception-traces{
				display: table;
			}
			.bong-exception-trace{
				display: table-row;
				background-color: #FEF7F7;
			}
			.bong-exception-trace:hover{
				background-color: #FFECEC;
			}
			.bong-exception-trace-file{
				display: table-cell;
			}
			.bong-exception-trace-line{
				display: table-cell;
			}
			.bong-exception-trace-source{
				display: table-cell;
			}
		</style>
	</head>
	<body>
		<div class="bong-exception">
			<div class="bong-exception-header">
				<div class="bong-exception-name"><?php echo get_class($ex) ?></div> <div class="bong-exception-code">(<?php echo $ex->code() ?>)</div> Thrown
			</div>
			<div class="bong-exception-footer">
				<span class="bong-exception-file"><code><?php echo $ex->getFile() ?></code></span>:<span class="bong-exception-line"><code><?php echo $ex->getLine() ?></code></span>
			</div>
			<div class="bong-exception-main">
				<code><?php echo htmlspecialchars($ex->getMessage()) ?></code>
			</div>
			<div class="bong-exception-params">
				<?php $params = $ex->params() ?>
				<?php foreach($params as $param): ?>
				<div class="bong-exception-param">
					<div class="bong-exception-param-key"><?php echo $param->name() ?></div>
					<div class="bong-exception-param-value"><code><?php echo $param->value() ?></code></div>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="bong-exception-traces">
				<h4>Stack Trace</h4>
				<?php $traces = $ex->getTRace(); ?>
				<?php foreach($traces as $trace): ?>
				<div class="bong-exception-trace">
					<div class="bong-exception-trace-file"><code><?php echo $trace['file'] ?></code></div>:<div class="bong-exception-trace-line"><code><?php echo $trace['line'] ?></code></div><div class="bong-exception-trace-source"><code><?php echo @$trace['class'] ?>::<?php echo @$trace['function'] ?>()</code></div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</body>
</html>