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
		if(!$response->isOk())
			return false;
		
		$games = json_decode($response->getBody(), true);
		
		$list = array();
		
		$gydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		foreach($games as $game) {
			$list[] = $gydrator->hydrate($game, new Entity\Game);
		}
		
		return $list;
	}
	
	public function get($id) {
		$id = urlencode($id);
	
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/{$id}");
		
		$response = $api->send();
		if(!$response->isOk())
			return false;
		
		$gydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		
		$game = json_decode($response->getBody(), true);
		$game = $gydrator->hydrate($game[0], new Entity\Game);
		
		return $game;
	}
	
	public function create($name, $decks, $player_id) {
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/create")
			->setMethod("POST")
			->setParameterPost(array(
				"name" => $name,
				"decks" => $decks,
				"player_id" => $player_id
			));
		
		$response = $api->send();
		if(!$response->isOk())
			return false;
		
		$gydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		
		$game = json_decode($response->getBody(), true);
		$game = $gydrator->hydrate($game[0], new Entity\Game);
		
		return $game;
	}
	
	public function update($id, $data) {
		$id = urlencode($id);
	
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/{$id}")
			->setMethod("POST")
			->setParameterPost($data);
		
		$response = $api->send();
		if(!$response->isOk())
			return false;
		
		$gydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		
		$game = json_decode($response->getBody(), true);
		$game = $gydrator->hydrate($game[0], new Entity\Game);
		
		return $game;
	}
	
	public function delete($id) {
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/{$id}")
			->setMethod("DELETE");
		
		$response = $api->send();
		if(!$response->isOk())
			return false;
		
		return true;
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
