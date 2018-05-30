<?php
/*
 * header
 */

?>
<!DOCTYPE html>
<!--

http://danfisher.io
http://thedngrzone.com
http://thedangerzone.io

-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<title>UFC</title>

	<!-- Add to homescreen for Chrome on Android -->
	<meta name="mobile-web-app-capable" content="yes">

	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="UFC">


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
		<div class="menu-button-line"></div>
		<div class="menu-button-line"></div>
		<div class="menu-button-line"></div>
	</div>
