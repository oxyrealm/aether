<?php

namespace Oxyrealm\Aether;

class Utils {
    public static function is_oxygen_editor(): bool {
        return defined( 'SHOW_CT_BUILDER' ) && ! defined( 'OXYGEN_IFRAME' );
    }
}