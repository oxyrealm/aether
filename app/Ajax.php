<?php

namespace Oxyrealm\Aether;

use Oxyrealm\Aether\Ajax\Admin;
use Oxyrealm\Aether\Ajax\Frontend;
use Oxyrealm\Aether\Utils\Access;

class Ajax {
	public function __construct() {
		if ( Access::can( true ) ) {
			new Admin();
			new Frontend();
		}
	}
}
