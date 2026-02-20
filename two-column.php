<?php
/*
* Template Name: Two Column
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
		$right_content = get_post_meta( get_the_ID(), '_right_column_content', true );
		if ( ! empty( $right_content ) ) {
			echo apply_filters( 'the_content', $right_content );
		}
		?>

	</div>

</main>

<?php get_footer(); ?>
