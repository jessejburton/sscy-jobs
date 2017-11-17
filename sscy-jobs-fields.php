<?php

function sscy_jobs_responsibilities_and_qualifications( $post ) {
    wp_nonce_field( basename(__FILE__), 'sscy_jobs_nonce' );
    $sscy_jobs_stored_meta = get_post_meta( $post->ID );

    if ( isset( $sscy_jobs_stored_meta['responsibilities'] ) ){
        $responsibilities = $sscy_jobs_stored_meta['responsibilities'][0];
    } else {
        $responsibilities = '';
    }

    ?>
        <div class="sscy_meta_row" id="jobs_responsibilities">
            <?php echo wp_editor( $responsibilities , 'custom_editor_1', array( 'responsibilities' => 'custom_editor_1' ) ); ?>
        </div>
    <?php
}

function sscy_jobs_working_conditions( $post ) {
    wp_nonce_field( basename(__FILE__), 'sscy_jobs_nonce' );
    $sscy_jobs_stored_meta = get_post_meta( $post->ID );

    if ( isset( $sscy_jobs_stored_meta['conditions'] ) ){
        $conditions = $sscy_jobs_stored_meta['conditions'][0];
    } else {
        $conditions = '';
    }    

    ?>
        <div class="sscy_meta_row" id="jobs_responsibilities">
            <?php echo wp_editor( $conditions, 'custom_editor_2', array( 'conditions' => 'custom_editor_2' ) ); ?>
        </div>
    <?php
}

function sscy_jobs_options( $post ) {
    wp_nonce_field( basename(__FILE__), 'sscy_jobs_nonce' );
    $sscy_jobs_stored_meta = get_post_meta( $post->ID );
    if ( isset( $sscy_jobs_stored_meta['apply_url'] ) ){
        $applyURL = $sscy_jobs_stored_meta['apply_url'];
    } else {
        $applyURL = '';
    }

    ?>
        <p>
            <label for="active">
                <input type="checkbox" name="active" id="active" value="yes" <?php if ( isset ( $sscy_jobs_stored_meta['active'] ) ) checked( $sscy_jobs_stored_meta['active'][0], true ); ?> />
                <?php _e( 'Active', 'prfx-textdomain' )?>
            </label>
        </p>

        <p>
            <label for="apply_url">Application Form URL</label><br />
            <input type="text" name="applu_url" id="apply_url" style="width: 100%;" value="<?php echo $applyURL; ?>" placeholder="http://www.saltspringcentre.com" />          
        </p>        
    <?php
}

