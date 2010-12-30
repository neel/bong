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
		<?php echo $this->viewContents ?>
	 </body>
</html>
<?php http::contentType('text/html'); ?>
