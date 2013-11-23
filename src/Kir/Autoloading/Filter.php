<?php
namespace Kir\Autoloading;

interface Filter {
	/**
	 * @param string $className
	 * @return bool
	 */
	public function test($className);
} 