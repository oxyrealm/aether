<?php

namespace Oxyrealm\Aether;

/**
 * Scripts and Styles Class
 */
class Assets {

	function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
		} else {
			add_action( 'wp_enqueue_scripts', [ $this, 'register' ], 5 );
		}
	}

	public function register(): void {
		$this->register_scripts( $this->get_scripts() );
		$this->register_styles( $this->get_styles() );
	}

	private function register_scripts( array $scripts ): void {
		foreach ( $scripts as $handle => $script ) {
			$src       = AETHER_ASSETS . $script['src'];
			$deps      = $script['deps'] ?? [];
			$in_footer = $script['in_footer'] ?? true;
			$version   = WP_DEBUG ? filemtime( AETHER_PATH . '/public/' . $script['src'] ) : AETHER_VERSION;

			wp_register_script( $handle, $src, $deps, $version, $in_footer );
		}
	}

	public function register_styles( array $styles ): void {
		foreach ( $styles as $handle => $style ) {
			$deps = $style['deps'] ?? [];

			wp_register_style( $handle, $style['src'], $deps, AETHER_VERSION );
		}
	}

	public function get_scripts(): array {
		return [
			// 'aether-manifest' => [
			// 	'src'       => '/js/manifest.js',
			// 	'deps'      => [ 'wp-i18n' ],
			// ],
			// 'aether-vendor'   => [
			// 	'src'       => '/js/vendor.js',
			// 	'deps'      => [ 'aether-manifest' ],
			// ],
			// 'aether-frontend' => [
			// 	'src'       => '/js/frontend.js',
			// 	'deps'      => [ 'aether-vendor' ],
			// ],
			// 'aether-admin'    => [
			// 	'src'       => '/js/admin.js',
			// 	'deps'      => [ 'aether-vendor', 'aether-manifest' ],
			// ],
		];
	}

	public function get_styles(): array {
		return [
			// 'aether-style'    => [
			// 	'src' => AETHER_ASSETS . '/css/app.css'
			// ],
			// 'aether-frontend' => [
			// 	'src' => AETHER_ASSETS . '/css/frontend.css'
			// ],
			// 'aether-admin'    => [
			// 	'src' => AETHER_ASSETS . '/css/admin.css'
			// ],
		];
	}
}
