<?php

namespace Oxyrealm\Aether;

class Utils {
    public static function is_request( string $type ): bool {
        // return match( $type ) {
        // 	'admin' => is_admin(),
        // 	'ajax' => defined( 'DOING_AJAX' ),
        // 	'rest' => defined( 'REST_REQUEST' ),
        // 	'cron' => defined( 'DOING_CRON' ),
        // 	'frontend' => ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ),
        // };

        /**
         * @version php7.4
         */
        switch ( $type ) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined( 'DOING_AJAX' );
            case 'rest':
                return defined( 'REST_REQUEST' );
            case 'cron':
                return defined( 'DOING_CRON' );
            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
            default:
                return false;
                break;
        }
    }

    public static function is_oxygen_editor(): bool {
        return defined( 'SHOW_CT_BUILDER' ) && ! defined( 'OXYGEN_IFRAME' );
    }
}