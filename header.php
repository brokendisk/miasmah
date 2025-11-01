<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<title><?php wp_title( '' ); ?><?php if ( wp_title( '', false ) ) { echo ' : '; } ?><?php bloginfo( 'name' ); ?></title>

		<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/img/favicon.ico" rel="shortcut icon">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<div class="container">

			<header class="header" role="banner">
				<div class="logo">
					<a href="<?php echo esc_url( home_url() ); ?>">Miasmah <span>recordings</span></a>
				</div>

				<button class="hamburger hamburger--spring-r" type="button">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</button>

				<nav class="nav" role="navigation">

					<?php nav(); ?>

					<?php get_template_part( 'searchform' ); ?>

				</nav>
			</header>
