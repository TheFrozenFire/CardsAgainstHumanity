<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\Hydrator;

use Application\Form;
use EdpCards\Entity;

class Games extends AbstractActionController {
	protected $player;

	public function indexAction() {
		$gameService = $this->serviceLocator->get('edpcardsclient_gameservice');
		$playerService = $this->serviceLocator->get('edpcardsclient_playerservice');
		
		$view = $this->getViewModel();
		
		$view->games = $gameService->getList()?:array();
		$view->players = $playerService->getList()?:array();
		
		return $view;
	}
	
	public function gameAction() {
		$gameService = $this->serviceLocator->get('edpcardsclient_gameservice');
		
		$game = $gameService->get($this->params('game_id'));
		
		if(!$game)
			return $this->getResponse()->setStatusCode(404);
	
		$view = $this->getViewModel();
		
		$view->game = $game;
		
		return $view;
	}
	
	public function loginAction() {
		$data = $this->getRequest()->getPost();
		
		$form = $this->getLoginForm();
		$form->setData($data["fields"]);
		
		if($form->isValid()) {
			$player = $form->getData();
		
			$playerService = $this->serviceLocator->get('edpcardsclient_playerservice');
			$player = $playerService->create($player);

			if($player) {
				$session = $this->serviceLocator->get('session');
				$session->playerID = $player->id;
			}
		}
		
		if(!empty($data["backurl"]))
			return $this->redirect()->toUrl($data["backurl"]);
		else
			return $this->redirect()->toRoute("games");
	}
	
	public function createAction() {
		$data = $this->getRequest()->getPost();
		
		$form = $this->getCreateGameForm();
		$form->setData($data["fields"]);
		
		$player = $this->getPlayer();
		
		if($player && $form->isValid()) {
			$game = $form->getData();
			
			$gameService = $this->serviceLocator->get('edpcardsclient_gameservice');
			$game = $gameService->create($game, $player);
			
			if($game) {
				return $this->redirect()->toRoute("games/game/{$game->id}");
			}
		}
		
		return $this->redirect()->toRoute("games");
	}
	
	public function getPlayer($cached = true) {
		if(!$this->player || !$cached) {
			$session = $this->serviceLocator->get('session');
			if(!$session || !$session->playerID)
				return false;
		
			$this->player = $this->serviceLocator->get('edpcardsclient_playerservice')->get($session->playerID);
		}
		
		return $this->player;
	}
	
	public function setPlayer(Entity\Player $player) {
		$this->player = $player;
	}
	
	protected function getViewModel() {
		$view = new ViewModel();
		
		$view->games = array();
		$view->players = array();
		$view->player = $this->getPlayer();
		
		$view->loginForm = $this->getLoginForm();
		$view->createGameForm = $this->getCreateGameForm();
		
		return $view;
	}
	
	protected function getLoginForm() {
		$form = new Form\Login;
		$form->setHydrator(new Hydrator\ClassMethods);
		$form->bind(new Entity\Player);
		
		return $form;
	}
	
	protected function getCreateGameForm() {
		$form = new Form\CreateGame;
		$form->setHydrator(new Hydrator\ClassMethods);
		$form->bind(new Entity\Game);
		
		$decks = array();
		/*if($cardService = $this->serviceLocator->get('edpcardsclient_cardservice')) {
			foreach($cardService->getDecks() as $deck) {
				$decks[$deck->id] = $deck->name;
			}
		}*/
		
		$form->get('fields')->get('decks')->setValueOptions($decks);
		
		return $form;
	}
}
