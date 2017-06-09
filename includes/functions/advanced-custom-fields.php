<?php
/**
 * advanced custom fields included in theme
 *
 */


require_once(get_template_directory().'/includes/functions/ufc-bets/page-events.php');
// 1. customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');

function my_acf_settings_path( $path ) {

    // update path
    $path = get_template_directory().'/includes/plugins/advanced-custom-fields/';

    // return
    return $path;

}


// 2. customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');

function my_acf_settings_dir( $dir ) {

    // update path
    $dir = get_template_directory().'/includes/plugins/advanced-custom-fields/';

    // return
    return $dir;

}


// 3. Hide ACF field group menu item
add_filter('acf/settings/show_admin', '__return_false');


// 4. Include ACF
include_once(get_template_directory().'/includes/plugins/advanced-custom-fields/acf.php' );



// 5. Add all custom fields
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_event-info',
		'title' => 'Event Info',
		'fields' => array (
			array (
				'key' => 'field_58d22e612f794',
				'label' => 'Event Start Date',
				'name' => 'event_start_date',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_58d22ef658473',
				'label' => 'Event Title',
				'name' => 'event_title',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_58d34ad4e7227',
				'label' => 'Event URL',
				'name' => 'event_url',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'default',
			'hide_on_screen' => array (
				0 => 'permalink',
				1 => 'the_content',
				2 => 'excerpt',
				3 => 'custom_fields',
				4 => 'discussion',
				5 => 'comments',
				6 => 'revisions',
				7 => 'slug',
				8 => 'author',
				9 => 'format',
				10 => 'featured_image',
				11 => 'categories',
				12 => 'tags',
				13 => 'send-trackbacks',
			),
		),
		'menu_order' => 0,
	));
}


?>
