<?php

/*****************************************************************
 * Creates custom WordPress Premium Google News Plugin meta boxes,
 * added to posts/pages and custom $post_types,
 ****************************************************************/

function bgnp_meta_boxes_add() {
	// Loop through and add meta boxes
	$post_types = get_post_types();
	foreach ( $post_types as $post_type ) {
		add_meta_box( 'bgnp_metabox', __( 'Google News Editors’ Picks', 'google-news-editors-picks-feeds' ), 'bgnp_add_meta_boxes', $post_type, 'normal' );
	}
}
add_action('add_meta_boxes', 'bgnp_meta_boxes_add');

/*****************************************************************
 * Callback outputs WordPress Premium Google News Plugin meta box,
 * including js meta box tab for Google News XML Sitemap settings,
 * js meta box tab for Standout Tag PLUS Tag Tracker,
 * js meta box tab for Editors’ Picks RSS Feeds w/ custom desc.
 ****************************************************************/

function bgnp_add_meta_boxes() {
	global $post;
	
	$post_id = $post;
	if (is_object($post_id)){
		$post_id = $post_id->ID;
	}

	$bgnp_rss_feed = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_bgnp_rss_feed', true)));
    	$bgnp_sec_feed = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_bgnp_sec_feed', true)));
    	$bgnp_rss_meta = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_bgnp_rss_meta', true)));
    	
    	/******
 	 * Include required files for qTip help strings for meta box support
 	 ***/
	require_once( bgnp_PATH . '/includes/bgnp-help.php' );
    
    	if($post->post_type!='page'){ 
    	echo '<input type="hidden" name="bgnp_noncename" id="bgnp_noncename" value="' . wp_create_nonce( bgnp_BASENAME ) . '" />';}
?>
<div class="bgnp-metabox-tabs-div">
	<div class="bgnp-tab-panel" id="bgnp_feed">
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="meta-checkbox"><?php _e( 'Homepage RSS :', 'google-news-editors-picks-feeds' )?></label>
					<?php echo '<img src="' . bgnp_URL . '/images/bgnp-question-mark.png" class="alignright bgnp_meta_help" alt="' . esc_attr($meta_field_help['rss_feed_help']) . '">'; ?>
				</th>
				<td>
					<input type="checkbox" name="bgnp_rss_feed" <?php if ($bgnp_rss_feed) echo 'checked=\"checked\"'; ?> value="true" />
					<span> Checking excludes article from the main homepage RSS Feed.</span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="meta-checkbox"><?php _e( 'Section-Based RSS:', 'google-news-editors-picks-feeds' )?></label>
					<?php echo '<img src="' . bgnp_URL . '/images/bgnp-question-mark.png" class="alignright bgnp_meta_help" alt="' . esc_attr($meta_field_help['sec_feed_help']) . '">'; ?>
				</th>
				<td>
					<input type="checkbox" name="bgnp_sec_feed" <?php if ($bgnp_sec_feed) echo 'checked=\"checked\"'; ?> value="true" />
					<span> Checking excludes article from the section-based RSS Feed.</span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="meta-textarea"><?php _e( 'Meta Description:', 'google-news-editors-picks-feeds') ?></label>
					<?php echo '<img src="' . bgnp_URL . '/images/bgnp-question-mark.png" class="alignright bgnp_meta_help" alt="' . esc_attr($meta_field_help['meta_help']) . '">'; ?>
				</th>
				<td>
					<input id= "bgnp_rss_meta" value="<?php echo $bgnp_rss_meta ?>" type="text" name="bgnp_rss_meta" maxlength="156" size="140"/></textarea>
				</td>
			</tr>
		</table>
	</div>
</div>
   <?php
}

/******************************************************************
 * Save options for meta fields Premium Google News Plugin meta box
 *****************************************************************/

function bgnp_save_postdata( $post_id ) {
  	// verify nonce
  	if ( !wp_verify_nonce( $_POST['bgnp_noncename'], bgnp_BASENAME )) {
    	return $post_id;
  	}
  	// prevent saving while WP auto saving
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	return $post_id; 
	// Kill the php script if the user does not have edit permissions
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	$bgnp_rss_feed	= $_POST['bgnp_rss_feed'];
	$bgnp_sec_feed	= $_POST['bgnp_sec_feed'];
	$bgnp_rss_meta	= $_POST['bgnp_rss_meta'];

	delete_post_meta($post_id, '_bgnp_rss_feed');
	delete_post_meta($post_id, '_bgnp_sec_feed');
	delete_post_meta($post_id, '_bgnp_rss_meta');

	add_post_meta($post_id, '_bgnp_rss_feed', $bgnp_rss_feed);
	add_post_meta($post_id, '_bgnp_sec_feed', $bgnp_sec_feed);
	add_post_meta($post_id, '_bgnp_rss_meta', $bgnp_rss_meta);
}
add_action('save_post', 'bgnp_save_postdata');
add_action('publish_post', 'bgnp_save_postdata');

/****************************************************************************
 * Enqueue WordPress Premium Google News Plugin meta box style(s) & script(s)
 ***************************************************************************/

function bgnp_enqueue_styles_scripts() {
	global $pagenow;
	if ( ! in_array( $pagenow, array( 'post-new.php', 'post.php', 'edit.php' ), true )) {
		return;
	}
		wp_enqueue_style( 'bgnp-meta-box-tabs' );
		
		// Enqueue Google News Plugin meta box script
		wp_enqueue_script( 'qtip.2.2.min', bgnp_URL . '/js/qtip.2.2.min.js', bgnp_FILE, array( 'jquery' ), bgnp_VERSION, true );
		wp_enqueue_script( 'bgnp-meta-box', bgnp_URL . '/js/bgnp-meta-box.js', bgnp_FILE, array( 'jquery' ), bgnp_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'bgnp_enqueue_styles_scripts' );
?>