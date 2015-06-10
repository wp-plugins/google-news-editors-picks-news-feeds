<?php
/*
Plugin Name: Google News Editors Picks Feed Generator
Version: 1.6.1
Plugin URI: http://www.googlenewsplugin.com
Donate link: http://www.googlenewsplugin.com
Description: Generates not one but two, highly customizable Google News Editors’ Picks RSS Feeds. Serious news sites should upgrade to the WordPress Premium Google News Plugin (http://www.googlenewsplugin.com) to take advantage of all tools available to Google News Publishers and optimize their site(s) for Google News.
Author: Peoples_Pundit
Author URI: http://www.googlenewsplugin.com
Text Domain: google-news-editors-picks-feeds
Domain Path: /languages/
License: GPL v3

* Copyright (C) 2008-2014, PPD Ventures LLC - ppd.ventures.llc@gmail.com

*/

/***************************************************************************
 * Define WordPress Premium Google News Plugin version number and constants,
 * DO NOT CHANGE ANY OF THESE CONSTANTS!
 **************************************************************************/

define( 'bgnp_FEED_VERSION', '1.6.1' );

if ( ! defined( 'bgnp_FILE' ) ) {
	define( 'bgnp_FILE', __FILE__ );
}

if ( !defined( 'bgnp_URL' ) ) {
	define( 'bgnp_URL', plugin_dir_url( __FILE__ ) );
}

if ( !defined( 'bgnp_PATH' ) ) {
	define( 'bgnp_PATH', plugin_dir_path( __FILE__ ) );
}
	
if ( ! defined( 'bgnp_BASENAME' ) ) {
	define( 'bgnp_BASENAME', plugin_basename( __FILE__ ) );
}

/****************************************************************************************
 * Run single site or network-wide activation of the WordPress Premium Google News Plugin
 ***************************************************************************************/

function bgnp_activate( $networkwide ) {
	if ( ! is_multisite() || ! $networkwide ) {
		_bgnp_activate();
	}
	else {
		/* Multi-site network activation - activate the plugin for all blogs */
		bgnp_network_activate_deactivate( true );
	}
}
register_activation_hook( bgnp_FILE, 'bgnp_activate' );

/*******************************************************************************************
 * Run single site or network-wide de-activation of the WordPress Premium Google News Plugin
 ******************************************************************************************/

function bgnp_deactivate( $networkwide ) {
	if ( ! is_multisite() || ! $networkwide ) {
		_bgnp_deactivate();
	}
	else {
		/* Multi-site network activation - de-activate the plugin for all blogs */
		bgnp_network_activate_deactivate( false );
	}
}
register_deactivation_hook( bgnp_FILE, 'bgnp_deactivate' );

/*****************************************************************************************************************************************************
 * Run activation of Premium Google News Plugin whenever a new blog is created, but obviously only on multisite when plugin is activated network-wide.
 ****************************************************************************************************************************************************/

function bgnp_activate_new_blog( $blog_id ) {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}

	if ( is_plugin_active_for_network( 'bgnp_BASENAME' ) ) {
		switch_to_blog( $blog_id );
		bgnp_activate();
		restore_current_blog();
	}
}
add_action( 'wpmu_new_blog', 'bgnp_activate_new_blog' );
add_action( 'activate_blog', 'bgnp_activate_new_blog' );

/**********************************
 * Run network-wide (de-)activation
 *********************************/

function bgnp_network_activate_deactivate( $activate = true ) {
	global $wpdb;

	$original_blog_id = get_current_blog_id(); // alternatively use: $wpdb->blogid
	$all_blogs        = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	if ( is_array( $all_blogs ) && $all_blogs !== array() ) {
		foreach ( $all_blogs as $blog_id ) {
			switch_to_blog( $blog_id );

			if ( $activate === true ) {
				_bgnp_activate();
			}
			else {
				_bgnp_deactivate();
			}
		}
		// Restore back to original blog
		switch_to_blog( $original_blog_id );
	}
}

/***********************************
 * Runs on activation of the plugin.
 **********************************/

function _bgnp_activate() {
	bgnp_load_textdomain(); // Make sure we have our translations available for the defaults
	

	do_action( 'bgnp_activate' );
}

/*************************************************************************************************
 * On deactivation, flush the rewrite rules so Google News XML sitemap stops working in the future
 ************************************************************************************************/

function _bgnp_deactivate() {
	
	
	do_action( 'bgnp_deactivate' );
}

/*******************
 * Load translations
 ******************/

function bgnp_load_textdomain() {
	load_plugin_textdomain( 'google-news-editors-picks-feeds', false, bgnp_BASENAME . '/languages/' );
}
add_action( 'init', 'bgnp_load_textdomain' );

/************************************************************
 * Load required admin files on plugin_loaded, not right away
 ***********************************************************/

function bgnp_admin_init() {
	global $pagenow;
	/**
 	 * Load required file
	 */
	require_once( bgnp_PATH . 'includes/bgnp-admin.php' );
}
add_action( 'plugins_loaded', 'bgnp_admin_init' );