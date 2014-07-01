<?php
/**
 * Featurlicious.
 *
 * @package   Featurlicious
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class.
 *
 * @package Featurlicious
 * @author  Your Name <email@example.com>
 */
class Featurlicious {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'featurlicious';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = 'featurlicious/featurlicious-admin';

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		// add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action( 'admin_menu', array($this, 'sm_register_featurlicious_menu_page' ));

		add_action( 'wp_ajax_sim_search', array( $this, 'sm_search_posts' ));
		add_action( 'wp_ajax_sim_update_area', array( $this, 'sm_update_area' ));
		add_action( 'wp_ajax_sim_remove_post', array( $this, 'sm_remove_post' ));
		add_action( 'wp_ajax_sim_remove_area', array( $this, 'sm_remove_area' ));

		add_action( 'init', array( $this, 'sm_create_area_post_type' ));
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/styles/stylesheets/screen.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {


		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
			wp_enqueue_script("jquery-ui-draggable");
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {


		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'featurlicious' to the name of your plugin
		 */
		$this->plugin_screen_hook_suffix = add_plugins_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
			);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}

	/* Mis funciones */

	/**
	*
	*/
	public function sm_register_featurlicious_menu_page() {
		add_menu_page( 'Featurlicious', 'Featurlicious', 'manage_options', 'featurlicious/featurlicious-admin.php', '', '', 26 );
	}

	/**
	*
	*/
	public function sm_search_posts() {

		$search = $_REQUEST["search"];

		$args = array(
			's' => $search,
			'post_type' => 'any'
			);

		$result = new WP_QUERY($args);

		$posts = array();

		if($result->have_posts()) {
			while($result->have_posts()) {
				$result->the_post();
				if(get_post_type() != 'sim_featured_area') {
					$post['the_id'] = get_the_ID();
					$post['the_title'] = get_the_title();
					$post['the_permalink'] = get_the_permalink();
					array_push($posts, $post);
				}
			}
		}

		wp_reset_postdata();

		echo json_encode($posts);
		die();
	}

	/**
	*
	*
	*/
	public function sm_create_area_post_type() {
		
		$args = array(
			'public'             => false,
			'exclude_from_search'=> false,
			'publicly_queryable' => false,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'excerpt', 'custom-fields' )
			);

		register_post_type( 'sim_featured_area', $args );

	}

	/**
	*
	*
	*/
	public function sm_create_area($title, $description) {
		
		$post = array(
			'post_title' => $title,
			'post_excerpt' => $description,
			'post_status' => 'publish',
			'post_type' => 'sim_featured_area'
			);

		$post_id = wp_insert_post($post);
	}

	/**
	*
	*
	*/
	public function sm_read_areas() {
		
		$args = array(
			'post_type' => 'sim_featured_area'
			);

		$result = new WP_QUERY($args);
		wp_reset_postdata();

		return $result;
	}

	/**
	*
	*
	*/
	public function sm_update_area() {

		$post_id = $_REQUEST['postId'];
		$post_title = $_REQUEST['title'];
		$post_permalink = $_REQUEST['permalink'];
		$area_id = $_REQUEST['id'];

		$new_post = array($post_id, $post_title, $post_permalink);

		$posts = get_post_meta($area_id, 'sim_posts', true);

		if(empty($posts)) {
			$posts = array();
		}

		array_push($posts, $new_post);
		$result = update_post_meta($area_id, 'sim_posts', $posts);

		echo json_encode($new_post);
		die();
	}

	/**
	*
	*/
	public function sm_remove_post() {

		$area_id = $_REQUEST['areaId'];
		$post_id = $_REQUEST['postId'];

		$posts = get_post_meta($area_id, 'sim_posts', true);

		foreach($posts as $key => $post) {
			if($post[0] == $post_id) {
				unset($posts[$key]);
				break;
			}
		}

		$result = update_post_meta($area_id, 'sim_posts', $posts);

		echo json_encode($posts);
		die();
	}

	/**
	*
	*/
	public function sm_remove_area() {

		$area_id = $_REQUEST['areaId'];

		wp_delete_post( $area_id );
	}

	/**
	*
	*/
	public function get_featured_area($area_id) {

		$args = array(
			'p' => $area_id,
			'post_type' => 'sim_featured_area'
			);

		$result = new WP_QUERY($args);
		wp_reset_postdata();

		$response = array();

		if($result->have_posts()) {
			while($result->have_posts()) {
				$result->the_post();
				$response['the_title'] = get_the_title();
				$response['the_description'] = get_the_excerpt();
				$posts = get_post_meta($area_id, 'sim_posts', true);
				if($posts) {
					$posts_ids = array();
					foreach($posts as $post) {
						array_push($posts_ids, $post[0]);
					}
				}				
			}
		}

		$posts = array();

		if(!empty($posts_ids)) {
			
			foreach($posts_ids as $id) {
				$post = get_post( $id );
				array_push($posts, $post);
			}
		}

		return $posts;		
	}
}
