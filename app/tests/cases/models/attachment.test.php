<?php
/* Attachment Test cases generated on: 2010-12-16 20:12:01 : 1292507161*/
App::import('Model', 'Attachment');

class AttachmentTestCase extends CakeTestCase {
	var $fixtures = array('app.attachment', 'app.project', 'app.category', 'app.person');

	function startTest() {
		$this->Attachment =& ClassRegistry::init('Attachment');
	}

	function endTest() {
		unset($this->Attachment);
		ClassRegistry::flush();
	}

}
?>