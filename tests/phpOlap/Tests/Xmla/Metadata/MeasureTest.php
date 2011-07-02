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

use phpOlap\Xmla\Metadata\Measure;

class MeasureTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		$resultSoap = '<root>
					      <row>
					        <CATALOG_NAME>FoodMart</CATALOG_NAME>
					        <SCHEMA_NAME>FoodMart</SCHEMA_NAME>
					        <CUBE_NAME>Sales</CUBE_NAME>
					        <MEASURE_NAME>Profit Growth</MEASURE_NAME>
					        <MEASURE_UNIQUE_NAME>[Measures].[Profit Growth]</MEASURE_UNIQUE_NAME>
					        <MEASURE_CAPTION>Gewinn-Wachstum</MEASURE_CAPTION>
					        <MEASURE_AGGREGATOR>127</MEASURE_AGGREGATOR>
					        <DATA_TYPE>130</DATA_TYPE>
					        <MEASURE_IS_VISIBLE>true</MEASURE_IS_VISIBLE>
					        <DESCRIPTION>Sales Cube - Profit Growth Member</DESCRIPTION>
					      </row>
						</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		
		$measure = new Measure();
		
		$measure->hydrate($node, $connection);
		
		$this->assertEquals($measure->getConnection(), $connection);
		$this->assertEquals($measure->getName(), 'Profit Growth');
		$this->assertEquals($measure->getUniqueName(), '[Measures].[Profit Growth]');
		$this->assertEquals($measure->getDescription(), 'Sales Cube - Profit Growth Member');
		$this->assertEquals($measure->getCaption(), 'Gewinn-Wachstum');
		$this->assertEquals($measure->getAggregator(), 'CALCULATED');
		$this->assertEquals($measure->getDataType(), 130);
		$this->assertEquals($measure->isVisible(), true);
	}

	public function testMin()
	{
		$resultSoap = '<root>
					      <row>
					        <CATALOG_NAME>FoodMart</CATALOG_NAME>
					        <SCHEMA_NAME>FoodMart</SCHEMA_NAME>
					        <CUBE_NAME>Sales</CUBE_NAME>
					        <MEASURE_NAME>Profit Growth</MEASURE_NAME>
					        <MEASURE_UNIQUE_NAME>[Measures].[Profit Growth]</MEASURE_UNIQUE_NAME>
					      </row>
						</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		
		$measure = new Measure();
		
		$measure->hydrate($node, $connection);
		
		$this->assertEquals($measure->getConnection(), $connection);
		$this->assertEquals($measure->getName(), 'Profit Growth');
		$this->assertEquals($measure->getUniqueName(), '[Measures].[Profit Growth]');
		$this->assertEquals($measure->getDescription(), null);
		$this->assertEquals($measure->getCaption(), null);
		$this->assertEquals($measure->getAggregator(), 'UNKNOWN');
		$this->assertEquals($measure->getDataType(), null);
		$this->assertEquals($measure->isVisible(), false);
	}

	public function testNull()
	{
		$resultSoap = '<root>
					      <row>
					        <CATALOG_NAME>FoodMart</CATALOG_NAME>
					        <SCHEMA_NAME>FoodMart</SCHEMA_NAME>
					        <CUBE_NAME>Sales</CUBE_NAME>
					        <MEASURE_NAME>Profit Growth</MEASURE_NAME>
					        <MEASURE_UNIQUE_NAME>[Measures].[Profit Growth]</MEASURE_UNIQUE_NAME>
					        <MEASURE_CAPTION/>
					        <MEASURE_AGGREGATOR/>
					        <DATA_TYPE/>
					        <MEASURE_IS_VISIBLE/>
					        <DESCRIPTION/>
					      </row>
						</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		
		$measure = new Measure();
		
		$measure->hydrate($node, $connection);
		
		$this->assertEquals($measure->getConnection(), $connection);
		$this->assertEquals($measure->getName(), 'Profit Growth');
		$this->assertEquals($measure->getUniqueName(), '[Measures].[Profit Growth]');
		$this->assertEquals($measure->getDescription(), null);
		$this->assertEquals($measure->getCaption(), null);
		$this->assertEquals($measure->getAggregator(), 'UNKNOWN');
		$this->assertEquals($measure->getDataType(), null);
		$this->assertEquals($measure->isVisible(), false);
	}
	
}