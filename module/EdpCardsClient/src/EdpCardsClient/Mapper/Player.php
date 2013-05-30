<?php
namespace EdpCardsClient\Mapper;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;
use Zend\Http;
use Zend\Stdlib\Hydrator\Filter;

use EdpCards\Entity;

class Player implements SM\ServiceLocatorAwareInterface {
	use SM\ServiceLocatorAwareTrait;
	
	protected $httpClient;

	public function getList() {
		$api = $this->getHttpClient();
		$players = json_decode($api->send()->getBody(), true);
		
		if(!$players)
			return false;
		
		$list = array();
		
		$hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		foreach($players as $player) {
			$list[] = $hydrator->hydrate($player, new Entity\Player);
		}
		
		return $list;
	}
	
	public function get($id) {
		$id = urlencode($id);
	
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/{$id}");
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		$hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		
		$player = json_decode($response->getBody(), true);
		$player = $hydrator->hydrate($player, new Entity\Player);
		
		return $player;
	}
	
	public function create(Entity\Player $player) {
		$hydrator = $this->getHydrator();
	
		$api = $this->getHttpClient();
		$api->setMethod("POST")
			->setParameterPost($hydrator->extract($player));
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		$player = json_decode($response->getBody(), true);
		$player = $hydrator->hydrate($player, new Entity\Player);
		
		return $player;
	}
	
	public function update(Entity\Player $player) {
		$hydrator = $this->getHydrator();
	
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/{$player->id}")
			->setMethod("POST")
			->setParameterPost($hydrator->extract($player));
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		$player = json_decode($response->getBody(), true);
		$player = $hydrator->hydrate($player[0], new Entity\Player);
		
		return $player;
	}
	
	public function delete($id) {
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/{$id}")
			->setMethod("DELETE");
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		return true;
	}
	
	public function getHttpClient() {
		if(!$this->httpClient) {
			$this->httpClient = $this->getServiceLocator()->get('edpcardsclient_httpclient');
			$this->httpClient->setUri($this->httpClient->getUri().'players/');
		}
		
		$client = clone $this->httpClient;
		$client->setRequest(clone $client->getRequest());
		return $client;
	}
	
	public function setHttpClient(\Zend\Http\Client $client) {
		$this->httpClient = $client;
	}
	
	public function getHydrator() {
		$hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		$hydrator->addFilter('getId', new Filter\MethodMatchFilter('getId'), Filter\FilterComposite::CONDITION_AND);
		$hydrator->addFilter('getPoints', new Filter\MethodMatchFilter('getPoints'), Filter\FilterComposite::CONDITION_AND);

		return $hydrator;
	}
}
