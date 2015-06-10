<?php
/*******************************************************************
 * Creates Google News Editor Picks RSS Feed Plugin Top-Level menu, 
 * Admin Tabs, Dashboard/general instruction page, 
 * Upgrade/Activation Tabs, 
 * Editors’ Picks RSS Feed settings page.
 ******************************************************************/

/************************************************************************
 * Hook adding Premium Google News Plugin dashboard link after activation
 ***********************************************************************/
function bgnp_action_links( $links, $file ) {
 
	if ($file == bgnp_BASENAME) {
		// add link to settings
		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=bgnp_dashboard' ) ) . '">' . __( 'Settings', 'google-news-editors-picks-feeds' ) . '</a>';
		array_unshift( $links, $settings_link );
		
		// add link to docs
		$faq_link = '<a href="http://googlenewsplugin.com/faqs/">' . __( 'FAQs', 'google-news-editors-picks-feeds' ) . '</a>';
		array_unshift( $links, $faq_link );
		
		// add link to premium support
		$premium_link = '<a href="http://www.googlenewsplugin.com/">' . __( 'Premium Upgrade', 'google-news-editors-picks-feeds' ) . '</a>';
		array_unshift( $links, $premium_link );
	}
			
	return $links;
}
add_filter('plugin_action_links', 'bgnp_action_links', 10, 2);

/*****************************************************************
 * Register WordPress Premium Google News Plugin css stylesheet(s)
 ****************************************************************/

function bgnp_register_styles() {
	
	// Register css stylesheet(s)
	wp_register_style( 'bgnp-admin-styles', bgnp_URL . '/css/bgnp-admin-styles.css' );
	wp_register_style( 'bgnp-meta-box-tabs', bgnp_URL . '/css/bgnp-meta-box-tabs.css' );

}
add_action( 'admin_init', 'bgnp_register_styles' );

/**********************************************
 * Create Premium Google News Plugin admin tabs
 *********************************************/

function bgnp_get_settings_page_tabs() {
	// Construct plugin's option page tab(s)
	$tabs = array(
		'bgnp_dashboard' => 'Dashboard',
		'bgnp_upgrade_settings' => 'Tutorials',
		'bgnp_feed_settings' => 'Editors’ Picks RSS Feeds',
	);
	     
	$links = array();
	foreach( $tabs as $tab => $name ) :
	if ( $tab == $current ) :
		$links[] = "<a class='nav-tab nav-tab-active' href='?page=$tab'>$name</a>";
        else :
		$links[] = "<a class='nav-tab' href='?page=$tab'>$name</a>";
        endif;
	endforeach;
	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $links as $link )
		echo $link;
	echo '</h2>';
	if ( $pagenow == 'admin.php' && $_GET['page'] == 'bgnp_dashboard' ) :
		if ( isset ( $_GET['tab'] ) ) :
			$tab = $_GET['tab'];
	    	else:
			$tab = 'bgnp_dashboard';
	    	endif;
	    	switch ( $tab ) :
	           case 'bgnp_dashboard' :
	               bgnp_general_settings_page();
	               break;
	           case 'bgnp_upgrade_settings' :
	               bgnp_upgrade_settings_page();
	               break;
	           case 'bgnp_feed_settings' :
	               bgnp_feed_settings_page();
	               break;
	        endswitch;
	endif;
}

/******************************************************************************************************
 * Loop to create Premium Google News Plugin license key activation, admin dashboard and options pages.
 * Add multisite Super Admin
 * Load admin CSS conditionally.
 *****************************************************************************************************/

