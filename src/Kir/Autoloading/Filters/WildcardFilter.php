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
	 * @param string $patternWithoutLeadingBackslash
	 * @return $this
	 */
	public function includeNamespace($patternWithoutLeadingBackslash) {
		return $this->addTester($patternWithoutLeadingBackslash, true);
	}

	/**
	 * @param string $patternWithoutLeadingBackslash
	 * @return $this
	 */
	public function excludeNamespace($patternWithoutLeadingBackslash) {
		return $this->addTester($patternWithoutLeadingBackslash, false);
	}

	/**
	 * @param string $className
	 * @return bool
	 */
	public function test($className) {
		if ($this->optimisticFiltering && !count($this->filters)) {
			return false;
		}
		$result = false;
		foreach ($this->filters as $filter) {
			$result = $filter($className, $result);
		}
		return $result;
	}

	/**
	 * @param string $unnormalizedPattern
	 * @param $include
	 * @return $this
	 */
	private function addTester($unnormalizedPattern, $include) {
		$normalizedPattern = ltrim($unnormalizedPattern, '\\');
		$pattern = Pattern::create($normalizedPattern);
		$this->filters[] = function ($className, $bit) use ($pattern, $include) {
			/* @var $pattern Pattern */
			if($pattern->match($className)) {
				return $include;
			}
			return $bit;
		};
		return $this;
	}
} 