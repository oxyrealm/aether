<?php

namespace Oxyrealm\Aether\Libs;

use Closure;

class Transient
{
	protected $module_name;

	public function __construct(string $module_name = '')
	{
		$this->module_name = $module_name;
	}

	public function has($key): bool
	{
		return get_transient($this->module_name . $key) !== false ? true : false;
	}

	public function get($key, $default = false)
	{
		return get_transient($this->module_name . $key, $default);
	}

	public function set($key, $value = null, $ttl = 0)
	{
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				if (!is_array($v)) {
					$this->set($k, $v, $ttl);
				} else {
					$this->set($k, $v[0], $v[1]);
				}
			}
			return;
		}

		set_transient($this->module_name . $key, $value, $ttl);
	}

	public function delete($key)
	{
		return delete_transient($this->module_name . $key);
	}

	public function remember($key, $ttl, Closure $callback)
	{
		$item = $this->get($key);

		if ($item !== false) {
			return $item;
		}

		$this->set($key, $item = $callback(), $ttl);

		return $item;
	}
}