function bgnp_add_admin_menu() {
	// Hook for Super Admin permissions for Premium Google News Plugin dashboard
	if (is_multisite() && !is_super_admin()) {
		$bgnpSuperAdminsError = 'Only Super Admins can access my plugin';
	  	return $bgnpSuperAdminsError;
	} else {
	// Add main page
	$admin_page = add_menu_page(__( 'Google News Editors’ Picks RSS Feed Generator', 'google-news-editors-picks-feeds' ),__( 'Google News' ), 'manage_options', 'bgnp_dashboard', 'bgnp_general_settings_page', bgnp_URL . '/images/bgnp-rss-icon.png', '6' );	
	$submenu_pages = array(	
		array( 'bgnp_dashboard',__( 'Tutorials' ),__('Tutorials'), 'manage_options', 'bgnp_upgrade_settings', 'bgnp_upgrade_settings_page', array( 'load_page' ), null ),
		array( 'bgnp_dashboard',__( 'Editors’ Picks RSS Feeds' ),__('Editors’ Picks'), 'manage_options', 'bgnp_feed_settings', 'bgnp_feed_settings_page', array( 'load_page' ), null ),
	);
	// Loop through submenu pages and add them
	if ( count( $submenu_pages ) ) {
		foreach ( $submenu_pages as $submenu_page ) {
	
			$admin_page = add_submenu_page( $submenu_page[0], $submenu_page[1], $submenu_page[2], $submenu_page[3], $submenu_page[4], $submenu_page[5]  );
		}
	}
	// Add separate menu for dashboard	
	global $submenu;
	if ( isset( $submenu['bgnp_dashboard'] ) ) {
		$submenu['bgnp_dashboard'][0][0] = __( 'Dashboard', 'google-news-editors-picks-feeds' );
	}
	// Load the CSS conditionally
	add_action( 'admin_print_styles-' . $submenu_pages, 'bgnp_admin_styles' );
}}
add_action('admin_menu', 'bgnp_add_admin_menu');

/********************************************************************
 * Create Editors’ Picks RSS Feeds to Premium Upgrade page,
 * Admin dashboard, and instructions page/dashboard.
 *******************************************************************/

function bgnp_general_settings_page() {

	?>
<div class="wrap">
	<?php echo '<img style="float:left" src="' . bgnp_URL . '/images/bgnp-rss-icon_50x50.png"/>'; ?>
	<h2><?php _e( 'Google News Editors’ Picks Feed Generator', 'google-news-editors-picks-feeds' ) ?></h2>
	<div class="metabox-holder has-right-sidebar">
		<?php bgnp_settings_page_sidebar();?>
		<?php bgnp_get_settings_page_tabs();?>	
		<div id="post-body" class="meta-box-sortables ui-sortable">
			<br /><h3><?php _e( 'Welcome to the Google News Editors’ Picks Feed Plugin Dashboard, where you will find general instructions for settings options and operations.', 'google-news-editors-picks-feeds' ) ?></h3>
			<hr size="3">
			<div id="post-body-content">
			<h2><?php _e( 'General Instructions', 'google-news-editors-picks-feeds' ) ?></h2>
			<hr size="3">
	          	<ol>
				<li>You <strong>MUST</strong> have <a href="http://bit.ly/1olZCua" target="_blank">suggested inclusion</a> of news site in Google News.</li>
	                		<li>You <strong>MUST</strong> <a href="http://bit.ly/1raApEY" target="_blank">submit your new Editors’ Picks RSS Feeds</a> to Google just as you did for your site.</li>
				<li>You <strong>MUST</strong> set feed settings before using the plugin.</li>
				<li>Read instructions below <strong>FIRST</strong> before creating RSS feeds.</li>	                               
			</ol>
			<hr size="3">
			<?php echo '<img style="float:left" src="' . bgnp_URL . '/images/bgnp-news-icon_50x50.png"/>'; ?>
			<h2><?php _e( 'Tutorials', 'google-news-editors-picks-feeds' ) ?></h2>
			<hr size="3">
			<ol>
				<li>The WordPress Google News Editors’ Picks Feed Generator was designed by publishers for publishers, with highly customizable feeds to increase traffic to your site(s)!</li>
				<li>Compatible with popular non-news sitemap and feed generators (i.e. Yoast SEO), and lightweight to minimize server load. </li>
				<li>The most comprehensive custom feed Google News feed plugin ever designed to meet the needs of publishers in Google News, and available for both single and multi-sites.</li>
				<li>Checkout the <a href="http://bit.ly/YMGVof" target="_blank">Creating Custom Google News Editors’ Picks RSS Feeds</a> tutorial to learn more!</li>
			</ol>
			<hr size="3">
			<?php echo '<img style="float:left" src="' . bgnp_URL . '/images/rss-news-feed_50x50.png"/>'; ?>
			<h2><?php _e( 'Editors’ Picks Instructions', 'google-news-editors-picks-feeds' ) ?></h2>
			<hr size="3">
			<ol>
	                <li>Google News <code>Editors’ Picks</code> RSS feeds enable you to provide up to five links to original news content.</li>
				<li>Your picks should reflect the best pieces of journalism on your site at any given time! Do not include links to offers, how-to articles, ads, or weather forecasts.</li>
				<li>Plugin also creates a section-based feed (e.g., Technology), which you can submit along with one homepage feed.</li>
				<li><strong>AFTER</strong> setting and "saving" feed options, go to "Permalinks" under "Settings" and click "Save Settings" to generate RSS feeds.</li>
				<li>Login to Google Webmaster Tools with the seemingly integrated postbox on the feed settings page, and submit the RSS feed.</li>
			</ol>
			<hr size="3">
			<?php echo '<img style="float:left" src="' . bgnp_URL . '/images/bgnp-webmaster-tools_50x50.png"/>'; ?>
			<h2><?php _e( 'Update Instructions', 'google-news-editors-picks-feeds' ) ?></h2>
			<hr size="3">
			<ol>
	                		<li>Purchase your Premium WordPress Google News Plugin and 1-year support plan with license <a href="http://bit.ly/1yZNiC7" target="_blank">here.</a></li>
				<li>A link to the plugin zip file will be emailed to you immediately after purchase. Check your spam folder if necessary.</li>
				<li>Deactivate Editors’ Picks RSS Feed Plugin before activiating the premium plugin, which will still recognize prior database/feed settings.</li>
				<li>Activate Premium WordPress Google News Plugin and enter the license key under "License" tab. Go to dashboard before "Sitemap Settings" tab for instructions.</li>
				<li><strong>AFTER</strong> setting and "saving" sitemap and feed options, go to "Permalinks" under "Settings" and click "Save Settings" to generate RSS feeds.</li>
				<li>Login to Google Webmaster Tools with the seemingly integrated postbox on the feed settings page, and submit the RSS feed.</li>
			</ol>
		<hr size="3"> 
			</div> <!-- #post-body-content -->
		</div> <!-- #post-body -->
	</div> <!-- .metabox-holder -->
</div> <!-- .wrap -->
	    <?php
}

