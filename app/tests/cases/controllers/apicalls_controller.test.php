<?php
/* Apicalls Test cases generated on: 2010-12-16 20:12:36 : 1292507016*/
App::import('Controller', 'Apicalls');

class TestApicallsController extends ApicallsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ApicallsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.apicall', 'app.user');

	function startTest() {
		$this->Apicalls =& new TestApicallsController();
		$this->Apicalls->constructClasses();
	}

	function endTest() {
		unset($this->Apicalls);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>