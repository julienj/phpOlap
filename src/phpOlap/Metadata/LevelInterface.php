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
*	Level interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
interface LevelInterface
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
     * Get dimension unique name
     *
     * @return String dimension unique name
     *
     */
	public function getDimensionUniqueName();

    /**
     * Get hierarchy unique name
     *
     * @return String hierarchy unique name
     *
     */
	public function getHierarchyUniqueName();

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
     * Get mumber
     *
     * @return Int Mumber
     *
     */
	public function getMumber();

    /**
     * Get cardinality
     *
     * @return Int Cardinality
     *
     */
	public function getCardinality();

    /**
     * Get type
     *
     * @return Int Type
     *
     */
	public function getType();

    /**
     * Get costum rollup settings
     *
     * @return String Unique settings
     *
     */
	public function getCustomRollupSettings();

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
     * Get Members
     *
     * @return Array Members collection
     *
     */
	public function getMembers();

    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection);
}