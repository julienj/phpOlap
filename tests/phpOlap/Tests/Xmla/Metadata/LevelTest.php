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

use phpOlap\Xmla\Metadata\Level;

class LevelTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		$resultSoap = '<root>
			      <row> 
			        <CATALOG_NAME>FoodMart</CATALOG_NAME> 
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME> 
			        <CUBE_NAME>Sales</CUBE_NAME> 
			        <DIMENSION_UNIQUE_NAME>[Time]</DIMENSION_UNIQUE_NAME> 
			        <HIERARCHY_UNIQUE_NAME>[Time.Weekly]</HIERARCHY_UNIQUE_NAME> 
			        <LEVEL_NAME>Year</LEVEL_NAME> 
			        <LEVEL_UNIQUE_NAME>[Time.Weekly].[Year]</LEVEL_UNIQUE_NAME> 
			        <LEVEL_CAPTION>Year</LEVEL_CAPTION> 
			        <LEVEL_NUMBER>1</LEVEL_NUMBER> 
			        <LEVEL_CARDINALITY>2</LEVEL_CARDINALITY> 
			        <LEVEL_TYPE>20</LEVEL_TYPE> 
			        <CUSTOM_ROLLUP_SETTINGS>0</CUSTOM_ROLLUP_SETTINGS> 
			        <LEVEL_UNIQUE_SETTINGS>1</LEVEL_UNIQUE_SETTINGS> 
			        <LEVEL_IS_VISIBLE>true</LEVEL_IS_VISIBLE> 
			        <DESCRIPTION>Sales Cube - Time.Weekly Hierarchy - Year Level</DESCRIPTION> 
			      </row> 
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findMembers')
					->will($this->onConsecutiveCalls('m1', 'm2'));		
		
		$level = new Level();
		
		$level->hydrate($node, $connection);
		
		$this->assertEquals($level->getConnection(), $connection);
		$this->assertEquals($level->getCubeName(), 'Sales');
		$this->assertEquals($level->getDimensionUniqueName(), '[Time]');
		$this->assertEquals($level->getHierarchyUniqueName(), '[Time.Weekly]');
		$this->assertEquals($level->getUniqueName(), '[Time.Weekly].[Year]');
		$this->assertEquals($level->getDescription(), 'Sales Cube - Time.Weekly Hierarchy - Year Level');
		$this->assertEquals($level->getCaption(), 'Year');
		$this->assertEquals($level->getMumber(), 1);
		$this->assertEquals($level->getCardinality(), 2);
		$this->assertEquals($level->getType(), 20);
		$this->assertEquals($level->getCustomRollupSettings(), 0);
		$this->assertEquals($level->getUniqueSettings(), 1);
		$this->assertEquals($level->isVisible(), true);
		$this->assertEquals($level->getMembers(), 'm1');
		$this->assertEquals($level->getMembers(), 'm1');
	}
	
}