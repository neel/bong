			<fieldset class="bong-admin-properties-fieldset">
				<legend>Common Components</legend>
				<div class="bong-admin-property <?php echo (($data->layout) ? 'bong-admin-property-with-info' : null) ?>">
					<a class="bong-admin-property-cross"></a>
					<div class="bong-admin-property-label">Layout</div>
					<div class="bong-admin-property-rhs">		
						<div class="bong-admin-property-found-status bong-admin-property-found-status-found <?php echo (!$data->layout ? 'bong-admin-property-hide' : '') ?>"></div>
						<button class="bong-dialog-btn bong-admin-property-edit <?php echo (!$data->layout ? 'bong-admin-property-hide' : '') ?>">
						<script type="text/bongscript" event="click">
							bong.href('<?php echo Resource::link() ?>/source/controllerLayout').eval();
						</script>
						</button>
					
						<div class="bong-admin-property-found-status bong-admin-property-found-status-notfound <?php echo ($data->layout ? 'bong-admin-property-hide' : '') ?>"></div>
						<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-property-create <?php echo ($data->layout ? 'bong-admin-property-hide' : '') ?>">
						<script type="text/bongscript" event="click">
							bong.href('<?php echo Resource::link() ?>/project/createControllerLayout').eval({scope: this});
						</script>
						</button>
					</div>
					<?php if($data->layout): ?>
						<div class="bong-admin-property-info">
							<div class="bong-admin-property-info-label">Common Layout Found at:</div> <div class="bong-admin-property-info-text"><?php echo $data->layout->filePath() ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="bong-admin-property <?php echo (($data->params) ? 'bong-admin-property-with-info' : null) ?>">
					<a class="bong-admin-property-cross"></a>
					<div class="bong-admin-property-label">Param</div>
					<div class="bong-admin-property-rhs">					
						<div class="bong-admin-property-found-status bong-admin-property-found-status-found <?php echo (!$data->params ? 'bong-admin-property-hide' : '') ?>"></div>
						<button class="bong-dialog-btn bong-admin-property-edit  <?php echo (!$data->params ? 'bong-admin-property-hide' : '') ?>">
						<script type="text/bongscript" event="click">
							bong.href('<?php echo Resource::link() ?>/source/controllerParams').eval();
						</script>
						</button>
					
						<div class="bong-admin-property-found-status bong-admin-property-found-status-notfound <?php echo ($data->params ? 'bong-admin-property-hide' : '') ?>"></div>
						<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-property-create" <?php echo ($data->params ? 'bong-admin-property-hide' : '') ?>>
						<script type="text/bongscript" event="click">
							bong.href('<?php echo Resource::link() ?>/source/controllerParams').eval();
						</script>							
						</button>
					</div>
					<?php if($data->params): ?>
						<div class="bong-admin-property-info">
							<div class="bong-admin-property-info-label">Common Params Found at:</div> <div class="bong-admin-property-info-text"><?php echo $data->params->filePath() ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="bong-admin-property <?php echo (($data->view) ? 'bong-admin-property-with-info' : null) ?>">
					<a class="bong-admin-property-cross"></a>
					<div class="bong-admin-property-label">View</div>
					<div class="bong-admin-property-rhs">					
						<div class="bong-admin-property-found-status bong-admin-property-found-status-found <?php echo (!$data->view ? 'bong-admin-property-hide' : '') ?>"></div>
						<button class="bong-dialog-btn bong-admin-property-edit <?php echo (!$data->view ? 'bong-admin-property-hide' : '') ?>">
						<script type="text/bongscript" event="click">
							bong.href('<?php echo Resource::link() ?>/source/controllerView').eval();
						</script>
						</button>
					
						<div class="bong-admin-property-found-status bong-admin-property-found-status-notfound <?php echo ($data->view ? 'bong-admin-property-hide' : '') ?>"></div>
						<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-property-create <?php echo ($data->view ? 'bong-admin-property-hide' : '') ?>">
						<script type="text/bongscript" event="click">
							bong.href('<?php echo Resource::link() ?>/source/controllerView').eval();
						</script>
						</button>
					</div>
					<?php if($data->view): ?>
						<div class="bong-admin-property-info">
							<div class="bong-admin-property-info-label">Common Params Found at:</div> <div class="bong-admin-property-info-text"><?php echo $data->view->filePath() ?></div>
						</div>
					<?php endif; ?>
				</div>
