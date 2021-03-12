<?php

namespace Oxyrealm\Aether;

use Oxyrealm\Aether\Ajax\Admin;
use Oxyrealm\Aether\Ajax\Frontend;
use Oxyrealm\Aether\Utils\Oxygen;

class Ajax {
	public function __construct() {
		if ( Oxygen::can( true ) ) {
			new Admin();
			new Frontend();
		}
	}
}
