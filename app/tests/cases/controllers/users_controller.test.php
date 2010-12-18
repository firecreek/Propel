<?php
/* Users Test cases generated on: 2010-12-17 11:12:29 : 1292560709*/
App::import('Controller', 'Users');

class TestUsersController extends UsersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UsersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.account', 'app.person', 'app.company', 'app.country', 'app.timezone', 'app.project', 'app.projects_person', 'app.attachment', 'app.category', 'app.log', 'app.milestone', 'app.post', 'app.people', 'app.posts_people', 'app.todo', 'app.todo_item', 'app.todos_item', 'app.time', 'app.projects_company', 'app.comment', 'app.comments_read', 'app.role', 'app.apicall');

	function startTest() {
		$this->Users =& new TestUsersController();
		$this->Users->constructClasses();
	}

	function endTest() {
		unset($this->Users);
		ClassRegistry::flush();
	}

}
?>