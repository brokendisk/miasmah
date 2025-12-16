<?php
    get_header();

    the_post();

    $r = the_record($post->ID);

    $the_cover = wp_get_attachment_image_src( $attachment_id = $r->cover, 'original' );
    $the_cover = $the_cover[0] ?? $the_cover;
    $the_catalog_nr = $r->catalog_nr;
    $the_release_date = $r->release_date;
    $the_release_date = date("F j Y", strtotime($the_release_date));
    $the_display_artist = $r->display_artist;
    $the_title = $r->title;
    $the_edition = $r->edition;
    $the_credits = $r->credits;
    $the_links = $r->links;
    $the_status = $r->release_status;
    $the_morr_id = $r->morr_uuid;

    $lp = $r->lp;
    $lp_link = $r->lp_link;
    $two_lp = $r->two_lp;
    $two_lp_link = $r->two_lp_link;
    $three_lp = $r->three_lp;
    $three_lp_link = $r->three_lp_link;
    $four_lp = $r->four_lp;
    $four_lp_link = $r->four_lp_link;
    $cd = $r->cd;
    $cd_link = $r->cd_link;
    $two_cd = $r->two_cd;
    $two_cd_link = $r->two_cd_link;
    $cassette = $r->cassette;
    $cassette_link = $r->cassette_link;
    $box_set = $r->box_set;
    $box_set_link = $r->box_set_link;
    $lp_special_edition = $r->lp_special_edition;
    $lp_special_edition_link = $r->lp_special_edition_link;
    $cd_special_edition = $r->cd_special_edition;
    $cd_special_edition_link = $r->cd_special_edition_link;
    $digital = $r->digital;
    $digital_link = $r->digital_link;
    $seven_inch = $r->seven_inch;
    $seven_inch_link = $r->seven_inch_link;

    // Return all vars
    global $wp_query;

    // Release cover grab - use cached image for better performance
    // Available sizes: full.jpeg, 50_square.jpeg, 100_square.jpeg, 200_square.jpeg, 
    // 300_square.jpeg, 400_square.jpeg, 500_square.jpeg, 600_square.jpeg, 
    // 800_square.jpeg, 1000_square.jpeg, 1400_square.jpeg
    $morr_cover = get_cached_morr_image($the_morr_id, '1000_square');

?>

