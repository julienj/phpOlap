<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Tests\Xmla\Connection\Adaptator;

use phpOlap\Xmla\Connection\Adaptator\SoapAdaptator;
use phpOlap\Xmla\Connection\Adaptator\AdaptatorException;

class SoapAdaptatorTest extends \PHPUnit_Framework_TestCase
{
    public function testDiscover()
    {

		$adaptator = $this->getSoapAdaptatorMockCall();
		
		$test = $adaptator->discover(
				'MDSCHEMA_DIMENSIONS',
				array(
					'DataSourceInfo' => 'Provider=Mondrian;DataSource=MondrianFoodMart;',
					'Catalog' => 'FoodMart',
					'Format' =>'Tabular'
				),array(
					'CATALOG_NAME' => 'FoodMart',
					'CUBE_NAME' => 'Sales'
				)
			);
		$result = '<Discover xmlns="urn:schemas-microsoft-com:xml-analysis">' .
				    '<RequestType>MDSCHEMA_DIMENSIONS</RequestType>' .
				    '<Restrictions>' .
				      '<RestrictionList>' .
				        '<CATALOG_NAME>FoodMart</CATALOG_NAME>' .
				        '<CUBE_NAME>Sales</CUBE_NAME>' .
				      '</RestrictionList>' .
				    '</Restrictions>' .
				    '<Properties>' .
				      '<PropertyList>' .
				        '<DataSourceInfo>Provider=Mondrian;DataSource=MondrianFoodMart;</DataSourceInfo>' .
				        '<Catalog>FoodMart</Catalog>' .
				        '<Format>Tabular</Format>' .
				      '</PropertyList>' .
				    '</Properties>' .
				  '</Discover>';
		
        $this->compareXml($test, $result); 
    }

    public function testDiscoverWithoutProperties()
    {

		$adaptator = $this->getSoapAdaptatorMockCall();
		
		$test = $adaptator->discover(
				'MDSCHEMA_DIMENSIONS',
				array(
					'DataSourceInfo' => 'Provider=Mondrian;DataSource=MondrianFoodMart;',
					'Catalog' => 'FoodMart',
					'Format' =>'Tabular'
				)
			);
		$result = '<Discover xmlns="urn:schemas-microsoft-com:xml-analysis">' .
				    '<RequestType>MDSCHEMA_DIMENSIONS</RequestType>' .
				    '<Restrictions>' .
				      '<RestrictionList>' .
				      '</RestrictionList>' .
				    '</Restrictions>' .
				    '<Properties>' .
				      '<PropertyList>' .
				        '<DataSourceInfo>Provider=Mondrian;DataSource=MondrianFoodMart;</DataSourceInfo>' .
				        '<Catalog>FoodMart</Catalog>' .
				        '<Format>Tabular</Format>' .
				      '</PropertyList>' .
				    '</Properties>' .
				  '</Discover>';
		
        $this->compareXml($test, $result); 
    }

    public function testDiscoverWith1Property()
    {

		$adaptator = $this->getSoapAdaptatorMockCall();
		
		$test = $adaptator->discover(
				'MDSCHEMA_DIMENSIONS',
				array(
					'DataSourceInfo' => 'Provider=Mondrian;DataSource=MondrianFoodMart;',
					'Catalog' => 'FoodMart',
					'Format' =>'Tabular'
				),array(
					'CUBE_NAME' => 'Sales'
				)
			);
		$result = '<Discover xmlns="urn:schemas-microsoft-com:xml-analysis">' .
				    '<RequestType>MDSCHEMA_DIMENSIONS</RequestType>' .
				    '<Restrictions>' .
				      '<RestrictionList>' .
				        '<CUBE_NAME>Sales</CUBE_NAME>' .
				      '</RestrictionList>' .
				    '</Restrictions>' .
				    '<Properties>' .
				      '<PropertyList>' .
				        '<DataSourceInfo>Provider=Mondrian;DataSource=MondrianFoodMart;</DataSourceInfo>' .
				        '<Catalog>FoodMart</Catalog>' .
				        '<Format>Tabular</Format>' .
				      '</PropertyList>' .
				    '</Properties>' .
				  '</Discover>';
		
        $this->compareXml($test, $result); 
    }

