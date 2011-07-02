<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Layout\Table;

use phpOlap\Layout\Table\TableLayoutAbstract;
use phpOlap\Metadata\ResultSetInterface;
use phpOlap\Metadata\CellAxisInterface;
use phpOlap\Metadata\CellDataInterface;

/**
*	Generate a csv table from ResultSetInterface object
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Layout
*	@subpackage Table
*/
class CsvTableLayout extends TableLayoutAbstract
{
	
    /**
     * {@inheritdoc}
     */
	protected function renderGlobalLayout()
	{
		return "%{{header}}%%{{body}}%";
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderHeaderRow($even)
	{
		return "{{cells}}\n";
	}

    /**
     * {@inheritdoc}
     */
	protected function renderHeaderRowColHierarchyTitle($even)
	{
		return "{{cells}}\n";
	}

    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellColHierarchyTitle($title, $repeat)
	{
		return $title . ";";
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellRowHierarchyTitle($title, $repeat)
	{
		
		return $title . ";";
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellAxis($row, $col, $axisSet)
	{
		return $axisSet[$row][$col]->getMemberCaption() .";";
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellEmpty($height, $width, $isFirst)
	{
		return ';';
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderBodyRow($even)
	{
		return "{{cells}}\n";
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderBodyCellAxis($row, $col, $axisSet)
	{	
		return $axisSet[$row][$col]->getMemberCaption() . ";";
	}

    /**
     * {@inheritdoc}
     */
	protected function renderBodyCellData($ordinal)
	{
		$dataSet = $this->resultSet->getDataSet();
		if (isset($dataSet[$ordinal])) {
			return $dataSet[$ordinal]->getValue() . ";";
		} else {
			return ";";
		}
		
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderSlicer()
	{
		return;
	}	
	
	
}