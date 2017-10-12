<?php

function sscy_jobs_responsibilities_and_qualifications( $post ) {
    wp_nonce_field( basename(__FILE__), 'sscy_jobs_nonce' );
    $sscy_jobs_stored_meta = get_post_meta( $post->ID );

    ?>
        <div class="sscy_meta_row" id="jobs_responsibilities">
            <textarea id="responsibilities" name="responsibilities"><?php echo sanitize_text_field($sscy_jobs_stored_meta['responsibilities'][0]) ?></textarea>
        </div>
    <?php
}

function sscy_jobs_working_conditions( $post ) {
    wp_nonce_field( basename(__FILE__), 'sscy_jobs_nonce' );
    $sscy_jobs_stored_meta = get_post_meta( $post->ID );

    ?>
        <div class="sscy_meta_row" id="jobs_conditions">
            <textarea id="conditions" name="conditions"><?php echo sanitize_text_field($sscy_jobs_stored_meta['conditions'][0]) ?></textarea>
        </div>
    <?php
}

function sscy_jobs_options( $post ) {
    wp_nonce_field( basename(__FILE__), 'sscy_jobs_nonce' );
    $sscy_jobs_stored_meta = get_post_meta( $post->ID );

    ?>
        <div class="sscy_meta_row" id="jobs_is_active">
            <label for="active">
                <input type="checkbox" name="active" id="active" value="yes" <?php if ( isset ( $sscy_jobs_stored_meta['active'] ) ) checked( $sscy_jobs_stored_meta['active'][0], 'yes' ); ?> />
                <?php _e( 'Active', 'prfx-textdomain' )?>
            </label>
        </div>
    <?php
}

function sscy_save_jobs( $post_id ) {
	// Checks save status
	$is_autosave 	= wp_is_post_autosave( $post_id );
	$is_revision 	= wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST['sscy_jobs_nonce'] ) && wp_verify_nonce( $_POST['sscy_jobs_nonce'], basename(__FILE__) ) ) ? 'true' : 'false';	
	$sscy_stored_meta = get_post_meta( $post_id );  // Get the data to know if a file already exists for this post 
		
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		die();
		return;	
	}   

    // Checks for input and saves
    if( isset( $_POST[ 'active' ] ) ) {
        update_post_meta( $post_id, 'active', 'active' );
    } else {
        update_post_meta( $post_id, 'active', 'inactive' );
    }
	
}
add_action( 'save_post', 'sscy_save_jobs' );
