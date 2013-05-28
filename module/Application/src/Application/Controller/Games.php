<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Games extends AbstractActionController {
	public function indexAction() {
		$playerService = $this->serviceLocator->get('edpcardsclient_playerservice');
		$gameService = $this->serviceLocator->get('edpcardsclient_gameservice');
		
		$view = new ViewModel();
		
		$view->players = $playerService->getList()?:array();
		$view->games = $gameService->getList()?:array();
		
		return $view;
	}
	
	public function gameAction() {
		$gameService = $this->serviceLocator->get('edpcardsclient_gameservice');
	
		$view = new ViewModel();
		
		
		return $view;
	}
}
