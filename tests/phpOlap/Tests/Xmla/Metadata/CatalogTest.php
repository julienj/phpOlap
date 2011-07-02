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

use phpOlap\Xmla\Metadata\Catalog;

class CatalogTest extends \PHPUnit_Framework_TestCase
{
	public function testHydrate()
	{
		$resultSoap = '<root>
					<row>
						<CATALOG_NAME>FoodMart</CATALOG_NAME>
						<DESCRIPTION>No description available</DESCRIPTION>
						<ROLES>California manager,No HR Cube</ROLES>
					</row>
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findSchemas')
					->will($this->onConsecutiveCalls('schema1', 'schema2'));		
		
		$catalog = new Catalog();
		
		$catalog->hydrate($node, $connection);
		
		$this->assertEquals($catalog->getConnection(), $connection);
		$this->assertEquals($catalog->getName(), 'FoodMart');
		$this->assertEquals($catalog->getDescription(), 'No description available');
		$this->assertEquals($catalog->getRoles(), array('California manager', 'No HR Cube'));	
		$this->assertEquals($catalog->getSchemas(), 'schema1');
		$this->assertEquals($catalog->getSchemas(), 'schema1');

	}
}