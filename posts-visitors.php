<?php

/**
 * The plugin bootstrap file
 * @since             1.0.0
 * @package           Posts_Visitors
 *
 * @wordpress-plugin
 * Plugin Name:       Posts Visitors
 * Description:      A plugin allows you to display how many times the post had been visited.
 * Version:           1.0.0
 * Author:            Huda Medoukh
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       posts-visitors
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die('you can not acess this file');
}

define('POST_VISITORS_ADMIN_DIR', plugin_dir_url(__FILE__) . 'admin');

define('POST_VISITORS_PUBLIC_DIR', plugin_dir_url(__FILE__) . 'public');

class PostsVisitors{


	public function __construct(){	

		add_action('admin_enqueue_scripts', array($this,'posts_visitors_admin_enqueue_files'));
	}

	public function posts_visitors_admin_enqueue_files()
	{
		wp_enqueue_style('posts-visitors-admin_css', POST_VISITORS_ADMIN_DIR . '/css/posts-visitors-admin.css');
		wp_enqueue_script('posts-visitors-admin_js', POST_VISITORS_ADMIN_DIR . '/js/posts-visitors-admin.js', array(), '1', true);
	}



}

include(plugin_dir_path(__FILE__) . '/includes/posts-visitors-shortcode.php');

include(plugin_dir_path(__FILE__) . '/includes/posts-visitors-functions.php');

include(plugin_dir_path(__FILE__) . 'includes/posts-visitors-dashboard.php');

include(plugin_dir_path(__FILE__) . 'includes/posts-visitors-settings.php');

$post_visitors = new PostsVisitors();
