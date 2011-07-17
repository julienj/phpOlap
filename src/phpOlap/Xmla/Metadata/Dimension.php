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
use phpOlap\Xmla\Metadata\Hierarchy;
use phpOlap\Xmla\Metadata\Level;
use phpOlap\Metadata\DimensionInterface;

/**
*	Dimension class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Metadata
*/
class Dimension extends MetadataBase implements DimensionInterface
{
	protected $cubeName;
	protected $caption;
	protected $ordinal;
	protected $type;
	protected $cardinality;
	protected $defaultHierarchyUniqueName;
	protected $isVirtual;
	protected $isReadWrite;
	protected $uniqueSettings;
	protected $isVisible;
	protected $hierarchies = array();

	protected $typeMap = array(
        0 => 'UNKNOWN',
        1 => 'TIME',
        2 => 'MEASURE',
        3 => 'OTHER',
        5 => 'QUANTITATIVE',
        6 => 'ACCOUNTS',
        7 => 'CUSTOMERS',
        8 => 'PRODUCTS',
        9 => 'SCENARIO',
        10 => 'UTILITY',
        11 => 'CURRENCY',
        12 => 'RATES',
        13 => 'CHANNEL',
        14 => 'PROMOTION',
        15 => 'ORGANIZATION',
        16 => 'BILL_OF_MATERIALS',
        17 => 'GEOGRAPHY'
		);


    /**
     * Get cube name
     *
     * @return String cube name
     *
     */
	public function getCubeName(){
		return $this->cubeName;
	}
	
    /**
     * Get caption
     *
     * @return String Caption
     *
     */
	public function getCaption(){
		return $this->caption;
	}

    /**
     * Get ordinal
     *
     * @return Int Ordinal
     *
     */
	public function getOrdinal(){
		return intval($this->ordinal);
	}
	
    /**
     * Get type
     *
     * @return String Type
     *
     */
	public function getType(){
		if (array_key_exists($this->type, $this->typeMap)) {
			return $this->typeMap[$this->type];
		}
		return 'UNKNOWN';
	}

    /**
     * Get cardinality
     *
     * @return Int Cardinality
     *
     */
	public function getCardinality(){
		return intval($this->cardinality);
	}

    /**
     * Get default hierarchy unique name
     *
     * @return String Default hierarchy unique name
     *
     */
	public function getDefaultHierarchyUniqueName(){
		return $this->defaultHierarchyUniqueName;
	}
	
    /**
     * Is virtual
     *
     * @return Boolean is virtual
     *
     */
	public function isVirtual(){
		return (bool) $this->isVirtual;
	}

    /**
     * Is read write
     *
     * @return Boolean is read write
     *
     */
	public function isReadWrite(){
		return (bool) $this->isReadWrite;
	}

    /**
     * Get unique settings
     *
     * @return String Unique settings
     *
     */
	public function getUniqueSettings(){
		return $this->uniqueSettings;
	}

    /**
     * Is visible
     *
     * @return Boolean is visible
     *
     */
	public function isVisible(){
		return (Bool) $this->isVisible;
	}

    /**
     * Get Hierarchies
     *
     * @return Array Hierarchies collection
     *
     */
	public function getHierarchies(){
		if (!$this->hierarchies) {
			$this->hierarchies = $this->getConnection()->findHierarchies(
					array(),
					array(
						'DIMENSION_UNIQUE_NAME' => $this->getUniqueName(),
						'CUBE_NAME' => $this->getCubeName()
					)
				);
		}
		return $this->hierarchies;
	}
	
    /**
     * Add Hierarchy
     *
     * @param Hierarchy $hierarchy Hierarchy object
     *
     */
	public function addHierachy(Hierarchy $hierarchy){
		$this->hierarchies[$hierarchy->getUniqueName()] = $hierarchy;
	}

    /**
     * Add Level
     *
     * @param Level $level Level object
     *
     */	
	public function addLevel(Level $level){
	    if (array_key_exists($level->getHierarchyUniqueName(), $this->hierarchies)) {
	       $this->hierarchies[$level->getHierarchyUniqueName()]->addLevel($level);
	    }
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
		$this->cubeName = parent::getPropertyFromNode($node, 'CUBE_NAME', false);
		$this->name = parent::getPropertyFromNode($node, 'DIMENSION_NAME', false);
		$this->uniqueName = parent::getPropertyFromNode($node, 'DIMENSION_UNIQUE_NAME', false);
		$this->description = parent::getPropertyFromNode($node, 'DESCRIPTION');
		$this->caption = parent::getPropertyFromNode($node, 'DIMENSION_CAPTION');
		$this->ordinal = parent::getPropertyFromNode($node, 'DIMENSION_ORDINAL');
		$this->type = parent::getPropertyFromNode($node, 'DIMENSION_TYPE');
		$this->cardinality = parent::getPropertyFromNode($node, 'DIMENSION_CARDINALITY');
		$this->defaultHierarchyUniqueName = parent::getPropertyFromNode($node, 'DEFAULT_HIERARCHY');
		$this->isVirtual = parent::getPropertyFromNode($node, 'IS_VIRTUAL');
		$this->isReadWrite = parent::getPropertyFromNode($node, 'IS_READWRITE');
		$this->uniqueSettings = parent::getPropertyFromNode($node, 'DIMENSION_UNIQUE_SETTINGS');
		$this->isVisible = parent::getPropertyFromNode($node, 'DIMENSION_IS_VISIBLE');
	}
	
	
}