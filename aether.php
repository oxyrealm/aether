<?php
/**
 * Aether
 *
 * @wordpress-plugin
 * Plugin Name:         Aether
 * Description:         The backbone and framework for all OxyRealm's plugins.
 * Version:             1.1.2
 * Author:              dPlugins
 * Author URI:          https://dplugins.com
 * Requires at least:   5.6
 * Tested up to:        5.7.2
 * Requires PHP:        7.4
 * Text Domain:         aether
 * Domain Path:         /languages
 *
 * @package             Aether
 * @author              oxyrealm <hello@oxyrealm.com>
 * @link                https://oxyrealm.com
 * @since               1.0.0
 * @copyright           2021 oxyrealm
 * @version             1.1.2
 */

defined( 'ABSPATH' ) || exit;

define( 'AETHER_VERSION', '1.1.2' );
define( 'AETHER_DB_VERSION', '001' );
define( 'AETHER_FILE', __FILE__ );
define( 'AETHER_PATH', dirname( AETHER_FILE ) );
define( 'AETHER_MIGRATION_PATH', AETHER_PATH . '/database/migrations/' );
define( 'AETHER_URL', plugins_url( '', AETHER_FILE ) );
define( 'AETHER_ASSETS', AETHER_URL . '/public' );

require_once __DIR__ . '/vendor/autoload.php';

use Oxyrealm\Aether\Admin;
use Oxyrealm\Aether\Assets;
use Oxyrealm\Aether\Utils;
use Oxyrealm\Aether\Utils\Migration;

final class Aether {

	public function __construct() {
		add_filter( 'plugin_action_links_' . plugin_basename( AETHER_FILE ), function ( $links ) {
			return Utils::plugin_action_links( $links, Admin::$slug );
		} );

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

	public function init_plugin(): void {
		$this->register_assets();

		add_action( 'init', [ $this, 'boot' ] );
	}

	public function boot(): void {
		Assets::do_register();

		new Admin();
	}

	private function register_assets(): void {
		Assets::register_style( "aether-admin-main", AETHER_URL . '/public/css/admin/main.css' );
		Assets::register_script( "aether-admin-main", AETHER_URL . '/public/js/admin/main.js' );
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
}

$aether = Aether::run();
