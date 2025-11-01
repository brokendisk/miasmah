<?php
// Template Name: Release Importer

// Include WordPress core functions
require_once(ABSPATH . 'wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/post.php');

// Testing Mode First
$test = true;

// Path to JSON file
$json_file = get_template_directory() . '/data/miasmah-release-data.json';

// heck if file exists
if (!file_exists($json_file)) {
    die('JSON file not found at: ' . $json_file);
}

// Convert our JSON into a PHP Array
$data = json_decode(file_get_contents($json_file), true);

if ($data) {
    if ($test) {  // Testing mode simply outputs the data onto the screen to verify
        foreach ($data as $release) {
            if (isset($release['metadata'])) {
                $metadata = $release['metadata'];
                $format = $release['formats'];
                // Find first non-empty catalog_nr and release_date from formats
                $catalog_nr = null;
                $release_date = null;
                if (!empty($format) && is_array($format)) {
                    foreach ($format as $f) {
                        if (!empty($f['catalog_nr']) && $catalog_nr === null) {
                            $catalog_nr = $f['catalog_nr'];
                        }
                        if (!empty($f['release_date']) && $release_date === null) {
                            $release_date = $f['release_date'];
                        }
                        if ($catalog_nr !== null && $release_date !== null) break;
                    }
                }
                echo '<div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">';
                echo 'Morr UUID: ' . $metadata['uuid'] . '<br>';
                echo 'Title: ' . $metadata['title'] . '<br>';
                echo 'Artist: ' . $metadata['display_artist'] . '<br>';
                echo 'Catalog Nr: ' . $format[0]['catalog_nr'] . '<br>';
                echo 'Release Date: ' . $format[0]['release_date'] . '<br>';
                $formats_arr = array();
                foreach ($format as $f) {
                    if (!empty($f['format_string'])) {
                        $formats_arr[] = $f['format_string'];
                    } elseif (!empty($f['status'])) {
                        if (strtolower($f['status']) === 'digital only') {
                            $formats_arr[] = 'Digital';
                        } else {
                            $formats_arr[] = $f['status'];
                        }
                    } else {
                        $formats_arr[] = 'Unknown';
                    }
                }
                echo 'Formats: ' . implode(', ', $formats_arr) . '<br>';
                echo 'Description: ' . $metadata['info_en'] . '<br>';
                echo '</div>';
            }
        }
    } else { // Live mode actually inserts the posts into our database
        foreach ($data as $release) {
            if (isset($release['metadata'])) {
                $metadata = $release['metadata'];
                $format = $release['formats'];
                
                // Find first non-empty catalog_nr and release_date from formats
                $catalog_nr = null;
                $release_date = null;
                if (!empty($format) && is_array($format)) {
                    foreach ($format as $f) {
                        if (!empty($f['catalog_nr']) && $catalog_nr === null) {
                            $catalog_nr = $f['catalog_nr'];
                        }
                        if (!empty($f['release_date']) && $release_date === null) {
                            $release_date = $f['release_date'];
                        }
                        if ($catalog_nr !== null && $release_date !== null) break;
                    }
                }
                
                
                // Use just the title - the GeneratedValue will handle adding the artist
                $post_title = !empty($metadata['title']) ? $metadata['title'] : 'Untitled Release';
                
                
                // Ensure we have valid content
                $post_content = !empty($metadata['info_en']) ? $metadata['info_en'] : 'No description available.';
                
                $post_arr = array(
                    'post_title' => $post_title,
                    'post_type' => 'release',
                    'post_status' => 'publish',
                    'post_content' => $post_content,
                    'meta_input' => array(
                        'display_artist' => $metadata['display_artist'],
                        'uuid' => $metadata['uuid']
                    )
                );
                
                // Check if post already exists
                $existing_post = get_page_by_title($post_title, OBJECT, 'release');
                
                if (!$existing_post) {
                    $post_id = wp_insert_post($post_arr, true);
                    if (is_wp_error($post_id)) {
                        echo 'Error creating release "' . $post_title . '": ' . $post_id->get_error_message() . '<br>';
                        continue; // Skip to next release
                    } else {
                        echo 'Created release: ' . $post_title . '<br>';
                        
                        // Let the GeneratedValue handle the permalink generation
                        // We'll trigger it by updating the post after the custom fields are set
                        echo 'Permalink will be generated by the system after custom fields are saved<br>';
                        
                        // Force update the title if it was changed by hooks
                        $update_result = wp_update_post(array(
                            'ID' => $post_id,
                            'post_title' => $post_title
                        ));
                        
                        if (is_wp_error($update_result)) {
                            echo 'Error updating post title: ' . $update_result->get_error_message() . '<br>';
                        }
                        
                        // If WordPress hooks are still interfering, force direct database update
                        global $wpdb;
                        $direct_update = $wpdb->update(
                            $wpdb->posts,
                            array('post_title' => $post_title),
                            array('ID' => $post_id)
                        );
                        
                        if ($direct_update === false) {
                            echo 'Direct database update failed: ' . $wpdb->last_error . '<br>';
                        }
                        
                        // Check if wp_wpc_release entry already exists for this post_id
                        global $wpdb;
                        $existing_release = $wpdb->get_row($wpdb->prepare(
                            "SELECT * FROM {$wpdb->prefix}wpc_release WHERE post_id = %d",
                            $post_id
                        ));
                        
                        if (!$existing_release) {
                            // Insert new entry into wp_wpc_release
                            $wpdb->insert(
                                $wpdb->prefix . 'wpc_release',
                                array(
                                    'post_id' => $post_id,
                                    'title' => $post_title,
                                    'display_artist' => $metadata['display_artist'],
                                    'catalog_nr' => $catalog_nr,
                                    'release_date' => $release_date,
                                    'morr_uuid' => $metadata['uuid'],
                                )
                            );
                            if ($wpdb->last_error) {
                                echo 'Error inserting into wp_wpc_release: ' . $wpdb->last_error . '<br>';
                            } else {
                                echo 'Inserted into wp_wpc_release table<br>';
                                
                                // Trigger the GeneratedValue functions by simulating a post save
                                global $wpc_content_types;
                                if (isset($wpc_content_types['release'])) {
                                    $release_type = $wpc_content_types['release'];
                                    
                                    // Get the post data
                                    $post = get_post($post_id);
                                    $postmeta = array();
                                    
                                    // Simulate the post meta data
                                    $postmeta['title'] = $metadata['title'];
                                    $postmeta['display_artist'] = $metadata['display_artist'];
                                    $postmeta['catalog_nr'] = $catalog_nr;
                                    $postmeta['release_date'] = $release_date;
                                    $postmeta['morr_uuid'] = $metadata['uuid'];
                                    
                                    // Call the update_post method to trigger GeneratedValue functions
                                    $release_type->update_post($post_id, $post, $postmeta);
                                    
                                    echo 'Triggered GeneratedValue functions for title and permalink<br>';
                                }
                            }
                        } else {
                            // Check if existing entry has null values and update if needed
                            $needs_update = false;
                            $update_data = array();
                            
                            if (empty($existing_release->catalog_nr) && !empty($catalog_nr)) {
                                $update_data['catalog_nr'] = $catalog_nr;
                                $needs_update = true;
                            }
                            if (empty($existing_release->release_date) && !empty($release_date)) {
                                $update_data['release_date'] = $release_date;
                                $needs_update = true;
                            }
                            if (empty($existing_release->title) && !empty($post_title)) {
                                $update_data['title'] = $post_title;
                                $needs_update = true;
                            }
                            if (empty($existing_release->display_artist) && !empty($metadata['display_artist'])) {
                                $update_data['display_artist'] = $metadata['display_artist'];
                                $needs_update = true;
                            }
                            if (empty($existing_release->morr_uuid) && !empty($metadata['uuid'])) {
                                $update_data['morr_uuid'] = $metadata['uuid'];
                                $needs_update = true;
                            }
                            
                            if ($needs_update) {
                                $wpdb->update(
                                    $wpdb->prefix . 'wpc_release',
                                    $update_data,
                                    array('post_id' => $post_id)
                                );
                                if ($wpdb->last_error) {
                                    echo 'Error updating wp_wpc_release: ' . $wpdb->last_error . '<br>';
                                } else {
                                    echo 'Updated wp_wpc_release entry for post_id: ' . $post_id . '<br>';
                                    
                                    // Trigger the GeneratedValue functions by simulating a post save
                                    global $wpc_content_types;
                                    if (isset($wpc_content_types['release'])) {
                                        $release_type = $wpc_content_types['release'];
                                        
                                        // Get the post data
                                        $post = get_post($post_id);
                                        $postmeta = array();
                                        
                                        // Simulate the post meta data
                                        $postmeta['title'] = $metadata['title'];
                                        $postmeta['display_artist'] = $metadata['display_artist'];
                                        $postmeta['catalog_nr'] = $catalog_nr;
                                        $postmeta['release_date'] = $release_date;
                                        $postmeta['morr_uuid'] = $metadata['uuid'];
                                        
                                        // Call the update_post method to trigger GeneratedValue functions
                                        $release_type->update_post($post_id, $post, $postmeta);
                                        
                                        echo 'Triggered GeneratedValue functions for title and permalink<br>';
                                    }
                                }
                            } else {
                                echo 'wp_wpc_release entry already exists and is complete for post_id: ' . $post_id . '<br>';
                            }
                        }
                    }
                } else {
                    echo 'Release already exists: ' . $post_title . '<br>';
                }
            }
        }
        
        // Update existing posts that might not have permalinks
        echo '<br><h3>Updating existing posts without permalinks...</h3>';
        $posts_without_slugs = get_posts(array(
            'post_type' => 'release',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => array(
                array(
                    'key' => 'post_name',
                    'value' => '',
                    'compare' => '='
                )
            )
        ));
        
        // Also check for posts with numeric slugs (WordPress default)
        $posts_with_numeric_slugs = get_posts(array(
            'post_type' => 'release',
            'post_status' => 'publish',
            'numberposts' => -1,
            'name' => array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9')
        ));
        
        $posts_to_update = array_merge($posts_without_slugs, $posts_with_numeric_slugs);
        
        foreach ($posts_to_update as $post) {
            $r = the_record($post->ID);
            
            if ($r) {
                // Trigger the GeneratedValue functions by simulating a post save
                global $wpc_content_types;
                if (isset($wpc_content_types['release'])) {
                    $release_type = $wpc_content_types['release'];
                    
                    // Get the post data
                    $post_obj = get_post($post->ID);
                    $postmeta = array();
                    
                    // Simulate the post meta data from the record
                    $postmeta['title'] = $r->title;
                    $postmeta['display_artist'] = $r->display_artist;
                    $postmeta['catalog_nr'] = $r->catalog_nr;
                    $postmeta['release_date'] = $r->release_date;
                    $postmeta['morr_uuid'] = $r->morr_uuid;
                    
                    // Call the update_post method to trigger GeneratedValue functions
                    $release_type->update_post($post->ID, $post_obj, $postmeta);
                    
                    echo 'Updated title and permalink for "' . $post->post_title . '"<br>';
                }
            }
        }
        
    }
} else {
    echo 'Error: Could not parse JSON file';
}
?>