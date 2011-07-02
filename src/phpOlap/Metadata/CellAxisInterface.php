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
*	CellAxis interface
*
*	@package Metadata
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/

interface CellAxisInterface
{
    /**
     * Return member unique name
     *
     * @return String Member unique name
     *
     */
	public function getMemberUniqueName();	

    /**
     * Return member caption
     *
     * @return String Member caption
     *
     */
	public function getMemberCaption();

    /**
     * Return level unique name
     *
     * @return String Level unique name
     *
     */
	public function getLevelUniqueName();

    /**
     * Return level number
     *
     * @return Int Level number
     *
     */
	public function getLevelNumber();

    /**
     * Return display info
     *
     * @return Int Display info
     *
     */
	public function getDisplayInfo();

    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node);	
}