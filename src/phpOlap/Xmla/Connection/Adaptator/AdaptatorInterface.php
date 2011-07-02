<?php 

/*
* This file is part of phpOlap.
*
* (c) Julien Jacottet <jjacottet@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace phpOlap\Xmla\Connection\Adaptator;

/**
*	@package Xmla
*	@subpackage Connection
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/
interface AdaptatorInterface
{

   /**
     * create statement request
     *
     * @param String $requestType Request type
     * @param Array $propertyList Proterty list
     * @param Array $restrictionList Restriction list
	 *
     * @return DOMNodeList list of <row> elements
     *
     */
	public function discover($requestType, Array $propertyList, Array $restrictionList = null);
	
   /**
     * create discover request
     *
     * @param String $statement MDX request
     * @param Array $propertyList Proterty list
	 *
     * @return DOMNodeList list of <row> elements
     *
     */
	public function execute($statement, Array $propertyList);
	
}