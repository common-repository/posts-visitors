<?php

/**
 * @author Huda Medoukh
 * @license http://www.gnu.org/licenses/gpl-2.0.txt
 * @package Posts_Visitors
 * @version 1.0.0
 * 
 * shortcode class definition that includes attributes and functions used to make a short code
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	
    die('you can not acess this file');
}

class Posts_Visitors_ShortCode
{

	public function __construct()
	{

		add_shortcode('posts-visitors', array($this, 'posts_visitors_shortcode'));
	}

	public function posts_visitors_shortcode($attrs = [])
	{

		$defaults = [
			'size' => 15,
			'label_text' => 'Post Visitors',
			'label' => '',
			'icon' => '',
			'icon_class' => 'dashicons-visibility'
		];

		$attrs   = shortcode_atts($defaults, $attrs);
		$post_id=get_the_ID();
		$counter =(int)get_post_meta($post_id, 'post_visitors_count', true);
		$output  = '<div  class="site-visitors">';
		$output .= '<span style="margin-top:4px;margin-right:3px;display:' . esc_attr($attrs['icon']) . ';"class="dashicons ' . esc_attr($attrs['icon_class']) . '">' . '</span>';
		$output	.= '<span  class="visitors-label" style=";display:'.esc_attr($attrs['label']) .';">' .  esc_html__($attrs['label_text'],'posts-visitors'). ':</span>';
		$output .= '<span class="visitors-counter" style="font-size:' . esc_attr($attrs['size']) . 'px">' . isset($counter)? esc_html__($counter,'posts-visitors'): '' . '</span>';
		$output .= '</div>';

		if (is_single()) {

			return $output;
		}
	}
}

$post_visitors_shortcode = new Posts_Visitors_ShortCode();
