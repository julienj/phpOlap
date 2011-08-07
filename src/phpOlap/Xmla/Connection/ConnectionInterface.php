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

use phpOlap\Xmla\Metadata\Database;
use phpOlap\Xmla\Metadata\Catalog;
use phpOlap\Xmla\Metadata\Schema;

/**
*	Connection Interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Connection
*/

interface ConnectionInterface {
	

    /**
     * Get Adaptator
     *
     * @return AdaptatorInterface Adaptator
     *
     */
	public function getSoapAdaptator();

   /**
     * find databases
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Database objects collection
     *
     */
	public function findDatabases(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one database
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Database object
      *
      */
 	public function findOneDatabase(Array $propertyList = null, Array $restrictionList = null);


   /**
     * find catalogs
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Catalog objects collection
     *
     */
	public function findCatalogs(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one catalog
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Catalog object
      *
      */
 	public function findOneCatalog(Array $propertyList = null, Array $restrictionList = null);

   /**
     * find schema
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Schema objects collection
     *
     */
	public function findSchemas(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one schema
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Schema object
      *
      */
 	public function findOneSchema(Array $propertyList = null, Array $restrictionList = null);


   /**
     * find cubes
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Cube object collection
     *
     */
	public function findCubes(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one cube
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Cube object
      *
      */
 	public function findOneCube(Array $propertyList = null, Array $restrictionList = null);


   /**
     * find dimensions
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Dimension objects collection
     *
     */
	public function findDimensions(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one dimension
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Dimension object
      *
      */
 	public function findOneDimension(Array $propertyList = null, Array $restrictionList = null);


   /**
     * find hierarchies
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Hierarchy objects collection
     *
     */
	public function findHierarchies(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one hierarchy
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Hierarchy object
      *
      */
 	public function findOneHierarchy(Array $propertyList = null, Array $restrictionList = null);


   /**
     * find levels
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Level objects collection
     *
     */
	public function findLevels(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one level
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Level object
      *
      */
 	public function findOneLevel(Array $propertyList = null, Array $restrictionList = null);


   /**
     * find members
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Member objects collection
     *
     */
	public function findMembers(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one member
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Member object
      *
      */
 	public function findOneMember(Array $propertyList = null, Array $restrictionList = null);


   /**
     * find measures
     *
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return Array Measure objects collection
     *
     */
	public function findMeasures(Array $propertyList = null, Array $restrictionList = null);

    /**
      * find one measure
      *
      * @param Array $propertyList Proterty list
      * @param Array $restrictionList Restriction list
 	 *
      * @return Measure object
      *
      */
 	public function findOneMeasure(Array $propertyList = null, Array $restrictionList = null);

   /**
     * execute statement
     *
     * @param Array $propertyList Proterty list
	 *
     * @return DOMNode Xml result
     *
     */
	public function statement($mdx, Array $propertyList = null);

}