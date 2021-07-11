<?php

namespace Oxyrealm\Aether\Utils;

use Exception;
use Oxyrealm\Aether\Libs\Transient as LibsTransient;

/**
 * @method static bool has( string $key )
 * @method static bool delete( string $key )
 * @method static mixed|false get( string $key, mixed $default = null )
 * @method static void set( string|array $key, mixed $value = null, int $ttl = 0 )
 * @method static mixed remember( string $key, int $ttl, \Closure $callback )
 */
class Transient {
	private static $instances = [];

	private static $transient;

	protected function __construct() {
		self::$transient = new LibsTransient();
	}

	public static function __callStatic( $method, $args ) {
		return self::getInstance()::$transient->{$method}( ...$args );
	}

	public static function getInstance(): Transient {
		$cls = static::class;
		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new static();
		}

		return self::$instances[ $cls ];
	}

	public function __wakeup() {
		throw new Exception( "Cannot unserialize a singleton." );
	}

	protected function __clone() {
	}
}