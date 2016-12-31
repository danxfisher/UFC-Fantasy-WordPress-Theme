<?php
/**
 * Custom UFC betting plugin
 */

global $ufcEvents_db_version;
$ufcEvents_db_version = '1.0';

// install database for events
function ufcEvents_install() {
 	global $wpdb;
 	global $ufcEvents_db_version;

 	$table_name = $wpdb->prefix . 'ufcEvents';

 	$charset_collate = $wpdb->get_charset_collate();

 	$sql = "CREATE TABLE $table_name (
 		id mediumint(9) NOT NULL AUTO_INCREMENT,
 		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
 		name tinytext NOT NULL,
 		PRIMARY KEY  (id)
 	) $charset_collate;";

 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 	dbDelta( $sql );

 	add_option( '$ufcEvents_db_version', $ufcEvents_db_version );
}

// install dummy data for ufcEvents
function ufcEvents_install_data() {
 	global $wpdb;

 	$welcome_name = 'UFC XXX';

 	$table_name = $wpdb->prefix . 'ufcEvents';

 	$wpdb->insert(
 		$table_name,
 		array(
 			'time' => current_time( 'mysql' ),
 			'name' => $welcome_name
 		)
 	);
}
