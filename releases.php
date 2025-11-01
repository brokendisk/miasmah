<?php
/*
* Template Name: Releases Page
*
* @package WordPress
*/
	get_header();

	// WP_Query works but there's more overhead so WPCRecordCollection::records_for_type is better

	// $args = array(
	//       //'posts_per_page'        => 10,
	//       //'ignore_sticky_posts'   => true,
	//       'paged'                 => false,
	//       'post_type'             => array('release'),
	//       'post_status'           => 'publish'
	//       //'has_password'          => false
	//     );

 	// $the_query = new WP_Query( $args );

	$releases = WPCRecordCollection::records_for_type('release')->filter("post_status", "publish")->results();

	// date sort $releases
	foreach ($releases as $key => $part) {
       $sort[$key] = strtotime($part->release_date);
  	}
  	array_multisort($sort, SORT_DESC, $releases);

	// Batch cache all release images for better performance (using smaller size for listings)
	$cached_images = batch_cache_morr_images($releases, '400_square');
?>

<main class="releases-all override" role="main" aria-label="Content">

	<div class="view-toggle">
		view options:
		<a href="#" class="covers">covers</a>
		<a href="#" class="list">list</a>
	</div>

	<ul class="cover-view is-active">
	<?php
		foreach ($releases as $release) {
			// Use cached image URL if available, fallback to direct URL
			$morr_cover = isset($cached_images[$release->morr_uuid]) 
				? $cached_images[$release->morr_uuid] 
				: get_cached_morr_image($release->morr_uuid, '400_square');

			// Create a simple placeholder (1x1 transparent pixel)
			$placeholder = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400"><rect width="400" height="400" fill="#777"/></svg>');

			echo 	'<li>
						<a href="' . get_the_permalink( $release->ID ) . '">
							<img src="' . $placeholder . '" 
								 data-src="' . $morr_cover . '" 
								 class="lazy" 
								 alt="' . esc_attr($release->display_artist . ' - ' . $release->title) . '" />
						</a>
					</li>';
		}
	?>
	</ul>

	<ul class="list-view">
    <?php
        foreach ($releases as $release) {

            echo '<li class="">
                    <a href="' . get_the_permalink( $release->ID ) . '">' .  $release->catalog_nr . ' - ' . '<b>' . $release->display_artist . '</b>' . '&nbsp;-&nbsp;' . $release->title . '</a>
                    </li>';
        }
	?>
	</ul>
</main>

<?php get_footer(); ?>