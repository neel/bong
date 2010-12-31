		<div class="bong-admin-component-source"><button class="bong-dialog-btn">Source
		<script type="text/bongscript" event="click">
			bong.href('<?php echo Resource::link() ?>/source/sspirit').eval();
		</script>
		</button></div>
		<h6 class="bong-admin-component-headline"><?php echo $data->controller->name() ?>Abstractor<button id="spiritPropertiesApply" class="bong-dialog-btn bong-dialog-btn-default">Apply</button></h6>
		<div class="bong-admin-properties-area">
			<div class="bong-admin-property">
				<a class="bong-admin-property-new"></a>
				<a class="bong-admin-property-cross">
					<script type="text/bongscript" event="click">
					var rhs = $(this).next().next();
					var defaultText = rhs.children().last()[0].value;
					var modified = rhs.parent().children().first();
					var hidden = rhs.children().last().prev()[0];
					var dropDown = rhs.children().last().prev().prev()[0];
					for(var i=0;i<dropDown.options.length;++i){
						if(dropDown.options[i].value == defaultText){
							dropDown.selectedIndex = i;
						}
					}
					var value = rhs.children().last().prev().prev().prev().children('span')[0];
					value.innerHTML = defaultText;
					hidden.value = defaultText;
					modified.hide();
					runtime.Dn();
					</script>
				</a>
				<div class="bong-admin-property-label">Binding</div>
				<div class="bong-admin-property-rhs">
					<div class="bong-admin-property-value">
						<span><?php echo $data->controller->binding() ?></span>
						<script type="text/bongscript" event="click">
							$(this).hide();
							$(this).next().show();
						</script>
					</div>
					<select style="display: none;">
						<option value="StaticBound" <?php echo ($data->controller->binding()=='StaticBound' ? 'selected' : '') ?>>StaticBound</option>
						<option value="InstanceBound" <?php echo ($data->controller->binding()=='InstanceBound' ? 'selected' : '') ?>>InstanceBound</option>
						<script type="text/bongscript" event="change">
							if(this.options[this.selectedIndex].value == $(this).next().next()[0].value){
								$(this).parent().parent().children().first().hide();
								runtime.Dn();
							}else{
								if(!$(this).parent().parent().children().first()[0].style.display || $(this).parent().parent().children().first()[0].style.display == 'none'){
									$(this).parent().parent().children().first().show();
									runtime.Up();
								}
							}
						</script>
						<script type="text/bongscript" event="blur">
							$(this).hide();
							$(this).prev().show();
						</script>
					</select>
					<input type="hidden" value="<?php echo $data->controller->binding() ?>" />
					<input name="default" type="hidden" value="<?php echo $data->controller->binding() ?>" />
				</div>
			</div>
			
			<div class="bong-admin-property">
				<a class="bong-admin-property-new"></a>
				<a class="bong-admin-property-cross">
					<script type="text/bongscript" event="click">
					var rhs = $(this).next().next();
					var defaultText = rhs.children().last()[0].value;
					var modified = rhs.parent().children().first();
					var hidden = rhs.children().last().prev()[0];
					var dropDown = rhs.children().last().prev().prev()[0];
					for(var i=0;i<dropDown.options.length;++i){
						if(dropDown.options[i].value == defaultText){
							dropDown.selectedIndex = i;
						}
					}
					var value = rhs.children().last().prev().prev().prev().children('span')[0];
					value.innerHTML = defaultText;
					hidden.value = defaultText;
					modified.hide();
					runtime.Dn();
					</script>
				</a>
				<div class="bong-admin-property-label">Serialization</div>
				<div class="bong-admin-property-rhs">
					<div class="bong-admin-property-value">
						<span><?php echo $data->controller->serialization() ?></span>
						<script type="text/bongscript" event="click">
							$(this).hide();
							$(this).next().show();
						</script>
					</div>
					<select style="display: none;">
						<option value="MemoryXDO" <?php echo ($data->controller->serialization()=='MemoryXDO' ? 'selected' : '') ?>>MemoryXDO</option>
						<option value="SerializableXDO" <?php echo ($data->controller->serialization()=='SerializableXDO' ? 'selected' : '') ?>>SerializableXDO</option>
						<script type="text/bongscript" event="change">
							if(this.options[this.selectedIndex].value == $(this).next().next()[0].value){
								$(this).parent().parent().children().first().hide();
								runtime.Dn();
							}else{
								if(!$(this).parent().parent().children().first()[0].style.display || $(this).parent().parent().children().first()[0].style.display == 'none'){
									$(this).parent().parent().children().first().show();
									runtime.Up();
								}
							}
						</script>
						<script type="text/bongscript" event="blur">
							$(this).hide();
							$(this).prev().show();
						</script>
					</select>
					<input type="hidden" value="<?php echo $data->controller->serialization() ?>" />
					<input name="default" type="hidden" value="<?php echo $data->controller->serialization() ?>" />
				</div>
			</div>
			
			<div class="bong-admin-property">
				<a class="bong-admin-property-new"></a>
				<a class="bong-admin-property-cross">
					<script type="text/bongscript" event="click">
					var rhs = $(this).next().next();
					var defaultText = rhs.children().last()[0].value;
					var modified = rhs.parent().children().first();
					var hidden = rhs.children().last().prev()[0];
					var dropDown = rhs.children().last().prev().prev()[0];
					for(var i=0;i<dropDown.options.length;++i){
						if(dropDown.options[i].value == defaultText){
							dropDown.selectedIndex = i;
						}
					}
					var value = rhs.children().last().prev().prev().prev().children('span')[0];
					value.innerHTML = defaultText;
					hidden.value = defaultText;
					modified.hide();
					runtime.Dn();
					</script>
				</a>
				<div class="bong-admin-property-label">Feeder</div>
				<div class="bong-admin-property-rhs">
					<div class="bong-admin-property-value">
						<span><?php echo $data->controller->feeder() ?></span>
						<script type="text/bongscript" event="click">
							$(this).hide();
							$(this).next().show();
						</script>
					</div>
					<select style="display: none;">
						<option value="ControllerFeeded" <?php echo ($data->controller->feeder()=='ControllerFeeded' ? 'selected' : '') ?>>ControllerFeeded</option>
						<option value="SelfFeeded" <?php echo ($data->controller->feeder()=='SelfFeeded' ? 'selected' : '') ?>>SelfFeeded</option>
						<option value="SpiritFeeded" <?php echo ($data->controller->feeder()=='SpiritFeeded' ? 'selected' : '') ?>>SpiritFeeded</option>
						<script type="text/bongscript" event="change">
							if(this.options[this.selectedIndex].value == $(this).next().next()[0].value){
								$(this).parent().parent().children().first().hide();
								runtime.Dn();
							}else{
								if(!$(this).parent().parent().children().first()[0].style.display || $(this).parent().parent().children().first()[0].style.display == 'none'){
									$(this).parent().parent().children().first().show();
									runtime.Up();
								}
							}
							$(this).next()[0].value = this.options[this.selectedIndex].value;
							$($(this).prev()[0]).children('span')[0].innerHTML = this.options[this.selectedIndex].value;
						</script>
						<script type="text/bongscript" event="blur">
							$(this).hide();
							$(this).prev().show();
						</script>
					</select>
					<input type="hidden" value="<?php echo $data->controller->feeder() ?>" />
					<input name="default" type="hidden" value="<?php echo $data->controller->feeder() ?>" />
				</div>
			</div>
			
			<div class="bong-admin-property">
				<a class="bong-admin-property-new"></a>
				<a class="bong-admin-property-cross">
					<script type="text/bongscript" event="click">
					var rhs = $(this).next().next();
					var defaultText = rhs.children().last()[0].value;
					var modified = rhs.parent().children().first();
					var hidden = rhs.children().last().prev()[0];
					var dropDown = rhs.children().last().prev().prev()[0];
					for(var i=0;i<dropDown.options.length;++i){
						if(dropDown.options[i].value == defaultText){
							dropDown.selectedIndex = i;
						}
					}
					var value = rhs.children().last().prev().prev().prev().children('span')[0];
					value.innerHTML = defaultText;
					hidden.value = defaultText;
					modified.hide();
					runtime.Dn();
					</script>
				</a>
				<div class="bong-admin-property-label">Session</div>
				<div class="bong-admin-property-rhs">
					<div class="bong-admin-property-value">
						<span><?php echo $data->controller->session() ?></span>
						<script type="text/bongscript" event="click">
							$(this).hide();
							$(this).next().show();
						</script>
					</div>
					<select style="display: none;">
						<option value="SessoionedSpirit" <?php echo ($data->controller->session()=='SessoionedSpirit' ? 'selected' : '') ?>>SessoionedSpirit</option>
						<option value="FloatingSpirit" <?php echo ($data->controller->session()=='FloatingSpirit' ? 'selected' : '') ?>>FloatingSpirit</option>
						<script type="text/bongscript" event="change">
							if(this.options[this.selectedIndex].value == $(this).next().next()[0].value){
								$(this).parent().parent().children().first().hide();
								runtime.Dn();
							}else{
								if(!$(this).parent().parent().children().first()[0].style.display || $(this).parent().parent().children().first()[0].style.display == 'none'){
									$(this).parent().parent().children().first().show();
									runtime.Up();
								}
							}
						</script>
						<script type="text/bongscript" event="blur">
							$(this).hide();
							$(this).prev().show();
						</script>
					</select>
					<input type="hidden" value="<?php echo $data->controller->session() ?>" />
					<input name="default" type="hidden" value="<?php echo $data->controller->session() ?>" />
				</div>
			</div>
			
		</div>

