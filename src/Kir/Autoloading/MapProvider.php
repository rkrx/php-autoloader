<?php
namespace Kir\Autoloading;

interface MapProvider {
	/**
	 * @param bool $className
	 * @return bool
	 */
	public function has($className);

	/**
	 * @param string $className
	 * @throws \Exception
	 * @return string
	 */
	public function resolveToFilename($className);
} 