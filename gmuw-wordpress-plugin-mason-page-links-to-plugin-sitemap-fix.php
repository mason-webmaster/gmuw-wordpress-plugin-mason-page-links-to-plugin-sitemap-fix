<?php

/**
 * Main plugin file for the Mason WordPress: "Page Links To" Plugin Sitemap Fix plugin
 */

/**
 * Plugin Name:       Mason WordPress: "Page Links To" Plugin Sitemap Fix
 * Author:            Jan Macario
 * Plugin URI:        https://github.com/mason-webmaster/gmuw-wordpress-plugin-mason-page-links-to-plugin-sitemap-fix
 * Description:       Mason WordPress plugin which fixes the sitemap for sites using the Page Links to WordPress plugin. Specifically, it excludes posts which are set to link to another URL using the Page Link To plugin.
 * Version:           0.9
 */

// Exit if this file is not called directly.
if (!defined('WPINC')) {
	die;
}

// Set up auto-updates
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
'https://github.com/mason-webmaster/gmuw-wordpress-plugin-mason-page-links-to-plugin-sitemap-fix',
__FILE__,
'gmuw-wordpress-plugin-mason-page-links-to-plugin-sitemap-fix'
);


//fix sitemaps issue
// disable post based on meta field
add_filter('wp_sitemaps_posts_query_args', 'gmuw_isemgu_remove_external_link_posts_from_sitemap', 10, 2);
function gmuw_isemgu_remove_external_link_posts_from_sitemap($args, $post_type) {
	
	//if ($post_type !== 'post') return $args; // can be any post type
	
	//get all posts which have a _links_to postmeta
	$mypostids = get_posts(
		array(
			'numberposts' => -1,
			'fields' => 'ids',
			'meta_key' => '_links_to',
			'meta_compare' => 'EXISTS'
		)
	);

	//if we have any posts
	if ($mypostids) {

		//either get the existing array of post__not_in, or start a new blank one
		$args['post__not_in'] = isset($args['post__not_in']) ? $args['post__not_in'] : array();
		
		//loop through IDs and add them to the list of exclusions
		foreach($mypostids as $mypostid){
			$args['post__not_in'][] = $mypostid;
		}

	}

	return $args;
	
}
