<?php
/*
Plugin name: Form Builder
 */
// Add a new submenu under DASHBOARD using array
// 
// 

if (!class_exists('cmb_Meta_Box')) {
	require_once( 'cmb/custom-meta-boxes.php' );
}
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
		#temp_container td {
			border:1px solid cyan;
		}

		.dashicons-plus {
			cursor: pointer;
		}


		#add_input {
			text-decoration: none;
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
						<a class="button button-default button-large" id="save_form_button" >Edit</a>
					</td>
					<td align="center">
						<div style="padding:15px;">
							<input type="text" style="width:100%;" name="temp" id="temp" disabled value="<?php echo $name; ?>">
						</div>
					</td>
					<td align="center">
						<div style="padding:15px;">
							<input type="text" style="width:100%;" disabled value="<?php echo $type; ?>">
						</div>
					</td>
					<td align="center">
						<a class="remove_input" id="<?php echo $id; ?>_button" data-id="<?php echo $id; ?>" ><span class="dashicons dashicons-minus"></span></a>	
					</td>
				</tr>
				<?php
			}
			?>
		</table>


		<a class="button button-primary button-large" id="save_form_button" style="float:right;">Save</a>
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

