<div class="bong-admin-project <?php echo ($xdo->project->isDefault ? 'bong-admin-project-default' : '') ?>">
	<div class="bong-admin-project-delete bong-admin-common-close"></div>
	<div class="bong-admin-project-name"><a href="select/<?php echo $xdo->project->name ?>"><?php echo $xdo->project->name ?></a></div>
	<div class="bong-admin-project-bottom">
		<div class="bong-admin-project-dir"><?php echo $xdo->project->location ?></div>
		<?php if($xdo->project->isDefault): ?>
			<div class="bong-admin-project-cntrl-isdefault">Default</div>
		<?php else: ?>
			<div class="bong-admin-project-cntrl-default">Make Default</div>
		<?php endif; ?>
	</div>
</div>
