<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<title><?php wp_title( '' ); ?><?php if ( wp_title( '', false ) ) { echo ' : '; } ?><?php bloginfo( 'name' ); ?></title>

		<?php
		$template_uri = esc_url( get_template_directory_uri() );
		?>
		<link rel="icon" href="<?php echo $template_uri; ?>/img/favicon.svg" type="image/svg+xml">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $template_uri; ?>/img/favicon-32x32.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $template_uri; ?>/img/favicon-180x180.png">
        
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

        <a href="https://miasmah.com/eks/" class="eks-flag">EKS &#060;</a>

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

				<ul class="eks-mobile-link">
					<li><a href="https://miasmah.com/eks/">EKS &#060;</a></li>
				</ul>

				<?php get_template_part( 'searchform' ); ?>

			</nav>
			</header>
