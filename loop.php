<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<p class="date">
			<time datetime="<?php the_time( 'Y-m-d' ); ?>"><?php
				the_date( 'd. F Y' ); ?></time>
		</p>

		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
		</h2>
		
		<?php the_content(); ?>

	</article>

<?php endwhile; ?>

<?php else : ?>

	<h2><?php esc_html_e( 'Sorry, nothing to display.' ); ?></h2>

<?php endif; ?>



