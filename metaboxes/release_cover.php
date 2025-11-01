<?php

class release_cover extends GenericMetabox {
    function __construct () {
        $this->label = "Cover";
        $this->context  = "side";
        $this->priority = "low";

        parent::__construct();
    }

    function echo_metabox () {
        parent::echo_metabox();

        $post           = get_post();
        $fields         = $this->content_type->fields;
        $relationships  = $this->content_type->relationships;
    ?>
            <div class="wpc_form_row">
                <?php $fields["cover"]->echo_field(); ?>
            </div>

            <div class="clear"></div>



<?php
    }

}

?>
