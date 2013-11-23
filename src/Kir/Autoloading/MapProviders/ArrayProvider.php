<?php
namespace Kir\Autoloading\MapProviders;

use Kir\Autoloading\MapProvider;

class ArrayProvider implements MapProvider {
	/**
	 * @var string[]
	 */
	private $map = [];

	/**
	 * @param array $map
	 */
	public function __construct(array $map) {
		$this->map = $map;
	}

	/**
	 * @param bool $className
	 * @return bool
	 */
	public function has($className) {
		return array_key_exists($className, $this->map);
	}

	/**
	 * @param string $className
	 * @return string
	 */
	public function resolveToFilename($className) {
		if(!$this->has($className)) {
			return '';
		}
		return (string) $this->map[$className];
	}
} 