    public function testExecute()
    {

		$adaptator = $this->getSoapAdaptatorMockCall();
		
		$test = $adaptator->execute(
				'SELECT {[Measures].[Org Salary]} ON columns, Hierarchize(Union({[Employees].[All Employees]}, [Employees].[All Employees].Children)) ON rows FROM HR WHERE ([Time].[1997])',
				array(
					'Catalog' => 'FoodMart',
					'DataSourceInfo' => 'Provider=Mondrian;DataSource=MondrianFoodMart;',
					'Format' =>'Multidimensional',
					'AxisFormat' =>'TupleFormat'
				)
			);
		$result = '<Execute xmlns="urn:schemas-microsoft-com:xml-analysis">' .
					  '<Command>' .
					    '<Statement>' .
					      'SELECT {[Measures].[Org Salary]} ON columns, Hierarchize(Union({[Employees].[All Employees]}, [Employees].[All Employees].Children)) ON rows FROM HR WHERE ([Time].[1997])' .
					    '</Statement>' .
					  '</Command>' .
					  '<Properties>' .
					    '<PropertyList>' .
					      '<Catalog>FoodMart</Catalog>' .
					      '<DataSourceInfo>Provider=Mondrian;DataSource=MondrianFoodMart;</DataSourceInfo>' .
					      '<Format>Multidimensional</Format>' .
					      '<AxisFormat>TupleFormat</AxisFormat>' .
					    '</PropertyList>' .
					  '</Properties>' .
					'</Execute>';
		
        $this->compareXml($test, $result); 
    }

	public function testCall()
	{
		
		$response = '
			<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" > 
			<SOAP-ENV:Header> 
			</SOAP-ENV:Header> 
			<SOAP-ENV:Body> 
			<cxmla:DiscoverResponse xmlns:cxmla="urn:schemas-microsoft-com:xml-analysis"> 
			  <cxmla:return> 
			    <root xmlns="urn:schemas-microsoft-com:xml-analysis:rowset" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:EX="urn:schemas-microsoft-com:xml-analysis:exception"> 
			      <xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="urn:schemas-microsoft-com:xml-analysis:rowset" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:sql="urn:schemas-microsoft-com:xml-sql" targetNamespace="urn:schemas-microsoft-com:xml-analysis:rowset" elementFormDefault="qualified"> 
			        <xsd:element name="root"> 
			          <xsd:complexType> 
			            <xsd:sequence> 
			              <xsd:element name="row" type="row" minOccurs="0" maxOccurs="unbounded"/> 
			            </xsd:sequence> 
			          </xsd:complexType> 
			        </xsd:element> 
			        <xsd:simpleType name="uuid"> 
			          <xsd:restriction base="xsd:string"> 
			            <xsd:pattern value="[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}"/> 
			          </xsd:restriction> 
			        </xsd:simpleType> 
			        <xsd:complexType name="row"> 
			          <xsd:sequence> 
			            <xsd:element sql:field="CATALOG_NAME" name="CATALOG_NAME" type="xsd:string"/> 
			            <xsd:element sql:field="SCHEMA_NAME" name="SCHEMA_NAME" type="xsd:string"/> 
			            <xsd:element sql:field="SCHEMA_OWNER" name="SCHEMA_OWNER" type="xsd:string"/> 
			          </xsd:sequence> 
			        </xsd:complexType> 
			      </xsd:schema> 
			      <row> 
			        <CATALOG_NAME>FoodMart</CATALOG_NAME> 
			        <SCHEMA_NAME>FoodMart</SCHEMA_NAME> 
			        <SCHEMA_OWNER/> 
			      </row> 
			    </root> 
			  </cxmla:return> 
			</cxmla:DiscoverResponse> 
			</SOAP-ENV:Body> 
			</SOAP-ENV:Envelope>';
		
		$adaptator = $this->getSoapAdaptatorMockDoRequest($response);

		$test = $adaptator->discover(
				'MDSCHEMA_DIMENSIONS',
				array(),
				array()
			);
		
		$result = new \DOMDocument();
		$result->loadXML($response);
				
		$this->assertEquals($test, $result->getElementsByTagName('row'));		
	}

