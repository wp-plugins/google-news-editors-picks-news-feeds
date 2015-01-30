<?php
/************************************************************
 * WordPress Premium Google News Plugin supporting functions,
 * Custom Editors’ Picks RSS Feeds and templates
 ***********************************************************/

/*************************************
 * Get Custom Editors’ Picks Feed Date
 ************************************/

function bgnp_rss_date( $timestamp = null ) {
	$timestamp = ($timestamp==null) ? time() : $timestamp;
	echo date(DATE_RSS, $timestamp);
}

/**************************************************
 * Create Custom Editors’ Picks RSS Feed functions,
 * output <dc:creator>Author name</dc:creator> tag, 
 * the_author() function outside the loop.
 *************************************************/

function bgnp_rss_author() {
	global $wpdb;
	$bgnp_author_rss = the_author();
	echo $bgnp_author_rss;
}

/************************************
 * Add Custom Editors’ Picks RSS Feed
 ***********************************/

function bgnp_init_rss_feeds() {
	// Get option to be used as section-based Editors’ Picks RSS feed slug
	$bgnp_sec_feed_cat = get_option('bgnp_sec_feed_cat');
	
	//initialize the homepage and section-based news feeds	
	add_feed( 'editors-picks', 'bgnp_do_rss_feed' );
	add_feed( 'editors-picks-' . $bgnp_sec_feed_cat, 'bgnp_do_sec_feed' );
}
add_filter('init','bgnp_init_rss_feeds');

/*************************************************************
 * Get Custom Homepage Editors’ Picks RSS Feed Meta & Template
 ************************************************************/

function bgnp_do_rss_feed() {
	global $bgnp_version, $wpdb;
	
	$args = array(
		'posts_per_page' => 5,
		'post_type' => 'post',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
			'key' => '_bgnp_rss_feed',
			'value' => 'true',
			)
		)
	);	
	$rss_feed_query = new WP_Query( $args );
	
	$bgnp_rss_feed_desc = stripslashes(get_option('bgnp_rss_feed_desc'));
	$bgnp_rss_feed_title = stripslashes(get_option('bgnp_rss_feed_title'));
	$bgnp_rss_image_url = get_option('bgnp_rss_image_url');
	$bgnp_rss_image_alt = stripslashes(get_option('bgnp_rss_image_alt'));
						
	//initialize the feed	
	include bgnp_PATH . '/includes/bgnp-rss-feed.php';
}

/******************************************************************
 * Get Custom Section-Based Editors’ Picks RSS Feed Meta & Template
 *****************************************************************/

function bgnp_do_sec_feed() {
	global $bgnp_version, $wpdb;
	
	$args = array(
		'posts_per_page' => 5,
		'post_type' => 'post',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
			'key' => '_bgnp_sec_feed',
			'value' => 'true',
			)
		)
	);	
	$sec_feed_query = new WP_Query( $args );
	
	$bgnp_sec_feed_desc = stripslashes(get_option('bgnp_sec_feed_desc'));
	$bgnp_sec_feed_title = stripslashes(get_option('bgnp_sec_feed_title'));
	$bgnp_sec_image_url = get_option('bgnp_sec_image_url');
	$bgnp_sec_image_alt = stripslashes(get_option('bgnp_sec_image_alt'));
				
	//initialize the feed	
	include bgnp_PATH . '/includes/bgnp-sec-feed.php';
}
?>