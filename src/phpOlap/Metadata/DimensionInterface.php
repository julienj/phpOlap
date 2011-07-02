<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Metadata;

use phpOlap\Xmla\Connection\ConnectionInterface;

/**
*	Dimension Interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
interface DimensionInterface
{

    /**
     * Get connection
     *
     * @return ConnectionInterface Connection object
     *
     */
	public function getConnection();

    /**
     * Get name
     *
     * @return String Name
     *
     */
	public function getName();

    /**
     * Get description
     *
     * @return String description
     *
     */
	public function getDescription();

    /**
     * Get cube name
     *
     * @return String cube name
     *
     */
	public function getCubeName();

    /**
     * Get unique name
     *
     * @return String Unique name
     *
     */
	public function getUniqueName();
	
    /**
     * Get caption
     *
     * @return String Caption
     *
     */
	public function getCaption();

    /**
     * Get ordinal
     *
     * @return Int Ordinal
     *
     */
	public function getOrdinal();
	
    /**
     * Get type
     *
     * @return String Type
     *
     */
	public function getType();

    /**
     * Get cardinality
     *
     * @return Int Cardinality
     *
     */
	public function getCardinality();

    /**
     * Get default hierarchy unique name
     *
     * @return String Default hierarchy unique name
     *
     */
	public function getDefaultHierarchyUniqueName();
	
    /**
     * Is virtual
     *
     * @return Boolean is virtual
     *
     */
	public function isVirtual();

    /**
     * Is read write
     *
     * @return Boolean is read write
     *
     */
	public function isReadWrite();

    /**
     * Get unique settings
     *
     * @return String Unique settings
     *
     */
	public function getUniqueSettings();

    /**
     * Is visible
     *
     * @return Boolean is visible
     *
     */
	public function isVisible();

    /**
     * Get Hierarchies
     *
     * @return Array Hierarchies collection
     *
     */
	public function getHierarchies();
	
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection);
	
	
}