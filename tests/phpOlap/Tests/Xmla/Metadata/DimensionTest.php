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

use phpOlap\Xmla\Metadata\Dimension;

class DimensionTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		$resultSoap = '<root>
					<row>
						<CATALOG_NAME>FoodMart</CATALOG_NAME>
						<SCHEMA_NAME>FoodMart</SCHEMA_NAME>
						<CUBE_NAME>Sales</CUBE_NAME>
						<DIMENSION_NAME>Time</DIMENSION_NAME>
						<DIMENSION_UNIQUE_NAME>[Time]</DIMENSION_UNIQUE_NAME>
						<DIMENSION_CAPTION>Time</DIMENSION_CAPTION>
						<DIMENSION_ORDINAL>4</DIMENSION_ORDINAL>
						<DIMENSION_TYPE>1</DIMENSION_TYPE>
						<DIMENSION_CARDINALITY>25</DIMENSION_CARDINALITY>
						<DEFAULT_HIERARCHY>[Time]</DEFAULT_HIERARCHY>
						<DESCRIPTION>Sales Cube - Time Dimension</DESCRIPTION>
						<IS_VIRTUAL>false</IS_VIRTUAL>
						<IS_READWRITE>false</IS_READWRITE>
						<DIMENSION_UNIQUE_SETTINGS>0</DIMENSION_UNIQUE_SETTINGS>
						<DIMENSION_IS_VISIBLE>true</DIMENSION_IS_VISIBLE>
					</row>
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findHierarchies')
					->will($this->onConsecutiveCalls('h1', 'h2'));		
		
		$dimension = new Dimension();
		
		$dimension->hydrate($node, $connection);
		
		$this->assertEquals($dimension->getConnection(), $connection);
		$this->assertEquals($dimension->getName(), 'Time');
		$this->assertEquals($dimension->getUniqueName(), '[Time]');
		$this->assertEquals($dimension->getDescription(), 'Sales Cube - Time Dimension');
		$this->assertEquals($dimension->getCaption(), 'Time');
		$this->assertEquals($dimension->getOrdinal(), 4);
		$this->assertEquals($dimension->getType(), 'TIME');
		$this->assertEquals($dimension->getCardinality(), 25);
		$this->assertEquals($dimension->getDefaultHierarchyUniqueName(), '[Time]');
		$this->assertEquals($dimension->isVirtual(), false);
		$this->assertEquals($dimension->isReadWrite(), false);
		$this->assertEquals($dimension->getUniqueSettings(), 0);
		$this->assertEquals($dimension->isVisible(), true);
		$this->assertEquals($dimension->getHierarchies(), 'h1');
		$this->assertEquals($dimension->getHierarchies(), 'h1');
	}

	public function testHydrateMin()
	{
		$resultSoap = '<root>
					<row>
						<CATALOG_NAME>FoodMart</CATALOG_NAME>
						<SCHEMA_NAME>FoodMart</SCHEMA_NAME>
						<CUBE_NAME>Sales</CUBE_NAME>
						<DIMENSION_NAME>Time</DIMENSION_NAME>
						<DIMENSION_UNIQUE_NAME>[Time]</DIMENSION_UNIQUE_NAME>

					</row>
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findHierarchies')
					->will($this->onConsecutiveCalls('h1', 'h2'));		
		
		$dimension = new Dimension();
		
		$dimension->hydrate($node, $connection);
		
		$this->assertEquals($dimension->getConnection(), $connection);
		$this->assertEquals($dimension->getCubeName(), 'Sales');
		$this->assertEquals($dimension->getName(), 'Time');
		$this->assertEquals($dimension->getUniqueName(), '[Time]');
		$this->assertEquals($dimension->getDescription(), null);
		$this->assertEquals($dimension->getCaption(), null);
		$this->assertEquals($dimension->getOrdinal(), 0);
		$this->assertEquals($dimension->getType(), 'UNKNOWN');
		$this->assertEquals($dimension->getCardinality(), 0);
		$this->assertEquals($dimension->getDefaultHierarchyUniqueName(), null);
		$this->assertEquals($dimension->isVirtual(), false);
		$this->assertEquals($dimension->isReadWrite(), false);
		$this->assertEquals($dimension->getUniqueSettings(), null);
		$this->assertEquals($dimension->isVisible(), false);
		$this->assertEquals($dimension->getHierarchies(), 'h1');
		$this->assertEquals($dimension->getHierarchies(), 'h1');
	}
	
}