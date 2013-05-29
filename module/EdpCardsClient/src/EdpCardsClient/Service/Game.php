<?php
namespace EdpCardsClient\Service;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;

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
	
	public function create($name, $decks, $player_id) {
		$name = filter_var($name, FILTER_SANITIZE_STRING);
		foreach($decks as &$deck) {
			$deck = filter_var($deck, FILTER_SANITIZE_STRING);
		}
		$player_id = filter_var($player_id, FILTER_SANITIZE_NUMBER_INT);
		
		return $this->getGameMapper()->create($name, $decks, $player_id);
	}
	
	public function update($id, $data) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
		$data = filter_var_array($data, array(
			
		));
		
		return $this->getGameMapper()->update($id, $data);
	}
	
	public function delete($id) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
		
		return $this->getGameMapper()->delete($id);
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
