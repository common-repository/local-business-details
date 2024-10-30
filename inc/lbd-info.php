<?php
/**
 *
 * @package    Local Business Details
 * @copyright  Copyright (c) 2021, Ray DelVecchio
 * @license    GPL-2.0+
 *
 */

class LocalBusiness {
	private $local_business_options;

	public function __construct() {
		// Add Settings page on WP admin
		add_action( 'admin_menu', array( $this, 'local_business_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'local_business_page_init' ) );
		
		// Register shortcodes on 'init'
		add_action( 'init', array( $this, 'lbd_shortcodes' ) );

		// Enqueue stylesheets on 'wp_enqueue_scripts'
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 1 );
		
		// Hook into closing body tag for HTML output
		add_action( 'wp_footer', array( $this, 'show_cta_button' ) );	
	}

	
	/**
	 * Registers the [biz_DETAIL] shortcodes.
	 * Where DETAIL = Phone Number, Contact Link, or Physical Address
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function lbd_shortcodes() {
		add_shortcode( 'biz_number', array( $this, 'biz_number_shortcode' ) );
		add_shortcode( 'biz_contact', array( $this, 'biz_contact_shortcode' ) );
		add_shortcode( 'biz_address', array( $this, 'biz_address_shortcode' ) );
	}


	/**
	 * Enqueues the CSS stylesheet for the sticky contact button
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function enqueue_styles() {

		// Use the .min stylesheet if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Enqueue the stylesheet
		wp_enqueue_style(
			'cta-button',
			trailingslashit( LBD_URL ) . "assets/css/cta-button$suffix.css",
			null,
			'20210703'
		);
		
		// Enqueue the script
		wp_enqueue_script(
			'cta-button-ux',
			trailingslashit( LBD_URL ) . "assets/js/cta-button.js",
			null,
			'20210703',
			true
		);		
	}


	/**
	 * Create settings page for plugin
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function local_business_add_plugin_page() {
		add_options_page(
			'Local Business', // page_title
			'Local Business', // menu_title
			'manage_options', // capability
			'local-business', // menu_slug
			array( $this, 'local_business_create_admin_page' ) // function
		);
	}


	/**
	 * Output content for the plugin settings page
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function local_business_create_admin_page() {
		$this->local_business_options = get_option( 'local_business_option_name' ); ?>

		<div class="wrap">
			<h2>Local Business</h2>
			<p>Enter your business details below, so website visitors can easily find &amp; get in touch with you.</p>
			<?php /* settings_errors(); */ ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'local_business_option_group' );
					do_settings_sections( 'local-business-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }


	/**
	 * Register the settings to be stored in the wp_options database table
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function local_business_page_init() {
		register_setting(
			'local_business_option_group', // option_group
			'local_business_option_name', // option_name
			array( $this, 'local_business_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'local_business_setting_section', // id
			'Settings', // title
			array( $this, 'local_business_section_info' ), // callback
			'local-business-admin' // page
		);

		add_settings_field(
			'phone_number_0', // id
			'Phone Number', // title
			array( $this, 'phone_number_0_callback' ), // callback
			'local-business-admin', // page
			'local_business_setting_section' // section
		);

		add_settings_field(
			'contact_link_1', // id
			'Contact Link', // title
			array( $this, 'contact_link_1_callback' ), // callback
			'local-business-admin', // page
			'local_business_setting_section' // section
		);

		add_settings_field(
			'address_2', // id
			'Address', // title
			array( $this, 'address_2_callback' ), // callback
			'local-business-admin', // page
			'local_business_setting_section' // section
		);
		
		add_settings_field(
			'cta_button_3', // id
			'CTA Button', // title
			array( $this, 'cta_button_3_callback' ), // callback
			'local-business-admin', // page
			'local_business_setting_section' // section
		);
		
		add_settings_field(
			'cta_image_link_4', // id
			'CTA Image', // title
			array( $this, 'cta_image_link_4_callback' ), // callback
			'local-business-admin', // page
			'local_business_setting_section' // section
		);		
	}
	
	
	/**
	 * Sanitize the options values
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function local_business_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['phone_number_0'] ) ) {
			$sanitary_values['phone_number_0'] = sanitize_text_field( $input['phone_number_0'] );
		}

		if ( isset( $input['contact_link_1'] ) ) {
			$sanitary_values['contact_link_1'] = sanitize_text_field( $input['contact_link_1'] );
		}

		if ( isset( $input['address_2'] ) ) {
			$sanitary_values['address_2'] = sanitize_textarea_field( $input['address_2'] );
		}
		
		if ( isset( $input['cta_button_3'] ) ) {
			$sanitary_values['cta_button_3'] = $input['cta_button_3'];
		}

		if ( isset( $input['cta_image_link_4'] ) ) {
			$sanitary_values['cta_image_link_4'] = sanitize_text_field( $input['cta_image_link_4'] );
		}		

		return $sanitary_values;
	}

	
	public function local_business_section_info() {
		// Nothing, yet.
	}
	
	/**
	 * Add settings page text input field for phone number
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function phone_number_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="local_business_option_name[phone_number_0]" id="phone_number_0" value="%s">',
			isset( $this->local_business_options['phone_number_0'] ) ? esc_attr( $this->local_business_options['phone_number_0']) : ''
		);
	}


	/**
	 * Add settings page text input field for contact link
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function contact_link_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="local_business_option_name[contact_link_1]" id="contact_link_1" value="%s">',
			isset( $this->local_business_options['contact_link_1'] ) ? esc_attr( $this->local_business_options['contact_link_1']) : ''
		);
	}


	/**
	 * Add settings page textarea field for business address
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function address_2_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="local_business_option_name[address_2]" id="address_2">%s</textarea>',
			isset( $this->local_business_options['address_2'] ) ? esc_attr( $this->local_business_options['address_2']) : ''
		);
	}

	/**
	 * Add settings page checkbox input field for sticky CTA button
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */	
	public function cta_button_3_callback() {
		printf(
			'<input type="checkbox" name="local_business_option_name[cta_button_3]" id="cta_button_3" value="cta_button_3" %s> <label for="cta_button_3">Display a sticky call-to-action button on every page</label>',
			( isset( $this->local_business_options['cta_button_3'] ) && $this->local_business_options['cta_button_3'] === 'cta_button_3' ) ? 'checked' : ''
		);
	}

	/**
	 * Add settings page text input field for image URL
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */	
	public function cta_image_link_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="local_business_option_name[cta_image_link_4]" id="cta_image_link_4" value="%s">',
			isset( $this->local_business_options['cta_image_link_4'] ) ? esc_attr( $this->local_business_options['cta_image_link_4']) : ''
		);
	}	

	/**
	 * Returns the local business phone number from the settings page via shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $atts The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 * @return string
	 */
	public function biz_number_shortcode( $atts, $content = null ) {
		// Set default attributes
		$args = shortcode_atts( array(
			'link' => 'true',
			'text' => 'phone',
		), $atts, 'biz_number' );

		// Filter to turn attribute string to boolean value
		$args['link'] = filter_var( $args['link'], FILTER_VALIDATE_BOOLEAN );
		
		// Get array of all custom WordPress options from plugin
		$local_business_options = get_option( 'local_business_option_name' );
		
		// Ensure the phone number option is enabled
		if (isset( $local_business_options['phone_number_0'] )) {
			
			// Set text to phone number if it exists
			if ($args['text'] == 'phone') $args['text'] = $local_business_options['phone_number_0'];
			
			if( $args['link'] ){
				// Hook into PHP library for country code?
				return '<p class="phone-number"><a href="' . esc_url('tel:+1' . preg_replace('~\D~', '', $local_business_options['phone_number_0'])) . '">' . esc_html($args['text']) .  '</a></p>';
			} else {
				return '<p class="phone-number">' . esc_html($local_business_options['phone_number_0']) . '</p>';
			}			
		} else {
			return '<p>Set your phone number!</p>';
		}
	}
	
	/**
	 * Returns the local business contact link from the settings page via shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $atts The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 * @return string
	 */	
	public function biz_contact_shortcode( $atts, $content = null ) {
		// Set default attributes
		$args = shortcode_atts( array(
			'link' => 'true',
			'text' => 'Contact Us',
		), $atts, 'biz_contact' );
		
		// Filter to turn attribute string to boolean value
		$args['link'] = filter_var( $args['link'], FILTER_VALIDATE_BOOLEAN );
		
		// Get array of all custom WordPress options from plugin
		$local_business_options = get_option( 'local_business_option_name' );
		
		// Ensure the custom setting exists
		// Then generate HTML link or URL string
		if (isset( $local_business_options['contact_link_1'] )) {
			if( $args['link'] ){
				return '<p><a href="' . esc_url($local_business_options['contact_link_1']) . '">' . esc_html($args['text']) .  '</a></p>';
			} else {
				return '<p>' . esc_html($local_business_options['contact_link_1']) . '</p>';
			}
		} else {
			return 'Set your contact link!';
		}
	}
	
	/**
	 * Returns the local business physical address from the settings page via shortcode.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $atts The user-inputted arguments.
	 * @param  string $content The content to wrap in a shortcode.
	 * @return string
	 */		
	public function biz_address_shortcode( ) {
		// Get array of all custom WordPress options from plugin
		$local_business_options = get_option( 'local_business_option_name' );
		
		// Add breaks to display address on multiple lines
		return isset( $local_business_options['address_2'] ) ? '<p>' . nl2br(esc_html( $local_business_options['address_2'])) . '</p>' : '<p>Insert Physical Address</p>';
	}
	
	/**
	 * Displays the CTA button on the front-end which sticks to bottom-right of page
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */		
	public function show_cta_button( ) {
		// Get array of all custom WordPress options from plugin
		$local_business_options = get_option( 'local_business_option_name' );

		// Determine whether to use image (user setting) or chat emoji (default)
		// Get the image link from settings page
		$imageLink = (isset( $local_business_options['cta_image_link_4'] ) && $local_business_options['cta_image_link_4'] !== '') ? $local_business_options['cta_image_link_4'] : null;
		
		// Check that the image link exists
		if ( $imageLink ) {
			$headerRequest = get_headers($imageLink); // Check URL
			$string = $headerRequest[0]; // HTTP Response Code
			// Find "OK" response, add class and style for image
			if ( strpos($string,"200") !== false ) $btnHtml = ' class="img" style="background-image: url(' . esc_url($local_business_options['cta_image_link_4']) . '); background-size: cover;"';
		}
		
		// If setting exists and it's set to checked, show CTA button
		if (isset( $local_business_options['cta_button_3'] ) && $local_business_options['cta_button_3'] === 'cta_button_3' ) {
			echo '<!-- Chat Scheduler -->
				<div id="drive-scheduler-wrapper">
					<div id="drive-scheduler-btn"' . (isset($btnHtml) ? $btnHtml : '') . '></div>
					<div id="drive-scheduler">
						<div class="close-btn">&times;</div>
						<div class="title-bar">Get in Touch!</div>
						<div class="provider chat"><p>Hello! How would you like to contact us?</p></div>
						<div class="customer chat phone">' . do_shortcode( '[biz_number text="Call now"]' ) . '</div>
						<div class="customer chat email">' . do_shortcode( '[biz_contact text="Send a message"]' ) . '</div>
					</div>
				</div>';
		} else {
			echo '<div class="dummy"></div>';
		}		
	}	

}
// If you only want this to operate on WP admin backend
//if ( is_admin() )
	
// Front-end implementation (not just admin)
$local_business = new LocalBusiness();

/* 
 * Retrieve this value with:
 * $local_business_options = get_option( 'local_business_option_name' ); // Array of All Options
 * $phone_number_0 = $local_business_options['phone_number_0']; // Phone Number
 * $contact_link_1 = $local_business_options['contact_link_1']; // Contact Link
 * $address_2 = $local_business_options['address_2']; // Address
 */
