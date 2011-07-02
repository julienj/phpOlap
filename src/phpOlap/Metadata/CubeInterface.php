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
*	Cube interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
interface CubeInterface
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
     * Get type
     *
     * @return String Type
     *
     */
	public function getType();
	
    /**
     * Get Dimentions
     *
     * @return Array Dimensions collection
     *
     */
	public function getDimensions();

    /**
     * Get Measures
     *
     * @return Array Measures collection
     *
     */
	public function getMeasures();
	
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection);
}
