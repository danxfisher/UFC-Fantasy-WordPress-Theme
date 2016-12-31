<?php

function mdl_enqueue_scripts() {

  /*^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
   *  css
   *^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

  // load bootstrap icons
  wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );

  // load styles
  wp_enqueue_style( 'mdl-style', get_stylesheet_uri() );

  // load custom styles
  wp_enqueue_style( 'custom', get_template_directory_uri() . '/includes/css/custom.css' );


  /*^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
   *  js
   *^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^*/

  //load custom scripts to footer
  wp_enqueue_script('scripts-bot', get_template_directory_uri() . '/includes/js/scripts.js',array(),false,true);

  // load captcha
  // wp_enqueue_script('scripts-bot',get_template_directory_uri() . '/includes/js/scripts.js',array(),false,true);

}
add_action( 'wp_enqueue_scripts', 'mdl_enqueue_scripts' );

?>
