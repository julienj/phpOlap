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

use phpOlap\Xmla\Metadata\CellAxis;

class CellAxisTest extends \PHPUnit_Framework_TestCase
{

	public function testHydrate()
	{
		
		$axisXml = new \DOMDocument();
		$axisXml->loadXML('
			<Member Hierarchy="Employees">
                <UName>[Employees].[All Employees]</UName>
                <Caption>All Employees</Caption>
                <LName>[Employees].[(All)]</LName>
                <LNum>0</LNum>
                <DisplayInfo>65537</DisplayInfo>
              </Member>');
				
		$node = $axisXml->getElementsByTagName('Member')->item(0);
		
		$cellAxis = new CellAxis();
		
		$cellAxis->hydrate($node);
		
		$this->assertEquals($cellAxis->getMemberUniqueName(), '[Employees].[All Employees]');
		$this->assertEquals($cellAxis->getMemberCaption(), 'All Employees');
		$this->assertEquals($cellAxis->getLevelUniqueName(), '[Employees].[(All)]');
		$this->assertEquals($cellAxis->getLevelNumber(), 0);
		$this->assertEquals($cellAxis->getDisplayInfo(), 65537);
	}
	
}