<?php
/**
 * Aether
 *
 * @wordpress-plugin
 * Plugin Name:         Aether
 * Description:         The fifth element for Oxygen Builder.
 * Version:             0.0.1
 * Author:              oxyrealm
 * Author URI:          https://oxyrealm.com
 * Requires at least:   5.6
 * Tested up to:        5.7
 * Requires PHP:        7.4
 * Text Domain:         aether
 * Domain Path:         /languages
 *
 * @package             Aether
 * @author              oxyrealm <hello@oxyrealm.com>
 * @link                https://oxyrealm.com
 * @since               0.0.1
 * @copyright           2021 oxyrealm
 * @version             0.0.1
 */

defined( 'ABSPATH' ) || exit;

define( 'AETHER_VERSION', '0.0.1' );
define( 'AETHER_DB_VERSION', '001' );
define( 'AETHER_FILE', __FILE__ );
define( 'AETHER_PATH', dirname( AETHER_FILE ) );
define( 'AETHER_MIGRATION_PATH', AETHER_PATH . '/database/migrations/' );
define( 'AETHER_URL', plugins_url( '', AETHER_FILE ) );
define( 'AETHER_ASSETS', AETHER_URL . '/public' );

require_once __DIR__ . '/vendor/autoload.php';

use Oxyrealm\Aether\Assets;
use Oxyrealm\Aether\Utils\Migration;

final class Aether {

	public array $container = [
		'modules' => []
	];

	public function __construct() {
		register_activation_hook( AETHER_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( AETHER_FILE, [ $this, 'deactivate' ] );

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	public static function run(): Aether {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new Aether();
		}

		return $instance;
	}

	public function activate(): void {
		if ( ! get_option( 'aether_installed' ) ) {
			update_option( 'aether_installed', time() );
		}

		$installed_db_version = get_option( 'aether_db_version' );

		if ( ! $installed_db_version || intval( $installed_db_version ) !== intval( AETHER_DB_VERSION ) ) {
			Migration::migrate( AETHER_MIGRATION_PATH, "\\Oxyrealm\\Aether\\Database\\Migrations\\", $installed_db_version ?: 0, AETHER_DB_VERSION );
			update_option( 'aether_db_version', AETHER_DB_VERSION );
		}

		update_option( 'aether_version', AETHER_VERSION );
	}

	public function deactivate(): void {
	}

	/**
	 * 
	 * @param string $prop 
	 * @return mixed 
	 */
	public function __get( string $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	public function __isset( $prop ): bool {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

	public function init_plugin(): void {
		$this->register_assets();

		add_action( 'init', [ $this, 'boot' ] );
	}

	public function boot(): void {
		$this->localization();

		$this->container['api'] = new Oxyrealm\Aether\Api();

		if ( $this->is_request( 'admin' ) ) {
			$this->container['admin'] = new Oxyrealm\Aether\Admin();
		}

		if ( $this->is_request( 'frontend' ) ) {
			$this->container['frontend'] = new Oxyrealm\Aether\Frontend();
		}

		if ( $this->is_request( 'ajax' ) ) {
			$this->container['ajax'] = new Oxyrealm\Aether\Ajax();
		}

	}

	private function register_assets(): void {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', [ Assets::class, 'do_register' ], 5 );
		} else {
			add_action( 'wp_enqueue_scripts', [ Assets::class, 'do_register' ], 5 );
		}
	}

	private function is_request( string $type ): bool {
		// return match( $type ) {
		// 	'admin' => is_admin(),
		// 	'ajax' => defined( 'DOING_AJAX' ),
		// 	'rest' => defined( 'REST_REQUEST' ),
		// 	'cron' => defined( 'DOING_CRON' ),
		// 	'frontend' => ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ),
		// };

		/**
		 * @version php7.4
		 */
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'rest':
				return defined( 'REST_REQUEST' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			default:
				return false;
				break;
		}
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @uses load_plugin_textdomain()
	 */
	public function localization(): void {
		load_plugin_textdomain( 'aether', false, dirname( plugin_basename( AETHER_FILE ) ) . '/languages/' );
	}
}

$aether = Aether::run();
