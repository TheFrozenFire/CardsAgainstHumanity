<?php
namespace EdpCardsClient\Service;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;

use EdpCards\Entity;

class Game implements SM\ServiceLocatorAwareInterface {
	use SM\ServiceLocatorAwareTrait;
	
	protected $gameMapper;
	
	public function getList() {
		return $this->getGameMapper()->getList();
	}
	
	public function get($id) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
	
		return $this->getGameMapper()->get($id);
	}
	
	public function create(Entity\Game $game) {
		return $this->getGameMapper()->create($game);
	}
	
	public function update(Entity\Game $game) {
		return $this->getGameMapper()->update($game);
	}
	
	public function delete($id) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
		
		return $this->getGameMapper()->delete($id);
	}
	
	public function getDecks() {
		$decks = $this->getGameMapper()->getDecks();
		if(!$decks)
			return false;
		
		foreach($decks as &$deck) {
			if(!$deck->description)
				$deck->description = $deck->id;
		}
		
		return $decks;
	}
	
	public function getGameMapper() {
		if(!$this->gameMapper) {
			$this->gameMapper = $this->getServiceLocator()->get('edpcardsclient_gamemapper');
		}
		
		return $this->gameMapper;
	}
	
	public function setGameMapper($gameMapper) {
		$this->gameMapper = $gameMapper;
	}
}
