<?php

namespace Oxyrealm\Aether;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        if ( Utils::is_oxygen_editor() ) {
            add_action( 'wp_footer', [ $this, 'render_frontend' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        }
    }

    /**
     * Load scripts and styles for the app
     */
    public function enqueue_scripts(): void {
        // wp_enqueue_style( 'aether-frontend' );
        // wp_enqueue_script( 'aether-frontend' );
        // wp_set_script_translations( 'aether-frontend', 'aether', AETHER_PATH . '/languages/' );
        wp_localize_script(
            'aether-frontend',
            'aether',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'aether-admin' ),
            ]
        );
    }

    /**
     * Render frontend app
     */
    public function render_frontend(): void {
    }

}
