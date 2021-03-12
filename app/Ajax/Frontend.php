<?php

namespace Oxyrealm\Aether\Ajax;

class Frontend {

    public function __construct() {
        $this->register();
    }

    public function register(): void {
        $this->register_ajaxs( $this->get_ajaxs() );
    }

    private function register_ajaxs( array $ajaxs ): void {
        foreach ( $ajaxs as $tag => $ajax ) {
            $priority = $ajax['priority'] ?? 10;
            $args     = $ajax['args'] ?? 1;

            add_action( "wp_ajax_aether_{$tag}", $ajax['handler'], $priority, $args );
        }
    }

    private function get_ajaxs(): array {
        return [
            'example' => [
                'handler' => [ $this, 'example' ]
            ],
        ];
    }

    public function example(): void {
        check_ajax_referer( 'aether-admin' );

        wp_send_json_success( null );

        wp_die();
    }

}