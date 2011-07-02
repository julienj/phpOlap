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

use phpOlap\Xmla\Metadata\Cube;

class CubeTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		$resultSoap = '<root>
					<row>
						<CATALOG_NAME>FoodMart</CATALOG_NAME>
						<SCHEMA_NAME>FoodMart</SCHEMA_NAME>
						<CUBE_NAME>Sales</CUBE_NAME>
						<CUBE_TYPE>CUBE</CUBE_TYPE>
						<LAST_SCHEMA_UPDATE>2011-05-07T00:52:12</LAST_SCHEMA_UPDATE>
						<IS_DRILLTHROUGH_ENABLED>true</IS_DRILLTHROUGH_ENABLED>
						<IS_WRITE_ENABLED>false</IS_WRITE_ENABLED>
						<IS_LINKABLE>false</IS_LINKABLE>
						<IS_SQL_ENABLED>false</IS_SQL_ENABLED>
						<DESCRIPTION>FoodMart Schema - Sales Cube</DESCRIPTION>
					</row>
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findDimensions')
					->will($this->onConsecutiveCalls('dim1', 'dim1'));		
		$connection->expects($this->any())
					->method('findMeasures')
					->will($this->onConsecutiveCalls('m1', 'm1'));

		$cube = new Cube();
		
		$cube->hydrate($node, $connection);
		
		$this->assertEquals($cube->getConnection(), $connection);
		$this->assertEquals($cube->getName(), 'Sales');
		$this->assertEquals($cube->getDescription(), 'FoodMart Schema - Sales Cube');
		$this->assertEquals($cube->getType(), 'CUBE');
		$this->assertEquals($cube->getDimensions(), 'dim1');
		$this->assertEquals($cube->getDimensions(), 'dim1');
		$this->assertEquals($cube->getMeasures(), 'm1');
		$this->assertEquals($cube->getMeasures(), 'm1');
	}
	
}