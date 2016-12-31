<?php

/*
*   Displays/hides meta box based on post format
*/
function dz_enqueue_admin_styles()
{
	if(is_admin())
	{
		wp_register_script('dz_postmeta', get_template_directory_uri() .'/includes/js/admin/zee-post-meta.js');
		wp_enqueue_script('dz_postmeta');

          wp_register_script('dz_adminscript', get_template_directory_uri() .'/includes/js/admin/admin-script.js');
		wp_enqueue_script('dz_adminscript');
	}
}

add_action('admin_enqueue_scripts','dz_enqueue_admin_styles');

?>
