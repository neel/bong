<style>
.info{
	font-family: "lucida grande", "tahoma", "verdana", "arial", sans-serif;
	font-size: 11px;
	font-style: bold;
	display: block;
	padding: 8px;
	margin-top: 8px;
	margin-left: 5px;
	margin-bottom: 0px;
	color: #FFFFFF;
	clear: right;
	height: 50px;
	font-weight:bold;
	padding-left: 50px;
	background: #627BAD url(/bong/sys/rc/img/icons/warning.png) no-repeat left;
}
</style>
<p class="info">
No View Created for Method <?php echo $meta->method ?> of <?php echo $meta->controller ?> Controller in Project <?php echo $meta->project->name ?>.<br />
XDO and Meta is opened to You for the ease of Development.
</p><br /><br />
<?php Dump::r($xdo); ?>
<?php Dump::r($meta); ?>
