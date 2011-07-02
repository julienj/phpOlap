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

use phpOlap\Xmla\Connection\ConnectionInterface;

/**
*	Database interface
*
*  	@author Julien Jacottet <jjacottet@gmail.com>
*	@package Metadata
*/
interface DatabaseInterface
{

    /**
     * Get connection
     *
     * @return ConnectionInterface Connection object
     *
     */
	public function getConnection();

    /**
     * Get name
     *
     * @return String Name
     *
     */
	public function getName();

    /**
     * Get description
     *
     * @return String description
     *
     */
	public function getDescription();

    /**
     * Get url
     *
     * @return String url
     *
     */
	public function getUrl();
	
    /**
     * Get data source info
     *
     * @return String dataSourceInfo
     *
     */
	public function getDataSourceInfo();
	
    /**
     * Get provider name
     *
     * @return String provider name
     *
     */
	public function getProviderName();

    /**
     * Get provider type
     *
     * @return String provider type
     *
     */
	public function getProviderType();

    /**
     * Get authentication mode
     *
     * @return String authentication mode
     *
     */
	public function getAuthenticationMode();
	
    /**
     * Get catalogs
     *
     * @return Array Catalogs collection
     *
     */
	public function getCatalogs();

    /**
     * Hydrate Element
     *
     * @param DOMNode $node Node
     * @param Connection $connection Connection
     *
     */	
	public function hydrate(\DOMNode $node,ConnectionInterface $connection);

}