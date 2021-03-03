<?php

namespace Oxyrealm\Aether;

use WP_REST_Controller;
use Oxyrealm\Aether\Api\Example;

/**
 * REST_API Handler
 */
class Api extends WP_REST_Controller {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes(): void {
		( new Example() )->register_routes();
	}
}
