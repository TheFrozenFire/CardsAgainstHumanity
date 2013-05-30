<?php
namespace Application\Form;

use Zend\Form;

class CreateGame extends Form\Form {
	public function __construct() {
		parent::__construct();
		
		$fields = new Form\Fieldset('fields');
		$this->add($fields);
		
		$fields->add($name = new Form\Element\Text("name"));
		$name->setLabel("Name");
		
		$fields->add($decks = new Form\Element\Select("decks"));
		$decks->setLabel("Decks");
		$decks->setAttribute("multiple", true);
		
		$this->add($login = new Form\Element\Button("submit"));
		$login->setAttribute("type", "submit");
	}
}
