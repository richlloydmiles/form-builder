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
						<a class="button button-default button-large" id="save_form_button" >Edit</a>
					</td>
					<td align="center">
						<div style="padding:15px;">
							<input type="text" style="width:100%;" class="name" disabled value="<?php echo $name; ?>">
						</div>
					</td>
					<td align="center">
						<div style="padding:15px;">
							<input type="text" style="width:100%;" class="type" disabled value="<?php echo $type; ?>">
						</div>
					</td>
					<td align="center">
						<a class="remove_input" id="<?php echo $id; ?>_button" data-id="<?php echo $id; ?>" ><span class="dashicons dashicons-minus"></span></a>	
					</td>
				</tr>
				<script>

					jQuery(document).ready(function($) {
						jQuery(document).on('click', '#<?php echo $id; ?>_button', function(event) {
							event.preventDefault();
							var data_id = jQuery(this).attr('data-id' );
							jQuery('#' + data_id ).remove();
						});
					});
				</script>
				<?php }
				?>
