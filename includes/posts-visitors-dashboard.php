<?php

/**
 * * The file that defines functions for add a box meta in the dahsboard for popular posts visitors
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


class Posts_Visitors_Dashboard
{

	public function __construct()
	{
		add_action('wp_dashboard_setup', array($this,'posts_visitors_dashboard_widget'));
	}


	function posts_visitors_dashboard_widget()
	{
		global $wp_meta_boxes;

		wp_add_dashboard_widget('post_visitors_widget', __('The Most Visited Posts','posts-visitors'),array($this,'posts_visitors_sort_the_posts'),null,null,'normal','high');
	}

	function posts_visitors_sort_the_posts()
	{
		$args = array(
			'post_type' => 'post',
			'meta_key' => 'post_visitors_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'posts_per_page'=>10
		);
	
		$post_query = new WP_Query($args);

		if ($post_query->have_posts()) {
	?>
			<table id="dashtabel">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col"><?php _e('Post Name','posts-visitors')?></th>
						<th scope="col"><?php _e('Post Visitors','posts-visitors')?></th>
					</tr>
				</thead>
				<tbody>
	
					<?php
					 $i=0;
					while ($post_query->have_posts()) {
						$i++;
						$post_query->the_post();
						$post_id = get_the_ID();
					?>
	
						<tr>
							<th scope="row"><?php esc_html_e($i,'posts-visitors')?></th>
							<td scope="col"><a href="<?php echo esc_url(the_permalink()); ?>"><?php esc_html_e(the_title(),'posts-visitors'); ?></a></td>
							<td scope="col"><?php esc_html_e((int)get_post_meta($post_id, 'post_visitors_count', true) )?></td>
	
						</tr>
	
	
					<?php
					}
					?>
				</tbody>
			</table>
		<?php
		} else {
		?>
			<p><?php esc_html_e('Sorry,There is No Posts.','posts-visitors'); ?></p>
	<?php
	
		}
	}
}

$post_visitors_dashboard = new Posts_Visitors_Dashboard();
