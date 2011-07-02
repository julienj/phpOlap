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
*	Generate a html table from ResultSetInterface object
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Layout
*	@subpackage Table
*/
class HtmlTableLayout extends TableLayoutAbstract
{
	
	public $tableClass = 'olapGrid';
	public $rowEvenClassName = 'even';
	public $rowOddClassName = 'odd';
	
    /**
     * {@inheritdoc}
     */
	protected function renderGlobalLayout()
	{
		return sprintf('<table class="%s"><thead>{{header}}</thead><tbody>{{body}}</tbody></table>', $this->tableClass);
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderHeaderRow($even)
	{
		return '<tr>{{cells}}</tr>';
	}

    /**
     * {@inheritdoc}
     */
	protected function renderHeaderRowColHierarchyTitle($even)
	{
		return '<tr>{{cells}}</tr>';
	}

    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellColHierarchyTitle($title, $repeat)
	{
		return sprintf('<th colspan="%d">%s</th>', $repeat, $title);
	}

    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellRowHierarchyTitle($title, $repeat)
	{
		
		return sprintf('<th rowspan="">%s</th>', $title);
	}

    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellAxis($row, $col, $axisSet)
	{
		if (!$this->ifDisplayAxisCell($row, $col, $axisSet)) {
			return;
		}
		return sprintf(
					'<th colspan="%d">%s</th>',
					$this->countAxisMemberSize($row, $col, $axisSet),
					$axisSet[$row][$col]->getMemberCaption()
				);
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderHeaderCellEmpty($height, $width, $isFirst)
	{
		if (!$isFirst) {
			return;
		}
		return sprintf('<th class="empty" rowspan="%d" colspan="%d"></th>',$height, $width);
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderBodyRow($even)
	{
		$class = $even ? $this->rowEvenClassName : $this->rowOddClassName;
		return sprintf('<tr class="%s">{{cells}}</tr>', $class);
	}
	
    /**
     * {@inheritdoc}
     */
	protected function renderBodyCellAxis($row, $col, $axisSet)
	{	
		if (!$this->ifDisplayAxisCell($row, $col, $axisSet)) {
			return;
		}
		$spacer = str_repeat("&nbsp;&nbsp;", $axisSet[$row][$col]->getLevelNumber());
		$caption =  $spacer . ' ' . $axisSet[$row][$col]->getMemberCaption();
		return sprintf(
				'<th rowspan="%d">%s</th>',
				$this->countAxisMemberSize($row, $col, $axisSet),
				$caption
		);
	}

    /**
     * {@inheritdoc}
     */
	protected function renderBodyCellData($ordinal)
	{
		$dataSet = $this->resultSet->getDataSet();
		if (isset($dataSet[$ordinal])) {
			return sprintf('<td>%s</td>', $dataSet[$ordinal]->getFormatedValue());
		} else {
			return '<td>-</td>';
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