<?php
namespace Kir\Autoloading;

use Kir\Autoloading\Test\Obj;

class ClassLoaderTest extends \PHPUnit_Framework_TestCase {
	public function setUp() {
		ClassLoader::getInstance()
		->setBasePath(__DIR__.'/../../..')
		->addPath('tests');
	}
	
	public function testLoader() {
		$obj = new Obj();
		$this->assertInstanceOf('\\Kir\\Autoloading\\Test\\Obj', $obj);
	}
}
 