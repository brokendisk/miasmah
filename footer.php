			<footer class="footer" role="contentinfo">
                
                <!-- render favicon here -->
                <img src="<?php echo get_template_directory_uri(); ?>/img/favicon.svg" alt="Miasmah Recordings" width="100" height="100">

				<div id="footer-sidebar1">
				<?php
				if ( is_active_sidebar('footer-1' ) ){
					dynamic_sidebar('footer-1');
				}
				?>
				</div>
				<div id="footer-sidebar2">
				<?php
				if ( is_active_sidebar('footer-2' ) ){
					dynamic_sidebar('footer-2');
				}
				?>
				</div>
				<div id="footer-sidebar3">
				<?php
				if( is_active_sidebar('footer-3' ) ){
					dynamic_sidebar('footer-3');
				}
				?>
				
			</footer>

		</div><!-- container -->

		<div class="overlay"></div>

		<?php wp_footer(); ?>

	</body>
</html>
