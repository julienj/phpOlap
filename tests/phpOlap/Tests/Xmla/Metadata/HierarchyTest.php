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

use phpOlap\Xmla\Metadata\Hierarchy;

class HierarchyTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		$resultSoap = '<root>
					      <row> 
					        <CATALOG_NAME>FoodMart</CATALOG_NAME> 
					        <SCHEMA_NAME>FoodMart</SCHEMA_NAME> 
					        <CUBE_NAME>Sales</CUBE_NAME> 
					        <DIMENSION_UNIQUE_NAME>[Time]</DIMENSION_UNIQUE_NAME> 
					        <HIERARCHY_NAME>Time</HIERARCHY_NAME> 
					        <HIERARCHY_UNIQUE_NAME>[Time]</HIERARCHY_UNIQUE_NAME> 
					        <HIERARCHY_CAPTION>Time</HIERARCHY_CAPTION> 
					        <DIMENSION_TYPE>1</DIMENSION_TYPE> 
					        <HIERARCHY_CARDINALITY>34</HIERARCHY_CARDINALITY> 
					        <DEFAULT_MEMBER>[Time].[1997]</DEFAULT_MEMBER> 
					        <DESCRIPTION>Sales Cube - Time Hierarchy</DESCRIPTION> 
					        <STRUCTURE>0</STRUCTURE> 
					        <IS_VIRTUAL>false</IS_VIRTUAL> 
					        <IS_READWRITE>false</IS_READWRITE> 
					        <DIMENSION_UNIQUE_SETTINGS>0</DIMENSION_UNIQUE_SETTINGS> 
					        <DIMENSION_IS_VISIBLE>true</DIMENSION_IS_VISIBLE> 
					        <HIERARCHY_ORDINAL>4</HIERARCHY_ORDINAL> 
					        <DIMENSION_IS_SHARED>true</DIMENSION_IS_SHARED> 
					        <PARENT_CHILD>false</PARENT_CHILD> 
					      </row>
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findLevels')
					->will($this->onConsecutiveCalls('l1', 'l2'));		
		
		$hierarchy = new Hierarchy();
		
		$hierarchy->hydrate($node, $connection);
		
		$this->assertEquals($hierarchy->getConnection(), $connection);
		$this->assertEquals($hierarchy->getCubeName(), 'Sales');
		$this->assertEquals($hierarchy->getDimensionUniqueName(), '[Time]');
		$this->assertEquals($hierarchy->getName(), 'Time');
		$this->assertEquals($hierarchy->getUniqueName(), '[Time]');
		$this->assertEquals($hierarchy->getDescription(), 'Sales Cube - Time Hierarchy');
		$this->assertEquals($hierarchy->getCaption(), 'Time');
		$this->assertEquals($hierarchy->getCardinality(), 34);
		$this->assertEquals($hierarchy->getDefaultMemberUniqueName(), '[Time].[1997]');
		$this->assertEquals($hierarchy->getStructure(), 0);
		$this->assertEquals($hierarchy->isVirtual(), false);
		$this->assertEquals($hierarchy->isReadWrite(), false);
		$this->assertEquals($hierarchy->getOrdinal(), 4);
		$this->assertEquals($hierarchy->getParentChild(), false);
		$this->assertEquals($hierarchy->getLevels(), 'l1');
		$this->assertEquals($hierarchy->getLevels(), 'l1');

	}
	
}