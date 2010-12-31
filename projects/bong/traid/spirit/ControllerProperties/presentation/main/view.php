		<div class="bong-admin-component-source"><button class="bong-dialog-btn">Source
		<script type="text/bongscript" event="click">
			bong.href('<?php echo Resource::link() ?>/source/controller').eval();
		</script>
		</button></div>
		<h6 class="bong-admin-component-headline"><?php echo $data->controller->name() ?>Controller</h6>
		<div class="bong-admin-properties-area">
			<?php $controller->spirit('ControllerProperties')->components(); ?>
		</div>

