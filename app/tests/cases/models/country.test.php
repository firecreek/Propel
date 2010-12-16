<?php
/* Country Test cases generated on: 2010-12-16 20:12:02 : 1292507702*/
App::import('Model', 'Country');

class CountryTestCase extends CakeTestCase {
	var $fixtures = array('app.country', 'app.company', 'app.account', 'app.person', 'app.user', 'app.project', 'app.timezone', 'app.projects_company');

	function startTest() {
		$this->Country =& ClassRegistry::init('Country');
	}

	function endTest() {
		unset($this->Country);
		ClassRegistry::flush();
	}

}
?>