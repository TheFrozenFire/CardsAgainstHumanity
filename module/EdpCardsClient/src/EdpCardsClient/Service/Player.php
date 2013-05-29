<?php
namespace EdpCardsClient\Service;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;

use EdpCards\Entity;

class Player implements SM\ServiceLocatorAwareInterface {
	use SM\ServiceLocatorAwareTrait;
	
	protected $playerMapper;
	
	public function getList() {
		return $this->getPlayerMapper()->getList();
	}
	
	public function get($id) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
	
		return $this->getPlayerMapper()->get($id);
	}
	
	public function create(Entity\Player $player) {
		return $this->getPlayerMapper()->create($player);
	}
	
	public function update(Entity\Player $player) {
		return $this->getPlayerMapper()->update($player);
	}
	
	public function delete($id) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
		
		return $this->getPlayerMapper()->delete($id);
	}
	
	public function getPlayerMapper() {
		if(!$this->playerMapper) {
			$this->playerMapper = $this->getServiceLocator()->get('edpcardsclient_playermapper');
		}
		
		return $this->playerMapper;
	}
	
	public function setPlayerMapper($playerMapper) {
		$this->playerMapper = $playerMapper;
	}
}
