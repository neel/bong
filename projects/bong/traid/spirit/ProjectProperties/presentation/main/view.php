		<h6 class="bong-admin-component-headline">Project::<?php echo $data->project->name() ?>::properties</h6>
		<div class="bong-admin-properties-area">
			<?php $controller->spirit('ProjectProperties')->properties(); ?>
			<?php $controller->spirit('ProjectProperties')->components(); ?>
		</div>

