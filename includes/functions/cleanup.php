<?php

// case insensitive URLs
function case_insensitive_url() {
  if (preg_match('/[A-Z]/', $_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = strtolower($_SERVER['REQUEST_URI']);
    $_SERVER['PATH_INFO']   = strtolower($_SERVER['PATH_INFO']);
  }
}
add_action('init', 'case_insensitive_url');

// clean up exercpt -- change '[...]' to '...'
function clean_up_excerpt( $more ) {
     return '...';
}
add_filter('excerpt_more', 'clean_up_excerpt');

// remove admin bar for non admins
function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}
add_action('after_setup_theme', 'remove_admin_bar');

// on login, redirect user to home page if not admin
function ufc_login_redirect( $redirect_to, $request, $user  ) {
    return (isset($user->roles) && is_array($user->roles) && in_array('administrator', $user->roles)) ? admin_url() : site_url();
}
add_filter( 'login_redirect', 'ufc_login_redirect', 10, 3 );

?>
