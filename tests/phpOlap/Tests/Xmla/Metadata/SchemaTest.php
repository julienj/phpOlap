<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Tests\Xmla\Metadata;

use phpOlap\Xmla\Metadata\Schema;

class SchemaTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		$resultSoap = '<root>
					<row>
						<CATALOG_NAME>FoodMart</CATALOG_NAME>
						<SCHEMA_NAME>FoodMart</SCHEMA_NAME>
						<SCHEMA_OWNER/>
					</row>
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findCubes')
					->will($this->onConsecutiveCalls('cube1', 'cube2'));		
		
		$schema = new Schema();
		
		$schema->hydrate($node, $connection);
		
		$this->assertEquals($schema->getConnection(), $connection);
		$this->assertEquals($schema->getName(), 'FoodMart');
		$this->assertEquals($schema->getCubes(), 'cube1');
		$this->assertEquals($schema->getCubes(), 'cube1');
	}
	
}