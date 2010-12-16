<?php
/* Log Test cases generated on: 2010-12-16 20:12:51 : 1292507751*/
App::import('Model', 'Log');

class LogTestCase extends CakeTestCase {
	var $fixtures = array('app.log', 'app.project', 'app.person');

	function startTest() {
		$this->Log =& ClassRegistry::init('Log');
	}

	function endTest() {
		unset($this->Log);
		ClassRegistry::flush();
	}

}
?>