<main class="release-single override" role="main" aria-label="Content">

    <div class="release-detail">
        <hgroup>
            <h2><span class="release-number"><?= $the_catalog_nr ?></span>&nbsp;&nbsp;<span class="release-date"><?= $the_release_date ?></span></h2>
            <h1><?= $the_display_artist ?>&nbsp;-&nbsp;<?= $the_title ?></h1>
            <p>
                <?= $the_edition ?><br>
                <?= $the_credits ?>
            </p>
        </hgroup>
    </div>

    <div class="release-cover">
        <?php
            // Check for CMS cover, otherwise print Morr cover
            if (!empty($the_cover)) {
                echo '<img src="' . $the_cover . '" alt="' . esc_attr($the_display_artist . ' - ' . $the_title) . '" />';
            } else {
                echo '<img src="' . $morr_cover . '" alt="' . esc_attr($the_display_artist . ' - ' . $the_title) . '" />';
            }
        ?>
    </div>

    <div class="release-description">
        <?php the_content(); ?>
        <?= $the_links; ?>
    </div>

    <div class="release-preview">
        
        <div class="release-player">
            <h3>Listen</h3>
            <div id="jquery_jplayer_1" class="jp-jplayer"></div>
            <div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
                <div class="jp-type-single">
                <div class="jp-gui jp-interface">
                    <div class="jp-volume-controls">
                    <button class="jp-mute" role="button" tabindex="0">mute</button>
                    <div class="jp-volume-bar">
                        <div class="jp-volume-bar-value"></div>
                    </div>
                    </div>
                    <div class="jp-controls-holder">
                    <div class="jp-controls">
                        <button class="jp-previous" role="button" tabindex="0">previous</button>
                        <button class="jp-play" role="button" tabindex="0">play</button>
                        <button class="jp-next" role="button" tabindex="0">next</button>
                        <button class="jp-stop" role="button" tabindex="0">stop</button>
                    </div>
                    <div class="jp-progress">
                        <div class="jp-seek-bar">
                        <div class="jp-play-bar"></div>
                        </div>
                    </div>
                    <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                    <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                    </div>
                </div>
                <div class="jp-details">
                    <div class="jp-title" aria-label="title">&nbsp;</div>
                </div>
                <div class="jp-playlist">
                    <ul>
                        <li></li> <!-- Empty <li> so HTML conforms with W3C spec -->
                    </ul>
                </div>
                </div>
            </div>
        </div>

        <div class="release-status">
        <?php

            if ($the_status == "Available") {
                echo '<h3 class="available">Buy</h3>';
            } elseif ($the_status == "Coming Soon" ) {
                echo '<h3 class="coming-soon">Coming Soon</h3>';
            } elseif ($the_status = "Sold Out") {
                echo '<h3 class="sold-out">Sold Out</h3>';
            }

            // LP
            if ( $lp == 1 && !empty($lp_link) ):
                echo '<a href="' . $lp_link . '" target="_blank">
                        &#62;&nbsp;LP
                    </a>';
            elseif ( $lp == 1 && empty($lp_link) ):
                echo '&#62;&nbsp;LP<br>';
            endif;

            // 2LP
            if ( $two_lp == 1 && !empty($two_lp_link) ):
                echo '<a href="' . $two_lp_link . '" target="_blank">
                        &#62;&nbsp;2LP
                    </a>';
            elseif ( $two_lp == 1 && empty($two_lp_link) ):
                echo '&#62;&nbsp;2LP<br>';
            endif;

            // 3LP
            if ( $three_lp == 1 && !empty($three_lp_link) ):
                echo '<a href="' . $three_lp_link . '" target="_blank">
                        &#62;&nbsp;3LP
                    </a>';
            elseif ( $three_lp == 1 && empty($three_lp_link) ):
                echo '&#62;&nbsp;3LP<br>';
            endif;

            // 4LP
            if ( $four_lp == 1 && !empty($four_lp_link) ):
                echo '<a href="' . $four_lp_link . '" target="_blank">
                        &#62;&nbsp;4LP
                    </a>';
            elseif ( $four_lp == 1 && empty($four_lp_link) ):
                echo '&#62;&nbsp;4LP<br>';
            endif;
            
            // CD
            if ( $cd == 1 && !empty($cd_link) ):
                echo '<a href="' . $cd_link . '" target="_blank">
                        &#62;&nbsp;CD
                    </a>';
            elseif ( $cd == 1 && empty($cd_link) ):
                echo '&#62;&nbsp;CD<br>';
            endif;

            // 2CD 
            if ( $two_cd == 1 && !empty($two_cd_link) ):
                echo '<a href="' . $two_cd_link . '" target="_blank">
                        &#62;&nbsp;2CD
                    </a>';
            elseif ( $two_cd == 1 && empty($two_cd_link) ):
                echo '&#62;&nbsp;2CD<br>';
            endif;

            // Cassette
            if ( $cassette == 1 && !empty($cassette_link) ):
                echo '<a href="' . $cassette_link . '" target="_blank">
                        &#62;&nbsp;Cassette
                    </a>';
            elseif ( $cassette == 1 && empty($cassette_link) ):
                echo '&#62;&nbsp;Cassette<br>';
            endif;

            // Box Set
            if ( $box_set == 1 && !empty($box_set_link) ):
                echo '<a href="' . $box_set_link . '" target="_blank">
                        &#62;&nbsp;Box Set
                    </a>';
            elseif ( $box_set == 1 && empty($box_set_link) ):
                echo '&#62;&nbsp;Box Set<br>';
            endif;

            // LP Special Edition
            if ( $lp_special_edition == 1 && !empty($lp_special_edition_link) ):
                echo '<a href="' . $lp_special_edition_link . '" target="_blank">
                        &#62;&nbsp;LP Special Edition
                    </a>';
            elseif ( $lp_special_edition == 1 && empty($lp_special_edition_link) ):
                echo '&#62;&nbsp;LP Special Edition<br>';
            endif;

            // CD Special Edition
            if ( $cd_special_edition == 1 && !empty($cd_special_edition_link) ):
                echo '<a href="' . $cd_special_edition_link . '" target="_blank">
                        &#62;&nbsp;LP Special Edition
                    </a>';
            elseif ( $cd_special_edition == 1 && empty($cd_special_edition_link) ):
                echo '&#62;&nbsp;LP Special Edition<br>';
            endif;

            // Digital
            if ( $digital == 1 && !empty($digital_link) ):
                echo '<a href="' . $digital_link . '" target="_blank">
                        &#62;&nbsp;Digital
                    </a>';
            elseif ( $digital == 1 && empty($digital_link) ):
                echo '&#62;&nbsp;Digital<br>';
            endif;

            // 7inch
            if ( $seven_inch == 1 && !empty($seven_inch_link) ):
                echo '<a href="' . $seven_inch_link . '" target="_blank">
                        &#62;&nbsp;7inch
                    </a>';
            elseif ( $seven_inch == 1 && empty($seven_inch_link) ):
                echo '&#62;&nbsp;7inch<br>';
            endif;

        ?>
            
        </div>

    </div>

</main>

<?php get_footer(); ?>