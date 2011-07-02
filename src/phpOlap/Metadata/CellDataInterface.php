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

/**
*	CellData Interface
*
*	@package Metadata
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/

interface CellDataInterface
{

    /**
     * Return cell value
     *
     * @return float Cell value
     *
     */
	public function getValue();

    /**
     * Return formated value
     *
     * @return String Cell formated value
     *
     */	
	public function getFormatedValue();

    /**
     * Return format
     *
     * @return String Cell format
     *
     */	
	public function getFormatString();
	
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node);
	
}