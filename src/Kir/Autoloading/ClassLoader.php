<?php
namespace Kir\Autoloading;

class ClassLoader {
	/**
	 * @var MapProvider[]
	 */
	private $mapProviders = [];

	/**
	 * @var Filter[]
	 */
	private $filters = [];

	/**
	 * @var string
	 */
	private $basePath = null;

	/**
	 * @var \Closure
	 */
	private $autoloader = null;

	/**
	 * @param string $basePath
	 * @param bool $autoRegister
	 */
	public function __construct($basePath = null, $autoRegister = true) {
		$this->basePath = $basePath;
		$this->autoloader = function ($className) {
			$this->includeClass($className);
		};
		if ($autoRegister) {
			$this->register();
		}
	}

	/**
	 * @return $this
	 */
	public function register() {
		spl_autoload_register($this->autoloader);
		return $this;
	}

	/**
	 * @return $this
	 */
	public function unregister() {
		spl_autoload_unregister($this->autoloader);
		return $this;
	}

	/**
	 * @param MapProvider $provider
	 * @return $this
	 */
	public function addClassMapProvider(MapProvider $provider) {
		$this->mapProviders[] = $provider;
		return $this;
	}

	/**
	 * @param Filter $filter
	 * @return $this
	 */
	public function addFilter(Filter $filter) {
		$this->filters[] = $filter;
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
		$concatenatedPaths = $this->concatPaths($this->basePath, $path);
		array_unshift($paths, $concatenatedPaths);
		$pathStr = join(PATH_SEPARATOR, $paths);
		set_include_path($pathStr);
		return $this;
	}

	/**
	 * @param string $className
	 * @return bool
	 */
	private function includeClass($className) {
		$className = ltrim($className, '\\');
		if (!$this->testClass($className)) {
			return false;
		}
		$filename = $this->getFilenameFromMapProviders($className);
		if($filename === null) {
			$filename = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
		}
		$this->includeFile($filename);
	}

	/**
	 * @param string $filename
	 */
	private function includeFile($filename) {
		/** @noinspection PhpIncludeInspection */
		require $filename;
	}

	/**
	 * @param string $className
	 * @return bool
	 */
	private function testClass($className) {
		foreach ($this->filters as $filter) {
			if (!$filter->test($className)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param string $className
	 * @return string
	 */
	private function getFilenameFromMapProviders($className) {
		$filename = null;
		foreach ($this->mapProviders as $mapProvider) {
			if ($mapProvider->has($className)) {
				$filename = $mapProvider->resolveToFilename($className);
				break;
			}
		}
		return $filename;
	}

	/**
	 * @param string $path1
	 * @param string $path2
	 * @return string
	 */
	private function concatPaths($path1, $path2) {
		$path1 = rtrim($path1, '/\\');
		$path2 = ltrim($path2, '/\\');
		return $path1 . DIRECTORY_SEPARATOR . $path2;
	}
}