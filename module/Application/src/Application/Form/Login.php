<?php
namespace Application\Form;

use Zend\Form;

class Login extends Form\Form {
	public function __construct() {
		parent::__construct();

		$this->add($email = new Form\Element\Email("email"));
		$email->setLabel("Email Address");
		
		$this->add($displayName = new Form\Element\Text("displayName"));
		$displayName->setLabel("Display Name");
	}
}
