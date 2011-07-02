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

use phpOlap\Xmla\Metadata\Database;
use phpOlap\Xmla\Connection\Connection;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{

		$resultSoap = '<root>
					<row>
						<DataSourceName>Provider=Mondrian;DataSource=MondrianFoodMart;</DataSourceName>
						<DataSourceDescription>Mondrian FoodMart Data Warehouse</DataSourceDescription>
						<URL>http://localhost:8080/mondrian/xmla</URL>
						<DataSourceInfo>Provider=Mondrian;DataSource=MondrianFoodMart;</DataSourceInfo>
						<ProviderName>Mondrian</ProviderName>
						<ProviderType>MDP</ProviderType>
						<AuthenticationMode>Unauthenticated</AuthenticationMode>
					</row>
				</root>';
		
		$document = new \DOMDocument();
		$document->loadXML($resultSoap);
		
		$node = $document->getElementsByTagName('row')->item(0);
		
		$connection = $this->getMock('phpOlap\Xmla\Connection\Connection', array(), array(), '', FALSE);
		$connection->expects($this->any())
					->method('findCatalogs')
					->will($this->onConsecutiveCalls('catalog1', 'catalog2'));		
		
		$database = new Database();
		
		$database->hydrate($node, $connection);
		
		$this->assertEquals($database->getConnection(), $connection);
		$this->assertEquals($database->getName(), 'Provider=Mondrian;DataSource=MondrianFoodMart;');
		$this->assertEquals($database->getDescription(), 'Mondrian FoodMart Data Warehouse');
		$this->assertEquals($database->getUrl(), 'http://localhost:8080/mondrian/xmla');
		$this->assertEquals($database->getDataSourceInfo(), 'Provider=Mondrian;DataSource=MondrianFoodMart;');
		$this->assertEquals($database->getProviderName(), 'Mondrian');
		$this->assertEquals($database->getProviderType(), 'MDP');
		$this->assertEquals($database->getAuthenticationMode(), 'Unauthenticated');
		$this->assertEquals($database->getCatalogs(), 'catalog1');
		$this->assertEquals($database->getCatalogs(), 'catalog1');
		
	}
	
}