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
use phpOlap\Metadata\CellAxisInterface;
use phpOlap\Xmla\Metadata\MetadataBase;


/**
*	cellAxis
*
*	@package Xmla
*	@subpackage Metadata
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/

class CellAxis implements CellAxisInterface
{

	protected $memberUniqueName;
	protected $memberCaption;
	protected $levelUniqueName;
	protected $levelNumber;
	protected $displayInfo;
	
    /**
     * Return member unique name
     *
     * @return String Member unique name
     *
     */
	public function getMemberUniqueName()
	{
		return $this->memberUniqueName;
	}	

    /**
     * Return member caption
     *
     * @return String Member caption
     *
     */	
	public function getMemberCaption()
	{
		return $this->memberCaption;
	}

    /**
     * Return level unique name
     *
     * @return String Level unique name
     *
     */	
	public function getLevelUniqueName()
	{
		return $this->levelUniqueName;
	}

    /**
     * Return level number
     *
     * @return Int Level number
     *
     */	
	public function getLevelNumber()
	{
		return $this->levelNumber;
	}

    /**
     * Return display info
     *
     * @return Int Display info
     *
     */	
	public function getDisplayInfo()
	{
		return $this->displayInfo;
	}

    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node)
	{
		$this->memberUniqueName = MetadataBase::getPropertyFromNode($node, 'UName', false);
		$this->memberCaption = MetadataBase::getPropertyFromNode($node, 'Caption', false);
		$this->levelUniqueName = MetadataBase::getPropertyFromNode($node, 'LName', false);
		$this->levelNumber = MetadataBase::getPropertyFromNode($node, 'LNum', false);
		$this->displayInfo = MetadataBase::getPropertyFromNode($node, 'DisplayInfo');
	}
	
}