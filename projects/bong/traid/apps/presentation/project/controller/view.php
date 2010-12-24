<div class="bong-admin-sidebar">
	<div class="bong-admin-sidebar-gap"></div>
	<ul class="bong-admin-sidebar-components bong-admin-sidebar-components-project">
		<li class="bong-admin-sidebar-components-label">Project</li>
		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-properties">Properties</li>
		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-selected bong-admin-sidebar-components-item-settings">Settings</li>
		<li class="bong-admin-sidebar-components-item bong-admin-sidebar-components-item-resource">Resources</li>
	</ul>
	<?php $controller->spirit('ControllerList')->main(); ?>
	<?php $controller->spirit('SpiritList')->main(); ?>
	<div class="bong-admin-sidebar-separator"></div>
</div>
<div class="bong-admin-central">
	<?php /*Dump::r($data->explorer);*/ ?>
	<div class="bong-admin-central-right">
		<h6 class="bong-admin-component-headline">XDOs</h6>
		<a class="bong-admin-component-xdo" href="#">0524100d1e7fcda48c2ae728f78d7faf</a>
		<a class="bong-admin-component-xdo" href="#">4524100d1e7fcda48c2ae728f78d7faf</a>
		<a class="bong-admin-component-xdo" href="#">bb6def095c09284ed8d9ec2e7bca6b3a</a>
		<a class="bong-admin-component-xdo" href="#">d9ee234525bc1c8bff4c6b2a6876aeea</a>
		<a class="bong-admin-component-xdo" href="#">2524100d1e7fcda48c2ae728f78d7faf</a>
		<a class="bong-admin-component-xdo" href="#">24f729bfad3f3131c20178254b86dd2e</a>
		<a class="bong-admin-component-xdo" href="#">08524100d1e7fcda48c2ae728f78d7fa</a>
		<a class="bong-admin-component-xdo" href="#">0524100d1e7fcda48c2ae728f78d7faf</a>
		<a class="bong-admin-component-xdo-more"></a>
		<h6 class="bong-admin-component-headline">Backends</h6>
		<a class="bong-admin-component-xdo" href="#">0524100d1e7fcda48c2ae728f78d7faf</a>
		<a class="bong-admin-component-xdo" href="#">4524100d1e7fcda48c2ae728f78d7faf</a>
		<a class="bong-admin-component-xdo" href="#">bb6def095c09284ed8d9ec2e7bca6b3a</a>
		<a class="bong-admin-component-xdo" href="#">d9ee234525bc1c8bff4c6b2a6876aeea</a>
		<a class="bong-admin-component-xdo" href="#">2524100d1e7fcda48c2ae728f78d7faf</a>
		<a class="bong-admin-component-xdo" href="#">24f729bfad3f3131c20178254b86dd2e</a>
		<a class="bong-admin-component-xdo" href="#">08524100d1e7fcda48c2ae728f78d7fa</a>
		<a class="bong-admin-component-xdo" href="#">0524100d1e7fcda48c2ae728f78d7faf</a>
	</div>
	<div class="bong-admin-central-main">
		<?php $controller->spirit('ControllerProperties')->main() ?>
		<?php $controller->spirit('MethodList')->main() ?>
	</div>
</div>
