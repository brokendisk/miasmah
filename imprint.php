<?php
/*
* Template Name: Imprint Page
*
* @package WordPress
*/
get_header(); ?>

<main role="main" aria-label="Content">

	<?php if ( have_posts() ) : while (have_posts() ) : the_post(); ?>

		<?php the_content(); ?>

	<?php endwhile; ?>

	<?php else : ?>

		<h1><?php esc_html_e( 'Sorry, nothing to display.' ); ?></h1>

	<?php endif; ?>

</main>

<?php get_footer(); ?>