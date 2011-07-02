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
*	Member interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
interface MemberInterface
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
     * Get unique name
     *
     * @return String Unique name
     *
     */
	public function getUniqueName();

    /**
     * Get ordinal
     *
     * @return Int Ordinal
     *
     */
	public function getOrdinal();

    /**
     * Get  type
     *
     * @return String type
     *
     */
	public function getType();
	
    /**
     * Get caption
     *
     * @return String Caption
     *
     */
	public function getCaption();
	
    /**
     * Get children cardinality
     *
     * @return Int Children cardinality
     *
     */
	public function getChildrenCardinality();

    /**
     * Get parent level
     *
     * @return Int Parent level
     *
     */
	public function getParentLevel();

    /**
     * Get parent count
     *
     * @return Int Parent count
     *
     */
	public function getParentCount();

    /**
     * Get depth
     *
     * @return Int Depth
     *
     */
	public function getDepth();
	
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection);
}