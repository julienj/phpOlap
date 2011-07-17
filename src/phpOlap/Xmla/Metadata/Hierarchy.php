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
use phpOlap\Metadata\HierarchyInterface;

/**
*	Hierarchy class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Metadata
*/
class Hierarchy extends MetadataBase implements HierarchyInterface
{
	protected $cubeName;
	protected $dimensionUniqueName;
	protected $caption;
	protected $cardinality;
	protected $defaultMemberUniqueName;
	protected $structure;
	protected $isVirtual;
	protected $isReadWrite;
	protected $ordinal;
	protected $parentChild;	
	protected $levels;


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
     * Get dimension unique name
     *
     * @return String dimension unique name
     *
     */
	public function getDimensionUniqueName(){
		return $this->dimensionUniqueName;
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
     * Get cardinality
     *
     * @return Int Cardinality
     *
     */
	public function getCardinality(){
		return intval($this->cardinality);
	}

    /**
     * Get default member unique name
     *
     * @return String Default Member Unique Name
     *
     */
	public function getDefaultMemberUniqueName(){
		return $this->defaultMemberUniqueName;
	}

    /**
     * Get Structure
     *
     * @return Int Struncture
     *
     */
	public function getStructure(){
		return intval($this->structure);
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
     * Get ordinal
     *
     * @return Int Ordinal
     *
     */
	public function getOrdinal(){
		return intval($this->ordinal);
	}


    /**
     * Get parent child
     *
     * @return Boolean Parent Child
     *
     */
	public function getParentChild(){
		return (bool) $this->parentChild;
	}

    /**
     * Get Levels
     *
     * @return Array Levels collection
     *
     */
	public function getLevels(){
		if (!$this->levels) {
			$this->levels = $this->getConnection()->findLevels(
					array(),
					array(
						'HIERARCHY_UNIQUE_NAME' => $this->getUniqueName(),
						'DIMENSION_UNIQUE_NAME' => $this->getDimensionUniqueName(),
						'CUBE_NAME' => $this->getCubeName()
					)
				);
		}
		return $this->levels;
	}

    /**
     * Add Level
     *
     * @param Level $level Level object
     *
     */
	public function addLevel(Level $level){
		$this->levels[$level->getUniqueName()] = $level;
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
		$this->dimensionUniqueName = parent::getPropertyFromNode($node, 'DIMENSION_UNIQUE_NAME', false);
		$this->name = parent::getPropertyFromNode($node, 'HIERARCHY_NAME', false);
		$this->uniqueName = parent::getPropertyFromNode($node, 'HIERARCHY_UNIQUE_NAME', false);
		$this->description = parent::getPropertyFromNode($node, 'DESCRIPTION');
		$this->caption = parent::getPropertyFromNode($node, 'HIERARCHY_CAPTION');
		$this->cardinality = parent::getPropertyFromNode($node, 'HIERARCHY_CARDINALITY');
		$this->defaultMemberUniqueName = parent::getPropertyFromNode($node, 'DEFAULT_MEMBER');
		$this->structure = parent::getPropertyFromNode($node, 'STRUCTURE');
		$this->isVirtual = parent::getPropertyFromNode($node, 'IS_VIRTUAL');
		$this->isReadWrite = parent::getPropertyFromNode($node, 'IS_READWRITE');
		$this->ordinal = parent::getPropertyFromNode($node, 'HIERARCHY_ORDINAL');
		$this->parentChild = parent::getPropertyFromNode($node, 'PARENT_CHILD');	
	}
}