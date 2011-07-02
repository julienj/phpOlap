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

use phpOlap\Xmla\Metadata\Member;

class MemberTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		$resultSoap = '<root>
					      <row>
					        <CATALOG_NAME>FoodMart</CATALOG_NAME>
					        <SCHEMA_NAME>FoodMart</SCHEMA_NAME>
					        <CUBE_NAME>Sales</CUBE_NAME>
					        <DIMENSION_UNIQUE_NAME>[Measures]</DIMENSION_UNIQUE_NAME>
					        <HIERARCHY_UNIQUE_NAME>[Measures]</HIERARCHY_UNIQUE_NAME>
					        <LEVEL_UNIQUE_NAME>[Measures].[MeasuresLevel]</LEVEL_UNIQUE_NAME>
					        <LEVEL_NUMBER>0</LEVEL_NUMBER>
					        <MEMBER_ORDINAL>1</MEMBER_ORDINAL>
					        <MEMBER_NAME>Store Cost</MEMBER_NAME>
					        <MEMBER_UNIQUE_NAME>[Measures].[Store Cost]</MEMBER_UNIQUE_NAME>
					        <MEMBER_TYPE>3</MEMBER_TYPE>
					        <MEMBER_CAPTION>Store Cost</MEMBER_CAPTION>
					        <CHILDREN_CARDINALITY>2</CHILDREN_CARDINALITY>
					        <PARENT_LEVEL>1</PARENT_LEVEL>
					        <PARENT_COUNT>4</PARENT_COUNT>
					        <DEPTH>10</DEPTH>
					      </row> 
						</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);

		$member = new Member();
		
		$member	->hydrate($node, $connection);
		
		$this->assertEquals($member->getConnection(), $connection);
		$this->assertEquals($member->getName(), 'Store Cost');
		$this->assertEquals($member->getUniqueName(), '[Measures].[Store Cost]');
		$this->assertEquals($member->getDescription(), null);
		$this->assertEquals($member->getCaption(), 'Store Cost');
		$this->assertEquals($member->getOrdinal(), 1);
		$this->assertEquals($member->getType(), 'MEASURE');
		$this->assertEquals($member->getChildrenCardinality(), 2);
		$this->assertEquals($member->getParentLevel(), 1);
		$this->assertEquals($member->getParentCount(), 4);
		$this->assertEquals($member->getDepth(), 10);
	}

	public function testHydrateMin()
	{
		$resultSoap = '<root>
					      <row>
					        <CATALOG_NAME>FoodMart</CATALOG_NAME>
					        <SCHEMA_NAME>FoodMart</SCHEMA_NAME>
					        <CUBE_NAME>Sales</CUBE_NAME>
					        <DIMENSION_UNIQUE_NAME>[Measures]</DIMENSION_UNIQUE_NAME>
					        <HIERARCHY_UNIQUE_NAME>[Measures]</HIERARCHY_UNIQUE_NAME>
					        <LEVEL_UNIQUE_NAME>[Measures].[MeasuresLevel]</LEVEL_UNIQUE_NAME>
					        <MEMBER_NAME>Store Cost</MEMBER_NAME>
					        <MEMBER_UNIQUE_NAME>[Measures].[Store Cost]</MEMBER_UNIQUE_NAME>
					      </row> 
						</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);

		$member = new Member();
		
		$member->hydrate($node, $connection);
		
		$this->assertEquals($member->getConnection(), $connection);
		$this->assertEquals($member->getName(), 'Store Cost');
		$this->assertEquals($member->getUniqueName(), '[Measures].[Store Cost]');
		$this->assertEquals($member->getDescription(), null);
		$this->assertEquals($member->getCaption(), null);
		$this->assertEquals($member->getOrdinal(), 0);
		$this->assertEquals($member->getType(), 'UNKNOWN');
		$this->assertEquals($member->getChildrenCardinality(), 0);
		$this->assertEquals($member->getParentLevel(), 0);
		$this->assertEquals($member->getParentCount(), 0);
		$this->assertEquals($member->getDepth(), 0);
	}

}