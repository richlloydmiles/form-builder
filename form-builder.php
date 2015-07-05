<?php
/*
Plugin name: Form Builder
 */
// Add a new submenu under DASHBOARD using array
// 
// 

if (!class_exists('formbuilder')) {
	require_once( 'classes/formbuilder.php' );
}

add_action('wp_head','form_ajax_url');

add_action('wp_ajax_get_form_template' , 'get_form_template'); 

function get_form_template() {
	$name = $_REQUEST['name'];
	$type = $_REQUEST['type'];

	ob_start(); 
	require_once( 'views/'.$_REQUEST['file'] );
	
	$temp = ob_get_contents();
	ob_end_clean();
	echo $temp;
}
function form_ajax_url() {
	?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
	</script>
	<?php
}

function save_form_options() {
	if (isset($_REQUEST['post_id']) && isset($_REQUEST['form_values'])) {
		echo update_post_meta($_REQUEST['post_id'], 'form_values', $_REQUEST['form_values']);
	}
	die();
}
/** In main plugin file **/

add_filter('admin_head','ShowTinyMCE');
function ShowTinyMCE() {
	// conditions here
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
}

add_action('wp_ajax_save_form_options' , 'save_form_options'); 

add_action('edit_form_advanced' , function($post) {
	
	if (get_post_type( $post->ID ) != 'form') {return;}
	?>
	<style>
		.temp_container {
			-webkit-transition: all 300ms ease-in-out;
			-moz-transition: all 300ms ease-in-out;
			-o-transition: all 300ms ease-in-out;
			transition: all 300ms ease-in-out;
		}

		.dashicons-plus {
			cursor: pointer;
		}


		#add_input {
			text-decoration: none;
		}

		.edit {
			width:100%;
			text-align: center;
		}
	</style>

	<a id="add_input" href="#TB_inline?width=600&height=550&inlineId=my-content-id" class="thickbox" ><span class="dashicons dashicons-plus" ></span></a>

	<table style="width:100%;" id="app">
		<?php
		foreach (get_post_meta( $post->ID, 'form_values', true ) as $value) {
			$value = (array) json_decode($value);
			$name = $value['name'];
			$type = $value['type'];
			$id = $value['id'];
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
							<select style="width:100%;" class="input_type" disabled>
								<option value="text" <?php selected( $type, 'text' ); ?>>text</option>
								<option value="email" <?php selected( $type, 'email' ); ?>>email</option>
								<option value="textarea" <?php selected( $type, 'textarea' ); ?>>textarea</option>
							</select>

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
<?php
}
?>
</table>


<a id="save_form_button" style="display:none;">Save</a>
<!-- 	<div id="poststuff">
		<?php the_editor('<h2>Some content</h2>','content'); ?>
	</div> -->

	<div id="my-content-id" style="display:none;">
		<h3>Input options</h3>
		<table style="width:100%;">
			<tr>
				<td>
					<label for="new_form_name">Input Name</label><br>
					<input type="text" id="new_form_name" style="width:100%;">
				</td>
			</tr>
			<tr>
				<td>
					<label for="new_form_type">Input Type</label><br>
					<select name="type" id="new_form_type" style="width:100%;">
						<option value="text">text</option>
						<option value="email">email</option>
						<option value="textarea">textarea</option>
					</select>
				</td>
			</tr>		
		</table>
		<a class="button button-primary button-large" id="add_form_input">Add</a>
	</div>	
	<script>
		jQuery(document).ready(function($) {
			jQuery(document).on('click', '#save_form_button', function(event) {
				event.preventDefault();
				var json = [];
				jQuery('.temp_container').each(function(index, el) {
					single_val = jQuery(el).attr('data-json');
					json.push(single_val);
				});
				console.log(json);
				jQuery.ajax({
					url: ajaxurl, 
					type: 'POST',
					data: {
						'action':'save_form_options',
						'form_values' : json, 
						'post_id' : '<?php echo $post->ID; ?>'
					},
				}).done(function(data , status) {
					console.log("success");
				});
			});
		}); 

		jQuery(document).ready(function($) {
			jQuery(document).on('click', '#add_form_input', function(event) {
				event.preventDefault();
				jQuery('.tb-close-icon').click();
				jQuery.ajax({
					url: ajaxurl, 
					type: 'POST',
					data: {
						'action':'get_form_template',
						'file' : 'new_form_element.php',
						'name' : jQuery('#new_form_name').val(),
						'type' : jQuery('#new_form_type').val()

					},
				}).done(function(data , status) {
					console.log("success");
					jQuery('#app').append(data);
					jQuery('#save_form_button').click();
				});

			});
		}); 
	</script>

	<div class="test">
		<pre>
			<!-- 	<?php print_r(get_post_meta( $post->ID, 'form_values', $single )); ?> -->
		</pre> 
	</div>
	<?php
} );

$form = new formbuilder();

