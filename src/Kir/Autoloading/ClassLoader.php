<?php
namespace Kir\Autoloading;

use Kir\StringUtils\Matching\Wildcards\Pattern;

class ClassLoader {
	/**
	 * @return $this
	 */
	public static function getInstance() {
		static $instance = null;
		if ($instance === null) {
			$instance = new static();
		}
		return $instance;
	}

	/**
	 * @var string
	 */
	private $basePath = null;

	/**
	 * @var array
	 */
	private $filters = array();

	/**
	 * @param string $basePath
	 */
	public function __construct($basePath = null) {
		$this->basePath = $basePath;
		spl_autoload_register(function ($className) {
			$className = ltrim($className, '\\');
			if(!$this->testClass($className)) {
				return false;
			}
			$filename = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
			$this->includeFile($filename);
		});
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
	 * @param string $basePath
	 * @return $this
	 */
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
		return $this;
	}

	/**
	 * @param string $path
	 * @return $this
	 */
	public function addPath($path) {
		$pathStr = get_include_path();
		$paths = explode(PATH_SEPARATOR, $pathStr);
		array_unshift($paths, $this->basePath . $path);
		$pathStr = join(PATH_SEPARATOR, $paths);
		set_include_path($pathStr);
		return $this;
	}

	/**
	 * @param string $className
	 * @return bool
	 */
	private function testClass($className) {
		foreach($this->filters as $filter) {
			if(!$filter($className)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param string $filename
	 */
	private function includeFile($filename) {
		/** @noinspection PhpIncludeInspection */
		require $filename;
	}
}