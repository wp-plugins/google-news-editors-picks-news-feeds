<?php
/****************************************************
 * Create (Homepage) Editors’ Picks RSS Feed Template
 ***************************************************/

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:media="http://search.yahoo.com/mrss/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
	/**
	 * Fires at the end of the Editors’ Picks root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_ns' );
	?>
>
<channel>
	<title><?php echo $bgnp_rss_feed_title; ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php echo get_bloginfo( 'url' ) ?></link>
	<description><?php echo $bgnp_rss_feed_desc; ?></description>
	<image>
		<url><?php echo $bgnp_rss_image_url; ?></url>
		<title><?php echo $bgnp_rss_image_alt; ?></title>
		<link><?php echo get_bloginfo( 'url' ) ?></link>
 	</image>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>

	<?php
	/**
	 * Fires at the end of the Editors’ Picks RSS Feed Header.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_head' );
	
	?>
<?php 
if ( $rss_feed_query->have_posts() ) :
while ( $rss_feed_query->have_posts() ) : $rss_feed_query->the_post(); global $post;
	// Resume adding Editors’ Picks feed template tags	
?>
	<item>
		<title><?php echo get_the_title($post->ID); ?></title>
		<link><?php echo get_permalink($post->ID); ?></link>
	<?php $bgnp_rss_meta = htmlspecialchars(stripcslashes(get_post_meta( $post->ID, '_bgnp_rss_meta', true ))); ?>
	<?php if ($bgnp_rss_meta) : ?>
		<description><?php echo '<![CDATA[<p>'.$bgnp_rss_meta.'</p> <br/><br/>Read Full Article: <a href="'.get_permalink($post->ID).'">'.get_the_title($post->ID).'</a>'.']]>'; ?></description>
	<?php else: ?>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
	<?php endif ?>
		<dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
		<pubDate><?php bgnp_rss_date( strtotime($post->post_date_gmt) ); ?></pubDate>
	<?php if(get_the_post_thumbnail()): ?>
		<media:content url="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>" medium="image" />
	<?php endif; ?>
		<guid><?php echo get_permalink($post->ID); ?></guid>
	<?php
	/**
	 * Fires at the end of each Editors’ Picks RSS feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );
	?>
	</item>
<?php endwhile;
endif;
wp_reset_postdata(); ?>
</channel>
</rss>