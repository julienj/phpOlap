<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Tests\Mdx;

use phpOlap\Mdx\Query;
use phpOlap\Mdx\QueryException;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testUnion1Dim()
    {
		$test = Query::union(array("[Promotion Media].[All Media]"));
		$result = "[Promotion Media].[All Media]";		
        $this->assertEquals($test, $result); 
    }

    public function testUnion2Dim()
    {
			
		$test = Query::union(array(
					"[Promotion Media].[All Media]",
					"[Promotion Media].[All Media].Children"
				));
		$result = "Union([Promotion Media].[All Media], [Promotion Media].[All Media].Children)";		
        $this->assertEquals($test, $result);
    }

    public function testUnion3Dim()
    {
			
		$test = Query::union(array(
					"[Promotion Media].[All Media]",
					"[Promotion Media].[All Media].Children",
					"[Promotion Media].[All Media].[Daily Paper].Children"
				));
		$result = "Union(" .
					"[Promotion Media].[All Media], " .
					"Union(" .
						"[Promotion Media].[All Media].Children, " .
						"[Promotion Media].[All Media].[Daily Paper].Children" .
					")" .
				  ")";
				
        $this->assertEquals($test, $result);
    }

    public function testCrossjoin1Dim()
    {
		$test = Query::crossjoin(array("[Promotion Media].[All Media]"));
		$result = "[Promotion Media].[All Media]";		
        $this->assertEquals($test, $result); 
    }

    public function testCrossjoin2Dim()
    {
			
		$test = Query::crossjoin(array(
					"[Promotion Media].[All Media]",
					"[Promotion Media].[All Media].Children"
				));
		$result = "Crossjoin([Promotion Media].[All Media], [Promotion Media].[All Media].Children)";		
        $this->assertEquals($test, $result);
    }

    public function testCrossjoin3Dim()
    {
			
		$test = Query::crossjoin(array(
					"[Promotion Media].[All Media]",
					"[Promotion Media].[All Media].Children",
					"[Promotion Media].[All Media].[Daily Paper].Children"
				));
		$result = "Crossjoin(" .
					"[Promotion Media].[All Media], " .
					"Crossjoin(" .
						"[Promotion Media].[All Media].Children, " .
						"[Promotion Media].[All Media].[Daily Paper].Children" .
					")" .
				  ")";
        $this->assertEquals($test, $result);
    }

    public function testInvalidAxis()
    {
        try {
			$test = new Query("[Sales]");
			$test->addElement("[Measures].[Unit Sales]", "AXIS");
        }
        catch (QueryException $expected) {
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }

    public function testEmptyRow()
    {
        try {
			$test = new Query("[Sales]");
			$test->addElement("[Measures].[Unit Sales]", "COL");
			$test->toMdx();
        }
        catch (QueryException $expected) {
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }

    public function testEmptyCol()
    {
        try {
			$test = new Query("[Sales]");
			$test->addElement("[Measures].[Unit Sales]", "ROW");
			$test->toMdx();
        }
        catch (QueryException $expected) {
            return;
        }
 
        $this->fail('An expected exception has not been raised.');
    }


    public function testSimpleQuery()
    {
		$test = new Query("[Sales]");
		$test->addElement("[Measures].[Unit Sales]", "COL");
		$test->addElement("[Measures].[Store Cost]", "COL");
		$test->addElement("[Measures].[Store Sales]", "COL");
		$test->addElement("[Promotion Media].[All Media]", "ROW");
		$test->addElement("[Promotion Media].[All Media].Children", "ROW");
		$test->addElement("[Product].[All Products]", "ROW");
		$test->addElement("[Time].[1997]", "FILTER");
		
		$result = "SELECT " .
					"{[Measures].[Unit Sales], [Measures].[Store Cost], [Measures].[Store Sales]} ON COLUMNS, " .
		  			"Crossjoin(" .
						"Hierarchize(" .
							"Union(" .
								"[Promotion Media].[All Media], " .
								"[Promotion Media].[All Media].Children" .
							")" .
						"), " .
						"[Product].[All Products]" .
					") ON ROWS " .
				  "FROM [Sales] " .
				  "WHERE [Time].[1997]";
				
        $this->assertEquals($test->toMdx(), $result);
    }

    public function testQueryWithNonEmpty()
    {
		$test = new Query("[Sales]");
		$test->addElement("[Measures].[Unit Sales]", "COL");
		$test->addElement("[Measures].[Store Cost]", "COL");
		$test->addElement("[Measures].[Store Sales]", "COL");
		$test->addElement("[Promotion Media].[All Media]", "ROW");
		$test->addElement("[Promotion Media].[All Media].Children", "ROW");
		$test->addElement("[Product].[All Products]", "ROW");
		$test->addElement("[Time].[1997]", "FILTER");
		$test->setNonEmpty(true);
		
		$result = "SELECT " .
					"NON EMPTY {[Measures].[Unit Sales], [Measures].[Store Cost], [Measures].[Store Sales]} ON COLUMNS, " .
		  			"NON EMPTY Crossjoin(" .
						"Hierarchize(" .
							"Union(" .
								"[Promotion Media].[All Media], " .
								"[Promotion Media].[All Media].Children" .
							")" .
						"), " .
						"[Product].[All Products]" .
					") ON ROWS " .
				  "FROM [Sales] " .
				  "WHERE [Time].[1997]";
				
        $this->assertEquals($test->toMdx(), $result);
    }

    public function testQueryWithoutFilter()
    {
		$test = new Query("[Sales]");
		$test->addElement("[Measures].[Unit Sales]", "COL");
		$test->addElement("[Measures].[Store Cost]", "COL");
		$test->addElement("[Measures].[Store Sales]", "COL");
		$test->addElement("[Promotion Media].[All Media]", "ROW");
		$test->addElement("[Promotion Media].[All Media].Children", "ROW");
		$test->addElement("[Product].[All Products]", "ROW");
		
		$result = "SELECT " .
					"{[Measures].[Unit Sales], [Measures].[Store Cost], [Measures].[Store Sales]} ON COLUMNS, " .
		  			"Crossjoin(" .
						"Hierarchize(" .
							"Union(" .
								"[Promotion Media].[All Media], " .
								"[Promotion Media].[All Media].Children" .
							")" .
						"), " .
						"[Product].[All Products]" .
					") ON ROWS " .
				  "FROM [Sales]";
				
        $this->assertEquals($test->toMdx(), $result);
    }

    public function testMultipleDimensionsColQuery()
    {
		$test = new Query("[Sales]");
		$test->addElement("[Measures].[Unit Sales]", "COL");
		$test->addElement("[Measures].[Store Cost]", "COL");
		$test->addElement("[Measures].[Store Sales]", "COL");
		$test->addElement("[Gender].[All Gender].Children", "COL");
		$test->addElement("[Promotion Media].[All Media]", "ROW");
		$test->addElement("[Product].[All Products].[Drink].[Alcoholic Beverages]", "ROW");
		$test->addElement("[Promotion Media].[All Media].Children", "ROW");
		$test->addElement("[Product].[All Products]", "ROW");
		$test->addElement("[Time].[1997]", "FILTER");
		
		$result = "SELECT " .
					"Crossjoin(" .
						"{[Measures].[Unit Sales], [Measures].[Store Cost], [Measures].[Store Sales]}, " .
						"[Gender].[All Gender].Children" .
					") ON COLUMNS, " .
					"Crossjoin(" .
						"Hierarchize(" .
							"Union([Promotion Media].[All Media], [Promotion Media].[All Media].Children)" .
						"), " .
						"Hierarchize(" .
							"Union([Product].[All Products].[Drink].[Alcoholic Beverages], [Product].[All Products])" .
						")) ON ROWS " .
					"FROM [Sales] " .
					"WHERE [Time].[1997]";
				
        $this->assertEquals($test->toMdx(), $result);
    }

	public function testNonEmpty()
	{
		$test = new Query("[Sales]");
		$this->assertEquals($test->getNonEmpty(), false);
		$test->setNonEmpty(true);
		$this->assertEquals($test->getNonEmpty(), true);
	}
}