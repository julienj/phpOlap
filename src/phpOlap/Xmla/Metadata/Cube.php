<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Xmla\Metadata;

use phpOlap\Xmla\Connection\ConnectionInterface;
use phpOlap\Xmla\Metadata\MetadataBase;
use phpOlap\Metadata\CubeInterface;
/**
*	Cube Class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Metadata
*/
class Cube extends MetadataBase implements CubeInterface
{

	protected $type;
	protected $dimensions;
	protected $measures;

    /**
     * Get type
     *
     * @return String Type
     *
     */
	public function getType(){
		return $this->type;
	}

    /**
     * Get Dimentions
     *
     * @return Array Dimensions collection
     *
     */
	public function getDimensions(){
		if (!$this->dimensions) {
			$this->dimensions = $this->getConnection()->findDimensions(
					array(),
					array('CUBE_NAME' => $this->getName())
				);
		}
		return $this->dimensions;
	}

    /**
     * Get Measures
     *
     * @return Array Measures collection
     *
     */
	public function getMeasures(){
		if (!$this->measures) {
			$this->measures = $this->getConnection()->findMeasures(
					array(),
					array('CUBE_NAME' => $this->getName())
				);
		}
		return $this->measures;
	}
	
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection)
	{
		$this->connection = $connection;
		$this->name = parent::getPropertyFromNode($node, 'CUBE_NAME', false);
		$this->description = parent::getPropertyFromNode($node, 'DESCRIPTION');
		$this->type = parent::getPropertyFromNode($node, 'CUBE_TYPE');
	}
}
