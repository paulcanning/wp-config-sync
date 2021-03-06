<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Config_Sync
 * @subpackage Wp_Config_Sync/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Config_Sync
 * @subpackage Wp_Config_Sync/includes
 * @author     Your Name <email@example.com>
 */
class Wp_Config_Sync {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Config_Sync_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wp_config_sync    The string used to uniquely identify this plugin.
	 */
	protected $wp_config_sync;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	protected $file;
	
	protected $options;
	
	protected $yaml;
	
	protected $config;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_CONFIG_SYNC_VERSION' ) ) {
			$this->version = WP_CONFIG_SYNC_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->wp_config_sync = 'wp-config-sync';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		$this->options = new Wp_Config_Sync_Options();
		$this->yaml = new Wp_Config_Sync_Yml();
		$this->file = WP_CONFIG_SYNC_PATH . '/config.yml';
		
		$sync = function( $args, $assoc_args ) {
			switch($args[0]) {
				case 'export':
					WP_CLI::confirm( "Are you sure you want to export the config?", $assoc_args );
					
					$export_options = $this->options->getOptions();
					$this->config = $this->options->unserializeOptions( $export_options );

					try {
						$this->yaml->dump( $this->config, $this->file );					
						WP_CLI::success( 'Config exported!' );
					} catch(Exception $e) {
						WP_CLI::error( $e->getMessage() );
					}
					
					break;
				case 'import':
					WP_CLI::confirm( "Are you sure you want to import the config?", $assoc_args );
					
					$import_options = $this->yaml->parse( $this->file );
					$current_options = $this->options->getOptions();
					$this->config = $this->options->serializeOptions( $import_options );
					$config_changes = array_diff( $this->config, $current_options );
					
					if( count( $config_changes ) > 0) {						
						$progress = WP_CLI\Utils\make_progress_bar( 'Importing config', count( $config_changes ) );
						
						foreach($config_changes as $name => $value) {
							if( update_option( $name, $value ) ) {
								WP_CLI::log( $name . ' updated: ' . $value );
								$progress->tick();
							}
						}
						
						$progress->finish();
					} else {
						WP_CLI::success( 'All options are up-to-date.' );
					}
					
					break;
				default:
					WP_CLI::error( 'Invalid/No argument supplied' );
					break;
			}
		};
		
		if ( class_exists( 'WP_CLI' ) ) {
			WP_CLI::add_command( 'config-sync', $sync );
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Config_Sync_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Config_Sync_i18n. Defines internationalization functionality.
	 * - Wp_Config_Sync_Admin. Defines all hooks for the admin area.
	 * - Wp_Config_Sync_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-config-sync-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-config-sync-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-config-sync-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-config-sync-public.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-config-sync-options.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-config-sync-yml.php';

		$this->loader = new Wp_Config_Sync_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Config_Sync_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Config_Sync_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Config_Sync_Admin( $this->get_wp_config_sync(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Config_Sync_Public( $this->get_wp_config_sync(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_wp_config_sync() {
		return $this->wp_config_sync;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Config_Sync_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
