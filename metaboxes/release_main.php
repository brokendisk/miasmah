<?php

class release_main extends GenericMetabox {
    function __construct () {
        $this->label = "Release";

        parent::__construct();
    }

    function echo_metabox () {
        parent::echo_metabox();

        $post           = get_post();
        $fields         = $this->content_type->fields;
        $relationships  = $this->content_type->relationships;
    ?>
            <style type="text/css">
                input#wpc_field_title,
                input#wpc_field_subtitle {
                    width: 400px;
                }
                input#wpc_field_time_from,
                input#wpc_field_time_until {
                    width: 80px;
                }
            </style>

            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["morr_uuid"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row">
                <?php $fields["release_status"]->echo_field_with_label_left(); ?><br><br>
            </div>
            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["catalog_nr"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["title"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["display_artist"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row">
                <?php $fields["type"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["edition"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["credits"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["links"]->echo_field_with_label_left(); ?>
            </div>
            <div class="wpc_form_row wpc_form_row_fullwidth">
                <?php $fields["release_date"]->echo_field_with_label_left(); ?>
            </div>

            <div class="wpc_form_row_header">Available formats</div>
            <div class="wpc_form_row available_edition">
                <div class="wpc_form_row">
                    <?php $fields["lp"]->echo_field(); ?>
                    <?php $fields["lp_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["two_lp"]->echo_field(); ?>
                    <?php $fields["two_lp_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["cd"]->echo_field(); ?>
                    <?php $fields["cd_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["two_cd"]->echo_field(); ?>
                    <?php $fields["two_cd_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["cassette"]->echo_field(); ?>
                    <?php $fields["cassette_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["box_set"]->echo_field(); ?>
                    <?php $fields["box_set_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["lp_special_edition"]->echo_field(); ?>
                    <?php $fields["lp_special_edition_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["download"]->echo_field(); ?>
                    <?php $fields["download_link"]->echo_field_with_label_left(); ?>
                </div>
                <div class="wpc_form_row">
                    <?php $fields["seven_inch"]->echo_field(); ?>
                    <?php $fields["seven_inch_link"]->echo_field_with_label_left(); ?>
                </div>
            </div>

            <div class="clear"></div>



<?php
    }

}

?>
