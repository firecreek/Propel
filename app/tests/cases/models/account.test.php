<?php
/* Account Test cases generated on: 2010-12-16 20:12:29 : 1292506829*/
App::import('Model', 'Account');

class AccountTestCase extends CakeTestCase {
	var $fixtures = array('app.account', 'app.person', 'app.user', 'app.company', 'app.project');

	function startTest() {
		$this->Account =& ClassRegistry::init('Account');
	}

	function endTest() {
		unset($this->Account);
		ClassRegistry::flush();
	}

}
?>