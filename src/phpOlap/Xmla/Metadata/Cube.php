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
     * Get unique name
     *
     * @return String Unique name
     *
     */
	public function getUniqueName(){
		return "[" . $this->name . "]";
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
     * Get Dimentions, Hierarchies and levels with 3 requests
     *
     * @return Array Dimensions collection
     *
     */
	public function getDimensionsAndHierarchiesAndLevels(){

		$dimensions = $this->getDimensions();
		
		// add hierarchies
		$hierarchies = $this->getConnection()->findHierarchies(
				array(),
				array('CUBE_NAME' => $this->getName())
		);				
		foreach ($hierarchies as $hierarchyUniqueName => $hierarchy) {
            if (array_key_exists($hierarchy->getDimensionUniqueName(), $dimensions)) {
                $dimensions[$hierarchy->getDimensionUniqueName()]->addHierachy($hierarchy);
            }
		}
		
		// add levels
		$levels = $this->getConnection()->findLevels(
				array(),
				array('CUBE_NAME' => $this->getName())
		);		
    	foreach ($levels as $levelUniqueName => $level) {
            if (array_key_exists($level->getDimensionUniqueName(), $dimensions)) {
                $dimensions[$level->getDimensionUniqueName()]->addLevel($level);
            }
    	}		
		
		$this->dimensions = $dimensions;		
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
