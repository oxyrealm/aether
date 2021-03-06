<?php

namespace Oxyrealm\Aether\Utils;

use Exception;
use eftec\bladeone\BladeOne;

class Blade {
	private static $instances = [];

	private static $blade;

	protected function __construct() {
		$views = AETHER_PATH . '/views';
		$cache = AETHER_PATH . '/cache';
		self::$blade = new BladeOne( $views, $cache, WP_DEBUG ? BladeOne::MODE_SLOW : BladeOne::MODE_AUTO ); 
	}

	public static function getInstance(): Blade {
		$cls = static::class;
		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new static();
		}

		return self::$instances[ $cls ];
	}

	public static function __callStatic( string $method, array $args ) {
		return self::getInstance()::$blade->{$method}( ...$args );
	}

	public function __get( string $name ) {
		return self::getInstance()::$blade->{$name};
	}

	public function __wakeup() {
		throw new Exception( "Cannot unserialize a singleton." );
	}

	protected function __clone() {
	}

	public static function blade() {
		return self::getInstance()::$blade;
	}
}
