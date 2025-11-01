<?php
/*
* Template Name: Info Page
*
* @package WordPress
*/
get_header(); ?>

<main class="grid grid--2col override" role="main" aria-label="Content">

	<div class="grid__main">

		<?php if ( have_posts() ) : while (have_posts() ) : the_post(); ?>

			<?php the_content(); ?>

		<?php endwhile; ?>

		<?php else : ?>

			<h1><?php esc_html_e( 'Sorry, nothing to display.' ); ?></h1>

		<?php endif; ?>

	</div>

    <div class="grid__side">

    	<?php
		if ( is_active_sidebar('info-right' ) ){
			dynamic_sidebar('info-right');
		}
		?>

	</div>

</main>

<?php get_footer(); ?>