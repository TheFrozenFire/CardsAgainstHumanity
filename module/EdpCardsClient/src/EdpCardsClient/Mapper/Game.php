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
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		$games = json_decode($response->getBody(), true);
		
		$list = array();
		
		$hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		foreach($games as $game) {
			$list[] = $hydrator->hydrate($game, new Entity\Game);
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
		
		$game = json_decode($response->getBody(), true);
		$game = $hydrator->hydrate($game[0], new Entity\Game);
		
		return $game;
	}
	
	public function create(Entity\Game $game, Entity\Player $player) {
		$hydrator = $this->getHydrator();
	
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/create")
			->setMethod("POST")
			->setParameterPost(array_merge(
				$hydrator->extract($game),
				array(
					"player_id" => $player->id
				)
			));
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		$game = json_decode($response->getBody(), true);
		$game = $hydrator->hydrate($game[0], new Entity\Game);
		
		return $game;
	}
	
	public function update(Entity\Game $game) {
		$hydrator = $this->getHydrator();
	
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/{$game->id}")
			->setMethod("POST")
			->setParameterPost($hydrator->extract($game));
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		$game = json_decode($response->getBody(), true);
		$game = $hydrator->hydrate($game[0], new Entity\Game);
		
		return $game;
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
	
	public function getDecks() {
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/decks");
		
		$response = $api->send();
		if(!$response->isSuccess())
			return false;
		
		return json_decode($response->getBody());
	}
	
	public function getHttpClient() {
		if(!$this->httpClient) {
			$this->httpClient = $this->getServiceLocator()->get('edpcardsclient_httpclient');
			$this->httpClient->setUri($this->httpClient->getUri().'games/');
		}
		
		return clone $this->httpClient;
	}
	
	public function setHttpClient(\Zend\Http\Client $client) {
		$this->httpClient = $client;
	}
	
	public function getHydrator() {
		$hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		$hydrator->addFilter('getId', new MethodMatchFilter('getId'), FilterComposite::CONDITION_AND);
		$hydrator->addFilter('getPlayers', new MethodMatchFilter('getPlayers'), FilterComposite::CONDITION_AND);
		$hydrator->addFilter('getPlayerCount', new MethodMatchFilter('getPlayerCount'), FilterComposite::CONDITION_AND);
		
		return $hydrator;
	}
}
