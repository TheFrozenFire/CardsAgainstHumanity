<?php
namespace Application\Form;

use Zend\Form;

class Login extends Form\Form {
	public function __construct() {
		parent::__construct();
		
		$fields = new Form\Fieldset('fields');
		$this->add($fields);
		
		$fields->add($email = new Form\Element\Email("email"));
		$email->setLabel("Email Address");
		
		$fields->add($displayName = new Form\Element\Text("displayName"));
		$displayName->setLabel("Display Name");
	}
}
