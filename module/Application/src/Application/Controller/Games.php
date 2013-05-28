<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Games extends AbstractActionController {
	public function indexAction() {
		$gameService = $this->serviceLocator->get('edpcardsclient_gameservice');
		$playerService = $this->serviceLocator->get('edpcardsclient_playerservice');
		
		$view = new ViewModel();
		
		$view->games = $gameService->getList()?:array();
		$view->players = $playerService->getList()?:array();
		
		return $view;
	}
	
	public function gameAction() {
		$gameService = $this->serviceLocator->get('edpcardsclient_gameservice');
		
		$game = $gameService->get($this->params('game_id'));
		
		if(!$game)
			return $this->getResponse()->setResponseCode(404);
	
		$view = new ViewModel();
		
		$view->game = $game;
		
		return $view;
	}
}
