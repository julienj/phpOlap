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

use phpOlap\Metadata\ResultSetInterface;
use phpOlap\Metadata\CellAxisInterface;
use phpOlap\Metadata\CellDataInterface;
use phpOlap\Layout\LayoutInterface;


/**
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Layout
*	@subpackage Table
*/
abstract class TableLayoutAbstract implements LayoutInterface
{
	
	protected $resultSet;
	protected $layout;
	public $displayRowColHierarchyTitle = true;

    /**
     * Constructor.
     *
     * @param ResultSetInterface $resultSet The resultSet object
     *
     */	
	public function __construct (ResultSetInterface $resultSet)
	{
		$this->resultSet = $resultSet;
	}
	
    /**
     * generate the layout
     *
     * @return String Layout
     *
     */
	public function generate()
	{
		$this->layout = $this->renderGlobalLayout();
		$this->layout = str_replace("{{header}}", $this->generateHeader(), $this->layout);
		$this->layout = str_replace("{{body}}", $this->generateBody(), $this->layout);
		
		return $this->layout;	
	}

    /**
     * generate table header
     *
     * @return String header table
     *
     */
	public function generateHeader()
	{

		$rowNb = count($this->resultSet->getColHierarchiesName());
		if ($this->displayRowColHierarchyTitle) {
			$rowNb = $rowNb * 2;
		}
		$colNb = count($this->resultSet->getColAxisSet());
		//$rowAxisNb = count($this->resultSet->getColAxisSet());
		
		$header = '';
		
		foreach ($this->resultSet->getColHierarchiesName() as $row => $colHierarchyName) {
			
			$rowHeaderContent = '';
			$rowContent = '';
			
			//	Row Hierarchy Title	
			foreach ($this->resultSet->getRowHierarchiesName() as $col => $rowHierarchyName) {
				if ($row +1 == count($this->resultSet->getColHierarchiesName()) ) {				
					$rowContent .= $this->renderHeaderCellRowHierarchyTitle($rowHierarchyName, $rowNb);
					$rowHeaderContent .= $this->renderHeaderCellEmpty(
													$rowNb - 1,
													count($this->resultSet->getColHierarchiesName()),
													false);
				} else { // empty cells
					$topLeft = ($row == 0 && $col == 0);
					$rowContent .= $this->renderHeaderCellEmpty(
													$rowNb - 1,
													count($this->resultSet->getColHierarchiesName()),
													($topLeft && !$this->displayRowColHierarchyTitle));
					$rowHeaderContent .= $this->renderHeaderCellEmpty(
													$rowNb - 1,
													count($this->resultSet->getColHierarchiesName()),
													$topLeft);
				}
			}
			
			// Col Hierarchy Title	
			$rowHeaderContent .= $this->renderHeaderCellColHierarchyTitle($colHierarchyName, $colNb);

			// Col Axis
			$colAxisSet = $this->resultSet->getColAxisSet();
			for ($col=0; $col < $colNb ; $col++) { 
				$rowContent .= $this->renderHeaderCellAxis($col, $row, $colAxisSet);
			}
			
			if ($this->displayRowColHierarchyTitle) {
				$header .= str_replace("{{cells}}", $rowHeaderContent, $this->renderHeaderRowColHierarchyTitle(true));
			}
			$header .= str_replace("{{cells}}", $rowContent, $this->renderHeaderRow(($row%2 == 0)));
		}

		return $header;
	}


    /**
     * generate table body
     *
     * @return String body table
     *
     */
	public function generateBody()
	{
		
		$body = '';
		$rowAxisSet = $this->resultSet->getRowAxisSet();
		$dataSet = $this->resultSet->getDataSet();
		
		foreach($rowAxisSet as $row => $aCol)
		{
			$rowContent = '';
			$even = ($row%2 == 0) ? true : false;

			// Axis cells
			foreach ($aCol as $col => $oCol) {
				$rowContent .= $this->renderBodyCellAxis($row, $col, $rowAxisSet);
			}
			
			// Datas
			$rowNum = count($this->resultSet->getColAxisSet());
			$start =  $rowNum * $row;
			$stop = $start + $rowNum;
			for ($i=$start; $i < $stop; $i++) { 
				$rowContent .= $this->renderBodyCellData($i);
			}
			
			$body .= str_replace("{{cells}}", $rowContent, $this->renderBodyRow($even));			
		}
		return $body;
		
	}

