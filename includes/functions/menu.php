<?php

/**
 * This theme uses wp_nav_menu() in one location.
*/
register_nav_menus( array(
  'primary'  => __( 'Header menu', 'dz' ),
      'front-page-menu' => __('Pages for Front Page', 'dz' ),
      'footer-menu' => __('Footer menu', 'dz' ),
) );

?>
