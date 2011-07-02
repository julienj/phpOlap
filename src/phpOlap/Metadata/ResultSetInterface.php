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
*	ResultSet interface
*
*	@package Metadata
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/

interface ResultSetInterface
{

    /**
     * Get cube name
     *
     * @return String cube name
     *
     */
	public function getCubeName();

    /**
     * Get columns name in array   
     *
     * @return Array columns name
     *
     */
	public function getColHierarchiesName();

    /**
     * Get rows name in array   
     *
     * @return Array rows name
     *
     */
	public function getRowHierarchiesName();

    /**
     * Get filters name in array   
     *
     * @return Array filters name
     *
     */
	public function getFilterHierarchiesName();

    /**
     * Get columns CellAxis collection
     *
     * @return Array CellAxis collection
     *
     */
	public function getColAxisSet();

    /**
     * Get rows CellAxis collection
     *
     * @return Array CellAxis collection
     *
     */
	public function getRowAxisSet();

    /**
     * Get filter CellAxis collection
     *
     * @return Array CellAxis collection
     *
     */
	public function getFilterAxisSet();

    /**
     * Get CellData collection
     *
     * @return Array CellData collection
     *
     */
	public function getDataSet();

    /**
     * Get CellData by ordinal
     *
     * @return CellData CellData object
     *
     */
	public function getDataCell($ordinal);

    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     *
     */	
	public function hydrate(\DOMNode $node);
	

}