    /**
     * Count height size (rowspan or colspan)
     *
     * @param Int $row Row index
     * @param Int $col Col index
     * @param Array $axisSet CellAxis collection
	 *
     * @return Tnt
     *
     */
	protected function countAxisMemberSize($row, $col, $axisSet)
	{
		$size = 0;
		$stop = false;
		while ( !$stop )
		{
			$size++;
			if (!isset($axisSet[$row+$size][$col]))
			{
				break;
			}
			for ($i=$col; $i >= 0 ; $i--) { 
				if ($axisSet[$row][$i]->getMemberUniqueName() != $axisSet[$row+$size][$i]->getMemberUniqueName())
				{
					$stop = true;
					break;
				}
			}
		}
		return $size;		
	}

    /**
     * Return if axis th node must be display
     *
     * @param Int $row Row index
     * @param Int $col Col index
     * @param Array $axisSet CellAxis collection
	 *
     * @return Tnt
     *
     */	
	protected function ifDisplayAxisCell($row, $col, $axisSet)
	{
		if ( $row == 0 ) {
			return true;
		} elseif ($this->countAxisMemberSize($row-1, $col, $axisSet) > 1) {
			return false;
		} else {
			return true;
		}
	}

    /**
     * Return the global Layout format.
	 *
     * You can use keywords '{{header}}', '{{body}}' and '%slider%'
	 *
     * @return String
     *
     */	
	abstract protected function renderGlobalLayout();

    /**
     * Return row format in header.
	 *
     * You can use keyword '{{cells}}'
	 *
     * @param Boolean $even is even row
	 *
     * @return String
     *
     */
	abstract protected function renderHeaderRow($even);

    /**
     * Return row format for hierarchy title in header.
	 *
     * You can use keyword '{{cells}}'
	 *
     * @param Boolean $even is even row
	 *
     * @return String
     *
     */
	abstract protected function renderHeaderRowColHierarchyTitle($even);

    /**
     * Return cell format for column hierarchy title in header .
	 *
     * @param String $title Col hierarchy title
     * @param Int $repeat 
	 *
     * @return String
     *
     */
	abstract protected function renderHeaderCellColHierarchyTitle($title, $repeat);

    /**
     * Return cell format for row hierarchy title in header .
	 *
     * @param String $title Row hierarchy title
     * @param Int $repeat 
	 *
     * @return String
     *
     */
	abstract protected function renderHeaderCellRowHierarchyTitle($title, $repeat);

    /**
     * Return cell axis format in header .
	 *
     * @param Int $row Axis set row index
     * @param Int $col Axis set col index 
     * @param Array $axisSet Axis set
	 *
     * @return String
     *
     */
	abstract protected function renderHeaderCellAxis($row, $col, $axisSet);

    /**
     * Return cell format for empty cell in header (corner top left).
	 *
     * @param Int $height rowspan
     * @param Int $width colspan
     * @param Boolean $isFirst is the first empty cell
	 *
	 *
     * @return String
     *
     */
	abstract protected function renderHeaderCellEmpty($height, $width, $isFirst);

    /**
     * Return row format in boby.
	 *
     * You can use keyword '{{cells}}'
	 *
     * @param Boolean $even is even row
	 *
     * @return String
     *
     */
	abstract protected function renderBodyRow($even);

    /**
     * Return cell axis format in boby.
	 *
     * @param Int $row Axis set row index
     * @param Int $col Axis set col index 
     * @param Array $axisSet Axis set
	 *
     * @return String
     *
     */
	abstract protected function renderBodyCellAxis($row, $col, $axisSet);

    /**
     * Return cell data format in boby.
	 *
     * @param Int $ordinal Cell index in DataSet 
	 *
     * @return String
     *
     */
	abstract protected function renderBodyCellData($ordinal);
	abstract protected function renderSlicer();

}