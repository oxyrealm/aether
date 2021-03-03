<?php

namespace Oxyrealm\Aether;

use Oxyrealm\Aether\Utils\Access;

/**
 * Admin Pages Handler
 */
class Admin {
	public function __construct() {
		if ( Access::can() ) {
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		}
	}

	public function admin_menu(): void {
		global $submenu;

		$capability = 'manage_options';
		$slug       = 'aether';

		$hook = add_menu_page(
			__( 'Aether', 'aether' ),
			__( 'Aether', 'aether' ),
			$capability,
			$slug,
			[
				$this,
				'plugin_page'
			],
			// 'data:image/svg+xml;base64,' . base64_encode( @file_get_contents( AETHER_PATH . '/public/img/icon.svg' ) ),
			// 2
		);

		if ( current_user_can( $capability ) ) {
			$submenu[ $slug ][] = [ __( 'Dashboard', 'aether' ), $capability, "admin.php?page={$slug}#/" ];
		}

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
}
