<?php
/* ProjectsPerson Fixture generated on: 2010-12-16 20:12:25 : 1292507905 */
class ProjectsPersonFixture extends CakeTestFixture {
	var $name = 'ProjectsPerson';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false),
		'person_id' => array('type' => 'integer', 'null' => false),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'person_id' => 1,
			'updated' => '2010-12-16 20:58:25',
			'created' => '2010-12-16 20:58:25'
		),
	);
}
?>