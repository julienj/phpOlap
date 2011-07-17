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
use phpOlap\Metadata\CatalogInterface;
use phpOlap\Xmla\Metadata\MetadataBase;


/**
*	Catalog class
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Xmla
*	@subpackage Metadata
*/
class Catalog extends MetadataBase implements CatalogInterface
{
	protected $roles;
	protected $schemas;


    /**
     * Get Roles
     *
     * @return Array roles
     *
     */
	public function getRoles(){
		return $this->roles;
	}

    /**
     * Get schemas
     *
     * @return Array Schemas collection
     *
     */
	public function getSchemas()
	{
		if (!$this->schemas) {
			$this->schemas = $this->getConnection()->findSchemas(
				array(),
				array('CATALOG_NAME' => $this->getName())
			);
		}
		return $this->schemas;
	}

    /**
     * Get unique name
     *
     * @return String Unique name
     *
     */
	public function getUniqueName(){
		return "[" . $this->name . "]";
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
		$this->name = parent::getPropertyFromNode($node, 'CATALOG_NAME', false);
		$this->description = parent::getPropertyFromNode($node, 'DESCRIPTION');
		$roles = parent::getPropertyFromNode($node, 'ROLES');
		$this->roles = explode(",", $roles);
	}
}