function bgnp_upgrade_settings_page() {

	?>
<div class="wrap">
	<?php echo '<img style="float:left" src="' . bgnp_URL . '/images/bgnp-news-icon_50x50.png"/>'; ?>
	<h2><?php _e( 'Tutorials', 'google-news-editors-picks-feeds' ) ?></h2>
	<div class="metabox-holder has-right-sidebar">
		<?php bgnp_settings_page_sidebar();?>
		<?php bgnp_get_settings_page_tabs();?>	
		<div id="post-body" class="meta-box-sortables ui-sortable">
			<br /><h3><?php _e( 'The WordPress Google News Editors’ Picks Plugin was designed by publishers for publishers. Check out tutorials below!', 'google-news-editors-picks-feeds' ) ?></h3>
			<hr size="3">
			<div id="post-body-content">
			<h2><?php _e( 'WordPress Google News Editors’ Picks Plugin', 'google-news-editors-picks-feeds' ) ?></h2>
			<hr size="3">
			<h3><?php _e( 'Creating Custom Google News Editors’ Picks RSS Feeds', 'google-news-editors-picks-feeds' ) ?></h3>
			<p><strong>Significantly Increase Traffic Referrals From Google News With Customizable News Feeds!</strong></span></p>
			<p><li>Tutorial explains in detail how to create, customize and validate the feeds and their features.</li>
			<li>Covers feed titles, their descriptions, images, homepage vs. section-based and more.</li>
			<li>Checkout the <a href="http://bit.ly/YMGVof" target="_blank">Creating Custom Google News Editors’ Picks RSS Feeds With WordPress Plugin</a> to learn more!</li></p>
			<hr size="3">
			<h3><?php _e( 'Enhance MailChimp Newsletter With Editors’ Picks', 'google-news-editors-picks-feeds' ) ?></h3>
			<hr size="3">
			<p><strong>Significantly Increase Traffic From MailChimp Newsletter!</strong></span></p>
			<p><li>With the Editors Picks RSS Feeds Generator, you can add proper images to your RSS-to-email newsletter in a matter of minutes!</li>
			<li>Some email programs, like Outlook, will place images and wrap your text -- if they even can -- in unattractive formats.</li>
			<li>Tutorial covers what the RSS media tag is, how to use it, and how to get your MailChimp campaigns attractive to your readers.</li>
			<li>Checkout the <a href="http://bit.ly/1wptmuC" target="_blank">Enhance MailChimp With Feeds Generator</a> tutorial to learn more!</li></p>
			<h3><?php _e( 'Do More With Section-based Editors’ Picks', 'google-news-editors-picks-feeds' ) ?></h3>
			<p><strong>Significantly Increase Traffic From Google News Diversifying Feeds</strong></span></p>
			<p><li>With the Editors Picks RSS Feeds Generator, you can add topic-specific feeds.</li>
			<li>Learn how to strengthen your news brand, and inch into categories and new markets.</li>
			<li>Checkout the <a href="http://bit.ly/1IpUv3H" target="_blank">Tutorial</a> to learn more!</li></p>
		<hr size="3"> 
			</div> <!-- #post-body-content -->
		</div> <!-- #post-body -->
	</div> <!-- .metabox-holder -->
</div> <!-- .wrap -->
	    <?php
}

