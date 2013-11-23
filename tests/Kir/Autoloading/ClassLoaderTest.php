<?php
namespace Kir\Autoloading;

use Kir\Autoloading\Test\Obj;

class ClassLoaderTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		$filter = new Filters\WildcardFilter();
		$filter->includeNamespace('Kir\\*');
		
		$loader = new ClassLoader();
		$loader
		->addFilter($filter)
		->setBasePath(AUTOLOADER__ROOT_PATH)
		->addPath('tests');
	}
	
	public function testLoader() {
		$obj = new Obj();
		$this->assertInstanceOf('\\Kir\\Autoloading\\Test\\Obj', $obj);
	}
}
 