<div class="bong-admin-sidebar">
	<div class="bong-admin-sidebar-gap"></div>
	<ul class="bong-admin-sidebar-components bong-admin-sidebar-components-project">
		<li class="bong-admin-sidebar-components-label">Project:<?php echo $data->project->name() ?></li>
		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-properties"><a href="<?php echo Resource::link() ?>/project/ls">Project List</a></li>
		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-selected bong-admin-sidebar-components-item-settings">Settings</li>
		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-resource">Resources</li>
	</ul>
	<bong:spirit name="ControllerList" call="main" />
	<bong:spirit name="SpiritList" call="main" />
	<div class="bong-admin-sidebar-separator"></div>
</div>
