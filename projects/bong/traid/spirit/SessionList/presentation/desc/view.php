bong.dialog({
	title: "Session <?php echo $data->info->IpAddress ?>",
	content: '<table width="100%"> \
		<tr><td class="TLabel" colspan="2">Client Signature</td></tr> \
		<tr><td class="TValue" colspan="2"><?php echo $data->info->ClientSignature ?></td></tr> \
		<tr> \
			<td width="20%" class="TLabel">IP Address</td> \
			<td class="TValue"><?php echo $data->info->IpAddress ?></td> \
		</tr> \
		<tr> \
			<td width="20%" class="TLabel">Session ID</td> \
			<td class="TValue"><?php echo $data->id ?></td> \
		</tr> \
		<tr> \
			<td width="20%" class="TLabel">State</td> \
			<td class="TValue"><?php echo $data->info->State ?></td> \
		</tr> \
		<tr> \
			<td width="20%" class="TLabel">Last Access</td> \
			<td class="TValue"><?php echo date(DATE_RFC822, $data->info->LastAccess) ?></td> \
		</tr> \
		<tr><td class="TLabel" colspan="2" style="text-align: right;border-bottom: 1px solid #AFBDDC;font-weight: bold;">XDOs</td></tr> \
		<?php foreach($data->info->xdos as $xdo): ?> \
		<tr class="TValue-xdo-link">
			<td class="TValue"><?php echo $xdo->name ?></td>
			<td class="TValue" style="text-align: right"><a class="" href="#" title="<?php echo $xdo->file ?>"><?php echo pathinfo($xdo->file, PATHINFO_BASENAME) ?></a></td>
		</tr> \
		<?php endforeach; ?> \
		\
	</table>',
	width: 420,
	buttons: [{
		label: 'Okay',
		isDefault: true,
		action: function(){
			bong.activeDialog().hide();
		}
	}]
})
