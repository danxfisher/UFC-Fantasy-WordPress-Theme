<?php
/**
 * Enqueue Google Fonts.
 */
function enqueue_google_font() {

$query_args = array(
 'family' => 'Oswald:400,700|Titillium+Web:400,700'
 );

 wp_register_style( 'google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
 wp_enqueue_style( 'google-fonts' );

}
add_action( 'wp_enqueue_scripts', 'enqueue_google_font' );
?>
