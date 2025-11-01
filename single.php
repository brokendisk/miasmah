<?php get_header(); ?>

	<main role="main" aria-label="Content">
	
		<section>

		<?php if ( have_posts() ) : while (have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( has_post_thumbnail() ) : // Check if Thumbnail exists. ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail(); // Fullsize image for the single post. ?>
					</a>
				<?php endif; ?>
				
				<p class="date">
					<time datetime="<?php the_time( 'd. F Y' ); ?>">
						<?php the_date(); ?>
					</time>
				</p>

				<h1>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				</h1>

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

<?php get_sidebar(); ?>

<?php get_footer(); ?>
