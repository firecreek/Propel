<?php
/* Company Test cases generated on: 2010-12-16 20:12:51 : 1292507691*/
App::import('Model', 'Company');

class CompanyTestCase extends CakeTestCase {
	var $fixtures = array('app.company', 'app.account', 'app.person', 'app.user', 'app.project', 'app.country', 'app.timezone', 'app.projects_company');

	function startTest() {
		$this->Company =& ClassRegistry::init('Company');
	}

	function endTest() {
		unset($this->Company);
		ClassRegistry::flush();
	}

}
?>