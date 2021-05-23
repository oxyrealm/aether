<?php

namespace Oxyrealm\Aether\Utils;

use Exception;

/**
 * @method static void success( $messages, $module_name = false )
 * @method static void warning( $messages, $module_name = false )
 * @method static void error( $messages, $module_name = false )
 * @method static void info( $messages, $module_name = false )
 * @method static void init()
 */
class Notice {
	private const ERROR = 'error';
	private const SUCCESS = 'success';
	private const WARNING = 'warning';
	private const INFO = 'info';

	private static $instances = [];

	public static function __callStatic( $method, $args ) {
		return self::getInstance()->{$method}( ...$args );
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

	private function init() {
		foreach (
			[
				self::ERROR,
				self::SUCCESS,
				self::WARNING,
				self::INFO,
			] as $level
		) {
			$messages = get_transient( "aether_notice_{$level}" );

			if ( $messages && is_array( $messages ) ) {
				foreach ( $messages as $message ) {
					echo sprintf(
						'<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
						$level,
						$message
					);
				}

				delete_transient( "aether_notice_{$level}" );
			}
		}
	}

	private function adds( $level, $messages, $module_name = false ) {
		if ( ! is_array( $messages ) ) {
			$messages = [ $messages ];
		}

		foreach ( $messages as $message ) {
			$this->add( $level, ( $module_name ? "<b>{$module_name}</b>: " : '' ) . $message );
		}
	}

	private function add( $level, $message, $code = 0, $duration = 60 ) {
		$messages = get_transient( "aether_notice_{$level}" );

		if ( $messages && is_array( $messages ) ) {
			if ( ! in_array( $message, $messages ) ) {
				$messages[] = $message;
			}
		} else {
			$messages = [ $message ];
		}

		set_transient( "aether_notice_{$level}", $messages, $duration );
	}

	private function success( $messages, $module_name = false ) {
		$this->adds( self::SUCCESS, $messages, $module_name );
	}

	private function warning( $messages, $module_name = false ) {
		$this->adds( self::WARNING, $messages, $module_name );
	}

	private function info( $messages, $module_name = false ) {
		$this->adds( self::INFO, $messages, $module_name );
	}

	private function error( $messages, $module_name = false ) {
		$this->adds( self::ERROR, $messages, $module_name );
	}
}