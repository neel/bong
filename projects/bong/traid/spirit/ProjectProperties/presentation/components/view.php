			<fieldset class="bong-admin-properties-fieldset">
				<legend>Common Components</legend>
				<div class="bong-admin-property bong-admin-property-with-info">
					<a class="bong-admin-property-cross"></a>
					<div class="bong-admin-property-label">Layout</div>
					<div class="bong-admin-property-rhs">
						<?php if($data->layout): ?>
							<div class="bong-admin-property-found-status bong-admin-property-found-status-found"></div>
							<button class="bong-dialog-btn bong-admin-property-edit">
								<script type="text/bongscript" event="click">
								bong.href('/bong/~bong/source/projectLayout').eval();
								</script>
							</button>
						<?php else: ?>
							<div class="bong-admin-property-found-status bong-admin-property-found-status-notfound"></div>
							<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-property-create"></button>
						<?php endif; ?>
					</div>
					<?php if($data->layout): ?>
						<div class="bong-admin-property-info">
							<div class="bong-admin-property-info-label">Common Layout Found at:</div> <div class="bong-admin-property-info-text"><?php echo $data->layout->filePath() ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="bong-admin-property bong-admin-property-with-info">
					<a class="bong-admin-property-cross"></a>
					<div class="bong-admin-property-label">Param</div>
					<div class="bong-admin-property-rhs">
						<?php if($data->params): ?>
							<div class="bong-admin-property-found-status bong-admin-property-found-status-found"></div>
							<button class="bong-dialog-btn bong-admin-property-edit">
								<script type="text/bongscript" event="click">
								bong.href('/bong/~bong/source/projectParams').eval();
								</script>
							</button>
						<?php else: ?>
							<div class="bong-admin-property-found-status bong-admin-property-found-status-notfound"></div>
							<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-property-create"></button>
						<?php endif; ?>
					</div>
					<?php if($data->params): ?>
						<div class="bong-admin-property-info">
							<div class="bong-admin-property-info-label">Common Params Found at:</div> <div class="bong-admin-property-info-text"><?php echo $data->params->filePath() ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="bong-admin-property bong-admin-property-with-info">
					<a class="bong-admin-property-cross"></a>
					<div class="bong-admin-property-label">View</div>
					<div class="bong-admin-property-rhs">
						<?php if($data->view): ?>
							<div class="bong-admin-property-found-status bong-admin-property-found-status-found"></div>
							<button class="bong-dialog-btn bong-admin-property-edit">
								<script type="text/bongscript" event="click">
								bong.href('/bong/~bong/source/projectView').eval();
								</script>
							</button>
						<?php else: ?>
							<div class="bong-admin-property-found-status bong-admin-property-found-status-notfound"></div>
							<button class="bong-dialog-btn bong-dialog-btn-default bong-admin-property-create"></button>
						<?php endif; ?>
					</div>
					<?php if($data->view): ?>
						<div class="bong-admin-property-info">
							<div class="bong-admin-property-info-label">Common View Found at:</div> <div class="bong-admin-property-info-text"><?php echo $data->view->filePath() ?></div>
						</div>
					<?php endif; ?>
				</div>
			</fieldset>
