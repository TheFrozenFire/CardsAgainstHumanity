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
