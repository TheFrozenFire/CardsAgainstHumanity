<?php
namespace EdpCardsClient\Mapper;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;
use Zend\Http;

use EdpCards\Entity;

class Player implements SM\ServiceLocatorAwareInterface {
	use SM\ServiceLocatorAwareTrait;
	
	protected $httpClient;

	public function getList() {
		$api = $this->getHttpClient();
		$players = json_decode($api->send()->getBody(), true);
		
		$list = array();
		
		$gydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		foreach($players as $player) {
			$list[] = $gydrator->hydrate($Player, new Entity\Player);
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
		
		$player = json_decode($response->getBody(), true);
		$player = $gydrator->hydrate($player[0], new Entity\Player);
		
		return $player;
	}
	
	public function create($email, $display_name) {
		$api = $this->getHttpClient();
		$api->setUri($api->getUri()."/create")
			->setMethod("POST")
			->setParameterPost(array(
				"email" => $email,
				"display_name" => $diplay_name
			));
		
		$response = $api->send();
		if(!$response->isOk())
			return false;
		
		$gydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
		
		$player = json_decode($response->getBody(), true);
		$player = $gydrator->hydrate($player[0], new Entity\Player);
		
		return $player;
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
		
		$player = json_decode($response->getBody(), true);
		$player = $gydrator->hydrate($player[0], new Entity\Player);
		
		return $player;
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
