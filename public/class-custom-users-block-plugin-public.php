<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/umairashraf1986
 * @since      1.0.0
 *
 * @package    Custom_Users_Block_Plugin
 * @subpackage Custom_Users_Block_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_Users_Block_Plugin
 * @subpackage Custom_Users_Block_Plugin/public
 * @author     Umair Ashraf <umair.ashraf1986@gmail.com>
 */
class Custom_Users_Block_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Users_Block_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Users_Block_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custom-users-block-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Users_Block_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Users_Block_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custom-users-block-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Register the JavaScript for Custom Users Gutenberg Block.
     *
     * @since    1.0.0
     */
    public function enqueue_block_assets() {
        wp_enqueue_script('custom-users-block', plugin_dir_url( __FILE__ ) . 'js/build/custom-users-block-build.js', array('wp-blocks', 'wp-editor', 'wp-components', 'wp-api', 'wp-i18n'));
    }

    /**
     * Register custom REST API endpoint to fetch users with email ending at '@rgbc.dev'.
     *
     * @since    1.0.0
     */
    public function register_custom_user_endpoint() {
        register_rest_route('custom/v1', '/users', array(
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => array($this, 'get_users_with_rgbc_email'),
        ));
    }

    public function get_users_with_rgbc_email() {
        global $wpdb;

        // Get users with email addresses ending in '@rgbc.dev'
        $users = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, display_name
            FROM {$wpdb->users}
            WHERE user_email LIKE %s",
                '%' . $wpdb->esc_like('@rgbc.dev')
            )
        );

        return array_map(function ($user) {
            return array(
                'id' => $user->ID,
                'name' => $user->display_name,
            );
        }, $users);
    }

    /**
     * Register custom users Gutenberg block
     *
     * @since    1.0.0
     */
    public function register_custom_users_block() {

        register_block_type('custom-users-block/block', array(
            'attributes' => array(
                'selectedUsers' => array(
                    'type' => 'array',
                    'default' => [],
                ),
            ),
            'editor_script' => 'custom-users-block',
            'render_callback' => array($this, 'render_custom_users_block'),
        ));

    }

    /**
     * Callback function for custom block render
     *
     * @since    1.0.0
     */
    public function render_custom_users_block($attributes) {
        if (is_user_logged_in()) {
            error_log(print_r($attributes, true));
            if(isset($attributes['selectedUsers'])) {
                $selectedUsers = $attributes['selectedUsers'];

                // Output HTML for each selected user
                $output = '<div class="custom-users-block">';

                foreach ($selectedUsers as $userId) {
                    $user = get_user_by('ID', $userId);

                    if ($user) {
                        $output .= '<div class="user">';
                        $output .= '<img src="' . get_avatar_url($userId) . '" alt="' . esc_attr($user->display_name) . '" />';
                        $output .= '<h2>' . esc_html($user->display_name) . '</h2>';
                        $output .= '<p>' . esc_html($user->user_email) . '</p>';
                        // Add more user information as needed
                        $output .= '</div>';
                    }
                }

                $output .= '</div>';

                return $output;
            }

            // If no user data is available or an error occurs, return a message
            return esc_html__('No user data available', 'custom-users-block-plugin');
        }
    }

}
