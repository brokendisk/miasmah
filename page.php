<?php get_header(); ?>

<main role="main" aria-label="Content">

	<section>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>

		<?php else : ?>
			<article>
				<h1><?php esc_html_e( 'Sorry, nothing to display.' ); ?></h1>
			</article>
		<?php endif; ?>

	</section>

</main>

<?php get_footer(); ?>