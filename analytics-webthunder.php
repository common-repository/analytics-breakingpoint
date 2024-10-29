<?php
/*
Plugin Name: Analytics WebThunder
Description: Analytics information from WebThunder.IO
Version: 0.0.1
Author: WebThunder.IO
Author URI: https://www.webthunder.io/
License: GNU General Public License v2
*/
/*
Copyright 2019 WebThunder

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Settings_Analytics_WebThunder_Information
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Analytics WebThunder',
            'manage_options',
            'analytics-webthunder',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'optiune_webthunder' );
        ?>
        <div class="wrap">
            <h1>Settings for Analytics WebThunder plugin</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'analytics-webthunder' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'optiune_webthunder', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Analytics WebThunder', // Title
            array( $this, 'print_section_info' ), // Callback
            'analytics-webthunder' // Page
        );

        add_settings_field(
            'id_number', // ID
            'Website ID Number', // Title
            array( $this, 'id_number_callback' ), // Callback
            'analytics-webthunder', // Page
            'setting_section_id' // Section
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = $input['id_number']; // absint( $input['id_number'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Please enter web site ID below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="optiune_webthunder[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }
}

if( is_admin() ):
    $my_settings_page = new Settings_Analytics_WebThunder_Information();
endif;


$options = get_option( 'optiune_webthunder' );
//var_dump($options);

if( isset( $options['id_number'] ) )
{
	$no_exists_value = $options['id_number'];
	//echo $no_exists_value;

	//echo "test";

	function WebThunder_Analytics_Footer_Function() {

		$options = get_option( 'optiune_webthunder' );
		$no_exists_value = $options['id_number'];

		echo "<!-- https://www.webthunder.io/ code start -->";
		echo "<script type='text/javascript'>";
		echo "(function(w,d){";
		echo "       h     = d.getElementsByTagName('head')[0];";
		echo "       s     = d.createElement('script'); s.async=1; s.setAttribute('data-website-id', '".$no_exists_value."');";
		echo "       s.src = 'https://analytics.webthunder.io/analytics.js';";
		echo "        h.appendChild(s);";
		echo "      })(window,document);";
		echo "</script>";
		echo "<!-- https://www.webthunder.io/ code end -->";
	}

	add_action('wp_footer', 'WebThunder_Analytics_Footer_Function', 5);

}

