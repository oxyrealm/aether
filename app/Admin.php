<?php

namespace Oxyrealm\Aether;

use Oxyrealm\Aether\Utils\Access;
use Oxyrealm\Aether\Utils\Blade;

/**
 * Admin Pages Handler
 */
class Admin {
	protected string $capability;

	public const SLUG = 'aether';
	public static array $setting_tabs = [];

	public function __construct() {
		$this->capability = 'manage_options';

		if ( Access::can() ) {
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
			add_action( 'admin_menu', [ $this, 'settings_menu' ], 1000 );
		}
	}

	public function admin_menu(): void {
		global $submenu;


		$hook = add_menu_page(
			__( 'Aether', 'aether' ),
			__( 'Aether', 'aether' ),
			$this->capability,
			self::SLUG ,
			[
				$this,
				'plugin_page'
			],
			// 'data:image/svg+xml;base64,' . base64_encode( @file_get_contents( AETHER_PATH . '/public/img/icon.svg' ) ),
			// 2
		);

		$submenu[ self::SLUG ][] = [ __( 'Dashboard', 'aether' ), $this->capability, 'admin.php?page='.self::SLUG ];

		add_action( 'load-' . $hook, [ $this, 'init_hooks' ] );
	}

	/**
	 * Initialize our hooks for the admin page
	 */
	public function init_hooks(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Load scripts and styles for the app
	 */
	public function enqueue_scripts(): void {
		// wp_enqueue_style( 'aether-admin' );
		// wp_enqueue_script( 'aether-admin' );
		// wp_set_script_translations( 'aether-admin', 'aether', AETHER_PATH . '/languages/' );
		// wp_localize_script(
		// 	'aether-admin',
		// 	'aether',
		// 	[
		// 		'ajax_url' => admin_url( 'admin-ajax.php' ),
		// 		'nonce'    => wp_create_nonce( 'aether-admin' ),
		// 	]
		// );
	}

	/**
	 * Render our admin page
	 */
	public function plugin_page(): void {
		echo '<div id="aether-app"></div>';
	}

	public function settings_menu() {
		add_submenu_page(
			self::SLUG,
			__( 'Settings', 'aether' ),
			__( 'Settings', 'aether' ),
			$this->capability,
			self::SLUG . '_settings',
			[
				$this,
				'settings_page'
			],
		);
	}

	public function settings_page() {
		$selected_tab = ! empty( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';

		array_unshift(self::$setting_tabs, [
			'id' => 'general',
			'label' => __( 'General', 'aether' ),
			'contents' => []
		]);

		$arr_k = array_keys(
			array_column(self::$setting_tabs, 'id'), 
			$selected_tab
		);

		$blade = Blade::blade();
		echo $blade->run('layouts.settings', [
			'tabs' => self::$setting_tabs,
			'selected_tab' => $selected_tab,
			'contents' => ! empty( $arr_k ) ? self::$setting_tabs[$arr_k[0]]['contents'] : []
		]);
	
	}
}