	public function testCallError()
	{
		
		$response = '
			<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" > 
			<SOAP-ENV:Header> 
			</SOAP-ENV:Header> 
			<SOAP-ENV:Body> 
			<SOAP-ENV:Fault> 
			  <faultcode>SOAP-ENV:Server.00HSBB01</faultcode> 
			  <faultstring>XMLA SOAP Body processing error</faultstring> 
			  <faultactor>Mondrian</faultactor> 
			  <detail> 
			    <XA:error xmlns:XA="http://mondrian.sourceforge.net"> 
			      <code>00HSBB01</code> 
			      <desc>The Mondrian XML: No enum const class mondrian.xmla.RowsetDefinition.DBSCHEMA_SCsHEMATA</desc> 
			    </XA:error> 
			  </detail> 
			</SOAP-ENV:Fault> 
			</SOAP-ENV:Body> 
			</SOAP-ENV:Envelope>';
		
		$adaptator = $this->getSoapAdaptatorMockDoRequest($response);
		
        try {
			$test = $adaptator->discover(
					'DBSCHEMA_SCsHEMATA',
					array(),
					array()
				);
        }
        catch (AdaptatorException $expected) {
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
	}

	public function testCallErrorNoSoap()
	{
		
		$response = null;
		
		$adaptator = $this->getSoapAdaptatorMockDoRequest($response);
		
        try {
			$test = $adaptator->execute('mdx', array());
        }
        catch (AdaptatorException $expected) {
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
	}

	public function testCallExecute()
	{
		
		$statementResult = new \DOMDocument();
		$statementResult->load(__DIR__.'/../statementResult.xml');
		$adaptator = $this->getSoapAdaptatorMockDoRequest($statementResult->saveXml());
		
		$test = $adaptator->execute('mdx', array());
        $result = $statementResult->getElementsByTagName('root')->item(0);

		$this->assertEquals($test, $result); 

	}

	public function testRequestsHistory()
	{
		
		$adaptator = $this->getSoapAdaptatorMockDoRequest("<result></result>");
		$test1 = $adaptator->discover(
				'DBSCHEMA_SCsHEMATA',
				array(),
				array()
			);
		$test2 = $adaptator->discover(
				'MDSCHEMA_DIMENSIONS',
				array(),
				array()
			);
			
		$this->assertEquals(2, count($adaptator->getRequestsHistory()));

	}

	protected function compareXml($test, $resultXml)
	{

		$result = new \DOMDocument();
		$result->loadXML($resultXml);
		
		$this->assertEquals($test, $result); 
	}

	protected function getSoapAdaptatorMockCall()
	{
        $stub = $this->getMock(
				'phpOlap\Xmla\Connection\Adaptator\SoapAdaptator',
				array('call'),
				array('http://localhost:8080/mondrian/xmla.jsp')
				); 
        $stub->expects($this->any()) 
             ->method('call') 
             ->will($this->returnArgument(0)); 
		return $stub;
	}

	protected function getSoapAdaptatorMockDoRequest($xml)
	{

      	$stub = $this->getMock(
				'phpOlap\Xmla\Connection\Adaptator\SoapAdaptator',
				array('__doRequest'),
				array('http://localhost:8080/mondrian/xmla.jsp')
				); 
        $stub->expects($this->any()) 
             ->method('__doRequest') 
             ->will($this->returnValue($xml)); 
		return $stub;
	}

}
