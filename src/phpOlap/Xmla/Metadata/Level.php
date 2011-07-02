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
use phpOlap\Metadata\LevelInterface;

/**
*	Level class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Metadata
*/
class Level extends MetadataBase implements LevelInterface
{
	protected $cubeName;
	protected $dimensionUniqueName;
	protected $hierarchyUniqueName;
	protected $uniqueName;
	protected $caption;
	protected $number;
	protected $cardinality;
	protected $type;
	protected $customRollupSettings;
	protected $uniqueSettings;
	protected $isVisible;
	protected $members;


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
     * Get hierarchy unique name
     *
     * @return String hierarchy unique name
     *
     */
	public function getHierarchyUniqueName(){
		return $this->hierarchyUniqueName;
	}

    /**
     * Get unique name
     *
     * @return String Unique name
     *
     */
	public function getUniqueName(){
		return $this->uniqueName;
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
     * Get mumber
     *
     * @return Int Mumber
     *
     */
	public function getMumber(){
		return intval($this->number);
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
     * Get type
     *
     * @return Int Type
     *
     */
	public function getType(){
		return intval($this->type);
	}

    /**
     * Get costum rollup settings
     *
     * @return String Unique settings
     *
     */
	public function getCustomRollupSettings(){
		return $this->customRollupSettings;
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
     * Get Members
     *
     * @return Array Members collection
     *
     */
	public function getMembers(){
		if (!$this->members) {
			$this->members = $this->getConnection()->findMembers(
					array(),
					array(
						'CUBE_NAME' => $this->getCubeName(),
						'DIMENSION_UNIQUE_NAME' => $this->getDimensionUniqueName(),
						'HIERARCHY_UNIQUE_NAME' => $this->getHierarchyUniqueName(),
						'LEVEL_UNIQUE_NAME' => $this->getUniqueName()
					)
				);
		}
		return $this->members;
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
		$this->hierarchyUniqueName = parent::getPropertyFromNode($node, 'HIERARCHY_UNIQUE_NAME', false);		
		$this->name = parent::getPropertyFromNode($node, 'LEVEL_NAME', false);
		$this->uniqueName = parent::getPropertyFromNode($node, 'LEVEL_UNIQUE_NAME', false);
		$this->description = parent::getPropertyFromNode($node, 'DESCRIPTION');
		$this->caption = parent::getPropertyFromNode($node, 'LEVEL_CAPTION');
		$this->number =  parent::getPropertyFromNode($node, 'LEVEL_NUMBER');
		$this->cardinality = parent::getPropertyFromNode($node, 'LEVEL_CARDINALITY');
		$this->type = parent::getPropertyFromNode($node, 'LEVEL_TYPE');
		$this->customRollupSettings = parent::getPropertyFromNode($node, 'CUSTOM_ROLLUP_SETTINGS');
		$this->uniqueSettings = parent::getPropertyFromNode($node, 'LEVEL_UNIQUE_SETTINGS');
		$this->isVisible = parent::getPropertyFromNode($node, 'LEVEL_IS_VISIBLE');
	}

}