<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace phpOlap\Xmla\Connection;

use phpOlap\Xmla\Connection\ConnectionInterface;
use phpOlap\Xmla\Connection\Adaptator\AdaptatorInterface;

use phpOlap\Xmla\Metadata\Database;
use phpOlap\Xmla\Metadata\Catalog;
use phpOlap\Xmla\Metadata\Schema;
use phpOlap\Xmla\Metadata\ResultSet;

/**
*	Connection Interface
*	@package Xmla
*	@subpackage Connection
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/
class Connection  implements ConnectionInterface
{	
	protected $soapAdaptator;

	protected $activDatabase;

	protected $activCatalog;
	
	protected $activSchema;
				
    /**
     * Constructor.
     *
     * @param AdaptatorInterface $soapAdaptator Soap Adaptator
     * @param Database $database Default database
     */
	function __construct(AdaptatorInterface $soapAdaptator, Database $database = null)
	{
		$this->soapAdaptator = $soapAdaptator;
		$this->activDatabase = $database;
	}
	
    /**
     * {@inheritdoc}
     */
	public function getSoapAdaptator()
	{
		return $this->soapAdaptator;
	}
	
    /**
     * {@inheritdoc}
     */
	public function getActivDatabase()
	{
		if (!$this->activDatabase) {
			$databases = $this->findDatabases();
			$this->activDatabase = $databases[0];
		}
		return $this->activDatabase;
	}
	
    /**
     * {@inheritdoc}
     */
	public function setActivDatabase(Database $database)
	{
		$this->activDatabase = $database;
	}
	
    /**
     * {@inheritdoc}
     */
	public function getActivCatalog()
	{
		if (!$this->activCatalog) {
			$catalogs = $this->findCatalogs();
			$this->activCatalog = $catalogs[0];
		}
		return $this->activCatalog;
	}
	
    /**
     * {@inheritdoc}
     */
	public function setActivCatalog(Catalog $catalog)
	{
		$this->activCatalog = $catalog;
	}
	
    /**
     * {@inheritdoc}
     */
	public function getActivSchema()
	{
		if (!$this->activSchema) {
			$schemas = $this->findSchemas();
			$this->activSchema = $schemas[0];
		}
		return $this->activSchema;
	}
	
    /**
     * {@inheritdoc}
     */
	public function setActivSchema(Schema $schema)
	{
		$this->activSchema = $schema;
	}
	
    /**
     * {@inheritdoc}
     */
	public function findDatabases(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$result = $this->getSoapAdaptator()->discover('DISCOVER_DATASOURCES', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Database');
	}
	
    /**
     * {@inheritdoc}
     */
	public function findCatalogs(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		
		$result = $this->getSoapAdaptator()->discover('DBSCHEMA_CATALOGS', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Catalog');
	}
	
    /**
     * {@inheritdoc}
     */
	public function findSchemas(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		
		$result = $this->getSoapAdaptator()->discover('DBSCHEMA_SCHEMATA', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Schema');
	}
	
    /**
     * {@inheritdoc}
     */
	public function findCubes(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		$restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_CUBES', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Cube');
	}
	
    /**
     * {@inheritdoc}
     */
	public function findDimensions(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		$restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_DIMENSIONS', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Dimension');
	}	
	
    /**
     * {@inheritdoc}
     */
	public function findHierarchies(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		$restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_HIERARCHIES', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Hierarchy');
	}
	
    /**
     * {@inheritdoc}
     */
	public function findLevels(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		$restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_LEVELS', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Level');
	}
	
    /**
     * {@inheritdoc}
     */
	public function findMembers(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		$restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_MEMBERS', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Member');
	}
	
    /**
     * {@inheritdoc}
     */
	public function findMeasures(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		$restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_MEASURES', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Measure');
	}


    /**
     * {@inheritdoc}
     */
	public function statement($mdx, Array $propertyList = null)
	{
		$propertyList = self::setDefault('Format', 'Multidimensional', $propertyList);
		$propertyList = self::setDefault('AxisFormat', 'TupleFormat', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);

		$resultSet = new resultSet();
		$resultSet->hydrate($this->getSoapAdaptator()->execute($mdx, $propertyList));
		return $resultSet;
	}	

	public static function setDefault($key, $default, Array $array = null)
	{
		
		$array = $array ? $array : array();
		if (!array_key_exists($key, $array)) {
			$array[$key] = $default;
		}
		return $array;
	}
	
	public function hydrate($result, $class)
	{
		$collection = array();
		foreach ($result as $node) {
			$object = new $class;
			$object->hydrate($node, $this);
			$collection[] = $object;
		}
		return $collection;
		
	}
}
