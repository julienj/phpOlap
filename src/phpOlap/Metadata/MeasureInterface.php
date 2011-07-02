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
*	Measure interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
interface MeasureInterface
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
     * Get caption
     *
     * @return String Caption
     *
     */
	public function getCaption();
	
    /**
     * Get aggregator
     *
     * @return String aggregator
     *
     */
	public function getAggregator();

    /**
     * Get data type
     *
     * @return String data type
     *
     */
	public function getDataType();

    /**
     * Is visible
     *
     * @return Boolean is visible
     *
     */
	public function isVisible();
	
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection);
}	