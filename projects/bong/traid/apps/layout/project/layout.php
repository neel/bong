<html>
	<head>
		<title><?php echo $params->title ?></title>
		
		<?php foreach($params->js as $js): ?>
		<script type="text/javascript" src="<?php echo $js ?>"></script>
		<?php endforeach; ?>
		
		<style type="text/css">
			<?php foreach($params->css as $css): ?>
			@import url("<?php echo $css ?>");
			<?php endforeach; ?>
		</style>
		
	</head>
	<body>
		<div id="bong-admin-header">
			<input type="text" name="search" class="bong-admin-doc-search" value="Search" />
			<a href="/bong/~bong/user/logout" class="bong-admin-header-cntrl">Logout</a>
			<a href="#" class="bong-admin-header-cntrl">Settings</a>
			<a href="#" class="bong-admin-home">bong</a>
		</div>
		<div id="bong-admin-body">
			<?php echo $this->viewContents ?>
		</div>
		<div id="bong-admin-editor-minimize-area">
			<!--
				<div class="bong-admin-editor-minimize-tab">layout.php<div class="bong-admin-editor-minimize-tab-close"></div></div>
				<div class="bong-admin-editor-minimize-tab">params.php<div class="bong-admin-editor-minimize-tab-close"></div></div>
			-->
		</div>
	</body>
</html>
<?php http::contentType('text/html'); ?>
