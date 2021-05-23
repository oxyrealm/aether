<?php

namespace Oxyrealm\Aether;

class Assets {
	private static $styles = [];
	private static $scripts = [];

	public static function do_register(): void {
		self::$scripts = array_filter(
			self::$scripts,
			fn( $script, $handle ): bool => ! wp_register_script(
				$handle, $script['src'],
				$script['deps'] ?? [],
				$script['version'] ?: AETHER_VERSION,
				$script['in_footer'] ?? true
			),
			ARRAY_FILTER_USE_BOTH
		);

		self::$styles = array_filter(
			self::$styles,
			fn( $style, $handle ): bool => ! wp_register_style(
				$handle,
				$style['src'],
				$style['deps'] ?? [],
				$style['version'] ?: AETHER_VERSION
			),
			ARRAY_FILTER_USE_BOTH
		);
	}

	public static function register_scripts( $scripts ) {
		self::$scripts = array_merge( self::$scripts, $scripts );
	}

	public static function register_styles( $styles ) {
		self::$styles = array_merge( self::$styles, $styles );
	}

	public static function register_script( $handle, $src, $deps = [], $ver = false, $in_footer = false ) {
		self::$scripts[ $handle ] = [
			'src'       => $src,
			'deps'      => $deps,
			'version'   => $ver,
			'in_footer' => $in_footer,
		];
	}

	public static function register_style( $handle, $src, $deps = [], $ver = false, $media = 'all' ) {
		self::$styles[ $handle ] = [
			'src'     => $src,
			'deps'    => $deps,
			'version' => $ver,
			'media'   => $media,
		];
	}

}
