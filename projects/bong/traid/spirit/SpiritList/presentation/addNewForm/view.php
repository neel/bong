					<form method="post" action="#" bong:handle="spirit">
						<div class="bong-dialog-form">
							<div class="bong-dialog-form-field">
								<label>Spirit Name:</label> <input type="text" name="name" value="" />
							</div>
						</div>
						
						<fieldset class="bong-admin-properties-fieldset">
							<legend>Properties</legend>
							<div class="bong-dialog-form">
								<div class="bong-dialog-form-field">
									<label>Binding:</label> <select name="binding" style="width: 132px">
												<option value="StaticBound">StaticBound</option>
												<option value="InstanceBound">InstanceBound</option>
											 </select>
								</div>
								<div class="bong-dialog-form-field">
									<label>Serialization:</label> <select name="serialization" style="width: 132px">											
														<option value="MemoryXDO">MemoryXDO</option>
														<option value="SerializableXDO">SerializableXDO</option>
												   </select>
								</div>
								<div class="bong-dialog-form-field">
									<label>Feeder:</label> <select name="feeder" style="width: 132px">
												<option value="ControllerFeeded">ControllerFeeded</option>
												<option value="SelfFeeded">SelfFeeded</option>
												<option value="SpiritFeeded">SpiritFeeded</option>
											</select>
								</div>
								<div class="bong-dialog-form-field">
									<label>Session:</label> <select name="session" style="width: 132px">
												<option value="SessionedSpirit">SessionedSpirit</option>
												<option value="FloatingSpirit">FloatingSpirit</option>
											 </select>
								</div>
							</div>
						</fieldset>
						
					</form>
