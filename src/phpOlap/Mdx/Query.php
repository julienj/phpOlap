<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Mdx;

use phpOlap\Mdx\QueryException;

/**
*	Create simple mdx query
*
*	@package Mdx
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/

class Query
{
	
	protected $cubeName = null;
	protected $rowElements = array();
	protected $colElements = array();
	protected $filterElements = array();
	protected $nonEmpty = false;

    /**
     * Constructor.
     *
     * @param string $cubeName A Cube unique name
     *
     */
	public function __construct($cubeName)
	{
		$this->cubeName = $cubeName;
	}

    /**
     * Set NonEmpty
     *
     * @param Boolean $nonEmpty NonEmpty
     *
     */
	public function setNonEmpty($nonEmpty)
	{
		$this->nonEmpty = $nonEmpty ? true : false;
	}

    /**
     * Get NonEmpty
     *
     * @return Boolean NonEmpty
     *
     */
	public function getNonEmpty()
	{
		return $this->nonEmpty;
	}

    /**
     * Add element
     *
     * @param String $element Element unique name
     * @param String $axis Axis name ("ROW", "COL" or "FILTER")
     *
     */
	public function addElement($element, $axis)
	{
		$dimension = $this->getDimensionUniqueName($element);
		
		switch ($axis)
		{
			case 'ROW':
				$this->rowElements[$this->getDimensionUniqueName($element)][] = $element;
			break;
			case 'COL':
				$this->colElements[$this->getDimensionUniqueName($element)][] = $element;
			break;
			case 'FILTER':
				$this->filterElements[$this->getDimensionUniqueName($element)][] = $element;
			break;
			default:
				throw new QueryException(sprintf('Axis "%s" unknown ! Use "ROW", "COL" or "FILTER"', $axis));
			break;
		}
	}

    /**
     * Return MDX query to string
     *
     * @return String MDX query
     *
     */
	public function toMdx()
	{
		if (count($this->colElements) == 0) {
			throw new QueryException('Axis "COL" can\'t be empty');
		}

		if (count($this->rowElements) == 0) {
			throw new QueryException('Axis "ROW" can\'t be empty');
		}
		
		$nonEmpty = $this->nonEmpty ? 'NON EMPTY ' : '';
		
		$mdx = "SELECT " .
				$nonEmpty .
				$this->getMdxPart($this->colElements) .
				" ON COLUMNS, " .
				$nonEmpty .
				$this->getMdxPart($this->rowElements) .
				" ON ROWS" .
				" FROM " .
				$this->cubeName;

		if ($this->filterElements) {
			$mdx .= " WHERE " .
					$this->getMdxPart($this->filterElements);
		}

		return $mdx;
		
	}

    /**
     * Convert a array of elements to Mdx part
     *
     * @param Array $elements elements array
	 *
     * @return String MDX part
     *
     */	
	protected function getMdxPart($elements)
	{
		
		$part = array();
		
		foreach( $elements as $uniqueName => $elements )
		{
			$union = self::union($elements);
			$part[] = (count($elements) > 1 && $uniqueName != "[Measures]") ? self::hierarchize($union) : $union;
		}
		
		if ( count($part) > 1 ) {
			return self::crossjoin($part);
		}
		else {
			return $part[0];
		}
	}

    /**
     * Convert a array of elements to union Mdx part
     *
     * @param Array $elements elements array
	 *
     * @return String MDX part
     *
     */
	static public function union($elements)
	{
		if (self::getDimensionUniqueName($elements[0]) == "[Measures]") {
			return "{" . implode(', ', $elements) . "}";
		}
		
		$union = null;		
		for ( $i = count($elements) - 1; $i >= 0  ; $i-- )
		{ 
			$union = ($union) ? 'Union(' . $elements[$i] . ', ' .$union . ')' : $elements[$i];
		}
		return $union;
	}

    /**
     * Convert a array of elements to crossjoin Mdx part
     *
     * @param Array $elements elements array
	 *
     * @return String MDX part
     *
     */
	static public function crossjoin($elements)
	{
		$crossjoin = null;		
		for ( $i = count($elements) - 1; $i >= 0  ; $i-- )
		{ 
			$crossjoin = ($crossjoin) ? 'Crossjoin(' . $elements[$i] . ', ' .$crossjoin . ')' : $elements[$i];
		}
		return $crossjoin;
	}

    /**
     * Convert a array of elements to hierarchize Mdx part
     *
     * @param Array $elements elements array
	 *
     * @return String MDX part
     *
     */
	static public function hierarchize($part)
	{
		return "Hierarchize(" . $part . ")";
	}

    /**
     * Return Unique dimension name
     *
     * @param String $element element unique name
	 *
     * @return String Dimension unique name
     *
     */
	static public function getDimensionUniqueName($element)
	{
		$part = explode(".", $element);
		return $part[0];
	}
	
}
