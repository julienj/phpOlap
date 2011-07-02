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

use phpOlap\Xmla\Metadata\ResultSet;
use phpOlap\Xmla\Metadata\MetadataException;

class ResultSetTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		
		$statementResult = new \DOMDocument();
		$statementResult->load(__DIR__.'/../Connection/statementResult.xml');
				
		$node = $statementResult->getElementsByTagName('root')->item(0);
		
		$resultSet = new ResultSet();
		
		$resultSet->hydrate($node);
		
		$this->assertEquals($resultSet->getCubeName(), 'HR');
		$this->assertEquals($resultSet->getColHierarchiesName(), array('Measures'));
		$this->assertEquals($resultSet->getRowHierarchiesName(), array('Employees'));
		$this->assertEquals($resultSet->getFilterHierarchiesName(), array('Time'));
		
		$colAxisSet = $resultSet->getColAxisSet();
		$this->assertEquals($colAxisSet[0][0]->getMemberUniqueName(), '[Measures].[Org Salary]');
		
		$rowAxisSet = $resultSet->getRowAxisSet();
		$this->assertEquals($rowAxisSet[0][0]->getMemberUniqueName(), '[Employees].[All Employees]');

		$filterAxisSet = $resultSet->getFilterAxisSet();
		$this->assertEquals($filterAxisSet[0][0]->getMemberUniqueName(), '[Time].[1997]');

		$dataSet = $resultSet->getDataSet();
		$this->assertEquals($dataSet[0]->getFormatedValue(), '$39,431.67');

		$this->assertEquals($resultSet->getDataCell(0)->getFormatedValue(), '$39,431.67');
	}

	public function testHydrateNull()
	{
		
		$statementResult = new \DOMDocument();
		$statementResult->loadXml('<root></root>');
				
		$node = $statementResult->getElementsByTagName('root')->item(0);
		$resultSet = new ResultSet();		
		$resultSet->hydrate($node);
		
		$this->assertEquals($resultSet->getCubeName(), null);
		$this->assertEquals($resultSet->getColHierarchiesName(), null);
		$this->assertEquals($resultSet->getRowHierarchiesName(), null);
		$this->assertEquals($resultSet->getFilterHierarchiesName(), null);
		$this->assertEquals($resultSet->getColAxisSet(), null);
		$this->assertEquals($resultSet->getRowAxisSet(), null);
		$this->assertEquals($resultSet->getFilterAxisSet(), null);
	}

	public function testGetAttribute()
	{
		
		$document = new \DOMDocument();
		$document->loadXml('<test></test>');				
		$node = $document->getElementsByTagName('test')->item(0);

        try {
			$test = ResultSet::getAttribute($node, 'att');
        }
        catch (MetadataException $expected) {
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
	}

}