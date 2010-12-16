<?php
/* PostsPerson Test cases generated on: 2010-12-16 20:12:01 : 1292507881*/
App::import('Model', 'PostsPerson');

class PostsPersonTestCase extends CakeTestCase {
	function startTest() {
		$this->PostsPerson =& ClassRegistry::init('PostsPerson');
	}

	function endTest() {
		unset($this->PostsPerson);
		ClassRegistry::flush();
	}

}
?>