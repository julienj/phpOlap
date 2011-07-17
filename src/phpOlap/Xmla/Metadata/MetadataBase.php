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

use phpOlap\Xmla\Metadata\MetadataException;

/**
*	metadata base class
*/

/**
*	MetadataBase class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Metadata
*/
abstract class MetadataBase
{
	
	protected $connection;
	protected $uniqueName;
	protected $name;
	protected $description;
	
    /**
     * Get connection
     *
     * @return Connection Connection object
     *
     */
	public function getConnection(){
		return $this->connection;
	}

    /**
     * Get name
     *
     * @return String Name
     *
     */
	public function getName(){
		return $this->name;
	}

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
     * Get description
     *
     * @return String description
     *
     */
	public function getDescription(){
		return $this->description;
	}

    /**
     * Get property from nide
     *
     * @param DOMNode $node Node
     * @param String $tagName tag name
     * @param Boolean $nullable property can be null
	 *
     * @return String description
     *
     */
	public static function getPropertyFromNode(\DOMNode $node, $tagName, $nullable = true)
	{
		$elements = $node->getElementsByTagName($tagName);

		if ($elements->length == 0) {
			if (!$nullable) {
				throw new MetadataException(sprintf('%s : hydratation error with "%s" node.', get_called_class(), $tagName)); 
			}
			return null;
		}
		$value = $elements->item(0)->nodeValue;
		return ($value == 'false') ? false : $value;
	}
}