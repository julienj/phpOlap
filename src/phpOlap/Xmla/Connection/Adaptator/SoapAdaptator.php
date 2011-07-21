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

use \SoapClient as SoapClient;

/**
*	Soap Adaptator
*
*	@package Xmla
*	@subpackage Connection
*  	@author Julien Jacottet <jjacottet@gmail.com>
*/
class SoapAdaptator extends SoapClient implements AdaptatorInterface
{

	protected $client = null;
	
	protected $url = null;
	
	protected $requestsHistory = array();

    /**
     * Constructor.
     *
     * @param String $uri Xmla uris
     */	
	public function __construct($url, $login = null, $password = null)
	{
		$this->url = $url;
		
		$params = array();
		$params['location'] = $url;
		$params['uri'] = 'http://schemas.xmlsoap.org/soap/envelope/';
		
		if ($login && $password) {
    		$params['login'] = $login;
    		$params['password'] = $password;
		}
		$this->client = parent::__construct(NULL, $params);
	}

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
	public function discover($requestType, Array $propertyList, Array $restrictionList = null)
	{
		$xml = new \DOMDocument('1.0', 'UTF-8');
		$xml->loadXml($this->getBaseSoap());	
		$body = $xml->getElementsByTagName( 'Body' )->item(0);
		
		$discover = $xml->createElement("Discover");
		$discover->setAttribute("xmlns", "urn:schemas-microsoft-com:xml-analysis");

		$body->appendChild($discover);
		
		$requestTypeNode = $this->createNode($xml, "RequestType", $requestType);
		$discover->appendChild($requestTypeNode);

		$restrictionsNode = $xml->createElement('Restrictions');
		$restrictionListNode = $this->createNodeFromArray($xml, "RestrictionList", $restrictionList);
		$restrictionsNode->appendChild($restrictionListNode);
		$discover->appendChild($restrictionsNode);

		$propertiesNode = $xml->createElement('Properties');
		$propertyListNode = $this->createNodeFromArray($xml, "PropertyList", $propertyList);
		$propertiesNode->appendChild($propertyListNode);
		$discover->appendChild($propertiesNode);
				
		return $this->call($xml, 'Discover');
	
	}
	
   /**
     * create discover request
     *
     * @param String $statement MDX request
     * @param Array $propertyList Proterty list
	 *
     * @return DOMNodeList list of <row> elements
     *
     */
	public function execute($statement, Array $propertyList)
	{
		$xml = new \DOMDocument('1.0', 'UTF-8');
		$xml->loadXml($this->getBaseSoap());	
		$body = $xml->getElementsByTagName( 'Body' )->item(0);

		$execute = $xml->createElement("Execute");
		$execute->setAttribute("xmlns", "urn:schemas-microsoft-com:xml-analysis");
		
		$body->appendChild($execute);
		
		$commandNode = $xml->createElement("Command");
		$statementNode = $this->createNode($xml, "Statement", $statement);
		$commandNode->appendChild($statementNode);
		$execute->appendChild($commandNode);

		if (count($propertyList) > 0) {
			$propertiesNode = $xml->createElement('Properties');
			$propertyListNode = $this->createNodeFromArray($xml, "PropertyList", $propertyList);
			$propertiesNode->appendChild($propertyListNode);
			$execute->appendChild($propertiesNode);
		}
		
		return $this->call($xml, 'Execute');
	}

   /**
     * send request
     *
     * @param DOMDocument $request Xmla request
     * @param String $action Action name ('Execute' or 'Discover')
	 *
     * @return DOMNodeList list of <row> elements
     *
     */
	protected function call(\DOMDocument $request, $action)
	{
		$resultSoap = $this->__doRequest($request->saveXML(), $this->url,  $action, 1);	
		if (!$resultSoap) {
			throw new AdaptatorException('SOAP error : no response');
		}
		$result = new \DOMDocument();
		$result->loadXML($resultSoap);
		
		$error = $result->getElementsByTagName('Fault');
		if ($error->length > 0) {
			$faultstring = $error->item(0)->getElementsByTagName('faultstring')->item(0)->nodeValue;
			throw new AdaptatorException(sprintf('XMLA error : %s', $faultstring));
		}
		
		$this->requestsHistory[] = $request->saveXml();
		
		if ($action == 'Execute') {
			return $result->getElementsByTagName('root')->item(0);
		}
		
		return $result->getElementsByTagName('row');
		
	}

	public function getRequestsHistory()
	{
		return $this->requestsHistory;
	}
	
	protected function createNode($dom, $name, $value)
	{
		$value = $dom->createTextNode($value);
		$node = $dom->createElement($name);
		$node->appendChild($value);
		return $node;
	}

	protected function createNodeFromArray($dom, $container, $collection)
	{
		$container = $dom->createElement($container);
		if (count($collection) > 0) {
			foreach ($collection as $name => $value) {
				$node = $this->createNode($dom, $name, $value);
				$container->appendChild($node);
			}
		}
		return $container;
	}

    function getBaseSoap() {

        return '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">' .
                    '<SOAP-ENV:Body />' .
                '</SOAP-ENV:Envelope>';
    }

}
