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
use phpOlap\Metadata\MemberInterface;


/**
*	Member class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
class Member extends MetadataBase implements MemberInterface
{
	protected $description = null;
	protected $ordinal;
	protected $type;
	protected $caption;
	protected $childrenCardinality;
	protected $parentLevel;
	protected $parentCount;
	protected $depth;

	protected $typeMap = array(
        0 => 'UNKNOWN',
        1 => 'REGULAR',
        2 => 'ALL',
        3 => 'MEASURE',
        4 => 'FORMULA',
        5 => 'NULL'
		);

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
     * Get  type
     *
     * @return String type
     *
     */
	public function getType(){
		if (array_key_exists($this->type, $this->typeMap)) {
			return $this->typeMap[$this->type];
		}
		return 'UNKNOWN';
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
     * Get children cardinality
     *
     * @return Int Children cardinality
     *
     */
	public function getChildrenCardinality(){
		return (Int) $this->childrenCardinality;
	}

    /**
     * Get parent level
     *
     * @return Int Parent level
     *
     */
	public function getParentLevel(){
		return (Int) $this->parentLevel;
	}

    /**
     * Get parent count
     *
     * @return Int Parent count
     *
     */
	public function getParentCount(){
		return (Int) $this->parentCount;
	}


    /**
     * Get depth
     *
     * @return Int Depth
     *
     */
	public function getDepth(){
		return (Int) $this->depth;
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
		$this->name = parent::getPropertyFromNode($node, 'MEMBER_NAME', false);
		$this->uniqueName = parent::getPropertyFromNode($node, 'MEMBER_UNIQUE_NAME', false);
		$this->description = parent::getPropertyFromNode($node, 'DESCRIPTION');
		$this->ordinal = parent::getPropertyFromNode($node, 'MEMBER_ORDINAL');
		$this->type = parent::getPropertyFromNode($node, 'MEMBER_TYPE');		
		$this->caption = parent::getPropertyFromNode($node, 'MEMBER_CAPTION');
		$this->childrenCardinality = parent::getPropertyFromNode($node, 'CHILDREN_CARDINALITY');
		$this->parentLevel = parent::getPropertyFromNode($node, 'PARENT_LEVEL');
		$this->parentCount = parent::getPropertyFromNode($node, 'PARENT_COUNT');
		$this->depth = parent::getPropertyFromNode($node, 'DEPTH');

	}
}