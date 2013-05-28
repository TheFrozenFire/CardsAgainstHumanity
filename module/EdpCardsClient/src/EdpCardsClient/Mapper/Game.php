<?php
namespace EdpCardsClient\Mapper;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;
use Zend\Http;

use EdpCards\Entity;

class Game implements SM\ServiceLocatorAwareInterface {
	use SM\ServiceLocatorAwareTrait;
	
	protected $httpClient;

	public function getList() {
		$api = $this->getHttpClient();
		$games = json_decode($api->send()->getBody(), true);
		
		$list = array();
		
		$gydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		foreach($games as $game) {
			$list[] = $gydrator->hydrate($game, new Entity\Game);
		}
		
		return $list;
	}
	
	public function getHttpClient() {
		if(!$this->httpClient) {
			$this->httpClient = $this->getServiceLocator()->get('edpcardsclient_httpclient');
		}
		
		return clone $this->httpClient;
	}
	
	public function setHttpClient(\Zend\Http\Client $client) {
		$this->httpClient = $client;
	}
}
