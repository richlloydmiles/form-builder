		<?php
		if (isset($name) && isset($type)) {

			$id = preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name)));
			$values = json_encode(array(
				'id'	=>	$id,
				'name'	=>	$name ,
				'type'	=>	$type  
				));
				?>
				<tr class="temp_container" id="<?php echo $id; ?>" data-json='<?php echo $values; ?>'>
					<td align="center">
						<a class="button button-default button-large edit" id="edit_<?php echo $id; ?>" >Edit</a>
					</td>
					<td align="center">
						<div style="padding:15px;">
							<input type="text" style="width:100%;" class="input_name" disabled value="<?php echo $name; ?>">
						</div>
					</td>
					<td align="center">
						<div style="padding:15px;">
							<input type="text" style="width:100%;" class="input_type" disabled value="<?php echo $type; ?>">
						</div>
					</td>
					<td align="center">
						<a class="remove_input" id="<?php echo $id; ?>_button" data-id="<?php echo $id; ?>" ><span class="dashicons dashicons-minus"></span></a>	
					</td>
				</tr>
				<script>
					jQuery(document).ready(function($) {
						jQuery(document).on('click', '#edit_<?php echo $id; ?>', function(event) {
							if (jQuery(this).hasClass('active')) {
								jQuery(this).parents('td').siblings('td').children('div').children('.input_name').attr('disabled' , 'disabled');
								jQuery(this).parents('td').siblings('td').children('div').children('.input_type').attr('disabled' , 'disabled');
								jQuery(this).text('Edit');
								jQuery(this).removeClass('active');	
								var new_val = '{"id":"a","name":"'+jQuery(this).parents('td').siblings('td').children('div').children('.input_name').val()+'","type":"'+ jQuery(this).parents('td').siblings('td').children('div').children('.input_type').val() +'"}'
								jQuery(this).parents('td').parents('tr').attr('data-json' , new_val);
								jQuery(this).parents('td').parents('tr').css({'background' : 'transparent'});
								jQuery('#save_form_button').click();
							} else {
								jQuery(this).parents('td').siblings('td').children('div').children('.input_name').removeAttr('disabled');
								jQuery(this).parents('td').siblings('td').children('div').children('.input_type').removeAttr('disabled');
								jQuery(this).text('save');
								jQuery(this).addClass('active');
								jQuery(this).parents('td').parents('tr').css({'background' : 'tomato'});
							}
						});
	});
		jQuery(document).ready(function($) {
			jQuery(document).on('click', '#<?php echo $id; ?>_button', function(event) {
				event.preventDefault();
				var data_id = jQuery(this).attr('data-id' );
				jQuery('#' + data_id ).remove();
				jQuery('#save_form_button').click();
			});
		});
	</script>
	<?php }
	?>
