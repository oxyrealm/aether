<?php

namespace Oxyrealm\Aether\Utils;

use Exception;
use Oxyrealm\Aether\Libs\Notice as LibsNotice;

/**
 * @method static void success( $messages, $module_name = false )
 * @method static void warning( $messages, $module_name = false )
 * @method static void error( $messages, $module_name = false )
 * @method static void info( $messages, $module_name = false )
 * @method static void init()
 */
class Notice {
	private static $instances = [];

	private static $notice;

	protected function __construct() {
		self::$notice = new LibsNotice();
	}

	public static function __callStatic( $method, $args ) {
		return self::getInstance()::$notice->{$method}( ...$args );
	}

	public static function getInstance(): Notice {
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