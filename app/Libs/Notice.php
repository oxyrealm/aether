<?php

namespace Oxyrealm\Aether\Libs;

class Notice {
	private const ERROR = 'error';
	private const SUCCESS = 'success';
	private const WARNING = 'warning';
	private const INFO = 'info';

    public function init() {
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

	public function adds( $level, $messages, $module_name = false ) {
		if ( ! is_array( $messages ) ) {
			$messages = [ $messages ];
		}

		foreach ( $messages as $message ) {
			$this->add( $level, ( $module_name ? "<b>{$module_name}</b>: " : '' ) . $message );
		}
	}

	public function add( $level, $message, $code = 0, $duration = 60 ) {
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

	public function success( $messages, $module_name = false ) {
		$this->adds( self::SUCCESS, $messages, $module_name );
	}

	public function warning( $messages, $module_name = false ) {
		$this->adds( self::WARNING, $messages, $module_name );
	}

	public function info( $messages, $module_name = false ) {
		$this->adds( self::INFO, $messages, $module_name );
	}

	public function error( $messages, $module_name = false ) {
		$this->adds( self::ERROR, $messages, $module_name );
	}
}