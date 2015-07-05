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

function form_ajax_url() {
	?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
	</script>
	<?php
}

function myFunction() {
    // The $_REQUEST contains all the data sent via ajax
	if ( isset($_REQUEST) ) {
		$temp = $_REQUEST['temp'];
		echo "<h1>$temp</h1>";  
	}
	die();
}
add_action('wp_ajax_myFunction' , 'myFunction'); // this is for people who are logged in

add_action('edit_form_advanced' , function($post) {
	
	if (get_post_type( $post->post_id ) != 'form') {return;}
	?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<style>
		#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
		#sortable div { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
		#sortable div span { position: absolute; margin-left: -1.3em; }
	</style>
	<script>
		jQuery(function() {
			jQuery( "#sortable" ).sortable();
			jQuery( "#sortable" ).disableSelection();
		});
	</script>

	<a class="add">+</a> 	<span class="dashicons dashicons-minus"></span>
	<input type="text" class="ui_val">
	<div id="sortable">
		<div class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1<a class="exit" style="float:right;">x</a></div>
	</div>

	<div class="app">

	</div>
	<script>
		jQuery(document).ready(function($) {
			jQuery(document).on('click', '.exit', function(event) {
				event.preventDefault();
				jQuery(this).parent('li').remove();
				var vals="";

				jQuery('#sortable>li').each(function(index, el) {
					vals += jQuery(this).text();
				});
				jQuery('.app').html(vals);
			});
			jQuery(document).on('click', '.add', function(event) {
				event.preventDefault();
				jQuery('#sortable').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'+jQuery('.ui_val').val()+'<a class="exit" style="float:right;">x</a></li>');

				var vals="";

				jQuery('#sortable>li').each(function(index, el) {
					vals += jQuery(this).text();
				});
				jQuery.ajax({
	url: ajaxurl, //key variable that is set in the php enqueue
	type: 'POST',
	data: {
            'action':'myFunction', //myFunction needs to match the function in the php file as well as the add_action for it
            'temp' : vals
        },
    }).done(function(data , status) {
//status is the http request status - 200 for all good, 404 not found e.t.c
jQuery('.app').html(data);
console.log("success");

});
});
		});
</script>
<?php
} );

$form = new formbuilder();

