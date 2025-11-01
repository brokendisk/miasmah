<?php

// theme support
if ( function_exists( 'add_theme_support' ) ) {

    // Add Thumbnail Theme Support.
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'large', 860, '', true ); // Large Thumbnail.
    add_image_size( 'medium', 430, '', true ); // Medium Thumbnail.
    add_image_size( 'small', 130, '', true ); // Small Thumbnail.
    //add_image_size( 'custom-size', 700, 200, true ); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Enable HTML5 support.
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
}


// navigation
function nav() {
    wp_nav_menu(
    array(
        'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'div',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul id="menu_element">%3$s</ul>',
        'depth'           => 0,
        'walker'          => '',
        )
    );
}


// Register webpack compiled js and css with theme
function enqueue_webpack_scripts() {
    
    $cssFilePath = glob( get_template_directory() . '/css/build/main.min.*.css' );
    $cssFileURI = get_template_directory_uri() . '/css/build/' . basename($cssFilePath[0]);
    wp_enqueue_style( 'main_css', $cssFileURI );
    
    $jsFilePath = glob( get_template_directory() . '/js/build/main.min.*.js' );
    $jsFileURI = get_template_directory_uri() . '/js/build/' . basename($jsFilePath[0]);
    wp_enqueue_script( 'main_js', $jsFileURI , null , null , true );

    // Get and localize playlist data on single release pages only
    if (is_singular('release')) {
        $json = morr_get_release();
        $playlist = array();
        
        if (!empty($json)) {
            $track_snippet_url_head = "https://resources.morrmusic.com/track/";
            $track_snippet_url_tail = "/snippet.mp3";
            
            $playlist_data = array_pop($json['playlists']);
            
            foreach ($playlist_data['tracks'] as $track_pointer) {
                $track = $json['tracks'][$track_pointer["uuid_track"]];
                $playlist[] = array(
                    'title' => $track['title'],
                    'mp3' => $track_snippet_url_head . $track['uuid'] . $track_snippet_url_tail
                );
            }
        }

        // Localize the script with the playlist data
        wp_localize_script('main_js', 'morrPlayerData', array(
            'playlist' => $playlist
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_webpack_scripts' );


// Register navigation
function register_menu() {
    register_nav_menus( array( // Using array to specify more menus if needed
        'header-menu'  => esc_html( 'Header Menu' ), // Main Navigation
        'extra-menu'   => esc_html( 'Extra Menu' ) // Extra Navigation if needed
    ) );
}
add_action( 'init', 'register_menu' );


// Remove the <div> surrounding the dynamic navigation to cleanup markup
// function my_wp_nav_menu_args( $args = '' ) {
//     $args['container'] = false;
//     return $args;
// }
// add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' ); // Remove surrounding <div> from WP Navigation


// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter( $var ) {
    return is_array( $var ) ? array() : '';
}
// add_filter( 'nav_menu_css_class', 'my_css_attributes_filter', 100, 1 ); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter( 'nav_menu_item_id', 'my_css_attributes_filter', 100, 1 ); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter( 'page_css_class', 'my_css_attributes_filter', 100, 1 ); // Remove Navigation <li> Page ID's (Commented out by default)


// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class( $classes ) {
    global $post;
    if ( is_home() ) {
        $key = array_search( 'blog', $classes, true );
        if ( $key > -1 ) {
            unset( $classes[$key] );
        }
    } elseif ( is_page() ) {
        $classes[] = sanitize_html_class( $post->post_name );
    } elseif ( is_singular() ) {
        $classes[] = sanitize_html_class( $post->post_name );
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_to_body_class' );


// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function pagination() {
    global $wp_query;
    $big = 999999999;
    echo paginate_links( array(
        'base'    => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
        'format'  => '?paged=%#%',
        'current' => max( 1, get_query_var( 'paged' ) ),
        'total'   => $wp_query->max_num_pages,
    ) );
}
add_action( 'init', 'pagination' );


// Remove Admin bar
// function remove_admin_bar() {
//     return false;
// }
// add_filter( 'show_admin_bar', 'remove_admin_bar' ); // Remove Admin bar


// Morr Music API grab and cache (set_transient) data forr track playlists
// https://css-tricks.com/the-deal-with-wordpress-transients/
function morr_get_release() {

    global $post;
    $r = the_record($post->ID);
    $the_morr_id = $r->morr_uuid;

    //set the transient to the release in question
    $transient = get_transient($the_morr_id);

    if(!empty($transient)) {
        return $transient;
    }

    else {
        // https://cluster-api.morrmusic.com/public/release/7015a633-0159-4215-a39b-d994700318b3
        $url = 'https://cluster-api.morrmusic.com/public/release/' . $the_morr_id . '/json';
        $body = wp_remote_retrieve_body(wp_remote_get($url));
        $json = json_decode($body, true);
        set_transient($the_morr_id, $json, WEEK_IN_SECONDS);
        return $json;
    }

}

// Morr Music Image Caching System
function get_cached_morr_image($morr_uuid, $size = '1000_square') {
    // Create cache directory if it doesn't exist
    $upload_dir = wp_upload_dir();
    $cache_dir = $upload_dir['basedir'] . '/morr-cache/';
    
    if (!file_exists($cache_dir)) {
        wp_mkdir_p($cache_dir);
    }
    
    // Generate filename based on UUID and size
    $filename = $morr_uuid . '_' . $size . '.jpeg';
    $file_path = $cache_dir . $filename;
    $file_url = $upload_dir['baseurl'] . '/morr-cache/' . $filename;
    
    // Check if cached file exists and is not too old (24 hours)
    if (file_exists($file_path) && (time() - filemtime($file_path)) < DAY_IN_SECONDS) {
        return $file_url;
    }
    
    // Download and cache the image
    $remote_url = "https://resources.morrmusic.com/image/for_primary_product/" . $morr_uuid . "/1/" . $size . ".jpeg";
    
    $response = wp_remote_get($remote_url, array(
        'timeout' => 30,
        'headers' => array(
            'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url()
        )
    ));
    
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $image_data = wp_remote_retrieve_body($response);
        
        if (file_put_contents($file_path, $image_data)) {
            return $file_url;
        }
    }
    
    // Fallback to original URL if caching fails
    return $remote_url;
}

// Batch cache Morr Music images for multiple releases
function batch_cache_morr_images($releases, $size = '1000_square') {
    $upload_dir = wp_upload_dir();
    $cache_dir = $upload_dir['basedir'] . '/morr-cache/';
    
    if (!file_exists($cache_dir)) {
        wp_mkdir_p($cache_dir);
    }
    
    $cached_urls = array();
    
    foreach ($releases as $release) {
        if (!empty($release->morr_uuid)) {
            $cached_urls[$release->morr_uuid] = get_cached_morr_image($release->morr_uuid, $size);
        }
    }
    
    return $cached_urls;
}

// Clear Morr Music image cache
function clear_morr_image_cache() {
    $upload_dir = wp_upload_dir();
    $cache_dir = $upload_dir['basedir'] . '/morr-cache/';
    
    if (file_exists($cache_dir)) {
        $files = glob($cache_dir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

// Add admin menu for cache management
function add_morr_cache_admin_menu() {
    add_management_page(
        'Morr Music Cache',
        'Morr Music Cache',
        'manage_options',
        'morr-cache',
        'morr_cache_admin_page'
    );
}
add_action('admin_menu', 'add_morr_cache_admin_menu');

function morr_cache_admin_page() {
    if (isset($_POST['clear_cache'])) {
        clear_morr_image_cache();
        echo '<div class="notice notice-success"><p>Morr Music image cache cleared successfully!</p></div>';
    }
    
    $upload_dir = wp_upload_dir();
    $cache_dir = $upload_dir['basedir'] . '/morr-cache/';
    $cache_size = 0;
    $file_count = 0;
    
    if (file_exists($cache_dir)) {
        $files = glob($cache_dir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                $cache_size += filesize($file);
                $file_count++;
            }
        }
    }
    
    ?>
    <div class="wrap">
        <h1>Morr Music Image Cache</h1>
        <p>Cache Status: <?php echo $file_count; ?> files, <?php echo size_format($cache_size); ?></p>
        <form method="post">
            <input type="submit" name="clear_cache" class="button button-secondary" value="Clear Cache" onclick="return confirm('Are you sure you want to clear the cache?');">
        </form>
    </div>
    <?php
}


// If Dynamic Sidebar Exists
if ( function_exists( 'register_sidebar' ) ) {
    // Define Sidebar Widget Area 1
    register_sidebar( array(
        'name'          => esc_html( 'Widget Area 1' ),
        'description'   => esc_html( 'Description for this widget-area...' ),
        'id'            => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );

    // Define Sidebar Widget Area 2
    register_sidebar( array(
        'name'          => esc_html( 'Widget Area 2' ),
        'description'   => esc_html( 'Description for this widget-area...' ),
        'id'            => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
}


// Custom  widgets
register_sidebar( array(
    'name' => 'Footer 1',
    'id' => 'footer-1',
    'description' => 'Appears in the footer area',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
) );

// register_sidebar( array(
//     'name' => 'Footer 2',
//     'id' => 'footer-2',
//     'description' => 'Appears in the footer area',
//     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//     'after_widget' => '</aside>',
//     'before_title' => '<h3 class="widget-title">',
//     'after_title' => '</h3>',
// ) );

// register_sidebar( array(
//     'name' => 'Footer 3',
//     'id' => 'footer-3',
//     'description' => 'Appears in the footer area',
//     'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//     'after_widget' => '</aside>',
//     'before_title' => '<h3 class="widget-title">',
//     'after_title' => '</h3>',
// ) );

register_sidebar( array(
    'name' => 'Info Right Column',
    'id' => 'info-right',
    'description' => 'Appears on the right column of the info page',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
) );


// Disable auto-update email
// add_filter( 'auto_core_update_send_email', 'wpb_stop_auto_update_emails', 10, 4 );
//   function wpb_stop_update_emails( $send, $type, $core_update, $result ) {
//     if ( ! empty( $type ) && $type == 'success' ) {
//         return false;
//     }
//     return true;
// }

// Custom permalink structure for release post type
// function custom_release_permalink_structure() {
//     add_rewrite_rule(
//         '^release/([^/]+)/?$',
//         'index.php?post_type=release&name=$matches[1]',
//         'top'
//     );
// }
// add_action('init', 'custom_release_permalink_structure');

// // Flush rewrite rules when theme is activated
// function flush_rewrite_rules_on_activation() {
//     custom_release_permalink_structure();
//     flush_rewrite_rules();
// }
// add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');

// // Force update of release post titles and slugs when they are saved
// function force_update_release_titles_and_slugs($post_id) {
//     if (get_post_type($post_id) === 'release') {
//         // Remove the action temporarily to avoid infinite loop
//         remove_action('save_post', 'force_update_release_titles_and_slugs');
        
//         // Get the post data
//         $post = get_post($post_id);
//         $r = the_record($post_id);
        
//         // Generate new title and slug
//         $new_title = "$r->display_artist - $r->title - $r->catalog_nr";
//         $new_title = trim(preg_replace("/\s+/", " ", $new_title));
        
//         $slug_parts = array();
        
//         if (!empty($r->display_artist)) {
//             $slug_parts[] = sanitize_title($r->display_artist);
//         }
        
//         if (!empty($r->title)) {
//             $slug_parts[] = sanitize_title($r->title);
//         }
        
//         if (!empty($r->catalog_nr)) {
//             $slug_parts[] = sanitize_title($r->catalog_nr);
//         }
        
//         $new_slug = implode('-', $slug_parts);
        
//         // Ensure we have a valid slug
//         if (empty($new_slug)) {
//             $new_slug = sanitize_title($r->title);
//         }
        
//         // Update the post title and slug if they're different
//         $update_data = array('ID' => $post_id);
        
//         if ($post->post_title !== $new_title) {
//             $update_data['post_title'] = $new_title;
//         }
        
//         if ($post->post_name !== $new_slug) {
//             $update_data['post_name'] = $new_slug;
//         }
        
//         if (count($update_data) > 1) { // More than just the ID
//             wp_update_post($update_data);
//         }
        
//         // Re-add the action
//         add_action('save_post', 'force_update_release_titles_and_slugs');
//     }
// }
// add_action('save_post', 'force_update_release_titles_and_slugs');