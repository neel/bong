<?php if(isset($_POST['contents'])): ?>
	bong.dialog({
		title: '<?php echo ($data->saveSuccess) ? 'Saved Successfully' : 'Failed to Save' ?>',
		content: '<?php echo ($data->filePath) ? "{$data->filePath} Saved Succcessfully" : "{$data->filePath} Failed to Save File not Writtable" ?>',
		buttons: [{
			label: 'Okay',
			isDefault: true,
			action: function(){
				bong.activeDialog().hide();
			}
		}]
	});
<?php else: 
	 if($data->exists): 
		 if($data->sourceRequested):
			 echo $data->source; 
		 else: ?>
			bong.editor({
				file: '<?php echo $data->file ?>',
				url: '<?php echo Resource::self('?source') ?>',
				save: function(data){
					bong.href('<?php echo Resource::self() ?>', {
						method: 'post',
						params: '&contents='+escape(data)
					}).eval();
				},
				<?php if(isset($data->phpDoc) && $data->phpDoc): ?>
				phpDoc: true
				<?php else: ?>
				embeddedPhpDoc: true
				<?php endif; ?>
			});
		<?php endif; 
	 else: ?>
		bong.dialog({
			title: '<?php echo $data->title ?> don\'t Exist',
			content: 'Would You Like to Create One ?',
			width: 370,
			buttons: [{
				label: 'Okay',
				isDefault: true,
				action: function(){
					bong.activeDialog().hide();
				}
			}]
		});
	<?php endif;
 endif; ?>