function bgnp_feed_settings_page() {
	$bgnp_rss_feed_loc = get_option('home').'/feed/editors-picks';
	$bgnp_rss_WMT = str_replace('http://'.$_SERVER['HTTP_HOST'].'/','',$bgnp_rss_feed_loc);
	$bgnp_WMT_path = 'https://www.google.com/webmasters/tools/';
	if ('bgnp_submit' == $_POST['bgnp_submit']) {
		update_option('bgnp_rss_feed_desc', $_POST['bgnp_rss_feed_desc']);
		update_option('bgnp_sec_feed_desc', $_POST['bgnp_sec_feed_desc']);
	    	update_option('bgnp_rss_feed_title', $_POST['bgnp_rss_feed_title']);
	    	update_option('bgnp_sec_feed_title', $_POST['bgnp_sec_feed_title']);
	    	update_option('bgnp_sec_feed_cat', $_POST['bgnp_sec_feed_cat']);
	    	update_option('bgnp_rss_image_url', $_POST['bgnp_rss_image_url']);
	    	update_option('bgnp_sec_image_url', $_POST['bgnp_sec_image_url']);
	    	update_option('bgnp_rss_image_alt', $_POST['bgnp_rss_image_alt']);
	    	update_option('bgnp_sec_image_alt', $_POST['bgnp_sec_image_alt']);
	    	
	}
	    
	$bgnp_rss_feed_desc = get_option('bgnp_rss_feed_desc');
	$bgnp_sec_feed_desc = get_option('bgnp_sec_feed_desc');
	$bgnp_rss_feed_title = get_option('bgnp_rss_feed_title');
	$bgnp_sec_feed_title = get_option('bgnp_sec_feed_title');
	$bgnp_sec_feed_cat = get_option('bgnp_sec_feed_cat');
	$bgnp_rss_image_url = get_option('bgnp_rss_image_url');
	$bgnp_sec_image_url = get_option('bgnp_sec_image_url');
	$bgnp_rss_image_alt = get_option('bgnp_rss_image_alt');
	$bgnp_sec_image_alt = get_option('bgnp_sec_image_alt');
	    
	?>
<div class="wrap">
	<?php echo '<img style="float:left" src="' . bgnp_URL . '/images/rss-news-feed_50x50.png"/>'; ?>
	<h2><?php _e( 'Editors’ Picks RSS Feeds', 'google-news-editors-picks-feeds' ) ?></h2>
	<form name="form3" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<?php if (isset($_GET['updated'])) : ?> 
    		<div id="message" class="updated"><strong><?php _e('Settings saved.', 'google-news-editors-picks-feeds' ) ?></strong></div>
	<?php endif; ?> 
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<?php bgnp_settings_page_sidebar(); ?>
		<?php bgnp_get_settings_page_tabs(); ?>    
	  	<input type="hidden" name="bgnp_submit" value="bgnp_submit" />
	  	<div id="post-body" class="meta-box-sortables ui-sortable">	
		<h2><?php _e( 'Global Feed Settings' ); ?></h2>
		<hr size="3">
		<table class="form-table">
			<tr>
		    		<th scope="row">
		    			<label for="textarea"><?php _e( 'Feed Titles:', 'google-news-editors-picks-feeds' ) ?></label>
		    			<?php echo '<a href="http://bit.ly/1lQlyaZ" target="_blank"><img class="alignright bgnp_admin_help" src="' . bgnp_URL . '/images/bgnp-question-mark.png"></a>'; ?>
		    		</th>
		    		<td>
		    			<input value="<?php echo htmlspecialchars(stripslashes($bgnp_rss_feed_title)) ?>" type="textarea" name="bgnp_rss_feed_title" size="50" /></textarea>
		    			<br /><p> e.g. News Site’s (homepage) editor’s picks</p>
		    		<br />
		    			<input value="<?php echo htmlspecialchars(stripslashes($bgnp_sec_feed_title)) ?>" type="textarea" name="bgnp_sec_feed_title" size="50" /></textarea>
		    			<br /><p> e.g. News Site’s (section-based) editor’s picks</p>
		    		<hr size="3">
		    		</td>
			</tr>
		    	<tr>
		    		<th scope="row">
		    			<label for="textarea"><?php _e( 'Feed Descriptions:', 'google-news-editors-picks-feeds' ) ?></label>
		    			<?php echo '<a href="http://bit.ly/1lQlyaZ" target="_blank"><img class="alignright bgnp_admin_help" src="' . bgnp_URL . '/images/bgnp-question-mark.png"></a>'; ?>
		    		</th>
		    		<td>
		    			<input value="<?php echo htmlspecialchars(stripslashes($bgnp_rss_feed_desc)) ?>" type="textarea" name="bgnp_rss_feed_desc" size="50" /></textarea>
		    			<br /><p> e.g. News Site’s best (homepage) articles of the day.</p>
		    		<br />
		    			<input value="<?php echo htmlspecialchars(stripslashes($bgnp_sec_feed_desc)) ?>" type="textarea" name="bgnp_sec_feed_desc" size="50" /></textarea>
		    			<br /><p> e.g. News Site’s best (section-based) articles of the day.</p>
		    		<hr size="3">
		    		</td>
		    	</tr>
			<tr>
				<th scope="row">
			        	<label for="select" class="bgnp-row-content"><?php _e( 'Feed Category:', 'google-news-editors-picks-feeds' )?></label>
			        	<?php echo '<a href="http://bit.ly/1sXb1Ri" target="_blank"><img class="alignright bgnp_admin_help" src="' . bgnp_URL . '/images/bgnp-question-mark.png"></a>'; ?>
			        </th>
			        <td>
		    			<select name="bgnp_sec_feed_cat" id="meta-select">
			        	<option <?php echo $bgnp_sec_feed_cat=="world"?'selected="selected"':'';?> value="world">World</option>
			        	<option <?php echo $bgnp_sec_feed_cat=="us"?'selected="selected"':'';?> value="us">U.S.</option>
			        	<option <?php echo $bgnp_sec_feed_cat=="business"?'selected="selected"':'';?> value="business">Business</option>
			        	<option <?php echo $bgnp_sec_feed_cat=="technology"?'selected="selected"':'';?> value="technology">Technology</option>
			        	<option <?php echo $bgnp_sec_feed_cat=="entertainment"?'selected="selected"':'';?> value="entertainment">Entertainment</option>
			        	<option <?php echo $bgnp_sec_feed_cat=="sports"?'selected="selected"':'';?> value="sports">Sports</option>
			        	<option <?php echo $bgnp_sec_feed_cat=="science"?'selected="selected"':'';?> value="science">Science</option>
			        	<option <?php echo $bgnp_sec_feed_cat=="health"?'selected="selected"':'';?> value="health">Health</option>
			        	</select>
			        	<br /><p> You <strong>MUST</strong> choose a category for the section-based Google News feed (e.g., World, U.S., Technology).</p>
			        	<p> The plugin auto-customizes your section-based news feed URL based on your category selection (e.g., <code>http://www.yoursite.com/feed/editors-picks-world, -us, -technology</code>).</p>
		    		<hr size="3">
		    		</td>
		    	</tr>
		    	<tr>
		    		<th scope="row">
		    			<label class="upload_image" for="bgnp_image_upload_button"><?php _e( 'RSS Image (Home):', 'premium-google-news-plugin' ) ?></label>
		    			<?php echo '<a href="http://bit.ly/1lQlyaZ" target="_blank"><img class="alignright bgnp_admin_help" src="' . bgnp_URL . '/images/bgnp-question-mark.png"></a>'; ?>
		    		</th>
		    		<td>
			        	<input id="upload_image" type="text" size="50" name="bgnp_rss_image_url" value="<?php echo $bgnp_rss_image_url; ?>" />
			        	<input id="bgnp_upload_image_button" class="bgnp_upload_image_button button" type="button" value="Upload Image" />
			        	<br /><span>Enter a URL or upload an image to be used in homepage RSS feed from media library.</span>
			        	<hr size="3">
			        	<p><strong>The feed images must follow these specifications:</strong></span></p>
			        	<p><li>Option 1: Width of 250px, Height between 20 and 40px</li>
			        	<li>Option 2: Height of 40px, Width between 125 and 250px</li>
			        	<li>Image must be .png format</li>
			        	<li>File size should only be as large as necessary, i.e. just a few KBs.</li>
			        	<li>Logo background should be transparent.</li>
			        	<li>Avoid any whitespace around the logo.</li></p>
				<hr size="3">
			        </td>
			</tr>
		    	<tr>
		    		<th scope="row">
		    			<label class="upload_image" for="bgnp_image_upload_button"><?php _e( 'RSS Image (Section):', 'premium-google-news-plugin' ) ?></label>
		    			<?php echo '<a href="http://bit.ly/1lQlyaZ" target="_blank"><img class="alignright bgnp_admin_help" src="' . bgnp_URL . '/images/bgnp-question-mark.png"></a>'; ?>
		    		</th>
		    		<td>
			        	<input id="upload_image" type="text" size="50" name="bgnp_sec_image_url" value="<?php echo $bgnp_sec_image_url; ?>" />
			        	<input id="bgnp_upload_image_button" class="bgnp_upload_image_button button" type="button" value="Upload Image" />
			        	<br /><span>Enter a URL or upload an image to be used in section-based RSS feed from media library.</span>
			        	<hr size="3">
			        	<p><strong>The feed images must follow these specifications:</strong></span></p>
			        	<p><li>Option 1: Width of 250px, Height between 20 and 40px</li>
			        	<li>Option 2: Height of 40px, Width between 125 and 250px</li>
			        	<li>Image must be .png format</li>
			        	<li>File size should only be as large as necessary, i.e. just a few KBs.</li>
			        	<li>Logo background should be transparent.</li>
			        	<li>Avoid any whitespace around the logo.</li></p>
				<hr size="3">
			        </td>
			</tr>
		    	<tr>
		    		<th scope="row">
		    			<label for="textarea"><?php _e( 'Alt Text (Home):', 'google-news-editors-picks-feeds' ) ?></label>
		    			<?php echo '<a href="http://bit.ly/1lQlyaZ" target="_blank"><img class="alignright bgnp_admin_help" src="' . bgnp_URL . '/images/bgnp-question-mark.png"></a>'; ?>
		    		</th>
		    		<td>
			    		<input value="<?php echo htmlspecialchars(stripslashes($bgnp_rss_image_alt)) ?>" type="textarea" name="bgnp_rss_image_alt" size="50" /></textarea>
			    		<br /><p> Enter text to use as alternative for the image. <strong>Must</strong> match feed title.</p>
			    	</td>
		    	</tr>
		    	<tr>
		    		<th scope="row">
		    			<label for="textarea"><?php _e( 'Alt Text (Section):', 'google-news-editors-picks-feeds' ) ?></label>
		    			<?php echo '<a href="http://bit.ly/1lQlyaZ" target="_blank"><img class="alignright bgnp_admin_help" src="' . bgnp_URL . '/images/bgnp-question-mark.png"></a>'; ?>
		    		</th>
		    		<td>
			    		<input value="<?php echo htmlspecialchars(stripslashes($bgnp_sec_image_alt)) ?>" type="textarea" name="bgnp_sec_image_alt" size="50" /></textarea>
			    		<br /><p> Enter text to use as alternative for the image. <strong>Must</strong> match feed title.</p>
			    	</td>
		    	</tr>
		</table>
	<hr size="3">
		<input class="button-primary" type="submit" value="<?php _e( 'Save Settings', 'google-news-editors-picks-feeds' ); ?>" />
	</form>
		<hr size="3">
		<h2><?php _e( 'Submit Feed In Google Webmaster Tools', 'google-news-editors-picks-feeds' ); ?></h2>
		<hr size="3"> 	 
	    		<div class="postbox">
	    		<?php echo '<img style="float:left" src="' . bgnp_URL . '/images/bgnp-webmaster-tools_50x50.png">'; ?>
		    	        <p>Google News homepage feed URL:<code><?php echo $bgnp_rss_feed_loc; ?></code><a class="button-secondary" href="<?php echo $bgnp_rss_feed_loc; ?>" target="_blank">View RSS feed</a>
		    	        <br /> Submit path to Google Webmaster Tools:<code><?php echo $bgnp_rss_WMT; ?></code><a class="button-secondary" href="<?php echo $bgnp_WMT_path; ?>" target="_blank">Submit RSS Feeds</a></p>
	    	        </div> <!-- .postbox --> 
		</div> <!-- #post-body .meta-box-sortables .ui-sortable -->
	</div> <!-- #poststuff .metabox-holder -->
</div> <!-- .wrap -->
	<?php
}

function bgnp_settings_page_sidebar() {
	?>
	<div id="sidebar" class="inner-sidebar">
		<div id="sidebar" class="postbox">
			<h3><?php _e( 'Resources Toolbar', 'google-news-editors-picks-feeds' ); ?></h3>
			<br />
			<?php echo '<a href="https://www.googlenewsplugin.com" target="_blank"><img style="float:left" src="' . bgnp_URL . '/images/bgnp-news-icon.png">Plugin Tutorials</a>'; ?>
			<br /><br />
			<?php echo '<a href="https://www.google.com/webmasters/tools/" target="_blank"><img style="float:left" src="' . bgnp_URL . '/images/bgnp-webmaster-tools-icon.png">Webmaster Tools</a>'; ?>
			<br /><br />
			<?php echo '<a href="https://support.google.com/news/publisher/" target="_blank"><img style="float:left" src="' . bgnp_URL . '/images/google-news-icon.png">Publisher Help Center</a>'; ?>
			<br /><br />
			<?php echo '<a href="http://validator.w3.org/feed/" target="_blank"><img style="float:left" src="' . bgnp_URL . '/images/bgnp-rss-icon.png">Validate RSS Feeds</a>'; ?>
			<br /><br />
			<?php echo '<a href="http://bit.ly/1sKtyyj" target="_blank"><img style="float:left" src="' . bgnp_URL . '/images/you-tube-icon.png">Associate YouTube</a>'; ?>
			<br /><br />
	    	</div> <!-- .postbox -->
	    	<h3>Even A Dollar Helps!</hr3>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="92XNH8SCVRSTL">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div> <!-- .inner-sidebar -->
	<?php
}

function bgnp_admin_styles() {
	/*******************
        * Enqueue stylesheet
        *******************/
       wp_enqueue_style('bgnp-admin-styles');
}

/************************************************************
 * Register against the proper action hook to enqueue scripts
 ***********************************************************/

add_action( 'admin_enqueue_scripts', 'bgnp_admin_js' );
function bgnp_admin_js( $hook ) {
	global $submenu_pages;
	// Exclude dashboard from localized JS, load conditionally
	if( $hook != array( 'bgnp_upgrade_settings', 'bgnp_feed_settings' ) ) {
	
		// Load dependencies/already registered js files
		wp_enqueue_media();
		
	     // Load js script(s)
		wp_enqueue_script( 'bgnp-admin-js', bgnp_URL . '/js/bgnp-admin.js', bgnp_FILE, array(jquery), bgnp_VERSION, true );
	}        
}

// Include required files
require_once( bgnp_PATH . '/includes/bgnp-meta-box.php');
require_once( bgnp_PATH . '/includes/bgnp-functions.php');