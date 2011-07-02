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

use phpOlap\Metadata\CellDataInterface;
use phpOlap\Xmla\Metadata\MetadataBase;

/**
*	CellData
*
*	@package Xmla
*	@subpackage Metadata
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/

class CellData implements CellDataInterface
{

	protected $value;
	protected $formatedValue;
	protected $formatString;

    /**
     * Return cell value
     *
     * @return float Cell value
     *
     */	
	public function getValue()
	{
		return $this->value;
	}	

    /**
     * Return formated value
     *
     * @return String Cell formated value
     *
     */	
	public function getFormatedValue()
	{
		return $this->formatedValue;
	}
	
    /**
     * Return format
     *
     * @return String Cell format
     *
     */
	public function getFormatString()
	{
		return $this->formatString;
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
		$this->value = MetadataBase::getPropertyFromNode($node, 'Value', true);
		$this->formatedValue = MetadataBase::getPropertyFromNode($node, 'FmtValue', true);
		$this->formatString = MetadataBase::getPropertyFromNode($node, 'FormatString', true);
	}
	
}