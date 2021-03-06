<?php

namespace Oxyrealm\Modules;

use Exception;

class ModuleDisabledException extends Exception{}

abstract class ModuleAbstract {
    protected function __construct() {
        if ( ! get_option( "{$this->module_id}_enabled", false ) ) {
            throw new ModuleDisabledException();
        }
    }

    abstract public function register();
    abstract public function boot();
}