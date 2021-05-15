<?php

namespace Oxyrealm\Aether\Utils;

class Notice {
    const ERROR = 'error';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const INFO = 'info';

    protected $module_id;
    protected $module_name;

    public function __construct($module_id, $module_name) {
        $this->module_id = $module_id;
        $this->module_name = $module_name;
    }

    public function init() {
        foreach (
            [
                self::ERROR,
                self::SUCCESS,
                self::WARNING,
                self::INFO,
            ] as $level
        ) {
            $messages = get_transient( "{$this->module_id}_notice_{$level}" );

            if ( $messages && is_array( $messages ) ) {
                foreach ( $messages as $message ) {
                    echo sprintf(
                        '<div class="notice notice-%s is-dismissible"><p><b>%s</b>: %s</p></div>',
                        $level,
                        $this->module_name,
                        $message
                    );
                }

                delete_transient( "{$this->module_id}_notice_{$level}" );
            }
        }
    }

    public function error( $messages ) {
        $this->adds( self::ERROR, $messages );
    }

    public function adds( $level, $messages ) {
        if ( ! is_array( $messages ) ) {
            $messages = [ $messages ];
        }

        foreach ( $messages as $message ) {
            $this->add( $level, $message );
        }
    }

    public function add( $level, $message, $code = 0, $duration = 60 ) {
        $messages = get_transient( "{$this->module_id}_notice_{$level}" );

        if ( $messages && is_array( $messages ) ) {
            if ( ! in_array( $message, $messages ) ) {
                $messages[] = $message;
            }
        } else {
            $messages = [ $message ];
        }

        set_transient( "{$this->module_id}_notice_{$level}", $messages, $duration );
    }

    public function success( $messages ) {
        $this->adds( self::SUCCESS, $messages );
    }

    public function warning( $messages ) {
        $this->adds( self::WARNING, $messages );
    }

    public function info( $messages ) {
        $this->adds( self::INFO, $messages );
    }
}