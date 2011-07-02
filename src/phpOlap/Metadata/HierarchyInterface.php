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
*	Hierarchy interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
interface HierarchyInterface
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
     * Get cardinality
     *
     * @return Int Cardinality
     *
     */
	public function getCardinality();

    /**
     * Get default member unique name
     *
     * @return String Default Member Unique Name
     *
     */
	public function getDefaultMemberUniqueName();

    /**
     * Get Structure
     *
     * @return Int Struncture
     *
     */
	public function getStructure();

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
     * Get ordinal
     *
     * @return Int Ordinal
     *
     */
	public function getOrdinal();

    /**
     * Get parent child
     *
     * @return Boolean Parent Child
     *
     */
	public function getParentChild();

    /**
     * Get Levels
     *
     * @return Array Levels collection
     *
     */
	public function getLevels();
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection);
}