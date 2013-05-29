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
	
	public function get($id) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
	
		return $this->getPlayerMapper()->get($id);
	}
	
	public function create($email, $display_name) {
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$display_name = filter_var($display_name, FILTER_SANITIZE_NUMBER_INT);
		
		return $this->getPlayerMapper()->create($email, $display_name);
	}
	
	public function update($id, $data) {
		$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
		$data = filter_var_array($data, array(
			
		));
		
		return $this->getPlayerMapper()->update($id, $data);
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
