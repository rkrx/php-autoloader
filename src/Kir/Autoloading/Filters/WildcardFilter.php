<?php
namespace Kir\Autoloading\Filters;

use Kir\StringUtils\Matching\Wildcards\Pattern;
use Kir\Autoloading\Filter;

class WildcardFilter implements Filter {
	/**
	 * @var \Closure[]
	 */
	private $filters = [];

	/**
	 * @var bool
	 */
	private $optimisticFiltering = false;

	/**
	 * @param bool $optimisticFiltering
	 */
	public function __construct($optimisticFiltering = false) {
		$this->optimisticFiltering = $optimisticFiltering;
	}

	/**
	 * @param string $namespaceWithoutLeadingBackslash
	 * @return $this
	 */
	public function includeNamespace($namespaceWithoutLeadingBackslash) {
		$namespace = ltrim($namespaceWithoutLeadingBackslash, '\\');
		$pattern = Pattern::create($namespace);
		$this->filters[] = function ($className) use ($namespace, $pattern) {
			/* @var $pattern Pattern */
			return $pattern->match($className);
		};
		return $this;
	}

	/**
	 * @param string $namespaceWithoutLeadingBackslash
	 * @return $this
	 */
	public function excludeNamespace($namespaceWithoutLeadingBackslash) {
		$namespace = ltrim($namespaceWithoutLeadingBackslash, '\\');
		$pattern = Pattern::create($namespace);
		$this->filters[] = function ($className) use ($namespace, $pattern) {
			/* @var $pattern Pattern */
			return !$pattern->match($className);
		};
		return $this;
	}

	/**
	 * @param string $className
	 * @return bool
	 */
	public function test($className) {
		if ($this->optimisticFiltering && !count($this->filters)) {
			return false;
		}
		foreach ($this->filters as $filter) {
			if (!$filter($className)) {
				return false;
			}
		}
		return true;
	}
} 