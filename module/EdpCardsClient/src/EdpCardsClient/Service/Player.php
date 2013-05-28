<?php
namespace EdpCardsClient\Service;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;

class Player implements SM\ServiceLocatorAwareInterface {
	use SM\ServiceLocatorAwareTrait;
	
	protected $playerMapper;
	
	public function getList() {
		return $this->getPlayerMapper()->getList();
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
