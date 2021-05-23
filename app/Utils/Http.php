<?php

namespace Oxyrealm\Aether\Utils;

use Exception;
use GuzzleHttp\Client;

class Http {
	private static $instances = [];

	private static $client;

	protected function __construct() {
		self::$client = new Client( [
			'timeout' => 60,
			'headers' => [
				'Accept' => 'application/json',
			]
		] );
	}

	public static function __callStatic( $method, $args ) {
		return self::getInstance()::$client->{$method}( ...$args );
	}

	public static function getInstance(): Http {
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