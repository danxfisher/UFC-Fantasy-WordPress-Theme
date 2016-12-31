<?php
/*
 * header
 */

?>
<!DOCTYPE html>
<!--

 _____     ______     __   __     ______     ______     ______
/\  __-.  /\  __ \   /\ "-.\ \   /\  ___\   /\  ___\   /\  == \
\ \ \/\ \ \ \  __ \  \ \ \-.  \  \ \ \__ \  \ \  __\   \ \  __<
 \ \____-  \ \_\ \_\  \ \_\\"\_\  \ \_____\  \ \_____\  \ \_\ \_\
  \/____/   \/_/\/_/   \/_/ \/_/   \/_____/   \/_____/   \/_/ /_/
 ______     ______     __   __     ______
/\___  \   /\  __ \   /\ "-.\ \   /\  ___\
\/_/  /__  \ \ \/\ \  \ \ \-.  \  \ \  __\
  /\_____\  \ \_____\  \ \_\\"\_\  \ \_____\
  \/_____/   \/_____/   \/_/ \/_/   \/_____/
 __    __     ______     _____     __     ______
/\ "-./  \   /\  ___\   /\  __-.  /\ \   /\  __ \
\ \ \-./\ \  \ \  __\   \ \ \/\ \ \ \ \  \ \  __ \
 \ \_\ \ \_\  \ \_____\  \ \____-  \ \_\  \ \_\ \_\
  \/_/  \/_/   \/_____/   \/____/   \/_/   \/_/\/_/


http://thedngrzone.com
http://thedangerzone.io

danger zone media

-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<title><?php echo get_the_title(); ?></title>

	<!-- Add to homescreen for Chrome on Android -->
	<meta name="mobile-web-app-capable" content="yes">
	<!--
	<link rel="icon" sizes="192x192" href="<?php bloginfo( 'template_directory' ); ?>/includes/img/android-desktop.png">
-->
	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="UFC">
	<!--
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo( 'template_directory' ); ?>/includes/img/ios-desktop.png">
	-->

	<!-- Tile icon for Win8 (144x144 + tile color) -->
	<!--
	<meta name="msapplication-TileImage" content="<?php bloginfo( 'template_directory' ); ?>/includes/img/touch/ms-touch-icon-144x144-precomposed.png">
	<meta name="msapplication-TileColor" content="#3372DF">

	<link rel="shortcut icon" href="<?php bloginfo( 'template_directory' ); ?>/includes/img/favicon.png">
	-->

	<!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
	<!--
	<link rel="canonical" href="http://www.example.com/">
	-->

	<?php wp_head(); ?>
</head>
<?php
	global $current_user;
	$current_user = wp_get_current_user();
?>
<body>
	<div id="menu-overlay">
		<div id="logged-in-menu">
			<?php if (is_user_logged_in()) { ?>
				Logged in as <span class="logged-in-user"><?php echo $current_user->user_login ?></span>
			<?php } ?>
		</div>
		<div id="close-menu"></div>
		<nav>
			<ul>
				<li>
					<a href="<?php echo home_url(); ?>">Home</a>
				</li>
				<?php if (is_user_logged_in()) { ?>
				<li>
					<a href="<?php echo get_permalink( get_page_by_title( 'Leaderboard' ) ) ?>">Leaderboard</a>
				</li>
				<li>
					<a href="<?php echo wp_logout_url(); ?>">Log Out</a>
				</li>
				<?php } else { ?>
				<li>
					<a href="<?php echo wp_login_url(); ?>">Log In</a>
				</li>
				<li>
					<a href="<?php echo wp_registration_url(); ?>">Sign Up</a>
				</li>
				<?php } ?>
			</ul>
		</nav>
	</div>
	<div class="menu-button">
		<!-- <a href="<?php echo home_url(); ?>">Home</a> -->
		<div class="menu-button-line"></div>
		<div class="menu-button-line"></div>
		<div class="menu-button-line"></div>
	</div>
