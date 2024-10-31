<?php

/**
 * * The file that defines functions settings options
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

class Posts_Visitors_Settings
{

	public function __construct()
	{
		add_action('admin_menu', array($this, 'posts_visitors_settings_menu'));
		add_action('admin_init',  array($this, 'posts_visitors_settings_init'));
	}

	// add settings menu page

	public function posts_visitors_settings_menu()
	{
		add_menu_page(
			__('Posts Visitors Settings', 'posts-visitors'),
			__('Posts Visitors', 'posts-visitors'),
			'manage_options',
			'posts-visitors-settings-page',
			array($this, 'posts_visitors_template_callback'),
			'dashicons-visibility',
			null
		);
	}
	// add settings Template Page
	public function posts_visitors_template_callback()
	{
		$this->options_general = get_option('posts_visitors_general');
		$this->options_shortcode = get_option('posts_visitors_shortcode');
		$shortcode_Screen = (isset($_GET['action']) && 'shortcode' == $_GET['action']) ? true : false;

?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<?php settings_errors();   //displays "error" and "updated" messages.
			?>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo esc_url( admin_url('admin.php?page=posts-visitors-settings-page')); ?>"class="nav-tab<?php if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'shortcode' != $_GET['action'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e('General'); ?></a>
				<a href="<?php echo esc_url(add_query_arg(array('action' => 'shortcode'), admin_url('admin.php?page=posts-visitors-settings-page'))); ?>" class="nav-tab<?php if ($shortcode_Screen) echo ' nav-tab-active'; ?>"><?php esc_html_e('Short Code'); ?></a>
			</h2>

			<form action="options.php" method="post">
				<?php

				// if it shortcode tab section
				if ($shortcode_Screen) {
					settings_fields('posts_visitors_shorcode');  	   // option group security field
					do_settings_sections('posts-visitors-settings-shortcode');   // output settings section here
				}
				// if it general tab section
				else {
					settings_fields('posts_visitors_general');       // security field
					do_settings_sections('posts-visitors-settings-page');  // output settings section here
					submit_button(__('Save Settings','posts-visitors')); 				 // save settings button

				}
				?>
			</form>
		</div>


		<?php
	}
	public function posts_visitors_settings_init()
	{

		//******************************section  settings of shortcode******************************//
		//******************************************************************************************/

		add_settings_section(
			'posts_visitors_settings_section_shortcode', // ID
			__('Short Code','posts-visitors'), // Title
			array($this, 'post_visitores_description_section_shrotcode'), //callback
			'posts-visitors-settings-shortcode' //page
		);

		register_setting(
			'posts_visitors_shorcode', // Option group
			'posts_visitors_shorcode', // Option name
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			) // Sanitize
		);

		add_settings_field(
			'shortcode', // ID
			__('Short Code', 'posts-visitors'), // Title 
			array($this, 'posts_visiors_shortcode_callback'),  // Callback
			'posts-visitors-settings-shortcode', // Page
			'posts_visitors_settings_section_shortcode' // Section           
		);


		//******************************section settings of general********************************//
		//******************************************************************************************/

		add_settings_section(
			'posts_visitors_settings_section', // ID
			__('General','posts-visitors'), // Title
			array($this, 'posts_visitors_description_section'), //callback
			'posts-visitors-settings-page' //page
		);

		//******************************************************************************************/

		// Register label text
		register_setting(
			'posts_visitors_general', // Option group
			'posts_visitors_label_text', // Option name
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			)
		);

		// Add label text
		add_settings_field(
			'posts_visitors_label_text', //id
			__('Label Text', 'posts-visitors'), // Title 
			array($this, 'posts_visitors_label_text_callback'), // Callback
			'posts-visitors-settings-page', // Page
			'posts_visitors_settings_section' //Section    
		);
		//******************************************************************************************/

		// Register select option field
		register_setting(
			'posts_visitors_general', // Option group
			'posts_visitors_select',  //Option name
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			)
		);

		// Add select fields
		add_settings_field(
			'posts_visitors_select', //id
			__('Select Position', 'posts-visitors'), //title
			array($this, 'posts_visitors_position_callback'), // Callback
			'posts-visitors-settings-page', // Page
			'posts_visitors_settings_section' //Section   
		);

		//******************************************************************************************/

		// Register icon class
		register_setting(
			'posts_visitors_general', // Option group
			'posts_visitors_icon_class', //Option name
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			)
		);

		// Add icon class
		add_settings_field(
			'posts_visitors_icon_class', //id
			__('Icon Class', 'posts-visitors'), //title
			array($this, 'posts_visitors_icon_class_callback'), // Callback
			'posts-visitors-settings-page', // Page
			'posts_visitors_settings_section' //Section  
		);

		//******************************************************************************************/

		// Register checkbox icon
		register_setting(
			'posts_visitors_general', // Option group
			'posts_visitors_checkbox_icon', //Option name
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_key',
				'default' => ''
			)
		);
		// Add checkbox icon
		add_settings_field(
			'posts_visitors_checkbox_icon', //id
			__('Display (Icon)', 'posts-visitors'), //title
			array($this, 'posts_visitors_checkbox_icon_callback'), // Callback
			'posts-visitors-settings-page', // Page
			'posts_visitors_settings_section' //Section  
		);

		//******************************************************************************************/

		// Register color icon
		register_setting(
			'posts_visitors_general', // Option group
			'posts_visitors_color', //option name
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_hex_color',
				'default' => ''
			)
		);

		// Add color icon
		add_settings_field(
			'posts_visitors_color', //id
			__('Pick The Color Of The Icon', 'posts-visitors'), //title
			array($this, 'posts_visitors_color_callback'), // Callback
			'posts-visitors-settings-page', // Page
			'posts_visitors_settings_section' //Section  
		);

		//******************************************************************************************/
			// Register checkbox label
		register_setting(
			'posts_visitors_general', // Option group
			'posts_visitors_checkbox_label', //option name
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_key',
				'default' => ''
			)
		);
		// Add checkbox fields
		add_settings_field(
			'posts_visitors_checkbox_label', //id
			__('Display (Label)', 'posts-visitors'), //title
			array($this, 'posts_visitors_checkbox_label_callback'), // Callback
			'posts-visitors-settings-page', // Page
			'posts_visitors_settings_section' //Section 
		);
	}
		//*******************************callbacks functions of settings fields section general*********************/
		//**********************************************************************************************************/	
		/**
		 * label text template
		 */

		 public function posts_visitors_label_text_callback()
		{
			$label_text = get_option('posts_visitors_label_text','Post Visitors');
		?>
			<input type="text" name="posts_visitors_label_text" class="regular-text" value="<?php echo isset($label_text) ? esc_attr($label_text) : ''; ?>" />
			<p class="description"><?php _e('Enter the label text of the posts visitors counter.','posts-visitors')?></p>


		<?php
		}
		/**
		 * color template
		 */
		public function posts_visitors_color_callback()
		{
			$color = get_option('posts_visitors_color','#0000');
		?>
			<input type="color" name="posts_visitors_color" id="colorChoice" value="<?php echo isset($color) ? esc_attr($color) : ''; ?>" />
			<p class="description"><?php _e('Choose the Color of the icon .','posts-visitors')?></p>


		<?php
		}
		/**
		 * icon class template
		 */
		public function posts_visitors_icon_class_callback()
		{
			$icon_class = get_option('posts_visitors_icon_class','dashicons-visibility');
		?>
			<input type="text" name="posts_visitors_icon_class" class="regular-text" value="<?php echo isset($icon_class) ? esc_attr($icon_class) : 'dashicons-visibilit'; ?>" />
			<p class="description">Enter the Posts visitors icon class. Any of the <a href='https://developer.wordpress.org/resource/dashicons/' target="_blank">Dashicons</a> classes are available.</p>
		<?php
		}



		/**
		 * select template
		 */
		public function posts_visitors_position_callback()
		{
			$select= get_option('posts_visitors_select','before');
		?>
			<select name="posts_visitors_select" class="regular-text">
				<option value="" disabled selected>Select the position </option>
				<option value="after" <?php selected('after', $select); ?>> after the content</option>
				<option value="before" <?php selected('before', $select); ?>> before the content</option>
				<option value="manual" <?php selected('manual', $select); ?>> manual</option>

			</select>
			<p class="description">Select where would you like to display the Posts visitors counter. Use <code>[posts-visitors]</code> shortcode for manual display.</p>
		<?php
		}


		/**
		 * Chekcbox icon template
		 */
		public function posts_visitors_checkbox_icon_callback()
		{

			$display_icon = get_option('posts_visitors_checkbox_icon','icon');

		?>
			<label for="">
				<input type="checkbox" name="posts_visitors_checkbox_icon" value="icon" <?php checked('icon', $display_icon); ?> /> <?php _e('Icon','posts-visitors'); ?>

			</label>
			<p class="description"><?php _e('Check the box if you want to display the Icon.','posts-visitors')?></p>

		<?php
		}
		/**
		 * Chekcbox label template
		 */
		public function posts_visitors_checkbox_label_callback()
		{

				$display_label = get_option('posts_visitors_checkbox_label','label');
			?>
				<label for="">
					<input type="checkbox" name="posts_visitors_checkbox_label" value="label" <?php checked('label', $display_label); ?> /> <?php _e('Iabel','posts-visitors'); ?>

				</label>

			<?php
		}
		//*******************************callbacks functions of settings fields section short code*********************/
		//*************************************************************************************************************/	

		public function posts_visiors_shortcode_callback(){
			?>
		<input size="50" type="text" value="[posts-visitors]" id="myInput">
		
		<div class="tooltip">
		<button onclick="myFunction()" onmouseout="outFunc()">
		  <span class="tooltiptext" id="myTooltip">Copy Short Code</span>
		  Copy Short Code
		  </button>
		</div>
		<p class="description"><?php _e('Click on the button to copy the Short Code from the text field.')?></p>
		<br>
		<br>
			<input size="50" type="text" value="[posts-visitors icon_class='dashicons-visibility']" id="myInput3">
		
		<div class="tooltip3">
		<button onclick="myFunction3()" onmouseout="outFunc3()">
		  <span class="tooltiptext3" id="myTooltip3">Copy Short Code</span>
		  Copy Short Code
		  </button>
		</div>
		<p class="description">If you want to change the icon add the attribute <code>icon_class='dashicons-visibility'</code>and the class of icon you want to your shortcode. Any of the <a href= "https://developer.wordpress.org/resource/dashicons/" target="_blank" >Dashicons</a> classes are available.</p>
		
		<br>
		<br>
			<input  size="50" type="text" value="[posts-visitors icon='none']" id="myInput2">
		
		<div class="tooltip2">
		<button onclick="myFunction2()" onmouseout="outFunc2()">
		  <span class="tooltiptext2" id="myTooltip2">Copy Short Code</span>
		  Copy Short Code
		  </button>
		</div>
		<p class="description">If you want to not visisible the icon add the attribute  <code>icon='none'</code>to your shortcode.</p>
		<br>
		<br>
			<input size="50" type="text" value="[posts-visitors label_text='Post Visitors']" id="myInput4">
		
		<div class="tooltip4">
		<button onclick="myFunction4()" onmouseout="outFunc4()">
		  <span class="tooltiptext4" id="myTooltip4">Copy Short Code</span>
		  Copy Short Code
		  </button>
		</div>
		<p class="description">If you want to change the lable text add the attribute <code>label_text='Post Visitors'</code> with your new text to your shortcode.</p>
		<br>
		<br>
			<input size="50" type="text" value="[posts-visitors label='none']" id="myInput5">
		
		<div class="tooltip5">
		<button onclick="myFunction5()" onmouseout="outFunc5()">
		  <span class="tooltiptext5" id="myTooltip5">Copy Short Code</span>
		  Copy Short Code
		  </button>
		</div>
		<p class="description">If you want to not visisible the label add the attribute  <code>label='none'</code>to your shortcode.</p>
		
			<?php
		
		}
		//*******************************callbacks functions of sections****************************/
		//******************************************************************************************/	
		public function posts_visitors_description_section()
		{?>

			<p><?php _e( 'This section is for general options of how to display The Posts VisItors Counter.','posts-visitors');?></p>
			<?php
		}
		public function post_visitores_description_section_shrotcode()
		{	
			?>
			<P><?php _e( 'Use Short Code to put your posts visitors counter any place you want and change position manually.','posts-visitors'); ?></p>
		<?php

		}
	
}

$post_visitors_settings = new Posts_Visitors_Settings();
