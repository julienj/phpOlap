README
======


phpOlap is a php API for OLAP (XMLA)

phpOlap can be used to explore schema (cubes, dimensions, hierarchies, levels, ...) and execute MDX Query, generate layout, ...

phpOlap is only supported on PHP 5.3.2 and up.


API : http://phpolap.org/

Database exploration
----------------

``` php
<?php
require_once '../autoload.php';
use phpOlap\Xmla\Connection\Connection;
use phpOlap\Xmla\Connection\Adaptator\SoapAdaptator;

// for Mondrian
$connection = new Connection(new SoapAdaptator('http://localhost:8080/mondrian/xmla'));
// for Microsoft SQL Server Analysis Services
//$connection = new Connection(new SoapAdaptator('http://192.168.1.13/olap/msmdpump.dll', 'user', 'pass'));
$database = $connection->getActivDatabase();
$catalog = $connection->getActivCatalog();
$schema = $connection->getActivSchema();
$cube = $connection->findOneCube(null, array('CUBE_NAME' => 'Sales'));
	
?>

<p><label>Database :</label> <?php echo $database->getName() ?></p>
<p><label>Catalog :</label> <?php echo $catalog->getName() ?></p>
<p><label>Schema :</label> <?php echo $schema->getName() ?></p>
<p><label>Cube :</label> <?php echo $cube->getName() ?></p>
<ul id="cubeExploration">
	<li class="measure">
		Measures
		<ul>
			<?php foreach ($cube->getMeasures() as $measure): ?>
				<li><?php echo $measure->getCaption() ?></li>
			<?php endforeach ?>
		</ul>
	</li>		
	<?php foreach ($cube->getDimensionsAndHierarchiesAndLevels() as $dimention): ?>
		<?php if($dimention->getType() != 'MEASURE') : ?>
		<li>
			<?php echo $dimention->getCaption() ?>
			<ul>
				<?php foreach ($dimention->getHierarchies() as $hierarchy): ?>
					<li>
						<?php echo $hierarchy->getCaption() ?>
						<ul>
							<?php foreach ($hierarchy->getLevels() as $level): ?>
								<li>
									<?php echo $level->getCaption() ?>
								</li>
							<?php endforeach ?>
						</ul>
					</li>
				<?php endforeach ?>
			</ul>
		</li>
		<?php endif; ?>
	<?php endforeach ?>
</ul>
		
```

Query
-----

``` php
<?php

require_once '../autoload.php';

use phpOlap\Mdx\Query;

$query = new Query("[Sales]");
$query->addElement("[Measures].[Unit Sales]", "COL");
$query->addElement("[Measures].[Store Cost]", "COL");
$query->addElement("[Measures].[Store Sales]", "COL");
$query->addElement("[Gender].[All Gender].Children", "COL");
$query->addElement("[Promotion Media].[All Media]", "ROW");
$query->addElement("[Product].[All Products].[Drink].[Alcoholic Beverages]", "ROW");
$query->addElement("[Promotion Media].[All Media].Children", "ROW");
$query->addElement("[Product].[All Products]", "ROW");
$query->addElement("[Time].[1997]", "FILTER");

echo $query->toMdx();
```

Layout
------
``` php
<?php
require_once '../autoload.php';

use phpOlap\Xmla\Connection\Connection;
use phpOlap\Xmla\Connection\Adaptator\SoapAdaptator;
use phpOlap\Layout\Table\HtmlTableLayout;
use phpOlap\Layout\Table\CsvTableLayout;


$resultSet = $connection->statement("
	select Hierarchize(Union(Union({([Measures].[Unit Sales], [Gender].[All Gender], [Marital Status].[All Marital Status])}, Union(Union(Crossjoin({[Measures].[Store Cost]}, {([Gender].[All Gender], [Marital Status].[All Marital Status])}), Crossjoin({[Measures].[Store Cost]}, Crossjoin([Gender].[All Gender].Children, {[Marital Status].[All Marital Status]}))), Crossjoin({[Measures].[Store Cost]}, Crossjoin({[Gender].[F]}, [Marital Status].[All Marital Status].Children)))), Crossjoin({[Measures].[Store Sales]}, Union(Crossjoin({[Gender].[All Gender]}, {[Marital Status].[All Marital Status]}), Crossjoin({[Gender].[All Gender]}, [Marital Status].[All Marital Status].Children))))) ON COLUMNS,
	  Crossjoin(Hierarchize(Crossjoin(Union({[Promotion Media].[All Media]}, [Promotion Media].[All Media].Children), Union(Union({[Product].[All Products]}, [Product].[All Products].Children), [Product].[Food].Children))), {[Store].[All Stores]}) ON ROWS
	from [Sales]
	where {[Time].[1997]}

");


// html table
$table = new HtmlTableLayout($resultSet);
echo $table->generate();

// csv
header("Content-type: application/vnd.ms-excel"); 
header("Content-disposition: attachment; filename=\"export.csv\"");
$csv = new CsvTableLayout($resultSet);
print($csv->generate()); 
exit;
