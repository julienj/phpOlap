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

use phpOlap\Xmla\Metadata\MetadataBase;
use phpOlap\Xmla\Metadata\MetadataException;

class MetadataBaseTest extends \PHPUnit_Framework_TestCase
{

	public function testGetPropertyFromNode()
	{
		$xml = '<root>
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
		$document->loadXML($xml);
		
		$node = $document->getElementsByTagName('row')->item(0);

		$this->assertEquals(MetadataBase::getPropertyFromNode($node, 'CATALOG_NAME', true), 'FoodMart');
		$this->assertEquals(MetadataBase::getPropertyFromNode($node, 'CATALOG_NAME', false), 'FoodMart');
		$this->assertEquals(MetadataBase::getPropertyFromNode($node, 'CATALOG_NAME'), 'FoodMart');
		$this->assertEquals(MetadataBase::getPropertyFromNode($node, 'XXXX', true), null);
		$this->assertEquals(MetadataBase::getPropertyFromNode($node, 'XXXX'), null);
		
		try {
			$result = MetadataBase::getPropertyFromNode($node, 'XXXX', false);
		} catch (MetadataException $e) {
			return;
		}
	 $this->fail('An expected exception has not been raised.');
	}
	
}