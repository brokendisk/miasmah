<?php

class Release extends GenericContentType {
    function __construct () {
        //$this->supports         = array('title','editor','thumbnail','excerpt');
        $this->supports         = array('title','editor','thumbnail');
        // $this->taxonomies       = array('category', 'post_tag');
        // $this->has_archive      = 'events';
        // $this->auto_publish_from_rel_edit   = true;

        $this->label            = "Releases";
        $this->singular_label   = "Release";

        parent::__construct();


        new TextField       ($this, array('id' => 'title', 'hint' => '', 'required' => true ) );
        new TextField       ($this, array('id' => 'display_artist', 'hint' => '') );
        new TextField       ($this, array('id' => 'catalog_nr', 'hint' => '') );
        new DateField       ($this, array('id' => 'release_date', 'hint' => '2012-02-01', 'default' => ''));
        new ImageField      ($this, array('id' => 'cover') );
        new TextField       ($this, array('id' => 'morr_uuid', 'hint' => '', 'required' => true ) );
        new TextAreaField   ($this, array('id' => 'edition', 'hint' => '') );
        new TextAreaField   ($this, array('id' => 'credits', 'hint' => '') );
        new TextAreaField   ($this, array('id' => 'links', 'hint' => '') );
        new SelectField     ($this, array('id' => 'release_status', 'default' => 'Coming Soon', 'options' => array(
            'Coming Soon',
            'Available',
            'Sold Out'
        )) );
        new CheckBoxField   ($this, array('id' => 'lp', 'label' => 'LP' ) );
        new CheckBoxField   ($this, array('id' => 'two_lp', 'label' => '2LP' ) );
        new CheckBoxField   ($this, array('id' => 'three_lp', 'label' => '3LP' ) );
        new CheckBoxField   ($this, array('id' => 'four_lp', 'label' => '4LP' ) );
        new CheckBoxField   ($this, array('id' => 'cd', 'label' => 'CD' ) );
        new CheckBoxField   ($this, array('id' => 'two_cd', 'label' => '2CD' ) );
        new CheckBoxField   ($this, array('id' => 'cassette', 'label' => 'Cassette' ) );
        new CheckBoxField   ($this, array('id' => 'box_set', 'label' => 'Box Set' ) );
        new CheckBoxField   ($this, array('id' => 'lp_special_edition', 'label' => 'LP Special Edition' ) );
        new CheckBoxField   ($this, array('id' => 'cd_special_edition', 'label' => 'CD Special Edition' ) );
        new CheckBoxField   ($this, array('id' => 'digital', 'label' => 'Digital' ) );
        new CheckBoxField   ($this, array('id' => 'seven_inch', 'label' => '7 inch' ) );

        new TextField   ($this, array('id' => 'lp_link', 'label' => 'LP Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'two_lp_link', 'label' => '2LP Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'three_lp_link', 'label' => '3LP Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'four_lp_link', 'label' => '4LP Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'cd_link', 'label' => 'CD Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'two_cd_link', 'label' => '2CD Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'cassette_link', 'label' => 'Cassette Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'box_set_link', 'label' => 'Box Set Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'lp_special_edition_link', 'label' => 'LP Special Edition Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'cd_special_edition_link', 'label' => 'CD Special Edition Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'digital_link' , 'label' => 'Digital Link' , 'hint' => '') );
        new TextField   ($this, array('id' => 'seven_inch_link', 'label' => '7" Link' , 'hint' => '') );

        // new FileField       ($this, array('id' => 'download' ) );
        
        // Set title to whatever: like Artist - Title - Cat Nr...
        new GeneratedValue  ($this, array('id' => 'post_title'), function ($value, $r) {
                $value = "$r->display_artist - $r->title";

                return trim(preg_replace("/\s+/", " ", $value));
            }
        );

        new GeneratedValue  ($this, array('id' => 'post_name'), function ($value, $r) {
                // Create slug from artist, title, and catalog number
                $slug_parts = array();
                
                if (!empty($r->display_artist)) {
                    $slug_parts[] = sanitize_title($r->display_artist);
                }
                
                if (!empty($r->title)) {
                    $slug_parts[] = sanitize_title($r->title);
                }
                
                $value = implode('-', $slug_parts);
                
                // Ensure we have a valid slug
                if (empty($value)) {
                    $value = sanitize_title($r->title);
                }

                return $value;
            }
        );

        // new CheckBoxField   ($this, array('id' => 'lang_de', 'label' => 'German' ) );
        // new CheckBoxField   ($this, array('id' => 'lang_en', 'label' => 'English' ) );
        // new CheckBoxField   ($this, array('id' => 'lang_pt', 'label' => 'Portuguese' ) );
        // new CheckBoxField   ($this, array('id' => 'lang_es', 'label' => 'Spanish' ) );

        // new TextField       ($this, array('id' => 'price', 'hint' => '12€') );
        // new RichtextField   ($this, array('id' => 'disclaimer'           , 'hint' => '', 'label' => 'Disclaimer', 'dont_auto_echo_metabox' => true) );

        // add_filter( 'manage_edit-event_columns',        array($this, 'set_custom_edit_columns') );
        // add_action( 'manage_event_posts_custom_column', array($this, 'custom_column'), 10, 2 );

        // new GeneratedValue  ($this, array('id' => 'post_title'), function ($value, $r) {
        //         $value = (!empty($r->date_from) ? strftime("%Y.%m.%d – ", strtotime($r->date_from) ) : "")."$r->title";

        //         return trim(preg_replace("/\s+/", " ", $value));
        //     }
        // );

        // new FormattedString ($this, array('id' => 'timestamp'), function ($value, $r) {
        //         $value = 0;

        //         switch ($r->duration_type) {
        //             case 'on one day':
        //                 $value = strtotime("$r->date_from ".(!empty($r->time_from) ? $r->time_from : "00:00") );
        //                 break;
        //             case 'over a whole day':
        //             case 'over multiple days':
        //                 $value = strtotime("$r->date_from 00:00");
        //                 break;
        //         }

        //         return $value;
        //     }
        // );

        // new FormattedString  ($this, array('id' => 'timestamp_end'), function ($value, $r) {
        //         $value = 1;

        //         switch ($r->duration_type) {
        //             case 'on one day':
        //                 $value = !empty($r->time_until) ? strtotime("$r->date_from $r->time_until") : intVal(strtotime("$r->date_from $r->time_from"))+3600;
        //                 break;
        //             case 'over a whole day':
        //                 $value = strtotime("$r->date_from 0:00 + 1 day");
        //                 break;
        //             case 'over multiple days':
        //                 $value = strtotime("$r->date_until 23:59");
        //                 break;
        //         }

        //         return $value;
        //     }
        // );
        // new GeneratedValue  ($this, array('id' => 'generated_date'), function ($value, $r) {
        //         $rd = $r->timestamp;

        //         if ( !empty($rd) ){
        //             $value = strftime("%F %T", $rd);
        //         } else {
        //             $value = strftime("%F %T");
        //         }

        //         return $value;
        //     }
        // );
        // new GeneratedValue  ($this, array('id' => 'generated_date_until'), function ($value, $r) {
        //         $rd = $r->timestamp_end;

        //         if ( !empty($rd) ){
        //             $value = strftime("%F %T", $rd);
        //         } else {
        //             $value = strftime("%F %T");
        //         }

        //         return $value;
        //     }
        // );
        // new GeneratedValue  ($this, array('id' => 'post_name'), function ($value, $r) {
        //         $value = "$r->title";

        //         return trim(preg_replace("/\s+/", " ", $value));
        //     }
        // );
        // new FormattedString  ($this, array('id' => 'where'), function ($value, $r) {
        //         $value = "";

        //         $the_locations = array();
        //         $locations = $r->connected_event_location->iterate();

        //         while ($location = $locations->next()){
        //             array_push($the_locations, "$location->link_google_maps");
        //         }

        //         return join($the_locations, "<br>");
        //     }
        // );
        // new FormattedString  ($this, array('id' => 'where_long'), function ($value, $r) {
        //         $the_locations = array();
        //         $locations = $r->connected_event_location->iterate();

        //         while ($location = $locations->next()){
        //             $link_string = "<i>$location->name</i>";
        //             $link_string .= "<br>$location->street $location->number";
        //             if ( !empty($location->street_supplement) ) $link_string .= "<br>$location->street_supplement";
        //             if ( !empty($location->postal_code) || !empty($location->city) ) $link_string .= "<br>$location->postal_code $location->city";
        //             $link_string .= "<br><span class='link-google-maps'>$location->link_google_maps_noname</span>";

        //             array_push($the_locations, $link_string);
        //         }

        //         return join($the_locations, "<br>");
        //     }
        // );
        // new FormattedString  ($this, array('id' => 'where_long_plain'), function ($value, $r) {
        //         $location = $r->connected_event_location->first_record();

        //         $link_string = "$location->name";
        //         $link_string .= " | $location->street $location->number";
        //         if ( !empty($location->street_supplement) ) $link_string .= " | $location->street_supplement";
        //         if ( !empty($location->postal_code) || !empty($location->city) ) $link_string .= " | $location->postal_code $location->city";

        //         return $link_string;
        //     }
        // );
        // new FormattedString  ($this, array('id' => 'languages'), function ($value, $r) {
        //         $value = array();

        //         if ( !empty($r->lang_de) ) array_push($value, "deutsch");
        //         if ( !empty($r->lang_en) ) array_push($value, "englisch");
        //         if ( !empty($r->lang_es) ) array_push($value, "spanisch");
        //         if ( !empty($r->lang_pt) ) array_push($value, "portugiesisch");

        //         return $value;
        //     }
        // );

        // new FormattedString  ($this, array('id' => 'when'), function ($value, $r) {
        //         $value = "";

        //         $date_from      = strftime("<span class='dayname'>%a</span>, <span class='daynum'>%d</span>.<span class='monthname'>%m</span>.<span class='year'>%Y</span>", strtotime($r->date_from) );
        //         $date_until     = strftime("<span class='dayname'>%a</span>, <span class='daynum'>%d</span>.<span class='monthname'>%m</span>.<span class='year'>%Y</span>", strtotime($r->date_until) );

        //         if ($r->duration_type == 'on one day')             $value = "$date_from / $r->time_from" . ( !empty($r->time_until) ? " – $r->time_until" : "");
        //         elseif ($r->duration_type == 'over a whole day')   $value = "$date_from";
        //         elseif ($r->duration_type == 'over multiple days') $value = "$date_from " . ( !empty($date_until) ? " – $date_until" : "");

        //         return trim(preg_replace("/\s+/", " ", $value));
        //     }
        // );

        // new FormattedString  ($this, array('id' => 'when_short'), function ($value, $r) {
        //         $value = "";

        //         $date_from      = strftime("<span class='daynum'>%d</span>.<span class='monthname'>%m</span>.<span class='year'>%Y</span>", strtotime($r->date_from) );
        //         $date_until     = strftime("<span class='daynum'>%d</span>.<span class='monthname'>%m</span>.<span class='year'>%Y</span>", strtotime($r->date_until) );

        //         if ($r->duration_type == 'on one day')             $value = "$r->time_from" . ( !empty($r->time_until) ? " – $r->time_until" : "") . " / $date_from";
        //         elseif ($r->duration_type == 'over a whole day')   $value = "$date_from";
        //         elseif ($r->duration_type == 'over multiple days') $value = "$date_from " . ( !empty($date_until) ? " – $date_until" : "");


        //         return trim(preg_replace("/\s+/", " ", $value));
        //     }
        // );


        // new FormattedString  ($this, array('id' => 'when_without_time', 'label' => 'when without time'), function ($value, $r) {
        //         $value      = "";
        //         $dateString = "<\s\p\a\\n \c\l\a\s\s='\d\a\\t\e-\d\a\y'>j.</\s\p\a\\n><\s\p\a\\n \c\l\a\s\s='\d\a\\t\e-\m\o\\n\\t\h'>n.</\s\p\a\\n><\s\p\a\\n \c\l\a\s\s='\d\a\\t\e-\y\e\a\\r'>Y</\s\p\a\\n>";

        //         switch ($r->duration_type) {
        //             case 'on one day':
        //                 $value = "<span class='date-from one-day'>".date($dateString, $r->timestamp) ."</span>";
        //                 break;
        //             case 'over a whole day':
        //                 $value = "<span class='date-from whole-day'>".date($dateString, strtotime($r->date_from)) ."</span>";
        //                 break;
        //             case 'over multiple days':
        //                 $value = "<span class='date-from multiple-days'>".date($dateString, strtotime($r->date_from)) ."</span> - ". date($dateString, strtotime($r->date_until));
        //                 break;
        //             case 'over a semester':
        //                 switch ($r->semester) {
        //                     case 'summer semester':
        //                         $value = "<span class='date-from over-semester'>Summer semester $r->semester_year</span>";
        //                         break;

        //                     default:
        //                         $value = "<span class='date-from over-semester'>Winter semester $r->semester_year/".($r->semester_year+1)."</span>";
        //                         break;
        //                 }
        //                 break;
        //         }

        //         return $value;
        //     }
        // );

        // new FormattedString  ($this, array('id' => 'when_without_date', 'label' => 'when without date'), function ($value, $r) {
        //         $value      = "";
        //         $dateString = "";

        //         switch ($r->duration_type) {
        //             case 'on one day':
        //                 $value = "<span class='date-time-from'>$r->time_from</span>".(!empty($r->time_until) ? "<span class='date-time-until'> - $r->time_until</span>" : "");
        //                 break;
        //             case 'over a whole day':
        //                 $value = "";
        //                 break;
        //             case 'over multiple days':
        //                 $value = "<span class='date-from multiple-days'>".date($dateString, strtotime($r->date_from)) ."</span> - ". date($dateString, strtotime($r->date_until));
        //                 break;
        //         }

        //         return trim($value, " \t\n\r\0\x0B-");
        //     }
        // );
    }

    function admin_init () {
        parent::admin_init();
    }

    // function set_custom_edit_columns($columns) {
    //     unset($columns['date']);
    //     unset($columns['tags']);

    //     $columns = $columns + array(
    //         'date_from'  => 'Wann',
    //         'type'    => 'Type',
    //         'date'    => 'Date'
    //       );

    //     return $columns;
    // }

    // function custom_column( $column, $post_id ) {
    //     $r = the_record($post_id);

    //     switch ( $column ) {
    //          case 'type':
    //             echo $r->type;
    //             break;
    //         case 'date_from':
    //             echo $r->when_short;
    //             break;
    //     }
    // }

}

 ?>
