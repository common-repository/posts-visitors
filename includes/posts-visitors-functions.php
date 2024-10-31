<?php

/**
 *  * The file that defines the core plugin class conatins functions for counter
 * @author Huda Medoukh
 * @license http://www.gnu.org/licenses/gpl-2.0.txt
 * @package Posts_Visitors
 * @version 1.0.0
 * 
 */
// If this file is called directly, abort.
if (!defined('ABSPATH')) {

	die('you can not acess this file');
}

class Posts_Visitors_Functions
{

	public function __construct()
	{
		add_action('template_redirect', array($this, 'posts_visitors_count'));
		add_action('manage_posts_columns', array($this, 'posts_visitors_add_custom_column'),10, 2);
		add_filter("the_content", array($this, 'posts_visitors_print_in_posts'));
		 add_action('manage_post_posts_custom_column', array($this, 'posts_visitors_add_value_to_column'), 10, 2);
	}

	//Count The Post Visitors//

	public function posts_visitors_count()
	{

		if (!is_single()) {
			return;      // if post-type != post donnot return count
		}

		$post_id = get_the_ID(); // The Post Id

		$user_ip = '';  // The user's IP address
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			//ip from share internet
			$user_ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
			
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			//ip pass from proxy
			$user_ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
		}else{
			
			$user_ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
		}

		$visitors_key = 'post_visitors_count';  // The post visitors post meta key
		$ip_key = 'post_visitors_ip';        // The IP Address post meta key
		$count = (int) get_post_meta($post_id, $visitors_key, true);  // The current post visitors count


		// Array of IP addresses that have already visited the post.
		if (get_post_meta($post_id, $ip_key, true) != '') {

			$ip = json_decode(get_post_meta($post_id, $ip_key, true), true);
		} else {
			$ip = array();
		}

		//checks if the user's IP already exists.
		for ($i = 0; $i < count($ip); $i++) {

			if ($ip[$i] == $user_ip)
				return false;
		}

		//If the script has gotten this far, it means that the user's IP address isn't in the database.
		// Update and encode the $ip array into a JSON string
		$ip[count($ip)] = $user_ip;
		$json_ip = json_encode($ip);
		$count++;

		// Update the post's metadata 
		update_post_meta($post_id, $visitors_key, $count); // Update the count
		update_post_meta($post_id, $ip_key, $json_ip); // Update the user IP JSON obect

	}

	//print post visitors in posts // 

	public function posts_visitors_print_in_posts($content)
	{

		$post_id  = get_the_ID(); 		// The Post Id
		$visitors_key = 'post_visitors_count'; 	// The post visitors post meta key
		$label_text = get_option('posts_visitors_label_text'); // The label Text that the admin choose it
		$label_display = get_option('posts_visitors_checkbox_label');  // visible or hidden the label choosen by the admin
		$icon_display = get_option('posts_visitors_checkbox_icon');   // visible or hidden the icon choosen by the admin
		$icon_class = get_option('posts_visitors_icon_class'); // The class of icon choosen by the admin
		$color = get_option('posts_visitors_color'); // The color of icon choosen by the admin
		$position = get_option('posts_visitors_select');  //the position of counter chosen by admin (1_befor the content,2_after the content,3_manual)
		$counter = get_post_meta($post_id, $visitors_key, true);      // The visitors count of the current post

		//post visitors count with label and icon
		$output  = '<div class="site-visitors">';
		$output .= $icon_display ? '<span style="color:' .esc_attr($color ) . ';margin-top:4px;margin-right:3px;"class="dashicons ' . esc_attr($icon_class) . '">' . '</span>' : ' ';
		$output .= $label_display ?'<span class="visitors-label" style="display:' . esc_attr($label_display) . ';">' . esc_html__($label_text) . ':</span>': '';
		$output .= '<span class="visitors-counter">' . esc_html__($counter) . '</span>' ;
		$output .= '</div>';

		if (is_single()) {

			if (isset($position)&& $position == "before") {

				return  $output . $content;	   // if the admin choose before content


			} elseif (isset($position)&& $position == "after") {

				return  $content . $output;    // if the admin choose after content

			} else {

				return $content;              // if the admin choose manual 

			}
		} else {

			return $content;  // if post type!=posts return the content without the visitros count 
		}
	}

	//add a custom column for the visitors of posts//

	public function posts_visitors_add_custom_column($post_columns, $post_type)
	{
		 $post_columns['visitors'] = __('Visitors Count','post-visitors'); //add column for count visitors
		return $post_columns;

	}

	//add the value of custom column for the visitors of posts//

	public function posts_visitors_add_value_to_column($column_name, $post_id)
	{
		if ($column_name != 'visitors') {
			return;
		}
		$visitors_key = 'post_visitors_count'; 	// The post visitors post meta key

		$visitors = (int)get_post_meta($post_id, $visitors_key, true); //the count visitors of posts
		esc_html_e( $visitors,'posts-visitors');
	}
}

$post_visitors_functions= new Posts_Visitors_Functions();
