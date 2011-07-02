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
use phpOlap\Xmla\Metadata\CellAxis;
use phpOlap\Xmla\Metadata\CellData;
use phpOlap\Metadata\ResultSetInterface;
use phpOlap\Xmla\Metadata\MetadataException;

/**
*	ResultSet
*
*	@package Xmla
*	@subpackage Metadata
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/

class ResultSet implements ResultSetInterface
{
	protected $cubeName;
	protected $hierarchiesName = array();
	protected $cellAxisSet = array();
	protected $cellDataSet = array();
	
    /**
     * Get cube name
     *
     * @return String cube name
     *
     */	
	public function getCubeName()
	{
		return $this->cubeName;
	}

    /**
     * Get columns name in array   
     *
     * @return Array columns name
     *
     */
	public function getColHierarchiesName()
	{
		if (isset($this->hierarchiesName['Axis0'])) {
			return $this->hierarchiesName['Axis0'];
		}
		return null;
	}

    /**
     * Get rows name in array   
     *
     * @return Array rows name
     *
     */
	public function getRowHierarchiesName()
	{
		if (isset($this->hierarchiesName['Axis1'])) {
			return $this->hierarchiesName['Axis1'];
		}
		return null;
	}

    /**
     * Get filters name in array   
     *
     * @return Array filters name
     *
     */
	public function getFilterHierarchiesName()
	{
		if (isset($this->hierarchiesName['SlicerAxis'])) {
			return $this->hierarchiesName['SlicerAxis'];
		}
		return null;
	}

    /**
     * Get columns CellAxis collection
     *
     * @return Array CellAxis collection
     *
     */
	public function getColAxisSet()
	{
		if (isset($this->cellAxisSet['Axis0'])) {
			return $this->cellAxisSet['Axis0'];
		}
		return null;
	}

    /**
     * Get rows CellAxis collection
     *
     * @return Array CellAxis collection
     *
     */
	public function getRowAxisSet()
	{
		if (isset($this->cellAxisSet['Axis1'])) {
			return $this->cellAxisSet['Axis1'];
		}
		return null;
	}

    /**
     * Get filter CellAxis collection
     *
     * @return Array CellAxis collection
     *
     */
	public function getFilterAxisSet()
	{
		if (isset($this->cellAxisSet['SlicerAxis'])) {
			return $this->cellAxisSet['SlicerAxis'];
		}
		return null;
	}

    /**
     * Get CellData collection
     *
     * @return Array CellData collection
     *
     */
	public function getDataSet()
	{
		return $this->cellDataSet;
	}

    /**
     * Get CellData by ordinal
     *
     * @return CellData CellData object
     *
     */
	public function getDataCell($ordinal)
	{
		return $this->cellDataSet[$ordinal];
	}

    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     *
     */	
	public function hydrate(\DOMNode $node)
	{
		$this->cubeName = MetadataBase::getPropertyFromNode($node, 'CubeName');
		$this->hierarchiesName = self::hydrateAxesInfos($node);
		$this->cellAxisSet = self::hydrateAxesSet($node);
		$this->cellDataSet = self::hydrateDataSet($node);
	}
	
	protected static function hydrateAxesInfos(\DOMNode $node)
	{
		$result = array();
		$axesInfo = $node->getElementsByTagName('AxisInfo');
		foreach ($axesInfo as $axisInfo) {
			$axisName = self::getAttribute($axisInfo, "name");
			$hierarchiesInfo = $axisInfo->getElementsByTagName('HierarchyInfo');
			foreach ($hierarchiesInfo as $hierarchyInfo) {
				$result[$axisName][] = self::getAttribute($hierarchyInfo, "name");
			}
		}
		return $result;
	}

	protected static function hydrateAxesSet(\DOMNode $node)
	{
		$result = array();
		$cellAxes = $node->getElementsByTagName('Axis');
		foreach ($cellAxes as $cellAxis) {
			$axisName = self::getAttribute($cellAxis, "name");
			$tuples = $cellAxis->getElementsByTagName('Tuple');
			$i = 0;
			foreach ($tuples as $tuple) {
				$members = $tuple->getElementsByTagName('Member');
				foreach ($members as $member) {
					$cell = new cellAxis();
					$cell->hydrate($member);
					$result[$axisName][$i][] = $cell;
				}
				$i++;
			}
		}
		return $result;
	}



	protected static function hydrateDataSet(\DOMNode $node)
	{
		$result = array();
		$cellData = $node->getElementsByTagName('Cell');
		foreach ($cellData as $data) {
			$cellOrdinal = self::getAttribute($data, "CellOrdinal");
			$cell = new CellData();
			$cell->hydrate($data);
			$result[$cellOrdinal] = $cell;
		}
		return $result;
	}
	
	public static function getAttribute(\DOMNode $node, $attribute)
	{
		if (!$node->hasAttribute($attribute)){
			throw new MetadataException('Hydratation error.'); 
		} 
		return $node->getAttribute($attribute);
	}

}