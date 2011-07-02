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

use phpOlap\Xmla\Metadata\CellData;

class CellDataTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		
		$cellXml = new \DOMDocument();
		$cellXml->loadXML('
	        <Cell CellOrdinal="0">
	          <Value>39431.6712</Value>
	          <FmtValue>$39,431.67</FmtValue>
	          <FormatString>Currency</FormatString>
	        </Cell>');
				
		$node = $cellXml->getElementsByTagName('Cell')->item(0);
		
		$cell = new CellData();
		
		$cell->hydrate($node);
		
		$this->assertEquals($cell->getValue(), 39431.6712);
		$this->assertEquals($cell->getFormatedValue(), '$39,431.67');
		$this->assertEquals($cell->getFormatString(), 'Currency');
	}
	
}