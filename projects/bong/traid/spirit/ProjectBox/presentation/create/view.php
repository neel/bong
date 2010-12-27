<div class="bong-admin-project bong-admin-project-new">
	<div class="bong-admin-project-name bong-admin-project-name-editable"><a href="#">name</a><input type="text" class="bong-admin-project-name-editable-cntrl" value="name" id="projectName" /></div>
	<div class="bong-admin-project-bottom">
		<div class="bong-admin-project-dir">bong</div>
		<button class="bong-admin-project-cntrl-new">Add New
			<script type="text/bongscript" event="click">
					bong.href('/bong/~bong/project/createProject/'+bong.byId('projectName').value).eval();
				</script>
		</button>
	</div>
</div>
