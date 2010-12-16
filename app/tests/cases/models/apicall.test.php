<?php
/* Apicall Test cases generated on: 2010-12-16 20:12:32 : 1292506832*/
App::import('Model', 'Apicall');

class ApicallTestCase extends CakeTestCase {
	var $fixtures = array('app.apicall', 'app.user');

	function startTest() {
		$this->Apicall =& ClassRegistry::init('Apicall');
	}

	function endTest() {
		unset($this->Apicall);
		ClassRegistry::flush();
	}

}
?>