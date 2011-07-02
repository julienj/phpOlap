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
use phpOlap\Metadata\MeasureInterface;

/**
*	Measure class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Metadata
*/
class Measure extends MetadataBase implements MeasureInterface
{
	protected $uniqueName;
	protected $caption;
	protected $aggregator;
	protected $dataType;
	protected $isVisible;

	protected $aggregatorMap = array(
				0 => 'UNKNOWN',
				1 => 'SUM',
				2 => 'COUNT',
				3 => 'MIN',
				4 => 'MAX',
				5 => 'AVG',
				6 => 'VAR',
				7 => 'STD',
				127 => 'CALCULATED'
		);

    /**
     * Get unique name
     *
     * @return String Unique name
     *
     */
	public function getUniqueName(){
		return $this->uniqueName;
	}
	
    /**
     * Get caption
     *
     * @return String Caption
     *
     */
	public function getCaption(){
		return $this->caption;
	}

    /**
     * Get aggregator
     *
     * @return String aggregator
     *
     */
	public function getAggregator(){
		if (array_key_exists($this->aggregator, $this->aggregatorMap)) {
			return $this->aggregatorMap[$this->aggregator];
		}
		return 'UNKNOWN';
	}

    /**
     * Get data type
     *
     * @return String data type
     *
     */
	public function getDataType(){
		return $this->dataType;
	}

    /**
     * Is visible
     *
     * @return Boolean is visible
     *
     */
	public function isVisible(){
		return (Bool) $this->isVisible;
	}
	
    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection)
	{
		$this->connection = $connection;
		$this->name = parent::getPropertyFromNode($node, 'MEASURE_NAME', false);
		$this->uniqueName = parent::getPropertyFromNode($node, 'MEASURE_UNIQUE_NAME', false);
		$this->description = parent::getPropertyFromNode($node, 'DESCRIPTION');
		$this->caption = parent::getPropertyFromNode($node, 'MEASURE_CAPTION');
		$this->aggregator = parent::getPropertyFromNode($node, 'MEASURE_AGGREGATOR');
		$this->dataType = parent::getPropertyFromNode($node, 'DATA_TYPE');
		$this->isVisible = parent::getPropertyFromNode($node, 'MEASURE_IS_VISIBLE');
	}
}	