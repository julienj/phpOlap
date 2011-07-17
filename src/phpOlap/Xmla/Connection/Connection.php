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
use phpOlap\Xmla\Connection\Adaptator\AdaptatorException;

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
			$this->activDatabase = $this->findOneDatabase();
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
			$this->activCatalog = $this->findOneCatalog();
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
			$this->activSchema = $this->findOneSchema();
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
	public function findOneDatabase(Array $propertyList = null, Array $restrictionList = null)
	{
		$databases = $this->findDatabases($propertyList, $restrictionList);
		return count($databases) ? current($databases) : null;	
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
	public function findOneCatalog(Array $propertyList = null, Array $restrictionList = null)
	{
		$catalogs = $this->findCatalogs($propertyList, $restrictionList);
		return count($catalogs) ? current($catalogs) : null;	
	}
	
    /**
     * {@inheritdoc}
     */
	public function findSchemas(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		
		try{
		    $result = $this->getSoapAdaptator()->discover('DBSCHEMA_SCHEMATA', $propertyList, $restrictionList);		    
		    return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Schema');
		} catch(AdaptatorException $e){
		    // if mssql
		    return array();
		}
	}
	
    /**
     * {@inheritdoc}
     */
	public function findOneSchema(Array $propertyList = null, Array $restrictionList = null)
	{
		$schemas = $this->findSchemas($propertyList, $restrictionList);
		return count($schemas) ? current($schemas) : null;
	}
	
    /**
     * {@inheritdoc}
     */
	public function findCubes(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		if ($this->getActivSchema()) {
            $restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
        }
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_CUBES', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Cube');
	}

    /**
     * {@inheritdoc}
     */
	public function findOneCube(Array $propertyList = null, Array $restrictionList = null)
	{
		$cubes = $this->findCubes($propertyList, $restrictionList);
		return count($cubes) ? current($cubes) : null;
	}

    /**
     * {@inheritdoc}
     */
	public function findDimensions(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		if ($this->getActivSchema()) {
            $restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
        }
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_DIMENSIONS', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Dimension');
	}	

    /**
     * {@inheritdoc}
     */
	public function findOneDimension(Array $propertyList = null, Array $restrictionList = null)
	{
		$dimensions = $this->findDimensions($propertyList, $restrictionList);
		return count($dimensions) ? current($dimensions) : null;
	}

    /**
     * {@inheritdoc}
     */
	public function findHierarchies(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		if ($this->getActivSchema()) {
            $restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
        }
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_HIERARCHIES', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Hierarchy');
	}

    /**
     * {@inheritdoc}
     */
	public function findOneHierarchy(Array $propertyList = null, Array $restrictionList = null)
	{
		$hierarchies = $this->findHierarchies($propertyList, $restrictionList);
		return count($hierarchies) ? current($hierarchies) : null;
	}


    /**
     * {@inheritdoc}
     */
	public function findLevels(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		if ($this->getActivSchema()) {
            $restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
        }
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_LEVELS', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Level');
	}

    /**
     * {@inheritdoc}
     */
	public function findOneLevel(Array $propertyList = null, Array $restrictionList = null)
	{
		$levels = $this->findLevels($propertyList, $restrictionList);
		return count($levels) ? current($levels) : null;
	}

    /**
     * {@inheritdoc}
     */
	public function findMembers(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		if ($this->getActivSchema()) {
            $restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
        }
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_MEMBERS', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Member');
	}

    /**
     * {@inheritdoc}
     */
	public function findOneMember(Array $propertyList = null, Array $restrictionList = null)
	{
		$members = $this->findLevels($propertyList, $restrictionList);
		return count($members) ? current($members) : null;
	}

    /**
     * {@inheritdoc}
     */
	public function findMeasures(Array $propertyList = null, Array $restrictionList = null)
	{
		$propertyList = self::setDefault('Format', 'Tabular', $propertyList);
		$propertyList = self::setDefault('DataSourceInfo', $this->getActivDatabase()->getDataSourceInfo(), $propertyList);
		$propertyList = self::setDefault('Catalog', $this->getActivCatalog()->getName(), $propertyList);
		if ($this->getActivSchema()) {
            $restrictionList = self::setDefault('SCHEMA_NAME', $this->getActivSchema()->getName(), $restrictionList);
        }
		
		$result = $this->getSoapAdaptator()->discover('MDSCHEMA_MEASURES', $propertyList, $restrictionList);
		
		return $this->hydrate($result, 'phpOlap\Xmla\Metadata\Measure');
	}

    /**
     * {@inheritdoc}
     */
	public function findOneMeasure(Array $propertyList = null, Array $restrictionList = null)
	{
		$measures = $this->findMeasures($propertyList, $restrictionList);
		return count($measures) ? current($measures) : null;
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
			$collection[$object->getUniqueName()] = $object;
		}
		return $collection;
		
	}
}
